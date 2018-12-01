<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Requests\StoreSalesReturnRequest;

// use Modal
use App\SalesOrder;
use App\SalesReturn;
use App\Product;
use App\MainProduct;

class SalesReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('sales-order-return-module'))
        {
            return view('sales_return.index');
        }else{
            return view('403');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(\Auth::user()->can('create-sales-order-return-module'))
        {
            $sales_order = SalesOrder::findOrFail($request->sales_order_id);
            $so_id = $sales_order->sales_order_invoice;
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
                    'sum_stock'=>MainProduct::findOrFail($mp_id)->product->sum('stock'),
                    'sum_inventory_cost_first'=>\DB::table('transaction_chart_accounts')
                                                ->join('sub_chart_accounts','transaction_chart_accounts.sub_chart_account_id','=','sub_chart_accounts.id')
                                                ->where('sub_chart_accounts.name','=','PERSEDIAAN '.MainProduct::find($mp_id)->family->name)
                                                ->where('transaction_chart_accounts.type','=','masuk')
                                                ->where('transaction_chart_accounts.description','=','SALDO AWAL')
                                                ->sum('transaction_chart_accounts.amount'),
                    'sum_inventory_quantity_first'=>\DB::table('transaction_chart_accounts')
                                                ->join('sub_chart_accounts','transaction_chart_accounts.sub_chart_account_id','=','sub_chart_accounts.id')
                                                ->where('sub_chart_accounts.name','=','PERSEDIAAN '.MainProduct::find($mp_id)->family->name)
                                                ->where('transaction_chart_accounts.type','=','masuk')
                                                ->where('transaction_chart_accounts.description','=','SALDO AWAL')
                                                ->sum('transaction_chart_accounts.memo'),
                    'sum_inventory_cost_debit'=>\DB::table('transaction_chart_accounts')
                                                ->join('sub_chart_accounts','transaction_chart_accounts.sub_chart_account_id','=','sub_chart_accounts.id')
                                                ->where('sub_chart_accounts.name','=','PERSEDIAAN '.MainProduct::find($mp_id)->family->name)
                                                ->where('transaction_chart_accounts.type','=','masuk')
                                                ->where('transaction_chart_accounts.description','!=','SALDO AWAL')
                                                ->sum('transaction_chart_accounts.amount'),
                    'sum_inventory_cost_credit'=>\DB::table('transaction_chart_accounts')
                                                ->join('sub_chart_accounts','transaction_chart_accounts.sub_chart_account_id','=','sub_chart_accounts.id')
                                                ->where('sub_chart_accounts.name','=','PERSEDIAAN '.MainProduct::find($mp_id)->family->name)
                                                ->where('transaction_chart_accounts.type','=','keluar')
                                                ->where('transaction_chart_accounts.description','!=','SALDO AWAL')
                                                ->sum('transaction_chart_accounts.amount'),
                    'sum_price_purchase'=>\DB::table('product_purchase_order')
                                          ->join('products','product_purchase_order.product_id','=','products.id')
                                          ->join('main_products','products.main_product_id','=','main_products.id')
                                          ->where('main_products.family_id','=',MainProduct::find($mp_id)->family_id)
                                          ->sum('price'),
                    'sum_qty_purchase'=>\DB::table('product_purchase_order')
                                          ->join('products','product_purchase_order.product_id','=','products.id')
                                          ->join('main_products','products.main_product_id','=','main_products.id')
                                          ->where('main_products.family_id','=',MainProduct::find($mp_id)->family_id)
                                          ->sum('quantity'),
                    'unit'=>MainProduct::find($mp_id)->unit->name,
                    'quantity'=>MainProduct::find($mp_id)->product->sum('stock'),
                    'category'=>MainProduct::find($mp_id)->category->name,
                    'ordered_products'=>$this->get_product_lists($mp_id, $request->sales_order_id),
                ];
            }
            return view('sales_return.create_new')
                ->with('sales_order', $sales_order)
                ->with('so_id',$so_id)
                ->with('main_product',$main_product)
                ->with('row_display', $row_display);
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
    public function store(StoreSalesReturnRequest $request)
    {
        if($request->ajax()){
            foreach ($request->product_id as $key => $value) {
                $sales_return = new SalesReturn;
                $sales_return->sales_order_id = $request->sales_order_id;
                $sales_return->product_id = $request->product_id[$key];
                $sales_return->quantity = $request->returned_quantity[$key];
                $sales_return->notes = $request->notes[$key];
                $sales_return->created_by = \Auth::user()->id;
                $sales_return->save();
            }
            $temp_sales_return_data = [];
            $temp_sales_return_hpp = [];
            foreach($request->child_product_id as $key=>$value){
                array_push($temp_sales_return_data, array(
                    'sales_order_id'=>$request->sales_order_id,
                    'main_product_id'=>$request->main_product_id_return[$key],
                    'child_product_id'=>$request->child_product_id[$key],
                    'amount_return_per_unit'=>$request->amount_return_per_unit[$key],
                ));
                array_push($temp_sales_return_hpp, array(
                    'sales_order_id'=>$request->sales_order_id,
                    'main_product_id'=>$request->main_product_id_return[$key],
                    'child_product_id'=>$request->child_product_id[$key],
                    'amount_return_per_unit'=>$request->parent_sum_inventory_cost[$key],
                ));
            }

            \DB::table('temp_sales_return')->insert($temp_sales_return_data);
            \DB::table('temp_sales_return_hpp')->insert($temp_sales_return_hpp);

            $inv_account = [];
            $return_account = [];
            $cost_goods_account = [];
            foreach ($request->parent_product_id as $key => $value) {
                $total_amount = \DB::table('temp_sales_return')
                    ->where('sales_order_id', $request->sales_order_id)
                    ->where('main_product_id','=', $request->parent_product_id[$key])->sum('amount_return_per_unit');
                $total_amount_hpp = \DB::table('temp_sales_return_hpp')
                    ->where('sales_order_id', $request->sales_order_id)
                    ->where('main_product_id','=', $request->parent_product_id[$key])->sum('amount_return_per_unit');
                    array_push($inv_account,[
                        'amount'=>$total_amount_hpp,
                        'sub_chart_account_id'=>$request->inventory_account[$key],
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s'),
                        'reference'=>$request->sales_order_invoice_id,
                        'source'=>$request->sales_order_invoice_code,
                        'type'=>'masuk',
                        'description'=>'',
                        'memo'=>'PERSEDIAAN'
                    ]);
                    array_push($return_account,[
                        'amount'=>$total_amount,
                        'sub_chart_account_id'=>$request->return_account[$key],
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s'),
                        'reference'=>$request->sales_order_invoice_id,
                        'source'=>$request->sales_order_invoice_code,
                        'type'=>'masuk',
                        'description'=>'',
                        'memo'=>'RETURN PENJUALAN'
                    ]);
                    array_push($cost_goods_account,[
                        'amount'=>$total_amount_hpp,
                        'sub_chart_account_id'=>$request->cost_goods_account[$key],
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s'),
                        'reference'=>$request->sales_order_invoice_id,
                        'source'=>$request->sales_order_invoice_code,
                        'type'=>'keluar',
                        'description'=>'',
                        'memo'=>'HARGA POKOK PENJUALAN'
                    ]);
            }
            \DB::table('transaction_chart_accounts')->insert($inv_account);
            \DB::table('transaction_chart_accounts')->insert($return_account);
            \DB::table('transaction_chart_accounts')->insert($cost_goods_account);
            //now delete the temp_sales_return
            \DB::table('temp_sales_return')
                ->where('sales_order_id', '=', $request->sales_order_id)
                ->delete();
            \DB::table('temp_sales_return_hpp')
                ->where('sales_order_id', '=', $request->sales_order_id)
                ->delete();

            return response("storeSalesReturnOk");
        }else{
            return "Please enable javascript";
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
        $sales_return = SalesReturn::findOrFail($id);
        return view('sales_return.show_new')
                ->with('sales_return', $sales_return);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\Auth::user()->can('edit-sales-order-return-module'))
        {
            $sales_return = SalesReturn::findOrFail($id);
            $sales_order = SalesOrder::findOrFail($sales_return->sales_order->id);
            return view('sales_return.edit')
                ->with('sales_return',$sales_return)
                ->with('sales_order',$sales_order);
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
    public function update(Request $request, $id)
    {
        $sales_return = SalesReturn::findOrFail($request->sales_return_id);
        $sales_return->quantity = preg_replace('#[^0-9.]#','',$request->quantity);
        $sales_return->notes = $request->notes;
        $sales_return->save();

        $price_per_unit = floatval(preg_replace('#[^0-9.]#', '', $request->sales_return_price_per_unit))*preg_replace('#[^0-9.]#','',$request->quantity_first);
        $price_return = floatval(preg_replace('#[^0-9.]#', '', $request->sales_return_price_per_unit))*preg_replace('#[^0-9.]#','',$request->quantity);

        $amount_inventory = \DB::table('transaction_chart_accounts')->select('amount')->where([['reference',$request->sales_order_invoice_id],['sub_chart_account_id',$request->inventory_account],['type','masuk']])->value('amount');
        $set_amount = $amount_inventory-$price_per_unit;
        $new_amount = $set_amount+$price_return;
        \DB::table('transaction_chart_accounts')->where('sub_chart_account_id',$request->inventory_account)->where('reference',$request->sales_order_invoice_id)->where('type','masuk')->update(['amount'=>$new_amount]);

        $amount_return = \DB::table('transaction_chart_accounts')->select('amount')->where('sub_chart_account_id',$request->return_account)->where('reference',$request->sales_order_invoice_id)->where('type','masuk')->value('amount');
        $set_amount2 = $amount_return-$price_per_unit;
        $new_amount2 = $set_amount2+$price_return;
        \DB::table('transaction_chart_accounts')->where('sub_chart_account_id',$request->return_account)->where('reference',$request->sales_order_invoice_id)->where('type','masuk')->update(['amount'=>$new_amount2]);

        $amount_cost_goods = \DB::table('transaction_chart_accounts')->select('amount')->where('sub_chart_account_id',$request->cost_goods_account)->where('reference',$request->sales_order_invoice_id)->where('type','keluar')->value('amount');
        $set_amount3 = $amount_cost_goods-$price_per_unit;
        $new_amount3 = $set_amount3+$price_return;
        //\DB::table('transaction_chart_accounts')->where('sub_chart_account_id',$request->cost_goods_account)->where('reference',$request->sales_order_invoice_id)->where('type','keluar')->update(['amount'=>$new_amount3]);

        return redirect('sales-return')
            ->with('successMessage','Sales return has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $sales_return = SalesReturn::findOrFail($request->sales_return_id);
        $sales_return->delete();
        return redirect('sales-return')
            ->with('successMessage',"$sales_return->codehas been deleted");
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
                    'price'=>$counter->price,
                    'price_per_unit'=>$counter->price_per_unit,
                ));
            }
            //$product_id_arr[] = $pid->id;
        }
        return $product_id_arr;


    }

    public function changeToAccept(request $request)
    {
        //initiate sales return and product models
        $sales_return = SalesReturn::findOrFail($request->id_to_be_accept);
        $product = Product::findOrFail($sales_return->product_id);

        //get product name and sales order code refference
        $product_name = $sales_return->product->name;
        $sales_order_ref = $sales_return->sales_order->code;

        //get the current product stok
        $current_stock = $product->stock;

        //product quantity to be returned
        $qty_to_return = $sales_return->quantity;

        //compare to product quantities to return and current product stock
        //if current product stock is lower than quantities to be returned,  then throw an error
        if($qty_to_return > $current_stock){
            return redirect('sales-return')
                    ->with('errorMessage',"Returned product quantity is higher than product stock, it is must be an error, please fix your sales return");
        }else{
            //update product stock by subtracting curent product stock by product quantity to returned
            $product->stock = $current_stock+$qty_to_return;
            $product->save();

            //change sales return to sent
            $sales_return->status = "accept";
            $sales_return->save();
            return redirect('sales-return')
                    ->with('successMessage',"$product_name on $sales_order_ref has been returned to the supplier");
        }
    }

    public function changeToResent(Request $request)
    {
        $sales_return = SalesReturn::findOrFail($request->id_to_be_resent);
        //get product name and sales order code refference
        $product_id = $sales_return->product_id;
        $quantity = $sales_return->quantity;
        $product_name = $sales_return->product->name;
        $sales_order_ref = $sales_return->sales_order->code;
        $sales_return->status = "resent";
        $sales_return->save();

        //now re add the quantity that returned to the stock
        $product = Product::findOrFail($product_id);
        $current_stock = $product->stock;
        $new_stock = $current_stock-$quantity;
        $product->stock = $new_stock;
        $product->save();

        $price_return = floatval(preg_replace('#[^0-9.]#', '', $request->sales_return_price_per_unit_to_complete))*preg_replace('#[^0-9.]#','',$request->sales_return_quantity_to_complete);
        $price_return_sales = floatval(preg_replace('#[^0-9.]#', '', $request->sales_return_price_per_unit_sales))*preg_replace('#[^0-9.]#','',$request->sales_return_quantity_to_complete);

        $amount_inventory = \DB::table('transaction_chart_accounts')->select('amount')->where([['reference',$request->sales_order_invoice_id_to_complete],['sub_chart_account_id',$request->inventory_account],['type','masuk']])->value('amount');
        $new_amount = $amount_inventory-$price_return;
        \DB::table('transaction_chart_accounts')->where('sub_chart_account_id',$request->inventory_account)->where('reference',$request->sales_order_invoice_id_to_complete)->where('type','masuk')->update(['amount'=>$new_amount]);

        $amount_cost_goods = \DB::table('transaction_chart_accounts')->select('amount')->where([['reference',$request->sales_order_invoice_id_to_complete],['sub_chart_account_id',$request->cost_goods_account],['type','keluar']])->value('amount');
        $new_amount2 = $amount_cost_goods-$price_return;
        \DB::table('transaction_chart_accounts')->where('sub_chart_account_id',$request->cost_goods_account)->where('reference',$request->sales_order_invoice_id_to_complete)->where('type','keluar')->update(['amount'=>$new_amount2]);

        $amount_sales_return = \DB::table('transaction_chart_accounts')->select('amount')->where([['reference',$request->sales_order_invoice_id_to_complete],['sub_chart_account_id',$request->sales_return_account],['type','masuk']])->value('amount');
        $new_amount3 = $amount_sales_return-$price_return_sales;
        \DB::table('transaction_chart_accounts')->where('sub_chart_account_id',$request->sales_return_account)->where('reference',$request->sales_order_invoice_id_to_complete)->where('type','masuk')->update(['amount'=>$new_amount3]);

        return redirect('sales-return')
                ->with('successMessage',"$product_name has been added back to inventory from $sales_order_ref");
    }
}
