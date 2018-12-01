<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\StoreSalesOrderInvoiceRequest;
use App\Http\Requests\UpdateSalesOrderInvoiceRequest;
use App\Http\Requests\StoreSalesInvoicePaymentRequest;
use App\Http\Requests\StoreSalesPaymentCash;
use App\Http\Requests\StoreSalesPaymentTransfer;
use App\Http\Requests\StoreSalesPaymentGiro;
//Carbon package
Use Carbon\Carbon;

use App\SalesOrderInvoice;
use App\SalesOrder;
use App\InvoiceTerm;
use App\PaymentMethod;
use App\SalesInvoicePayment;
use App\Cash;
use App\Bank;
use App\GiroSalesInvoicePayment;
use App\BankSalesInvoicePayment;
use App\CashSalesInvoicePayment;
use App\TransactionChartAccount;
use DB;
use App\Customer;
use App\MainProduct;
use App\Product;

class SalesOrderInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('sales-order-invoice-module'))
        {
            return view('sales_order.list_invoice');
        }else{
            return view('403');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,$id)
    {
        if(\Auth::user()->can('create-sales-order-invoice-module'))
        {
            $sales_order = SalesOrder::findOrFail($request->sales_order_id);

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
                                          ->where('main_products.family_id','=',14)
                                          ->sum('quantity'),
                    'unit'=>MainProduct::find($mp_id)->unit->name,
                    'category'=>MainProduct::find($mp_id)->category->name,
                    'ordered_products'=>$this->get_product_lists($mp_id, $id)
                ];
            }
            return view('sales_order.create_invoice_new')
                ->with('total_price', $this->count_total_price($sales_order))
                ->with('sales_order', $sales_order)
                ->with('main_product',$main_product)
                ->with('row_display', $row_display);
        }else{
            return view('403');
        }
    }

    protected function count_total_price($sales_order)
    {
        $sum_price = \DB::table('product_sales_order')
                    ->where('sales_order_id', $sales_order->id)
                    ->sum('price');
        return $sum_price;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSalesOrderInvoiceRequest $request)
    {
       if($request->ajax()){
            $sales_order_id = $request->sales_order_id;
            $sales_order_code = SalesOrder::findOrFail($sales_order_id)->code;
            $customer_invoice_term = SalesOrder::findOrFail($sales_order_id)->customer->invoice_term->day_many;
            $sales_order_created_at = SalesOrder::findOrFail($sales_order_id)->created_at;

            // $current = Carbon::now();
            // $due_date = $current->addDays($customer_invoice_term);
            $date = date_create($sales_order_created_at);
            date_add($date,date_interval_create_from_date_string($customer_invoice_term.' days'));
            $due_date = date_format($date,"Y-m-d");
            $price_ppn = (floatval(preg_replace('#[^0-9.]#', '', $request->bill_price))/100)*$request->ppn;
            $data = [
                'code'=>'INV-'.$sales_order_code,
                'sales_order_id' =>$request->sales_order_id,
                'bill_price'=>floatval(preg_replace('#[^0-9.]#', '', $request->bill_price)),
                'notes'=>$request->notes,
                'created_by'=>\Auth::user()->id,
                'due_date'=> $due_date,
                'persen_ppn'=>$request->ppn,
                'price_ppn'=>$price_ppn,
                'ppn_hidden'=>$request->ppn_hidden,
            ];

            $save = SalesOrderInvoice::create($data);
            //get last inserted id of sales_order_invoice
            $sales_order_invoice_id = $save->id;
            $sales_order_invoice_code = $save->code;

            if($save){

                //find sales_order model
                $sales_order = SalesOrder::findOrFail($request->sales_order_id);
                $customer = Customer::findOrFail($sales_order->customer_id);
                //Build sync data to update SalesOrder relation w/ products
                $syncData = [];
                foreach($request->product_id as $key=>$value){
                    $syncData[$value] = [
                        'quantity'=> $request->quantity[$key],
                        'price'=>floatval(preg_replace('#[^0-9.]#', '', $request->price[$key])),
                        'price_per_unit'=>floatval(preg_replace('#[^0-9.]#', '', $request->price_per_unit[$key]))
                    ];
                }
                //First, delete all the relation column for product and sales order on table prouduct_sales_order before syncing
                \DB::table('product_sales_order')->where('sales_order_id','=',$sales_order->id)->delete();
                //Now time to sync the products
                $sales_order->products()->sync($syncData);

                // insert temp sales invoice
                $temp_sales_invoice_data = [];
                foreach ($request->product_id as $key => $value) {
                  array_push($temp_sales_invoice_data, array(
                    'sales_order_id'=>$request->sales_order_id,
                    'main_product_id'=>$request->main_product_id_child[$key],
                    'child_product_id'=>$request->product_id[$key],
                    'price_per_unit'=>floatval(preg_replace('#[^0-9.]#','',$request->price[$key])),
                  ));
                }
                //save temp sales invoice
                \DB::table('temp_sales_invoice')->insert($temp_sales_invoice_data);


                //account inventory and receivable save
                $inv_account = [];
                $sales_account = [];
                $cost_goods_account = [];
                foreach ($request->parent_product_id as $key => $value) {
                    $total_amount = \DB::table('temp_sales_invoice')
                      ->where('sales_order_id',$request->sales_order_id)
                      ->where('main_product_id',$request->parent_product_id[$key])->sum('price_per_unit');
                    array_push($inv_account,[
                        'amount'=>$request->parent_sum_inventory_cost[$key]*$request->parent_sum_quantity[$key],
                        'sub_chart_account_id'=>$request->inventory_account[$key],
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s'),
                        'reference'=>$sales_order_invoice_id,
                        'source'=>$sales_order_invoice_code,
                        'type'=>'keluar',
                        'description'=>'INVOICE TO : '.$customer->name,
                        'memo'=>'PERSEDIAAN'
                    ]);
                    array_push($sales_account,[
                        'amount'=>$total_amount,
                        'sub_chart_account_id'=>$request->sales_order_account[$key],
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s'),
                        'reference'=>$sales_order_invoice_id,
                        'source'=>$sales_order_invoice_code,
                        'type'=>'masuk',
                        'description'=>'INVOICE TO : '.$customer->name,
                        'memo'=>'PENJUALAN'
                    ]);
                    array_push($cost_goods_account,[
                        'amount'=>$request->parent_sum_inventory_cost[$key]*$request->parent_sum_quantity[$key],
                        'sub_chart_account_id'=>$request->cost_goods_account[$key],
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s'),
                        'reference'=>$sales_order_invoice_id,
                        'source'=>$sales_order_invoice_code,
                        'type'=>'masuk',
                        'description'=>'INVOICE TO : '.$customer->name,
                        'memo'=>'HARGA POKOK PENJUALAN'
                    ]);
                }

                //now delete temp sales invoice
                \DB::table('temp_sales_invoice')
                  ->where('sales_order_id','=',$request->sales_order_id)
                  ->delete();

                //now save $inv_account,$sales_account,$cost_goods_account
                \DB::table('transaction_chart_accounts')->insert($inv_account);
                \DB::table('transaction_chart_accounts')->insert($sales_account);
                \DB::table('transaction_chart_accounts')->insert($cost_goods_account);

                //now save account receivable
                $transaction_sub_chart_account = New TransactionChartAccount;
                $transaction_sub_chart_account->amount = floatval(preg_replace('#[^0-9.]#', '', $request->bill_price));
                $transaction_sub_chart_account->sub_chart_account_id = $request->select_account;
                $transaction_sub_chart_account->reference = $sales_order_invoice_id;
                $transaction_sub_chart_account->source = $sales_order_invoice_code;
                $transaction_sub_chart_account->type = 'masuk';
                $transaction_sub_chart_account->description = 'INVOICE TO : '.$customer->name;
                $transaction_sub_chart_account->memo = '';
                $transaction_sub_chart_account->save();
            }

            $response = [
                'msg'=>'storeSalesOrderInvoiceOk',
                'sales_order_id'=>$request->sales_order_id
            ];
            return response()->json($response);
        }
        else{

            return "Please activate javascript in your browser";
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
        $sales_order_invoice = SalesOrderInvoice::findOrFail($id);
        $sales_order = SalesOrder::findOrFail($sales_order_invoice->sales_order->id);

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
                'category'=>MainProduct::find($mp_id)->category->name,
                'ordered_products'=>$this->get_product_lists($mp_id, $sales_order->id)
            ];
        }
        return view('sales_order.show_invoice')
            ->with('sales_order_invoice', $sales_order_invoice)
            ->with('sales_order', $sales_order)
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
        if(\Auth::user()->can('edit-sales-order-invoice-module'))
        {
            $sales_order_invoice = SalesOrderInvoice::findOrFail($id);
            $sales_order = SalesOrder::findOrFail($sales_order_invoice->sales_order->id);

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
                    'category'=>MainProduct::find($mp_id)->category->name,
                    'ordered_products'=>$this->get_product_lists($mp_id, $sales_order->id)
                ];
            }
            return view('sales_order.edit_invoice_new')
                    ->with('sales_order_invoice',$sales_order_invoice)
                    ->with('sales_order',$sales_order)
                    ->with('main_product',$main_product)
                    ->with('row_display', $row_display);
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
    public function update(UpdateSalesOrderInvoiceRequest $request, $id)
    {
        $sales_order_invoice = SalesOrderInvoice::findOrFail($request->sales_order_invoice_id);
        $sales_order_invoice->bill_price = floatval(preg_replace('#[^0-9.]#','',$request->bill_price));
        $sales_order_invoice->notes = $request->notes;
        $sales_order_invoice->persen_ppn = $request->persen_ppn;
        $sales_order_invoice->price_ppn = floatval(preg_replace('#[^0-9.]#','',$request->bill_price))/100*$request->persen_ppn;
        $sales_order_invoice->ppn_hidden = $request->ppn_hidden;
        $sales_order_invoice->save();
        //UPDATE SUCCESS


        //find sales order model
        $sales_order = SalesOrder::findOrFail($request->sales_order_id);
        $customer = Customer::findOrFail($sales_order->customer_id);
        //build sync data to update PO relative w/products
        $syncData = [];
        foreach ($request->product_id as $key => $value) {
            $syncData[$value] = ['quantity'=>$request->quantity[$key], 'price'=>floatval(preg_replace('#[^0-9.]#','',$request->price[$key])), 'price_per_unit'=>floatval(preg_replace('#[^0-9.]#','',$request->price_per_unit[$key]))];
        }
        //first, delete all the relation column between product and sales order on table product sales order before syncing
        \DB::table('product_sales_order')->where('sales_order_id','=',$sales_order->id)->delete();
        //now time to sync the product
        $sales_order->products()->sync($syncData);

        //DELETE transaction chart account reference
        \DB::table('transaction_chart_accounts')->where('reference',$request->sales_order_invoice_id)->where('source',$request->sales_order_invoice_code)->delete();

        // insert temp sales invoice
        $temp_sales_invoice_data = [];
        foreach ($request->product_id as $key => $value) {
          array_push($temp_sales_invoice_data, array(
            'sales_order_id'=>$request->sales_order_id,
            'main_product_id'=>$request->main_product_id_child[$key],
            'child_product_id'=>$request->product_id[$key],
            'price_per_unit'=>floatval(preg_replace('#[^0-9.]#','',$request->price[$key])),
          ));
        }
        //save temp sales invoice
        \DB::table('temp_sales_invoice')->insert($temp_sales_invoice_data);

        //NOW SAVE transaction chart account
        $inv_account = [];
        $sales_account = [];
        $cost_goods_account = [];
        foreach ($request->parent_product_id as $key => $value) {
            $total_amount = \DB::table('temp_sales_invoice')
              ->where('sales_order_id',$request->sales_order_id)
              ->where('main_product_id',$request->parent_product_id[$key])->sum('price_per_unit');
            array_push($inv_account,[
                'amount' =>$request->parent_sum_inventory_cost[$key]*$request->parent_sum_quantity[$key],
                'sub_chart_account_id' =>$request->inventory_account[$key],
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
                'reference'=>$request->sales_order_invoice_id,
                'source'=>$request->sales_order_invoice_code,
                'type'=>'keluar',
                'description'=>'INVOICE TO : '.$customer->name,
                'memo'=>'PERSEDIAAN'
            ]);
            array_push($sales_account,[
                'amount'=>$total_amount,
                'sub_chart_account_id'=>$request->sales_order_account[$key],
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
                'reference'=>$request->sales_order_invoice_id,
                'source'=>$request->sales_order_invoice_code,
                'type'=>'masuk',
                'description'=>'INVOICE TO : '.$customer->name,
                'memo'=>'PENJUALAN'
            ]);
            array_push($cost_goods_account,[
                'amount'=>$request->parent_sum_inventory_cost[$key]*$request->parent_sum_quantity[$key],
                'sub_chart_account_id'=>$request->cost_goods_account[$key],
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
                'reference'=>$request->sales_order_invoice_id,
                'source'=>$request->sales_order_invoice_code,
                'type'=>'masuk',
                'description'=>'INVOICE TO : '.$customer->name,
                'memo'=>'HARGA POKOK PENJUALAN'
            ]);
        }

        //now delete temp sales invoice
        \DB::table('temp_sales_invoice')
          ->where('sales_order_id','=',$request->sales_order_id)
          ->delete();

        \DB::table('transaction_chart_accounts')->insert($inv_account);
        \DB::table('transaction_chart_accounts')->insert($sales_account);
        \DB::table('transaction_chart_accounts')->insert($cost_goods_account);

        $transaction_sub_chart_account = New TransactionChartAccount;
        $transaction_sub_chart_account->amount = floatval(preg_replace('#[^0-9.]#', '', $request->bill_price));
        $transaction_sub_chart_account->sub_chart_account_id = $request->select_account;
        $transaction_sub_chart_account->reference = $request->sales_order_invoice_id;
        $transaction_sub_chart_account->source = $request->sales_order_invoice_code;
        $transaction_sub_chart_account->type = 'masuk';
        $transaction_sub_chart_account->description = 'INVOICE TO : '.$customer->name;
        $transaction_sub_chart_account->memo = '';
        $transaction_sub_chart_account->save();
        return redirect('sales-order-invoice')
            ->with('successMessage','has been update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = SalesOrderInvoice::findOrFail($request->sales_order_invoice_id);
        $inv_code = $id->code;
        $id->delete();

        if($id->sales_invoice_payment->count()){
            //delete bank sales invoice payment
            $bank = $id->sales_invoice_payment;
            foreach ($bank as $key) {
                \DB::table('bank_sales_invoice_payment')->where('sales_invoice_payment_id','=',$key->id)->delete();
            }

            //delete cash sales invoice payment
            $cash = $id->sales_invoice_payment;
            foreach ($cash as $key) {
                \DB::table('cash_sales_invoice_payment')->where('sales_invoice_payment_id','=',$key->id)->delete();
            }
        }

        //delete sales invoice payment
        \DB::table('sales_invoice_payments')->where('sales_order_invoice_id','=',$request->sales_order_invoice_id)->delete();
        \DB::table('transaction_chart_accounts')->where('source','=',$inv_code)->delete();
        return redirect('sales-order-invoice')
            ->with('successMessage','Invoice has been deleted');
    }

    protected function get_product_lists($mp_id, $po_id)
    {
        $total_quantity = [];
        $product_id_arr = [];
        $product_ids = MainProduct::find($mp_id)->product;
        foreach($product_ids as $pid){
            $counter = \DB::table('product_sales_order')
                        ->where('product_id','=', $pid->id)
                        ->where('sales_order_id', '=', $po_id)
                        ->first();
            $total_quantity[] = $counter;
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
        $sum_qty = array_sum($total_quantity);
        return $product_id_arr;
    }
    //change status invoice to "Completed"
    public function completeSalesInvoice(Request $request)
    {
        $invoice = SalesOrderInvoice::findOrFail($request->sales_order_invoice_id);
        //check the bill and the paid price
        $bill_price = $invoice->bill_price;
        $paid_price = $invoice->paid_price;

        if($paid_price < $bill_price)
        {
            return redirect()->back()
                ->with('errorMessage', "Invoice can not be completed, Paid price is less than the Bill price");
        }
        else{
            $invoice->status = 'completed';
            $invoice->save();
             return redirect()->back()
             ->with('successMessage', "Invoice has been completed");
        }
    }

    // public function completeSalesAccount(Request $request)
    // {
    //     $transaction_sub_chart_account = New TransactionChartAccount;
    //     $transaction_sub_chart_account->amount = $request->amount_piutang;
    //     $transaction_sub_chart_account->sub_chart_account_id = $request->select_account_id;
    //     $transaction_sub_chart_account->save();
    //     return redirect()->back()
    //         ->with('successMessage',"Send Piutang Account has been completed");
    // }

    public function createPayment(Request $request)
    {
      if(\Auth::user()->can('create-sales-order-invoice-payment-module'))
      {
        $invoice_id = $request->invoice_id;
        $invoice = SalesOrderInvoice::findOrFail($invoice_id);
        $payment_methods = PaymentMethod::lists('name', 'id');
        $cashs = Cash::lists('name','id');
        $banks = Bank::lists('name','id');
        return view('sales_order.create_payment')
            ->with('payment_methods', $payment_methods)
            ->with('invoice', $invoice)
            ->with('cashs',$cashs)
            ->with('banks',$banks);
      }else{
        return view('403');
      }

    }

    public function storePaymentCash(StoreSalesPaymentCash $request)
    {
        $invoice_id = $request->sales_order_invoice_id;
        $invoice_code = $request->sales_order_invoice_code;
        $cash_id = $request->cash_id;
        $amount = floatval(preg_replace('#[^0-9.]#', '', $request->amount));

        $sales_order_invoice = SalesOrderInvoice::findOrFail($invoice_id);
        //get current paid_price of the invoice
        $current_paid_price = $sales_order_invoice->paid_price;
        //build new paid_price to be updated
        $new_paid_price = $current_paid_price+$amount;

        $sales_order_id = $sales_order_invoice->sales_order->id;
        $sales_order_customer_id = $sales_order_invoice->sales_order->customer_id;
        $customer = Customer::findOrFail($sales_order_customer_id);

        $sales_invoice_payment = new SalesInvoicePayment;
        $sales_invoice_payment->sales_order_invoice_id = $invoice_id;
        $sales_invoice_payment->amount = floatval(preg_replace('#[^0-9.]#', '', $amount));
        $sales_invoice_payment->payment_method_id = $request->payment_method_id;
        $sales_invoice_payment->receiver = \Auth::user()->id;
        $save = $sales_invoice_payment->save();

        $cash_sales_invoice_payment = new CashSalesInvoicePayment;
        $cash_sales_invoice_payment->cash_id = $cash_id;
        $cash_sales_invoice_payment->sales_invoice_payment_id = $sales_invoice_payment->id;
        $cash_sales_invoice_payment->save();

        $cash_value = Cash::findOrFail($cash_id);
        $current_cash_value = $cash_value->value;
        $new_cash_value = $current_cash_value+$amount;

        $transaction_sub_chart_account = New TransactionChartAccount;
        $transaction_sub_chart_account->amount = $amount;
        $transaction_sub_chart_account->sub_chart_account_id = $request->select_account;
        $transaction_sub_chart_account->reference = $invoice_id;
        $transaction_sub_chart_account->source = $invoice_code;
        $transaction_sub_chart_account->type = 'masuk';
        $transaction_sub_chart_account->description = $customer->name.' PAYMENT INVOICE : '.$invoice_code;
        $transaction_sub_chart_account->memo = 'PAYMENT FOR : '.$invoice_code;
        $transaction_sub_chart_account->save();

        $trans_payment_k = New TransactionChartAccount;
        $trans_payment_k->amount = $amount;
        $trans_payment_k->sub_chart_account_id = 34;
        $trans_payment_k->reference = $invoice_id;
        $trans_payment_k->source = $invoice_code;
        $trans_payment_k->type = 'keluar';
        $trans_payment_k->description = 'INVOICE TO : '.$customer->name;
        $trans_payment_k->memo = '';
        $trans_payment_k->save();
        if($save){
            //update invoice's paid_price
            $sales_order_invoice->paid_price = $new_paid_price;
            $cash_value->value = $new_cash_value;
            $update_paid_price = $sales_order_invoice->save();
            $update_cash_value = $cash_value->save();

            return redirect('sales-order/'.$sales_order_id)
            ->with('successMessage', 'Payment has been added');
        }
        else{
            return "Failed to save invoice payment, contact the developer";
        }

    }

    public function storePaymentTransfer(StoreSalesPaymentTransfer $request)
    {
        $invoice_id = $request->sales_order_invoice_id;
        $invoice_code = $request->sales_order_invoice_code;
        $bank_id = $request->bank_id;
        $amount = floatval(preg_replace('#[^0-9.]#','',$request->amount));

        $sales_order_invoice = SalesOrderInvoice::findOrFail($invoice_id);
        $current_paid_price = $sales_order_invoice->paid_price;
        $new_paid_price = $current_paid_price+$amount;

        $sales_order_id = $sales_order_invoice->sales_order->id;
        $sales_order_customer_id = $sales_order_invoice->sales_order->customer_id;
        $customer = Customer::findOrFail($sales_order_customer_id);

        $sales_invoice_payment = new SalesInvoicePayment;
        $sales_invoice_payment->sales_order_invoice_id = $invoice_id;
        $sales_invoice_payment->amount = floatval(preg_replace('#[^0-9.]#','',$amount));
        $sales_invoice_payment->payment_method_id = $request->payment_method_id;
        $sales_invoice_payment->receiver = \Auth::user()->id;
        $save = $sales_invoice_payment->save();

        $bank_sales_invoice_payment = new BankSalesInvoicePayment;
        $bank_sales_invoice_payment->bank_id = $bank_id;
        $bank_sales_invoice_payment->sales_invoice_payment_id = $sales_invoice_payment->id;
        $bank_sales_invoice_payment->save();

        $bank_value = Bank::findOrFail($bank_id);
        $current_bank_value = $bank_value->value;
        $new_bank_value = $current_bank_value+$amount;

        $transaction_sub_chart_account = New TransactionChartAccount;
        $transaction_sub_chart_account->amount = $amount;
        $transaction_sub_chart_account->sub_chart_account_id = $request->select_account;
        $transaction_sub_chart_account->reference = $invoice_id;
        $transaction_sub_chart_account->source = $invoice_code;
        $transaction_sub_chart_account->type = 'masuk';
        $transaction_sub_chart_account->description = $customer->name.' PAYMENT INVOICE : '.$invoice_code;
        $transaction_sub_chart_account->memo = 'PAYMENT FOR : '.$invoice_code;
        $transaction_sub_chart_account->save();

        $trans_payment_k = New TransactionChartAccount;
        $trans_payment_k->amount = $amount;
        $trans_payment_k->sub_chart_account_id = 34;
        $trans_payment_k->reference = $invoice_id;
        $trans_payment_k->source = $invoice_code;
        $trans_payment_k->type = 'keluar';
        $trans_payment_k->description = 'INVOICE TO : '.$customer->name;
        $trans_payment_k->memo = '';
        $trans_payment_k->save();
        if($save){
            //update invoice's paid price
            $sales_order_invoice->paid_price = $new_paid_price;
            $bank_value->value =$new_bank_value;
            $update_paid_price = $sales_order_invoice->save();
            $update_bank_value = $bank_value->save();
            return redirect('sales-order/'.$sales_order_id)
                            ->with('successMessage','Payment has been added');
        }else{
            return "Failed to save invoice payment, contact the developer";
        }
    }

    public function storePaymentGiro(StoreSalesPaymentGiro $request)
    {
        $invoice_id = $request->sales_order_invoice_id;
        $invoice_code = $request->sales_order_invoice_code;
        //$bank_id = $request->bank_id;
        $amount = floatval(preg_replace('#[^0-9.]#','',$request->amount_giro));

        $sales_order_invoice = SalesOrderInvoice::findOrFail($invoice_id);
        $current_paid_price = $sales_order_invoice->paid_price;
        $new_paid_price = $current_paid_price+$amount;

        $sales_order_id = $sales_order_invoice->sales_order->id;
        $sales_order_customer_id = $sales_order_invoice->sales_order->customer_id;
        $customer = Customer::findOrFail($sales_order_customer_id);

        $sales_invoice_payment = new SalesInvoicePayment;
        $sales_invoice_payment->sales_order_invoice_id = $invoice_id;
        $sales_invoice_payment->amount = floatval(preg_replace('#[^0-9.]#','',$amount));
        $sales_invoice_payment->payment_method_id = $request->payment_method_id;
        $sales_invoice_payment->receiver = \Auth::user()->id;
        $save = $sales_invoice_payment->save();

        $giro_sales_invoice_payment = new GiroSalesInvoicePayment;
        $giro_sales_invoice_payment->no_giro = $request->no_giro;
        $giro_sales_invoice_payment->bank = $request->nama_bank;
        $giro_sales_invoice_payment->tanggal_cair = $request->tanggal_cair;
        $giro_sales_invoice_payment->amount = floatval(preg_replace('#[^0-9.]#','',$amount));
        $giro_sales_invoice_payment->sales_invoice_payment_id = $sales_invoice_payment->id;
        $giro_sales_invoice_payment->save();

        // $bank_value = Bank::findOrFail($bank_id);
        // $current_bank_value = $bank_value->value;
        // $new_bank_value = $current_bank_value+$amount;

        $transaction_sub_chart_account = New TransactionChartAccount;
        $transaction_sub_chart_account->amount = $amount;
        $transaction_sub_chart_account->sub_chart_account_id = $request->gir_account;
        $transaction_sub_chart_account->reference = $invoice_id;
        $transaction_sub_chart_account->source = $invoice_code;
        $transaction_sub_chart_account->type = 'masuk';
        $transaction_sub_chart_account->description = $customer->name.' PAYMENT INVOICE : '.$invoice_code;
        $transaction_sub_chart_account->memo = 'PAYMENT FOR : '.$invoice_code;
        $transaction_sub_chart_account->save();

        $trans_payment_k = New TransactionChartAccount;
        $trans_payment_k->amount = $amount;
        $trans_payment_k->sub_chart_account_id = 34;
        $trans_payment_k->reference = $invoice_id;
        $trans_payment_k->source = $invoice_code;
        $trans_payment_k->type = 'keluar';
        $trans_payment_k->description = 'INVOICE TO : '.$customer->name;
        $trans_payment_k->memo = '';
        $trans_payment_k->save();
        if($save){
            //update invoice's paid price
            $sales_order_invoice->paid_price = $new_paid_price;
            //$bank_value->value =$new_bank_value;
            $update_paid_price = $sales_order_invoice->save();
            //$update_bank_value = $bank_value->save();
            return redirect('sales-order/'.$sales_order_id)
                            ->with('successMessage','Payment has been added');
        }else{
            return "Failed to save invoice payment, contact the developer";
        }
    }

    public function printInv(Request $request)
    {
        $data['sales_order_invoice'] = SalesOrderInvoice::findOrFail($request->id);
        $sales_order_invoice = SalesOrderInvoice::findOrFail($request->id);
        $data['sales_order'] = SalesOrder::findOrFail($sales_order_invoice->sales_order->id);
        $sales_order = SalesOrder::findOrFail($sales_order_invoice->sales_order->id);
        $data['invoice_term'] = InvoiceTerm::findOrFail($sales_order->customer->invoice_term_id);
        $pdf = \PDF::loadView('pdf.inv_sales_order',$data);
        return $pdf->stream('inv_sales_order.pdf');
    }

}
