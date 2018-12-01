<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

//Form requests
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Http\Requests\StorePaymentInvoicePurchaseKasRequest;
use App\Http\Requests\StorePaymentInvoicePurchaseTransferRequest;
use App\Http\Requests\StorePaymentInvoicePurchaseGiroRequest;

use App\Supplier;
use App\Cash;
use App\Bank;
use App\PurchaseOrder;
use App\PurchaseOrderInvoice;
use App\PurchaseInvoicePayment;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('supplier-module'))
        {
            return view('supplier.index');
        }else{
            return view('403');
        }
    }


    public function create()
    {
        if(\Auth::user()->can('create-supplier-module'))
        {
            $supp = Supplier::all();
            $code_fix = '';
            if(count($supp) > 0)
            {
                $code_supp = Supplier::all()->max('id');
                $sub_str = $code_supp+1;
                $code_fix = 'SUP0'.$sub_str;
            }else
            {
                $code_supp = count($supp)+1;
                $code_fix = 'SUP0'.$code_supp;
            }
            return view('supplier.create')
                ->with('code_fix',$code_fix);
        }else{
            return view('403');
        }
    }


    public function store(StoreSupplierRequest $request)
    {
        $supplier = new Supplier;
        $supplier->code = preg_replace('/\s+/','',$request->code);
        $supplier->name = preg_replace('/\s+/',' ',$request->name);
        $supplier->address = preg_replace('/\s+/',' ',$request->address);
        $supplier->pic_name = preg_replace('/\s+/',' ',$request->pic_name);
        $supplier->primary_email = preg_replace('/\s+/',' ',$request->primary_email);
        $supplier->primary_phone_number = preg_replace('/\s+/',' ',$request->primary_phone_number);
        $supplier->save();
        return redirect('supplier')
          ->with('successMessage','Supplier has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('supplier.show')
            ->with('supplier', $supplier);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\Auth::user()->can('edit-supplier-module'))
        {
            $supplier = Supplier::findOrFail($id);
            return view('supplier.edit')
                ->with('supplier', $supplier);
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
    public function update(UpdateSupplierRequest $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->code = preg_replace('/\s+/','',$request->code);
        $supplier->name = preg_replace('/\s+/',' ',$request->name);
        $supplier->address = preg_replace('/\s+/',' ',$request->address);
        $supplier->pic_name = preg_replace('/\s+/',' ',$request->pic_name);
        $supplier->primary_email = preg_replace('/\s+/',' ',$request->primary_email);
        $supplier->primary_phone_number = preg_replace('/\s+/',' ',$request->primary_phone_number);
        $supplier->save();
        return redirect('supplier')
            ->with('successMessage', "$supplier->name has been updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $supplier = Supplier::findOrFail($request->supplier_id);
        $supplier->delete();
        return redirect('supplier')
            ->with('successMessage', "$supplier->name has been deleted");
    }

    protected function payment_invoices($id)
    {
      if(\Auth::user()->can('create-purchase-order-invoice-payment-module'))
      {
        $supplier = Supplier::findOrFail($id);
        $banks = Bank::lists('name', 'id');
        $cashs = Cash::lists('name','id');
        $purchase = \DB::table('purchase_orders')->where('supplier_id',$id)->get();
        $data_invoice = [];
        foreach ($purchase as $key) {
            if(count(PurchaseOrder::findOrFail($key->id)->purchase_order_invoice) == 0)
            {

            }else{
                array_push($data_invoice,[
                    'id'=>PurchaseOrder::findOrFail($key->id)->purchase_order_invoice->id,
                    'code'=>PurchaseOrder::findOrFail($key->id)->purchase_order_invoice->code,
                    'purchase_order_id'=>PurchaseOrder::findOrFail($key->id)->purchase_order_invoice->purchase_order_id,
                    'bill_price'=>PurchaseOrder::findOrFail($key->id)->purchase_order_invoice->bill_price,
                    'paid_price'=>PurchaseOrder::findOrFail($key->id)->purchase_order_invoice->paid_price,
                    'status'=>PurchaseOrder::findOrFail($key->id)->purchase_order_invoice->status,
                    'created_at'=>PurchaseOrder::findOrFail($key->id)->purchase_order_invoice->created_at,
                ]);
            }
        }
        // print_r($data_invoice);
        // exit();
        return view('supplier.payment_invoices')
            ->with('supplier',$supplier)
            ->with('banks',$banks)
            ->with('cashs',$cashs)
            ->with('data_invoice',$data_invoice);
      }else{
        return view('403');
      }

    }

    public function store_invoice_payment_cash(StorePaymentInvoicePurchaseKasRequest $request)
    {
        $data_invoice_payment = [];
        $invoice_payment_id = [];
        $data_cash_invoice_payment = [];
        $data_transaction_invoice_payment = [];
        $data_transaction_invoice_payment_hutang = [];
        foreach ($request->invoice_id as $key => $value) {
            //update to table purchase order invoices
            \DB::table('purchase_order_invoices')->where('purchase_order_id',$request->purchase_order_id[$key])->update(['paid_price'=>$request->paid_price[$key]+floatval(preg_replace('#[^0-9.]#', '', $request->amount[$key]))]);
            array_push($data_invoice_payment,[
                'purchase_order_invoice_id'=>$request->invoice_id[$key],
                'amount'=>floatval(preg_replace('#[^0-9.]#', '', $request->amount[$key])),
                'payment_method_id'=>$request->payment_method_id,
                'receiver'=>\Auth::user()->id,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
            ]);
            array_push($data_transaction_invoice_payment,[
                'amount'=>floatval(preg_replace('#[^0-9.]#', '', $request->amount[$key])),
                'sub_chart_account_id'=>$request->cash_account,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$request->invoice_id[$key],
                'source'=>$request->invoice_code[$key],
                'type'=>'keluar',
                'description'=>'PAYMENT INVOICE : '.$request->invoice_code[$key].' '.$request->supplier_name,
                'memo'=>'PAYMENT FOR : '.$request->invoice_code[$key]
            ]);
            array_push($data_transaction_invoice_payment_hutang,[
                'amount'=>floatval(preg_replace('#[^0-9.]#', '', $request->amount[$key])),
                'sub_chart_account_id'=>79,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$request->invoice_id[$key],
                'source'=>$request->invoice_code[$key],
                'type'=>'keluar',
                'description'=>'INVOICE TO : '.$request->supplier_name,
                'memo'=>''
            ]);
        }

        //insert to table purchase invoice payments
        \DB::table('purchase_invoice_payments')->insert($data_invoice_payment);
        //insert to table transaction chart account
        \DB::table('transaction_chart_accounts')->insert($data_transaction_invoice_payment);
        //insert to table transaction chart account "hutang"
        \DB::table('transaction_chart_accounts')->insert($data_transaction_invoice_payment_hutang);
        foreach ($request->invoice_id as $key => $value) {
            //update to table purchase order invoices
            array_push($data_cash_invoice_payment,[
                'cash_id'=>$request->cash_id,
                'purchase_invoice_payment_id'=>\DB::table('purchase_invoice_payments')->select('id')->where('purchase_order_invoice_id',$request->invoice_id[$key])->latest()->first()->id,
            ]);
        }
        //insert to table cash purchase invoice payments
        \DB::table('cash_purchase_invoice_payment')->insert($data_cash_invoice_payment);

        // foreach ($data_cash_invoice_payment as $key) {
        //         print_r($key['purchase_invoice_payment_id']->id);
        // }
        // print_r($data_cash_invoice_payment);
        // exit();
        $cash_value = Cash::findOrFail($request->cash_id);
        $cash_value->value = $cash_value->value-floatval(preg_replace('#[^0-9.]#', '', $request->sum_amount));
        $cash_value->save();

        return redirect('supplier')
            ->with('successMessage','payment supplier invoice has been added');
    }

    public function store_invoice_payment_bank(StorePaymentInvoicePurchaseTransferRequest $request)
    {
        $data_invoice_payment = [];
        $invoice_payment_id = [];
        $data_cash_invoice_payment = [];
        $data_transaction_invoice_payment = [];
        $data_transaction_invoice_payment_hutang = [];
        foreach ($request->invoice_id as $key => $value) {
            //update to table purchase order invoices
            \DB::table('purchase_order_invoices')->where('purchase_order_id',$request->purchase_order_id[$key])->update(['paid_price'=>$request->paid_price[$key]+floatval(preg_replace('#[^0-9.]#', '', $request->amount[$key]))]);
            array_push($data_invoice_payment,[
                'purchase_order_invoice_id'=>$request->invoice_id[$key],
                'amount'=>floatval(preg_replace('#[^0-9.]#', '', $request->amount[$key])),
                'payment_method_id'=>$request->payment_method_id,
                'receiver'=>\Auth::user()->id,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
            ]);
            array_push($data_transaction_invoice_payment,[
                'amount'=>floatval(preg_replace('#[^0-9.]#', '', $request->amount[$key])),
                'sub_chart_account_id'=>$request->transfer_account,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$request->invoice_id[$key],
                'source'=>'purchase_order_payment',
                'type'=>'keluar',
                'description'=>'PAYMENT INVOICE : '.$request->invoice_code[$key].' '.$request->supplier_name,
                'memo'=>'PAYMENT FOR : '.$request->invoice_code[$key]
            ]);
            array_push($data_transaction_invoice_payment_hutang,[
                'amount'=>floatval(preg_replace('#[^0-9.]#', '', $request->amount[$key])),
                'sub_chart_account_id'=>79,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$request->invoice_id[$key],
                'source'=>'purchase_order_payment',
                'type'=>'keluar',
                'description'=>'INVOICE TO : '.$request->supplier_name,
                'memo'=>''
            ]);
        }

        //insert to table purchase invoice payments
        \DB::table('purchase_invoice_payments')->insert($data_invoice_payment);
        //insert to table transaction chart account
        \DB::table('transaction_chart_accounts')->insert($data_transaction_invoice_payment);
        //insert to table transaction chart account "hutang"
        \DB::table('transaction_chart_accounts')->insert($data_transaction_invoice_payment_hutang);

        foreach ($request->invoice_id as $key => $value) {
            //update to table purchase order invoices
            array_push($data_cash_invoice_payment,[
                'bank_id'=>$request->bank_id,
                'purchase_invoice_payment_id'=>\DB::table('purchase_invoice_payments')->select('id')->where('purchase_order_invoice_id',$request->invoice_id[$key])->latest()->first()->id,
            ]);
        }
        //insert to table cash purchase invoice payments
        \DB::table('bank_purchase_invoice_payment')->insert($data_cash_invoice_payment);

        // foreach ($data_cash_invoice_payment as $key) {
        //         print_r($key['purchase_invoice_payment_id']->id);
        // }
        // print_r($data_cash_invoice_payment);
        // exit();
        $bank_value = Bank::findOrFail($request->bank_id);
        $bank_value->value = $bank_value->value-floatval(preg_replace('#[^0-9.]#', '', $request->sum_amount));
        $bank_value->save();

        return redirect('supplier')
            ->with('successMessage','payment supplier invoice has been added');
    }

    public function store_invoice_payment_giro(StorePaymentInvoicePurchaseGiroRequest $request)
    {
        $data_invoice_payment = [];
        $invoice_payment_id = [];
        $data_giro_invoice_payment = [];
        $data_transaction_invoice_payment = [];
        $data_transaction_invoice_payment_hutang = [];
        foreach ($request->invoice_id as $key => $value) {
            //update to table purchase order invoices
            \DB::table('purchase_order_invoices')->where('purchase_order_id',$request->purchase_order_id[$key])->update(['paid_price'=>$request->paid_price[$key]+floatval(preg_replace('#[^0-9.]#', '', $request->amount[$key]))]);
            array_push($data_invoice_payment,[
                'purchase_order_invoice_id'=>$request->invoice_id[$key],
                'amount'=>floatval(preg_replace('#[^0-9.]#', '', $request->amount[$key])),
                'payment_method_id'=>$request->payment_method_id,
                'receiver'=>\Auth::user()->id,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
            ]);
            array_push($data_transaction_invoice_payment,[
                'amount'=>floatval(preg_replace('#[^0-9.]#', '', $request->amount[$key])),
                'sub_chart_account_id'=>$request->gir_account,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$request->invoice_id[$key],
                'source'=>'purchase_order_payment',
                'type'=>'keluar',
                'description'=>'PAYMENT INVOICE : '.$request->invoice_code[$key].' '.$request->supplier_name,
                'memo'=>'PAYMENT FOR : '.$request->invoice_code[$key]
            ]);
            array_push($data_transaction_invoice_payment_hutang,[
                'amount'=>floatval(preg_replace('#[^0-9.]#', '', $request->amount[$key])),
                'sub_chart_account_id'=>79,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$request->invoice_id[$key],
                'source'=>'purchase_order_payment',
                'type'=>'keluar',
                'description'=>'INVOICE TO : '.$request->supplier_name,
                'memo'=>''
            ]);
        }

        //insert to table purchase invoice payments
        \DB::table('purchase_invoice_payments')->insert($data_invoice_payment);
        //insert to table transaction chart account
        \DB::table('transaction_chart_accounts')->insert($data_transaction_invoice_payment);
        //insert to table transaction chart account "hutang"
        \DB::table('transaction_chart_accounts')->insert($data_transaction_invoice_payment_hutang);

        foreach ($request->invoice_id as $key => $value) {
            //update to table purchase order invoices
            array_push($data_giro_invoice_payment,[
                'no_giro'=>$request->no_giro[$key],
                'bank'=>$request->bank[$key],
                'tanggal_cair'=>$request->tanggal_cair[$key],
                'amount'=>floatval(preg_replace('#[^0-9.]#', '', $request->amount[$key])),
                'purchase_invoice_payment_id'=>\DB::table('purchase_invoice_payments')->select('id')->where('purchase_order_invoice_id',$request->invoice_id[$key])->latest()->first()->id,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s')
            ]);
        }
        //insert to table cash purchase invoice payments
        \DB::table('giro_purchase_invoice_payment')->insert($data_giro_invoice_payment);

        // foreach ($data_cash_invoice_payment as $key) {
        //         print_r($key['purchase_invoice_payment_id']->id);
        // }
        // print_r($data_cash_invoice_payment);
        // exit();
        // $bank_value = Bank::findOrFail($request->bank_id);
        // $bank_value->value = $bank_value->value-floatval(preg_replace('#[^0-9.]#', '', $request->sum_amount));
        // $bank_value->save();

        return redirect('supplier')
            ->with('successMessage','payment supplier invoice has been added');
    }

}
