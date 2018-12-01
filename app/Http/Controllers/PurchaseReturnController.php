<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

//form requests
use App\Http\Requests\StorePurchaseReturnRequest;

use App\PurchaseOrder;
use App\PurchaseReturn;
use App\Product;
use App\MainProduct;

class PurchaseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('purchase-order-return-module'))
        {
            return view('purchase_return.index');
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
        if(\Auth::user()->can('create-purchase-order-return-module'))
        {
            $purchase_order = PurchaseOrder::findOrFail($request->purchase_order_id);
            $po_id = $purchase_order->purchase_order_invoice;
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
                      'ordered_products'=>$this->get_product_lists($mp_id, $request->purchase_order_id),
                  ];
              }
              return view('purchase_return.create')
                  ->with('purchase_order', $purchase_order)
                  ->with('po_id',$po_id)
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
    public function compare_stock_and_quantity(){
        $error = array();
        $error[]= 'returned_quantity is higher than stock';
        $error[]= 'returned_quantity is higher than stock again';
        return $error;

    }

    public function store(StorePurchaseReturnRequest $request)
    {
        if($request->ajax()){

            /*foreach($request->product_id as $key=>$value){
                $current_stock =  \DB::table('products')->where('id', $request->product_id[$key])->first()->stock;
                $returned_quantity = $request->returned_quantity[$key];
                if($current_stock < $returned_quantity){
                    echo "Over limit";
                    break;
                }
            }*/
            foreach($request->product_id as $key=>$value){
                $purchase_return = new PurchaseReturn;
                $purchase_return->purchase_order_id = $request->purchase_order_id;
                $purchase_return->product_id = $request->product_id[$key];
                $purchase_return->quantity = preg_replace('#[^0-9]#', '', $request->returned_quantity[$key]);
                $purchase_return->notes = $request->notes[$key];
                $purchase_return->created_by = \Auth::user()->id;
                $purchase_return->save();
            }

            $temp_purchase_return_data = [];
            foreach($request->child_product_id as $key=>$value){
                array_push($temp_purchase_return_data, array(
                    'purchase_order_id'=>$request->purchase_order_id,
                    'main_product_id'=>$request->main_product_id_return[$key],
                    'child_product_id'=>$request->child_product_id[$key],
                    'amount_return_per_unit'=>$request->amount_return_per_unit[$key],
                ));
            }

            \DB::table('temp_purchase_return')->insert($temp_purchase_return_data);


            $inv_account = [];
            //$return_account = [];
            //$cost_goods_account = [];
            foreach ($request->parent_product_id as $key => $value) {
                $total_amount = \DB::table('temp_purchase_return')
                    ->where('purchase_order_id', $request->purchase_order_id)
                    ->where('main_product_id','=', $request->parent_product_id[$key])->sum('amount_return_per_unit');
                    array_push($inv_account,[
                        'amount'=>$total_amount,
                        'sub_chart_account_id'=>$request->inventory_account[$key],
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s'),
                        'reference'=>$request->purchase_order_invoice_id,
                        'source'=>$request->purchase_order_invoice_code,
                        'type'=>'keluar',
                        'description'=>'',
                        'memo'=>''
                    ]);
                    // array_push($return_account,[
                    //     'amount'=>$total_amount,
                    //     'sub_chart_account_id'=>$request->return_account[$key],
                    //     'created_at'=>date('Y-m-d H:i:s'),
                    //     'updated_at'=>date('Y-m-d H:i:s'),
                    //     'reference'=>$request->sales_order_invoice_id,
                    //     'source'=>'sales_order_invoices',
                    //     'type'=>'masuk',
                    // ]);
                    // array_push($cost_goods_account,[
                    //     'amount'=>$total_amount,
                    //     'sub_chart_account_id'=>$request->cost_goods_account[$key],
                    //     'created_at'=>date('Y-m-d H:i:s'),
                    //     'updated_at'=>date('Y-m-d H:i:s'),
                    //     'reference'=>$request->sales_order_invoice_id,
                    //     'source'=>'sales_order_invoices',
                    //     'type'=>'keluar',
                    // ]);
            }
            \DB::table('transaction_chart_accounts')->insert($inv_account);
            // \DB::table('transaction_chart_accounts')->insert($return_account);
            // \DB::table('transaction_chart_accounts')->insert($cost_goods_account);
            //now delete the temp_sales_return
            \DB::table('temp_purchase_return')
                ->where('purchase_order_id', '=', $request->purchase_order_id)
                ->delete();

            return response("storePurchaseReturnOk");
        }
        else{
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
        $purchase_return = PurchaseReturn::findOrFail($id);
        return view('purchase_return.show')
            ->with('purchase_return', $purchase_return);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\Auth::user()->can('edit-purchase-order-return-module'))
        {
            $purchase_return = PurchaseReturn::findOrFail($id);
            $purchase_order = PurchaseOrder::findOrFail($purchase_return->purchase_order_id);
            return view('purchase_return.edit')
                ->with('purchase_return', $purchase_return)
                ->with('purchase_order', $purchase_order);
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
        $purchase_return = PurchaseReturn::findOrFail($request->purchase_return_id);
        $purchase_return->quantity = preg_replace('#[^0-9]#', '', $request->quantity);
        $purchase_return->notes = $request->notes;
        $purchase_return->save();

        $price_per_unit = floatval(preg_replace('#[^0-9.]#', '', $request->purchase_return_price_per_unit))*preg_replace('#[^0-9.]#','',$request->quantity_first);
        $price_return = floatval(preg_replace('#[^0-9.]#', '', $request->purchase_return_price_per_unit))*preg_replace('#[^0-9.]#','',$request->quantity);


        $amount_inventory = \DB::table('transaction_chart_accounts')->select('amount')->where([['reference',$request->purchase_order_invoice_id],['sub_chart_account_id',$request->inventory_account],['type','keluar']])->value('amount');
        $set_amount = $amount_inventory-$price_per_unit;
        $new_amount = $set_amount+$price_return;
        \DB::table('transaction_chart_accounts')->where('sub_chart_account_id',$request->inventory_account)->where('reference',$request->purchase_order_invoice_id)->where('type','keluar')->update(['amount'=>$new_amount]);

        return redirect('purchase-return');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $purchase_return = PurchaseReturn::findOrFail($request->purchase_return_id);
        $purchase_return->delete();
        return redirect('purchase-return')
          ->with('successMessage',"$purchase_return->codehas been deleted");
    }


    public function changeToSent(Request $request){

        //initiate Purchase return and Product models
        $purchase_return = PurchaseReturn::findOrFail($request->id_to_be_send);
        $product = Product::findOrFail($purchase_return->product_id);

        //get product name and purchase order code refference
        $product_name = $purchase_return->product->name;
        $purchase_order_ref = $purchase_return->purchase_order->code;
        //get the current product stok
        $current_stock = $product->stock;
        //product quantity to be returned
        $qty_to_return = $purchase_return->quantity;

        //compare to product quantities to return and current product stock
        //if current product stock is lower than quantities to be returned, then throw an errror
        if($qty_to_return > $current_stock){
            return redirect('purchase-return')
                ->with('errorMessage', "Returned product quantity is higher than product stock, it is must be an error, please fix your purchase return");
        }
        else{
            //update product stock by subtracting curent product stock by product quantity to returned
            $product->stock = $current_stock-$qty_to_return;
            $product->save();

            //change purchase return to sent
            $purchase_return->status = 'sent';
            $purchase_return->save();

            return redirect('purchase-return')
                ->with('successMessage', "$product_name on $purchase_order_ref has been returned to the supplier");
        }

    }

    public function changeToCompleted(Request $request){

        $purchase_return = PurchaseReturn::findOrFail($request->id_to_be_completed);
        //get product name and purchase order code refference
        $product_id = $purchase_return->product_id;
        $quantity = $purchase_return->quantity;
        $product_name = $purchase_return->product->name;
        $purchase_order_ref = $purchase_return->purchase_order->code;
        $purchase_return->status = 'completed';
        $purchase_return->save();

        //now re add the quantity that returned to the stock
        $product = Product::findOrFail($product_id);
        $current_stock = $product->stock;
        $new_stock = $current_stock+$quantity;
        $product->stock = $new_stock;
        $product->save();

        $price_return = floatval(preg_replace('#[^0-9.]#', '', $request->purchase_return_price_per_unit_to_complete))*preg_replace('#[^0-9.]#','',$request->purchase_return_quantity_to_complete);

        $amount_inventory = \DB::table('transaction_chart_accounts')->select('amount')->where([['reference',$request->purchase_order_invoice_id_to_complete],['sub_chart_account_id',$request->inventory_account],['type','keluar']])->value('amount');
        $new_amount = $amount_inventory-$price_return;

        \DB::table('transaction_chart_accounts')->where('sub_chart_account_id',$request->inventory_account)->where('reference',$request->purchase_order_invoice_id_to_complete)->where('type','keluar')->update(['amount'=>$new_amount]);

        //delete transaction chart account
        return redirect('purchase-return')
            ->with('successMessage', "$product_name has been added back to inventory from $purchase_order_ref");
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
                    'price'=>$counter->price,
                ));
            }
            //$product_id_arr[] = $pid->id;
        }
        return $product_id_arr;
    }

}
