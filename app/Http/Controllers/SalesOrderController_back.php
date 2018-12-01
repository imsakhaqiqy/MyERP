<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\StoreSalesOrderRequest;
use App\Http\Requests\UpdateSalesOrderRequest;

use App\SalesOrder;
use App\Customer;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(\Auth::user()->can('sales-order-module'))
      {
          return view('sales_order.index');
      }else{
          return view('403');
      }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      if(\Auth::user()->can('create-sales-order-module'))
      {
        $customer_options = Customer::lists('name', 'id');
        return view('sales_order.create')
            ->with('customer_options', $customer_options);
      }else{
        return view('403');
      }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSalesOrderRequest $request)
    {
        if($request->ajax()){
            $data = [
                'customer_id'=>$request->customer_id,
                'notes'=>$request->notes,
                'creator'=>\Auth::user()->id
            ];

            $save = SalesOrder::create($data);
            $sales_order_id = $save->id;

            //update the code for this sales order
            $code = \DB::table('sales_orders')->where('id', $sales_order_id)->update(['code'=>'SO-'.$sales_order_id]);
            $sales_order = SalesOrder::find($sales_order_id);

            //Build sync data to store the relation w/ products
            $syncData = [];
            foreach($request->product_id as $key=>$value){
                //$syncData[$value] = ['quantity'=> $request->quantity[$key], 'price'=>floatval(preg_replace('#[^0-9.]#', '', $request->price[$key]))];
                $syncData[$value] = ['quantity'=> $request->quantity[$key]];
            }
            //sync the sales order product relation
            $sales_order->products()->sync($syncData);

            $response = [
                'msg'=>'storeSalesOrderOk',
                'sales_order_id'=>$sales_order_id
            ];
            return response()->json($response);
        }
        else{
            die("Seriously, we only need an ajax call");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
            $sales_order = SalesOrder::findOrFail($id);
            //invoice related with this purchase order
            $invoice =  $sales_order->sales_order_invoice();
            //sales return related
            $sales_returns = $sales_order->sales_returns;
            $total_price = $this->count_total_price($sales_order);

            $main_product = $sales_order->products;

            $row_display = [];
            $main_products_arr = [];
            if($sales_order->products->count()){
                foreach($sales_order->products as $prod){
                    array_push($main_products_arr, $prod->main_product->id);
                }
            }

            $main_products = array_unique($main_products_arr);
            foreach($main_products as $mp_id){
                $row_display[] = [
                    'main_product_id'=>MainProduct::find($mp_id)->id,
                    'main_product'=>MainProduct::find($mp_id)->name,
                    'description'=>MainProduct::find($mp_id)->product->first()->description,
                    'image'=>MainProduct::find($mp_id)->image,
                    'family'=>MainProduct::find($mp_id)->family->name,
                    'unit'=>MainProduct::find($mp_id)->unit->name,
                    'quantity'=>MainProduct::find($mp_id)->product,
                    'category'=>MainProduct::find($mp_id)->category->name,
                    'ordered_products'=>$this->get_product_lists($mp_id, $id)
                ];
            }

            return view('sales_order.show')
                ->with('sales_order', $sales_order)
                ->with('total_price', $total_price)
                ->with('invoice', $invoice)
                ->with('sales_returns', $sales_returns)
                ->with('main_product',$main_product)
                ->with('row_display', $row_display);
    }

    protected function count_total_price($sales_order)
    {
        $sum_price = \DB::table('product_sales_order')
                    ->where('sales_order_id', $sales_order->id)
                    ->sum('price');
        return $sum_price;
    }
    public function edit($id)
    {
      if(\Auth::user()->can('edit-sales-order-module'))
      {
        $sales_order = SalesOrder::findOrFail($id);
        $customer_options = Customer::lists('name', 'id');
        $total_price = $this->count_total_price($sales_order);
        return view('sales_order.edit')
            ->with('sales_order', $sales_order)
            ->with('total_price', $total_price)
            ->with('customer_options', $customer_options);
      }else{
        return view('403');
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSalesOrderRequest $request)
    {
        if($request->ajax()){

            $id = $request->id;
            $sales_order = SalesOrder::findOrFail($id);
            $sales_order->customer_id = $request->customer_id;
            $sales_order->notes = $request->notes;
            $update = $sales_order->save();

            //Build sync data to update PO relation w/ products
            $syncData = [];
            foreach($request->product_id as $key=>$value){
                $syncData[$value] = ['quantity'=> $request->quantity[$key]];
            }

            //First, delete all the relation cloumn between product and purchase order on table prouduct_sales_order before syncing
            \DB::table('product_sales_order')->where('sales_order_id','=',$id)->delete();
            //Now time to sync the products
            $sales_order->products()->sync($syncData);

            $response = [
                'msg'=>'updateSalesOrderOk',
                'sales_order_id'=>$id
            ];
            return response()->json($response);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $sales_order = SalesOrder::findOrFail($request->sales_order_id);
        $sales_order->delete();
        //delete related data with this sales order in the database
        //product related

        //TODO relation table delete

        \DB::table('product_sales_order')->where('sales_order_id','=',$request->sales_order_id)->delete();
        return redirect('sales-order')
            ->with('successMessage', "Sales order has been deleted");
    }


}
