<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
//form request
use App\Http\Requests\StorePurchaseOrderInvoiceRequest;
use App\Http\Requests\UpdatePurchaseOrderInvoiceRequest;
use App\Http\Requests\StorePurchasePaymentCash;
use App\Http\Requests\StorePurchasePaymentTransfer;
use App\Http\Requests\StorePurchasePaymentGiro;

use App\PurchaseOrderInvoice;
use App\PurchaseOrder;
use App\PaymentMethod;
use App\PurchaseInvoicePayment;
use App\Bank;
use App\Cash;
use App\GiroPurchaseInvoicePayment;
use App\BankPurchaseInvoicePayment;
use App\CashPurchaseInvoicePayment;
use App\TransactionChartAccount;
use App\MainProduct;
use App\Product;
use App\Supplier;
use DB;

class PurchaseOrderInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('purchase-order-invoice-module'))
        {
            return view('purchase_order.list_invoice');
        }else{
            return view('403');
        }
    }

    protected function count_total_price($purchase_order)
    {
        $sum_price = \DB::table('product_purchase_order')
                    ->where('purchase_order_id', $purchase_order->id)
                    ->sum('price');
        return $sum_price;
    }

    public function create(Request $request,$id)
    {
        if(\Auth::user()->can('create-purchase-order-invoice-module'))
        {
            $purchase_order = PurchaseOrder::findOrFail($request->purchase_order_id);
            $payment_methods = PaymentMethod::lists('name', 'id');
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
                    'family'=>MainProduct::find($mp_id)->family->name,
                    'unit'=>MainProduct::find($mp_id)->unit->name,
                    'image'=>MainProduct::find($mp_id)->image,
                    'category'=>MainProduct::find($mp_id)->category->name,
                    'ordered_products'=>$this->get_product_lists($mp_id, $id)
                ];
            }
            // echo '<pre>';
            // echo print_r($row_display);
            // echo '</pre>';
            // exit();
            return view('purchase_order.create_invoice_new')
                ->with('total_price', $this->count_total_price($purchase_order))
                ->with('purchase_order', $purchase_order)
                ->with('payment_methods', $payment_methods)
                ->with('main_product',$main_product)
                ->with('row_display', $row_display);
        }else{
            return view('403');
        }
    }

    public function store(StorePurchaseOrderInvoiceRequest $request)
    {
        if($request->ajax()){

            $data = [
                'code'=>$request->code,
                'purchase_order_id' =>$request->purchase_order_id,
                'bill_price'=>floatval(preg_replace('#[^0-9.]#', '', $request->bill_price)),
                //'payment_method_id'=>$request->payment_method_id,
                // 'paid_price'=>floatval(preg_replace('#[^0-9.]#', '', $request->paid_price)),
                'notes'=>$request->notes,
                'created_by'=>\Auth::user()->id,
                'term'=>$request->term,
            ];

            $save = PurchaseOrderInvoice::create($data);
            $po_id = $save->id;
            $po_code = $save->code;
            if($save){
                //find purchase_order model
                $purchase_order = PurchaseOrder::findOrFail($request->purchase_order_id);
                $supplier = Supplier::findOrFail($purchase_order->supplier_id);
                //Build sync data to update PO relation w/ products
                $syncData = [];
                foreach($request->product_id as $key=>$value){
                    $syncData[$value] = ['quantity'=> $request->quantity[$key], 'price_per_unit'=>floatval(preg_replace('#[^0-9.]#', '', $request->price_per_unit[$key])), 'price'=>floatval(preg_replace('#[^0-9.]#', '', $request->price[$key]))];
                }
                //First, delete all the relation cloumn between product and purchase order on table prouduct_purchase_order before syncing
                \DB::table('product_purchase_order')->where('purchase_order_id','=',$purchase_order->id)->delete();
                //Now time to sync the products
                $purchase_order->products()->sync($syncData);

                // insert temp purchase invoice
                $temp_purchase_invoice_data = [];
                foreach($request->product_id as $key=>$value){
                    array_push($temp_purchase_invoice_data, array(
                        'purchase_order_id'=>$request->purchase_order_id,
                        'main_product_id'=>$request->main_product_id_child[$key],
                        'child_product_id'=>$request->product_id[$key],
                        'price_per_unit'=>floatval(preg_replace('#[^0-9.]#', '', $request->price[$key])),
                    ));
                }

                \DB::table('temp_purchase_invoice')->insert($temp_purchase_invoice_data);
                // $inv_account = [];
                // foreach ($request->parent_product_id as $key => $value) {
                //     // $inv_account->amount =  floatval(preg_replace('#[^0-9.]#', '', $request->price_parent[$key]));
                //     // $inv_account->sub_chart_account_id = $request->inventory_account[$key];
                //     //$inv_account->save();
                //     array_push($inv_account,[
                //         'amount' =>floatval(preg_replace('#[^0-9.]#', '', $request->price_parent[$key])),
                //         'sub_chart_account_id' =>$request->inventory_account[$key],
                //         'created_at'=>date('Y-m-d H:i:s'),
                //         'updated_at'=>date('Y-m-d H:i:s'),
                //         'reference'=>$po_id,
                //         'source'=>'purchase_order_invoices',
                //         'type'=>'masuk',
                //     ]);
                // }

                $inv_account = [];
                foreach ($request->parent_product_id as $key => $value) {
                    $total_amount = \DB::table('temp_purchase_invoice')
                        ->where('purchase_order_id', $request->purchase_order_id)
                        ->where('main_product_id','=', $request->parent_product_id[$key])->sum('price_per_unit');
                        array_push($inv_account,[
                            'amount'=>$total_amount,
                            'sub_chart_account_id'=>$request->inventory_account[$key],
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=>date('Y-m-d H:i:s'),
                            'reference'=>$po_id,
                            'source'=>$po_code,
                            'type'=>'masuk',
                            'description'=>'INVOICE FROM : '.$supplier->name,
                            'memo'=>'',
                        ]);
                }

                // now delete temp purchase invoice
                \DB::table('temp_purchase_invoice')
                    ->where('purchase_order_id', '=', $request->purchase_order_id)
                    ->delete();

                // save trans account hutang
                \DB::table('transaction_chart_accounts')->insert($inv_account);
                $transaction_sub_chart_account = New TransactionChartAccount;
                $transaction_sub_chart_account->amount = floatval(preg_replace('#[^0-9.]#', '', $request->bill_price));
                $transaction_sub_chart_account->sub_chart_account_id = $request->select_account;
                $transaction_sub_chart_account->reference = $po_id;
                $transaction_sub_chart_account->source = $po_code;
                $transaction_sub_chart_account->type = 'masuk';
                $transaction_sub_chart_account->description = 'INVOICE FROM : '.$supplier->name;
                $transaction_sub_chart_account->memo = '';
                $transaction_sub_chart_account->save();
            }

            $response = [
                'msg'=>'storePurchaseOrderInvoiceOk',
                'purchase_order_id'=>$request->purchase_order_id
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
        $purchase_order_invoice = PurchaseOrderInvoice::findOrFail($id);
        $purchase_order = PurchaseOrder::findOrFail($purchase_order_invoice->purchase_order->id);
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
                'quantity'=>'',
                'category'=>MainProduct::find($mp_id)->category->name,
                'ordered_products'=>$this->get_product_lists($mp_id, $purchase_order->id)
            ];
        }
        // print_r($this->get_product_lists($mp_id,$id));
        // exit();
        return view('purchase_order.show_invoice')
            ->with('purchase_order_invoice', $purchase_order_invoice)
            ->with('purchase_order', $purchase_order)
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
        if(\Auth::user()->can('edit-purchase-order-invoice-module'))
        {
            $purchase_order_invoice = PurchaseOrderInvoice::findOrFail($id);
            $purchase_order = PurchaseOrder::findOrFail($purchase_order_invoice->purchase_order->id);
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
                    'quantity'=>'',
                    'category'=>MainProduct::find($mp_id)->category->name,
                    'ordered_products'=>$this->get_product_lists($mp_id, $purchase_order->id)
                ];
            }
            return view('purchase_order.edit_invoice')
                ->with('purchase_order_invoice', $purchase_order_invoice)
                ->with('purchase_order', $purchase_order)
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
    public function update(UpdatePurchaseOrderInvoiceRequest $request, $id)
    {
        $purchase_order_invoice = PurchaseOrderInvoice::findOrFail($request->purchase_order_invoice_id);
        $purchase_order_invoice->code = $request->code;
        $purchase_order_invoice->bill_price = floatval(preg_replace('#[^0-9.]#', '', $request->bill_price));
        $purchase_order_invoice->notes = $request->notes;
        $purchase_order_invoice->term = $request->term;
        $purchase_order_invoice->save();

        // //find purchase_order model
        $purchase_order = PurchaseOrder::findOrFail($request->purchase_order_id);
        $supplier = Supplier::findOrFail($purchase_order->supplier_id);
        //Build sync data to update PO relation w/ products
        $syncData = [];
        foreach($request->product_id as $key=>$value){
            $syncData[$value] = ['quantity'=> $request->quantity[$key], 'price_per_unit'=>floatval(preg_replace('#[^0-9.]#', '', $request->price_per_unit[$key])),'price'=>floatval(preg_replace('#[^0-9.]#', '', $request->price[$key]))];
        }
        //First, delete all the relation cloumn between product and purchase order on table prouduct_purchase_order before syncing
        \DB::table('product_purchase_order')->where('purchase_order_id','=',$purchase_order->id)->delete();
        //Now time to sync the products
        $purchase_order->products()->sync($syncData);

        //DELETE transaction chart account reference
        \DB::table('transaction_chart_accounts')->where('reference',$request->purchase_order_invoice_id)->where('source',$request->purchase_order_invoice_code)->delete();

        // insert temp purchase invoice
        $temp_purchase_invoice_data = [];
        foreach($request->product_id as $key=>$value){
            array_push($temp_purchase_invoice_data, array(
                'purchase_order_id'=>$request->purchase_order_id,
                'main_product_id'=>$request->main_product_id_child[$key],
                'child_product_id'=>$request->product_id[$key],
                'price_per_unit'=>floatval(preg_replace('#[^0-9.]#', '', $request->price[$key])),
            ));
        }

        \DB::table('temp_purchase_invoice')->insert($temp_purchase_invoice_data);

        //NOW SAVE transaction chart account
        $inv_account = [];
        foreach ($request->parent_product_id as $key => $value) {
            // $inv_account->amount =  floatval(preg_replace('#[^0-9.]#', '', $request->price_parent[$key]));
            // $inv_account->sub_chart_account_id = $request->inventory_account[$key];
            //$inv_account->save();
            $total_amount = \DB::table('temp_purchase_invoice')
                ->where('purchase_order_id', $request->purchase_order_id)
                ->where('main_product_id','=', $request->parent_product_id[$key])->sum('price_per_unit');
            array_push($inv_account,[
                'amount' =>$total_amount,
                'sub_chart_account_id' =>$request->inventory_account[$key],
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
                'reference'=>$request->purchase_order_invoice_id,
                'source'=>$request->purchase_order_invoice_code,
                'type'=>'masuk',
                'description'=>'INVOICE FROM : '.$supplier->name,
                'memo'=>''
            ]);
        }

        // now delete temp purchase invoice
        \DB::table('temp_purchase_invoice')
            ->where('purchase_order_id', '=', $request->purchase_order_id)
            ->delete();

        \DB::table('transaction_chart_accounts')->insert($inv_account);
        $transaction_sub_chart_account = New TransactionChartAccount;
        $transaction_sub_chart_account->amount = floatval(preg_replace('#[^0-9.]#', '', $request->bill_price));
        $transaction_sub_chart_account->sub_chart_account_id = $request->select_account;
        $transaction_sub_chart_account->reference = $request->purchase_order_invoice_id;
        $transaction_sub_chart_account->source = $request->purchase_order_invoice_code;
        $transaction_sub_chart_account->type = 'masuk';
        $transaction_sub_chart_account->description = 'INVOICE FROM : '.$supplier->name;
        $transaction_sub_chart_account->memo = '';
        $transaction_sub_chart_account->save();

        return redirect('purchase-order-invoice')
            ->with('successMessage', "Invoice has been updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $purch_order_inv = PurchaseOrderInvoice::findOrFail($request->purchase_order_invoice_id);
        $inv_code = $purch_order_inv->code;
        $purch_order_inv->delete();

        if($purch_order_inv->purchase_invoice_payment->count()){
            //delete bank purchase invoice payment
            $bank = $purch_order_inv->purchase_invoice_payment;
            foreach ($bank as $key) {
                \DB::table('bank_purchase_invoice_payment')->where('purchase_invoice_payment_id','=',$key->id)->delete();
            }
            //delete cash purchase invoice payment
            $cash = $purch_order_inv->purchase_invoice_payment;
            foreach ($cash as $key) {
                \DB::table('cash_purchase_invoice_payment')->where('purchase_invoice_payment_id','=',$key->id)->delete();
            }
        }

        //delete purchase invoice payment
        \DB::table('purchase_invoice_payments')->where('purchase_order_invoice_id','=',$request->purchase_order_invoice_id)->delete();
        //delete transaction chart account
        \DB::table('transaction_chart_accounts')->where('source','=',$inv_code)->delete();

        return redirect('purchase-order-invoice')
        ->with('successMessage', 'Invoice has been deleted');
    }

    protected function get_product_lists($mp_id, $po_id)
    {
        $total_quantity = [];
        $product_id_arr = [];
        $product_ids = MainProduct::find($mp_id)->product;
        foreach($product_ids as $pid){
            $counter = \DB::table('product_purchase_order')
                        ->where('product_id','=', $pid->id)
                        ->where('purchase_order_id', '=', $po_id)
                        ->first();
            $total_quantity[] = $counter->quantity;
            if(count($counter)){
                array_push($product_id_arr,array(
                    'family'=>Product::findOrFail($pid->id)->main_product->family->name,
                    'code'=>Product::findOrFail($pid->id)->name,
                    'description'=>Product::findOrFail($pid->id)->description,
                    'unit'=>Product::findOrFail($pid->id)->main_product->unit->name,
                    'quantity'=>$counter->quantity,
                    'product_id'=>$counter->product_id,
                    'category'=>Product::findOrFail($pid->id)->main_product->category->name,
                    'price_per_unit'=>$counter->price_per_unit,
                    'price'=>$counter->price,
                ));
            }
            //$product_id_arr[] = $pid->id;
        }
        $sum_qty = array_sum($total_quantity);
        return $product_id_arr;


    }

    protected function sum_qty($sm)
    {
        return array_sum($sm);
    }

    public function completePurchaseInvoice(Request $request)
    {
        $invoice = PurchaseOrderInvoice::findOrFail($request->purchase_order_invoice_id);
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

    public function completePurchaseAccount(Request $request)
    {
        $transaction_sub_chart_account = New TransactionChartAccount;
        $transaction_sub_chart_account->amount = $request->amount_hutang;
        $transaction_sub_chart_account->sub_chart_account_id = $request->select_account_id;
        $transaction_sub_chart_account->save();
        return redirect()->back()
            ->with('successMessage',"Send Hutang Account has been completed");
    }

    public function createPayment(Request $request)
    {
      if(\Auth::user()->can('create-purchase-order-invoice-payment-module'))
      {
        $invoice_id = $request->invoice_id;
        $invoice = PurchaseOrderInvoice::findOrFail($invoice_id);
        $payment_methods = PaymentMethod::lists('name','id');
        $banks = Bank::lists('name', 'id');
        $cashs = Cash::lists('name','id');
        return view('purchase_order.create_payment')
                    ->with('invoice',$invoice)
                    ->with('payment_method',$payment_methods)
                    ->with('banks',$banks)
                    ->with('cashs',$cashs);
      }else{
        return view('403');
      }

    }

    public function storePaymentCash(StorePurchasePaymentCash $request)
    {
        $invoice_id = $request->purchase_order_invoice_id;
        $invoice_code = $request->purchase_order_invoice_code;
        $cash_id = $request->cash_id;
        // $payment_method_id = $request->payment_method_id;
        $amount = floatval(preg_replace('#[^0-9.]#', '', $request->amount));

        $purchase_order_invoice = PurchaseOrderInvoice::findOrFail($invoice_id);
        //get current paid_price of the invoice
        $current_paid_price = $purchase_order_invoice->paid_price;
        //build new paid_price to be updated
        $new_paid_price = $current_paid_price+$amount;

        $purchase_order_id = $purchase_order_invoice->purchase_order->id;
        $purchase_order_supplier_id = $purchase_order_invoice->purchase_order->supplier_id;
        $supplier = Supplier::findOrFail($purchase_order_supplier_id);

        $purchase_invoice_payment = new PurchaseInvoicePayment;
        $purchase_invoice_payment->purchase_order_invoice_id = $invoice_id;
        $purchase_invoice_payment->amount = floatval(preg_replace('#[^0-9.]#', '', $amount));
        $purchase_invoice_payment->payment_method_id = $request->payment_method_id;
        $purchase_invoice_payment->receiver = \Auth::user()->id;
        $save = $purchase_invoice_payment->save();

        $cash_purchase_invoice_payment = new CashPurchaseInvoicePayment;
        $cash_purchase_invoice_payment->cash_id = $cash_id;
        $cash_purchase_invoice_payment->purchase_invoice_payment_id = $purchase_invoice_payment->id;
        $cash_purchase_invoice_payment->save();

        $cash_value = Cash::findOrFail($cash_id);
        $current_cash_value = $cash_value->value;
        $new_cash_value = $current_cash_value-$amount;

        $transaction_sub_chart_account = New TransactionChartAccount;
        $transaction_sub_chart_account->amount = $amount;
        $transaction_sub_chart_account->sub_chart_account_id = $request->cash_account;
        $transaction_sub_chart_account->reference = $invoice_id;
        $transaction_sub_chart_account->source = $invoice_code;
        $transaction_sub_chart_account->type = 'keluar';
        $transaction_sub_chart_account->description = 'PAYMENT INVOICE : '.$invoice_code.' '.$supplier->name;
        $transaction_sub_chart_account->memo = 'PAYMENT FOR : '.$invoice_code;
        $transaction_sub_chart_account->save();

        $trans_payment_k = New TransactionChartAccount;
        $trans_payment_k->amount = $amount;
        $trans_payment_k->sub_chart_account_id = 79;
        $trans_payment_k->reference = $invoice_id;
        $trans_payment_k->source = $invoice_code;
        $trans_payment_k->type = 'keluar';
        $trans_payment_k->description = 'INVOICE FROM : '.$supplier->name;
        $trans_payment_k->memo = '';
        $trans_payment_k->save();
        if($save){
            //update invoice's paid_price
            $purchase_order_invoice->paid_price = $new_paid_price;
            $cash_value->value = $new_cash_value;
            $update_paid_price = $purchase_order_invoice->save();
            $update_cash_value = $cash_value->save();

            return redirect('purchase-order/'.$purchase_order_id)
            ->with('successMessage', 'Payment has been added');
        }
        else{
            return "Failed to save invoice payment, contact the developer";
        }
    }


    public function storePaymentTransfer(StorePurchasePaymentTransfer $request)
    {
        $invoice_id = $request->purchase_order_invoice_id;
        $invoice_code = $request->purchase_order_invoice_code;
        $bank_id = $request->bank_id;
        $amount = floatval(preg_replace('#[^0-9.]#', '', $request->amount));

        $purchase_order_invoice = PurchaseOrderInvoice::findOrFail($invoice_id);
        $current_paid_price = $purchase_order_invoice->paid_price;
        $new_paid_price = $current_paid_price+$amount;

        $purchase_order_id = $purchase_order_invoice->purchase_order->id;
        $purchase_order_supplier_id = $purchase_order_invoice->purchase_order->supplier_id;
        $supplier = Supplier::findOrFail($purchase_order_supplier_id);

        $purchase_invoice_payment = new PurchaseInvoicePayment;
        $purchase_invoice_payment->purchase_order_invoice_id = $invoice_id;
        $purchase_invoice_payment->amount = floatval(preg_replace('#[^0-9.]#','',$amount));
        $purchase_invoice_payment->payment_method_id = $request->payment_method_id;
        $purchase_invoice_payment->receiver = \Auth::user()->id;
        $save = $purchase_invoice_payment->save();

        $bank_purchase_invoice_payment = new BankPurchaseInvoicePayment;
        $bank_purchase_invoice_payment->bank_id = $bank_id;
        $bank_purchase_invoice_payment->purchase_invoice_payment_id = $purchase_invoice_payment->id;
        $bank_purchase_invoice_payment->save();

        $bank_value = Bank::findOrFail($bank_id);
        $current_bank_value = $bank_value->value;
        $new_bank_value = $current_bank_value-$amount;

        $transaction_sub_chart_account = New TransactionChartAccount;
        $transaction_sub_chart_account->amount = $amount;
        $transaction_sub_chart_account->sub_chart_account_id = $request->transfer_account;
        $transaction_sub_chart_account->reference = $invoice_id;
        $transaction_sub_chart_account->source = $invoice_code;
        $transaction_sub_chart_account->type = 'keluar';
        $transaction_sub_chart_account->description = 'PAYMENT INVOICE : '.$invoice_code.' '.$supplier->name;
        $transaction_sub_chart_account->memo = 'PAYMENT FOR : '.$invoice_code;
        $transaction_sub_chart_account->save();

        $trans_payment_k = New TransactionChartAccount;
        $trans_payment_k->amount = $amount;
        $trans_payment_k->sub_chart_account_id = 79;
        $trans_payment_k->reference = $invoice_id;
        $trans_payment_k->source = $invoice_code;
        $trans_payment_k->type = 'keluar';
        $trans_payment_k->description = 'INVOICE FROM : '.$supplier->name;
        $trans_payment_k->memo = '';
        $trans_payment_k->save();
        if($save){
            //update invoice's paid_price
            $purchase_order_invoice->paid_price = $new_paid_price;
            $bank_value->value = $new_bank_value;
            $update_paid_price = $purchase_order_invoice->save();
            $update_bank_value = $bank_value->save();

            return redirect('purchase-order/'.$purchase_order_id)
                ->with('successMessage','Payment has been added');
        }else{
            return "Failed to save invoice payment, contact the developer";
        }
    }

    public function storePaymentGiro(StorePurchasePaymentGiro $request)
    {
        $invoice_id = $request->purchase_order_invoice_id;
        $invoice_code = $request->purchase_order_invoice_code;
        //$bank_id = $request->bank_id;
        $amount = floatval(preg_replace('#[^0-9.]#', '', $request->amount_giro));

        $purchase_order_invoice = PurchaseOrderInvoice::findOrFail($invoice_id);
        $current_paid_price = $purchase_order_invoice->paid_price;
        $new_paid_price = $current_paid_price+$amount;

        $purchase_order_id = $purchase_order_invoice->purchase_order->id;
        $purchase_order_supplier_id = $purchase_order_invoice->purchase_order->supplier_id;
        $supplier = Supplier::findOrFail($purchase_order_supplier_id);

        $purchase_invoice_payment = new PurchaseInvoicePayment;
        $purchase_invoice_payment->purchase_order_invoice_id = $invoice_id;
        $purchase_invoice_payment->amount = floatval(preg_replace('#[^0-9.]#','',$amount));
        $purchase_invoice_payment->payment_method_id = $request->payment_method_id;
        $purchase_invoice_payment->receiver = \Auth::user()->id;
        $save = $purchase_invoice_payment->save();

        $giro_sales_invoice_payment = new GiroPurchaseInvoicePayment;
        $giro_sales_invoice_payment->no_giro = $request->no_giro;
        $giro_sales_invoice_payment->bank = $request->nama_bank;
        $giro_sales_invoice_payment->tanggal_cair = $request->tanggal_cair;
        $giro_sales_invoice_payment->amount = floatval(preg_replace('#[^0-9.]#','',$amount));
        $giro_sales_invoice_payment->purchase_invoice_payment_id = $purchase_invoice_payment->id;
        $giro_sales_invoice_payment->save();

        // $bank_value = Bank::findOrFail($bank_id);
        // $current_bank_value = $bank_value->value;
        // $new_bank_value = $current_bank_value-$amount;

        $transaction_sub_chart_account = New TransactionChartAccount;
        $transaction_sub_chart_account->amount = $amount;
        $transaction_sub_chart_account->sub_chart_account_id = $request->gir_account;
        $transaction_sub_chart_account->reference = $invoice_id;
        $transaction_sub_chart_account->source = $invoice_code;
        $transaction_sub_chart_account->type = 'keluar';
        $transaction_sub_chart_account->description = 'PAYMENT INVOICE : '.$invoice_code.' '.$supplier->name;
        $transaction_sub_chart_account->memo = 'PAYMENT FOR : '.$invoice_code;
        $transaction_sub_chart_account->save();

        $trans_payment_k = New TransactionChartAccount;
        $trans_payment_k->amount = $amount;
        $trans_payment_k->sub_chart_account_id = 79;
        $trans_payment_k->reference = $invoice_id;
        $trans_payment_k->source = $invoice_code;
        $trans_payment_k->type = 'keluar';
        $trans_payment_k->description = 'INVOICE FROM : '.$supplier->name;
        $trans_payment_k->memo = '';
        $trans_payment_k->save();
        if($save){
            //update invoice's paid_price
            $purchase_order_invoice->paid_price = $new_paid_price;
            //$bank_value->value = $new_bank_value;
            $update_paid_price = $purchase_order_invoice->save();
            //$update_bank_value = $bank_value->save();

            return redirect('purchase-order/'.$purchase_order_id)
                ->with('successMessage','Payment has been added');
        }else{
            return "Failed to save invoice payment, contact the developer";
        }
    }
}
