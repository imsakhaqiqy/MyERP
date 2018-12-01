<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\StoreSalesOrderRequest;
use App\Http\Requests\UpdateSalesOrderRequest;

use App\SalesOrder;
use App\Customer;
use App\Driver;
use App\Vehicle;
use App\MainProduct;
use App\InvoiceTerm;
use App\Product;

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
            $driver_options = Driver::lists('name','id');
            $vehicle_options = Vehicle::lists('number_of_vehicle','id');
            $so = SalesOrder::all();
            $code_fix = '';
            if(count($so) > 0)
            {
                $code_so = SalesOrder::all()->max('id');
                $sub_str = $code_so+1;
                $code_fix = 'SO-'.$sub_str;
            }else
            {
                $code_so = count($so)+1;
                $code_fix = 'SO-'.$code_so;
            }
            $sales_order = \DB::table('sales_orders')->latest()->first();
            $so = SalesOrder::all();
            $count_so = count($so)+1;
            $code_so = 'SO-0'.$count_so;
            return view('sales_order.create_sales')
                ->with('customer_options', $customer_options)
                ->with('driver_options',$driver_options)
                ->with('vehicle_options',$vehicle_options)
                ->with('sales_order',$sales_order)
                ->with('code_so',$code_fix);
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
                'creator'=>\Auth::user()->id,
                'driver_id'=>$request->driver_id,
                'vehicle_id'=>$request->vehicle_id,
                'ship_date'=>$request->ship_date,
            ];

            $save = SalesOrder::create($data);
            $sales_order_id = $save->id;

            //update the code for this sales order
            $code = \DB::table('sales_orders')->where('id', $sales_order_id)->update(['code'=>$request->do_number]);
            $sales_order = SalesOrder::find($sales_order_id);

            //Build sync data to store the relation w/ products
            $syncData = [];
            foreach($request->product_id as $key=>$value){
                //$syncData[$value] = ['quantity'=> $request->quantity[$key], 'price'=>floatval(preg_replace('#[^0-9.]#', '', $request->price[$key]))];
                $syncData[$value] = ['quantity'=> floatval(preg_replace('#[^0-9.]#', '', $request->quantity[$key]))];
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
            if($sales_order->status == 'posted'){
                $customer_options = Customer::lists('name', 'id');
                $driver_options = Driver::lists('name','id');
                $vehicle_options = Vehicle::lists('number_of_vehicle','id');
                $total_price = $this->count_total_price($sales_order);
                //$main_product = MainProduct::findOrFail($purchase_order->)
                // $main_product = $sales_order->products;
                //
                // $row_display = [];
                // $main_products_arr = [];
                // if($sales_order->products->count()){
                //     foreach($sales_order->products as $prod){
                //         array_push($main_products_arr, $prod->main_product->id);
                //     }
                // }
                //
                // $main_products = array_unique($main_products_arr);
                //
                // foreach($main_products as $mp_id){
                //     $row_display[] = [
                //         'main_product_id'=>MainProduct::find($mp_id)->id,
                //         'main_product'=>MainProduct::find($mp_id)->name,
                //         'image'=>MainProduct::find($mp_id)->image,
                //         'description'=>MainProduct::find($mp_id)->product->first()->description,
                //         'family'=>MainProduct::find($mp_id)->family->name,
                //         'unit'=>MainProduct::find($mp_id)->unit->name,
                //         'quantity'=>MainProduct::find($mp_id)->product->sum('stock'),
                //         'category'=>MainProduct::find($mp_id)->category->name,
                //         'ordered_products'=>$this->get_product_lists($mp_id, $id)
                //     ];
                // }
                // print_r($sales_order->id);
                // exit();
                return view('sales_order.edit')
                    ->with('sales_order', $sales_order)
                    ->with('total_price', $total_price)
                    ->with('customer_options', $customer_options)
                    ->with('driver_options',$driver_options)
                    ->with('vehicle_options',$vehicle_options);
                    // ->with('main_product',$main_product)
                    // ->with('row_display', $row_display);
            }
            else{

                return response('404');
            }
        }else{
            return view('403');
        }
    }

    protected function get_product_lists($mp_id, $po_id)
    {

        $product_id_arr = [];
        $product_ids = MainProduct::find($mp_id)->product;
        foreach($product_ids as $pid){
            $counter = \DB::table('product_sales_order')
                        ->where('product_id','=', $pid->id)
                        ->where('sales_order_id', '=', $po_id)
                        ->first();
            if(count($counter)){
                array_push($product_id_arr,array(
                    'family'=>Product::findOrFail($pid->id)->main_product->family->name,
                    'code'=>Product::findOrFail($pid->id)->name,
                    'description'=>Product::findOrFail($pid->id)->description,
                    'unit'=>Product::findOrFail($pid->id)->main_product->unit->name,
                    'quantity'=>$counter->quantity,
                    'product_id'=>$counter->product_id,
                    'category'=>Product::findOrFail($pid->id)->main_product->category->name,
                ));
            }
            //$product_id_arr[] = $pid->id;
        }
        return $product_id_arr;


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
            $sales_order->code = $request->do_number;
            $sales_order->customer_id = $request->customer_id;
            $sales_order->notes = $request->notes;
            $sales_order->driver_id = $request->driver_id;
            $sales_order->vehicle_id = $request->vehicle_id;
            $sales_order->ship_date = $request->ship_date;
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
        if(count($sales_order->sales_order_invoice)){
            if($sales_order->sales_order_invoice->sales_invoice_payment->count()){
                //bank sales invoice payment related
                $bank = $sales_order->sales_order_invoice->sales_invoice_payment;
                foreach ($bank as $key) {
                    \DB::table('bank_sales_invoice_payment')->where('sales_invoice_payment_id','=',$key->id)->delete();
                }
                //cash sales invoice payment related
                $cash = $sales_order->sales_order_invoice->sales_invoice_payment;
                foreach ($cash as $key) {
                    \DB::table('cash_sales_invoice_payment')->where('sales_invoice_payment_id','=',$key->id)->delete();
                }
            }
        }
        //product related
        \DB::table('product_sales_order')->where('sales_order_id','=',$request->sales_order_id)->delete();
        //invoice related
        \DB::table('sales_order_invoices')->where('sales_order_id','=',$request->sales_order_id)->delete();
        //return related
        \DB::table('sales_returns')->where('sales_order_id','=',$request->sales_order_id)->delete();
        //sales invoice payment related
        \DB::table('sales_invoice_payments')->where('sales_order_invoice_id','=',$request->payment_id)->delete();
        //delete transaction chart account
        if($request->id_invoice_delete == '' && $request->code_invoice_delete == '')
        {

        }else{
            \DB::table('transaction_chart_accounts')->where('reference','=',$request->id_invoice_delete)->where('source','=',$request->code_invoice_delete)->delete();
        }
        return redirect('sales-order')
            ->with('successMessage', "Sales order has been deleted");
    }

    public function printPdf(Request $request)
    {
        $data['sales_order'] = SalesOrder::findOrFail($request->id);
        $data['total_price'] = $this->count_total_price($data['sales_order']);

        $pdf =  \PDF::loadView('pdf.sales_order',$data);
        return $pdf->stream('sales_order.pdf');
    }

    public function printDO(Request $request)
    {
        $data['sales_order'] = SalesOrder::findOrFail($request->id);
        $sales_order = SalesOrder::findOrFail($request->id);
        $invoice_term_id = $sales_order->customer->invoice_term_id;
        $data['invoice_term'] = InvoiceTerm::findOrFail($invoice_term_id);
        $pdf = \PDF::loadView('pdf.do_sales_order',$data);
        return $pdf->stream('do_sales_order.pdf');
    }

    public function updateStatus(Request $request)
    {
        $sales_order = SalesOrder::findOrFail($request->sales_order_id);
        $previous_status = $sales_order->status;
        //only update the product inventory's stock if the required status is different with previous status
        if($previous_status != $request->status){
            $sales_order->status = $request->status;
            $updateStatus = $sales_order->save();
            switch ($request->status) {
                case 'processing':
                    $this->update_product_stock_from_process($sales_order);
                    break;
                case 'cancelled':
                    $this->update_product_stock_from_cancelled($sales_order);
                default:
                    # code...
                    break;
            }
        }

        return back()->with('successMessage', "Status has been changed");
    }

    protected function update_product_stock_from_process($sales_order)
    {
        if(count($sales_order->products)){
            foreach($sales_order->products as $product){
                //echo $product->id;
                $current_stock = \DB::table('products')->where('id', $product->id)->value('stock');
                $stock_to_sale = $product->pivot->quantity;
                $new_stock = $current_stock-$stock_to_sale;
                $update_product_stock = \DB::table('products')->where('id', $product->id)->update(['stock'=>$new_stock]);
            }
        }
        return TRUE;
    }

    protected function update_product_stock_from_cancelled($sales_order)
    {
        if(count($sales_order->products)){
            foreach($sales_order->products as $product){
                //echo $product->id;
                $current_stock = \DB::table('products')->where('id', $product->id)->value('stock');
                $stock_to_sale = $product->pivot->quantity;
                $new_stock = $current_stock+$stock_to_sale;
                $update_product_stock = \DB::table('products')->where('id', $product->id)->update(['stock'=>$new_stock]);
            }
        }
        return TRUE;
    }

    public function callSubProduct(Request $request)
    {
        if($request->ajax()){
            $results = array();
            $list_sub_product = \DB::table('products')->where('main_product_id',$request->id)->get();
            foreach($list_sub_product as $ls){
                array_push($results, array(
                    'family'=>MainProduct::find($request->id)->family->name,
                    'id'=>$ls->id,
                    'name'=>$ls->name,
                    'description'=>$ls->description,
                    'unit'=>MainProduct::find($request->id)->unit->name,
                    'category'=>MainProduct::find($request->id)->category->name,
                ));
            }
            return response()->json($results);
        }
    }

    public function list_piutang(Request $request)
    {
        if(\Auth::user()->can('list-piutang-module'))
        {
            $customer = Customer::get();
            $data_piutang = [];
            foreach ($customer as $cus) {
                $data_piutang [] = [
                    'id'=>$cus->id,
                    'code'=>$cus->code,
                    'name'=>$cus->name,
                    'sales'=>$this->list_sales($cus->id)
                ];
            }
            return view('sales_piutang.list')
                ->with('data_piutang',$data_piutang);
        }else{
            return view('403');
        }
    }

    protected function list_sales($customer_id)
    {
        $sales = \DB::table('sales_orders')->where('customer_id',$customer_id)->get();
        $data_sales = [];
        foreach ($sales as $key) {
            if(count(SalesOrder::findOrFail($key->id)->sales_order_invoice) == 0)
            {

            }else
            {
                $data_sales [] = [
                    'id'=>SalesOrder::findOrFail($key->id)->sales_order_invoice->id,
                    'code'=>SalesOrder::findOrFail($key->id)->sales_order_invoice->code,
                    'created_at'=>SalesOrder::findOrFail($key->id)->sales_order_invoice->created_at,
                    'due_date'=>SalesOrder::findOrFail($key->id)->sales_order_invoice->due_date,
                    'bill_price'=>SalesOrder::findOrFail($key->id)->sales_order_invoice->bill_price,
                    'paid_price'=>SalesOrder::findOrFail($key->id)->sales_order_invoice->paid_price,
                ];
            }
        }

        return $data_sales;
    }

}
