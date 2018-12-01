<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;

use App\PurchaseOrder;
use App\Supplier;
use App\MainProduct;
use App\PurchaseOrderInvoice;
use App\Product;



class PurchaseOrderController extends Controller
{

    public function wp_post(Request $request)
    {

        $returned = [];
        $returned['firstname'] = $request->firstname;
        $returned['lastname']=$request->lastname;
        $returned['email']=$request->email;
        $returned['mobile']=$request->mobile;
        $returned['address']=$request->address;
        $returned['countrycode']=$request->countrycode;
        $returned['regioncode']=$request->regioncode;
        $returned['citycode']=$request->citycode;
        $returned['productcode']=$request->productcode;
        $returned['validfrom']=$request->validfrom;
        $returned['paymenttypecode']=$request->paymenttypecode;
        $returned['signature']=$request->signature;
        $returned['ordernumber']= 77;
        return response()->json($returned);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('purchase-order-module')){
            return view('purchase_order.index');
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
        if(\Auth::user()->can('create-purchase-order-module')){
            $supplier_options = Supplier::lists('name', 'id');
            return view('purchase_order.create')
                ->with('supplier_options', $supplier_options);
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
    public function store(StorePurchaseOrderRequest $request)
    {
        if($request->ajax()){
            $po = PurchaseOrder::all();
            $code_fix = '';
            if(count($po) > 0)
            {
                $code_po = PurchaseOrder::all()->max('id');
                $sub_str = $code_po+1;
                $code_fix = 'PO-'.$sub_str;
            }else
            {
                $code_po = count($po)+1;
                $code_fix = 'PO-'.$code_po;
            }
            // $max_po_id = \DB::table('purchase_orders')->max('id');
            // $next_po_id = $max_po_id+1;
            // $code = 'PO-'.$next_po_id;

            $data = [
                'code'=>$code_fix,
                'supplier_id'=>$request->supplier_id,
                'notes'=>$request->notes,
                'creator'=>\Auth::user()->id
            ];

            $save = PurchaseOrder::create($data);
            $purchase_order_id = $save->id;

            $purchase_order = PurchaseOrder::find($purchase_order_id);

            //Build sync data to store the relation w/ products
            $syncData = [];
            foreach($request->product_id as $key=>$value){
                //$syncData[$value] = ['quantity'=> $request->quantity[$key], 'price'=>floatval(preg_replace('#[^0-9.]#', '', $request->price[$key]))];
                $syncData[$value] = ['quantity'=> $request->quantity[$key]];
            }
            //sync the purchase order product relation
            $purchase_order->products()->sync($syncData);

            $response = [
                'msg'=>'storePurchaseOrderOk',
                'purchase_order_id'=>$purchase_order_id
            ];
            return response()->json($response);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function count_total_price($purchase_order)
    {
        $sum_price = \DB::table('product_purchase_order')
                    ->where('purchase_order_id', $purchase_order->id)
                    ->sum('price');
        return $sum_price;
    }

    public function show($id)
    {
        $purchase_order = PurchaseOrder::findOrFail($id);
        //invoice related with this purchase order
        $invoice =  $purchase_order->purchase_order_invoice();
        //purchase returns related
        $purchase_returns = $purchase_order->purchase_returns;
        $total_price = $this->count_total_price($purchase_order);

        $main_product = $purchase_order->products;

        $row_display = [];
        $main_products_arr = [];
        if($purchase_order->products->count()){
            foreach($purchase_order->products as $prod){
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
                'quantity'=>MainProduct::find($mp_id)->product->sum('stock'),
                'category'=>MainProduct::find($mp_id)->category->name,
                'ordered_products'=>$this->get_product_lists($mp_id, $id)
            ];
        }

        return view('purchase_order.show')
            ->with('purchase_order', $purchase_order)
            ->with('total_price', $total_price)
            ->with('invoice', $invoice)
            ->with('purchase_returns', $purchase_returns)
            ->with('main_product',$main_product)
            ->with('row_display', $row_display);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\Auth::user()->can('edit-purchase-order-module'))
        {
            $purchase_order = PurchaseOrder::findOrFail($id);
            $supplier_options = Supplier::lists('name', 'id');
            $total_price = $this->count_total_price($purchase_order);
            //$main_product = MainProduct::findOrFail($purchase_order->)
            $main_product = $purchase_order->products;

            $row_display = [];
            $main_products_arr = [];
            if($purchase_order->products->count()){
                foreach($purchase_order->products as $prod){
                    array_push($main_products_arr, $prod->main_product->id);
                }
            }

            $main_products = array_unique($main_products_arr);

            foreach($main_products as $mp_id){
                $row_display[] = [
                    'main_product_id'=>MainProduct::find($mp_id)->id,
                    'main_product'=>MainProduct::find($mp_id)->name,
                    'image'=>MainProduct::find($mp_id)->image,
                    'description'=>MainProduct::find($mp_id)->product->first()->description,
                    'family'=>MainProduct::find($mp_id)->family->name,
                    'unit'=>MainProduct::find($mp_id)->unit->name,
                    'quantity'=>MainProduct::find($mp_id)->product->sum('stock'),
                    'category'=>MainProduct::find($mp_id)->category->name,
                    'ordered_products'=>$this->get_product_lists($mp_id, $id)
                ];
            }
            /*echo '<pre>';
            print_r($row_display);
            echo '</pre>';

            exit();*/

            return view('purchase_order.edit')
                ->with('purchase_order', $purchase_order)
                ->with('total_price', $total_price)
                ->with('supplier_options', $supplier_options)
                ->with('main_product',$main_product)
                ->with('row_display', $row_display);
        }else{
            return view('403');
        }

    }


    protected function get_product_lists($mp_id, $po_id)
    {

        $product_id_arr = [];
        $product_ids = MainProduct::find($mp_id)->product;
        foreach($product_ids as $pid){
            $counter = \DB::table('product_purchase_order')
                        ->where('product_id','=', $pid->id)
                        ->where('purchase_order_id', '=', $po_id)
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
    public function update(UpdatePurchaseOrderRequest $request)
    {
        if($request->ajax()){

            $id = $request->id;
            $purchase_order = PurchaseOrder::findOrFail($id);
            $purchase_order->supplier_id = $request->supplier_id;
            $purchase_order->notes = $request->notes;
            $update = $purchase_order->save();

            //Build sync data to update PO relation w/ products
            $syncData = [];
            foreach($request->product_id as $key=>$value){
                $syncData[$value] = ['quantity'=> $request->quantity[$key]];
            }

            //First, delete all the relation cloumn between product and purchase order on table prouduct_purchase_order before syncing
            \DB::table('product_purchase_order')->where('purchase_order_id','=',$id)->delete();
            //Now time to sync the products
            $purchase_order->products()->sync($syncData);

            $response = [
                'msg'=>'updatePurchaseOrderOk',
                'purchase_order_id'=>$id
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
        $purchase_order = PurchaseOrder::findOrFail($request->purchase_order_id);
        $purchase_order->delete();

        //delete all the related data with this purchase order in the database
        if(count($purchase_order->purchase_order_invoice)){
            if($purchase_order->purchase_order_invoice->purchase_invoice_payment->count()){
                //bank purchase invoice payment related
                $bank = $purchase_order->purchase_order_invoice->purchase_invoice_payment;
                foreach ($bank as $key) {
                    \DB::table('bank_purchase_invoice_payment')->where('purchase_invoice_payment_id','=',$key->id)->delete();
                }
                $cash = $purchase_order->purchase_order_invoice->purchase_invoice_payment;
                foreach ($cash as $key) {
                    \DB::table('cash_purchase_invoice_payment')->where('purchase_invoice_payment_id','=',$key->id)->delete();
                }
            }
        }
        //product related
        \DB::table('product_purchase_order')->where('purchase_order_id','=',$request->purchase_order_id)->delete();
        //invoice related
        \DB::table('purchase_order_invoices')->where('purchase_order_id','=',$request->purchase_order_id)->delete();
        //return related
        \DB::table('purchase_returns')->where('purchase_order_id','=',$request->purchase_order_id)->delete();
        //purchase invoice payment related
        \DB::table('purchase_invoice_payments')->where('purchase_order_invoice_id','=',$request->payment_id)->delete();
        if($request->id_invoice_delete == '' && $request->code_invoice_delete == '')
        {

        }else
        {
            //transaction chart account
            \DB::table('transaction_chart_accounts')->where('reference','=',$request->id_invoice_delete)->where('source','=',$request->code_invoice_delete)->delete();
        }
        return redirect('purchase-order')
            ->with('successMessage', "Purchase Order has been deleted");
    }

    public function printPdf(Request $request){

        $data['purchase_order'] = PurchaseOrder::findOrFail($request->id);
        $data['total_price'] = $this->count_total_price($data['purchase_order']);

        $pdf = \PDF::loadView('pdf.purchase_order', $data);
        return $pdf->stream('purchase_order.pdf');
    }


    public function accept(Request $request)
    {

        $purchase_order = PurchaseOrder::findOrFail($request->id_to_be_accepted);
        $purchase_order->status = 'accepted';
        $purchase_order->save();

        //update stock quantity to each products based on the accepted purchase order
        //error prevent control incase there are no relational product
        if(count($purchase_order->products) > 0){
            foreach($purchase_order->products as $product){

                //$prod_qu []= ['id'=>$product->id, 'stock'=>$product->pivot->quantity];
                $current_stock = \DB::table('products')->where('id', $product->id)->value('stock');
                $added_stock = $current_stock+$product->pivot->quantity;
                $update_stock = \DB::table('products')
                                ->where('id', $product->id)
                                ->update(['stock'=> $added_stock]);
            }
        }


        //return redirect('purchase-order');
        return back();

    }

    public function complete(Request $request)
    {
        $purchase_order = PurchaseOrder::findOrFail($request->id_to_be_completed);
        $purchase_order->status = 'completed';
        $purchase_order->save();
        return back();
    }

    public function callSubProduct(Request $request)
    {
        if($request->ajax()){
            $results = array();
            $list_sub_product = \DB::table('products')->where('main_product_id',$request->id)->get();
            foreach($list_sub_product as $ls){
                array_push($results,[
                    'id'=>$ls->id,
                    'name'=>$ls->name,
                    'family'=>MainProduct::find($request->id)->family->name,
                    'description'=>$ls->description,
                    'unit'=>MainProduct::find($request->id)->unit->name,
                    'category'=>MainProduct::find($request->id)->category->name,
                    'created_at'=>$ls->created_at,
                ]);
            }
            return response()->json($results);
        }
    }

    public function list_hutang(Request $request)
    {
        if(\Auth::user()->can('list-hutang-module'))
        {
            $supplier = Supplier::get();
            $data_hutang = [];
            foreach ($supplier as $sup) {
                $data_hutang [] = [
                    'id'=>$sup->id,
                    'code'=>$sup->code,
                    'name'=>$sup->name,
                    'balance'=>'',
                    'purchase'=>$this->list_purchase($sup->id)
                ];
            }
            return view('purchase_hutang.index')
                ->with('data_hutang',$data_hutang);
        }else{
            return view('403');
        }
    }

    protected function list_purchase($supplier_id)
    {
        $purchase = \DB::table('purchase_orders')->where('supplier_id',$supplier_id)->get();
        $data_purchase = [];
        foreach ($purchase as $key) {
            if(count(PurchaseOrder::findOrFail($key->id)->purchase_order_invoice) == 0){

            }else{
                $data_purchase [] =[
                    'id'=>PurchaseOrder::findOrFail($key->id)->purchase_order_invoice->id,
                    'code'=>PurchaseOrder::findOrFail($key->id)->purchase_order_invoice->code,
                    'created_at'=>PurchaseOrder::findOrFail($key->id)->purchase_order_invoice->created_at,
                    'due_date'=>PurchaseOrder::findOrFail($key->id)->purchase_order_invoice->term,
                    'bill_price'=>PurchaseOrder::findOrFail($key->id)->purchase_order_invoice->bill_price,
                    'paid_price'=>PurchaseOrder::findOrFail($key->id)->purchase_order_invoice->paid_price,
                ];
            }
        }

        return $data_purchase;
    }

}
