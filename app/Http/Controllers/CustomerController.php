<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Requests\StorePaymentInvoiceSalesKasRequest;
use App\Http\Requests\StorePaymentInvoiceSalesTransferRequest;
use App\Http\Requests\StorePaymentInvoiceSalesGiroRequest;
use App\Customer;
use App\Cash;
use App\Bank;
use App\SalesOrder;
use App\SalesOrderInvoice;
use App\SalesInvoicePayment;
use App\InvoiceTerm;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('customer-module'))
        {
            return view('customer.index');
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
        if(\Auth::user()->can('create-customer-module'))
        {
            $invoice_terms = InvoiceTerm::lists('name', 'id');
            return view('customer.create')
            ->with('invoice_terms', $invoice_terms);
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
    public function store(StoreCustomerRequest $request)
    {
        $customer = new Customer;
        $customer->name = preg_replace('/\s+/',' ',$request->name);
        $customer->phone_number = preg_replace('/\s+/','',$request->phone_number);
        $customer->address = preg_replace('/\s+/',' ',$request->address);
        $customer->invoice_term_id = preg_replace('/\s+/',' ',$request->invoice_term_id);
        $customer->save();
        //now update customer's code
        $customer_id = $customer->id;
        $customer_code = \DB::table('customers')->where('id',$customer_id)->update(['code'=>'CST-'.$customer_id]);
        return redirect('customer')
            ->with('successMessage', "Customer has been added");


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customer.show')
          ->with('customer',$customer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\Auth::user()->can('edit-customer-module'))
        {
            $invoice_terms = InvoiceTerm::lists('name', 'id');
            $customer = Customer::findOrFail($id);
            return view('customer.edit')
            ->with('invoice_terms', $invoice_terms)
            ->with('customer', $customer);
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
    public function update(UpdateCustomerRequest $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->name = preg_replace('/\s+/',' ',$request->name);
        $customer->phone_number = preg_replace('/\s+/','',$request->phone_number);
        $customer->address = preg_replace('/\s+/',' ',$request->address);
        $customer->invoice_term_id = preg_replace('/\s+/',' ',$request->invoice_term_id);
        $customer->save();
        return redirect('customer/'.$id.'/edit')
            ->with('successMessage', 'Customer has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $customer = Customer::findOrFail($request->customer_id);
        $customer->delete();
        return redirect('customer')
            ->with('successMessage', 'Customer has been deleted');
    }

    protected function payment_invoices($id)
    {
      if(\Auth::user()->can('create-sales-order-invoice-payment-module'))
      {
        $customer = Customer::findOrFail($id);
        $banks = Bank::lists('name', 'id');
        $cashs = Cash::lists('name','id');
        $sales = \DB::table('sales_orders')->where('customer_id',$id)->get();
        $data_invoice = [];
        foreach ($sales as $key) {
            if(count(SalesOrder::findOrFail($key->id)->sales_order_invoice) == 0)
            {

            }else{
                array_push($data_invoice,[
                    'id'=>SalesOrder::findOrFail($key->id)->sales_order_invoice->id,
                    'code'=>SalesOrder::findOrFail($key->id)->sales_order_invoice->code,
                    'sales_order_id'=>SalesOrder::findOrFail($key->id)->sales_order_invoice->sales_order_id,
                    'bill_price'=>SalesOrder::findOrFail($key->id)->sales_order_invoice->bill_price,
                    'paid_price'=>SalesOrder::findOrFail($key->id)->sales_order_invoice->paid_price,
                    'status'=>SalesOrder::findOrFail($key->id)->sales_order_invoice->status,
                    'created_at'=>SalesOrder::findOrFail($key->id)->sales_order_invoice->created_at,
                ]);
            }
        }
        // print_r($data_invoice);
        // exit();
        return view('customer.payment_invoices')
            ->with('customer',$customer)
            ->with('banks',$banks)
            ->with('cashs',$cashs)
            ->with('data_invoice',$data_invoice);
      }else{
        return view('403');
      }

    }

    public function store_invoice_payment_cash(StorePaymentInvoiceSalesKasRequest $request)
    {
        $data_invoice_payment = [];
        $invoice_payment_id = [];
        $data_cash_invoice_payment = [];
        $data_transaction_invoice_payment = [];
        $data_transaction_invoice_payment_piutang = [];
        foreach ($request->invoice_id as $key => $value) {
            //update to table purchase order invoices
            \DB::table('sales_order_invoices')->where('sales_order_id',$request->sales_order_id[$key])->update(['paid_price'=>$request->paid_price[$key]+floatval(preg_replace('#[^0-9.]#', '', $request->amount[$key]))]);
            array_push($data_invoice_payment,[
                'sales_order_invoice_id'=>$request->invoice_id[$key],
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
                'type'=>'masuk',
                'description'=>$request->customer_name.' PAYMENT INVOICE : '.$request->invoice_code[$key],
                'memo'=>'PAYMENT FOR : '.$request->invoice_code[$key]
            ]);
            array_push($data_transaction_invoice_payment_piutang,[
                'amount'=>floatval(preg_replace('#[^0-9.]#', '', $request->amount[$key])),
                'sub_chart_account_id'=>34,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$request->invoice_id[$key],
                'source'=>$request->invoice_code[$key],
                'type'=>'keluar',
                'description'=>'INVOICE TO : '.$request->customer_name,
                'memo'=>''
            ]);
        }

        //insert to table purchase invoice payments
        \DB::table('sales_invoice_payments')->insert($data_invoice_payment);
        //insert to table transaction chart account
        \DB::table('transaction_chart_accounts')->insert($data_transaction_invoice_payment);
        //insert to table transaction chart account "hutang"
        \DB::table('transaction_chart_accounts')->insert($data_transaction_invoice_payment_piutang);

        foreach ($request->invoice_id as $key => $value) {
            //update to table purchase order invoices
            array_push($data_cash_invoice_payment,[
                'cash_id'=>$request->cash_id,
                'sales_invoice_payment_id'=>\DB::table('sales_invoice_payments')->select('id')->where('sales_order_invoice_id',$request->invoice_id[$key])->latest()->first()->id,
            ]);
        }
        //insert to table cash purchase invoice payments
        \DB::table('cash_sales_invoice_payment')->insert($data_cash_invoice_payment);

        // foreach ($data_cash_invoice_payment as $key) {
        //         print_r($key['purchase_invoice_payment_id']->id);
        // }
        // print_r($data_cash_invoice_payment);
        // exit();
        $cash_value = Cash::findOrFail($request->cash_id);
        $cash_value->value = $cash_value->value+floatval(preg_replace('#[^0-9.]#', '', $request->sum_amount));
        $cash_value->save();

        return redirect('customer')
            ->with('successMessage','payment customer invoice has been added');
    }

    public function store_invoice_payment_bank(StorePaymentInvoiceSalesTransferRequest $request)
    {
        $data_invoice_payment = [];
        $invoice_payment_id = [];
        $data_transfer_invoice_payment = [];
        $data_transaction_invoice_payment = [];
        $data_transaction_invoice_payment_piutang = [];
        foreach ($request->invoice_id as $key => $value) {
            //update to table purchase order invoices
            \DB::table('sales_order_invoices')->where('sales_order_id',$request->sales_order_id[$key])->update(['paid_price'=>$request->paid_price[$key]+floatval(preg_replace('#[^0-9.]#', '', $request->amount[$key]))]);
            array_push($data_invoice_payment,[
                'sales_order_invoice_id'=>$request->invoice_id[$key],
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
                'type'=>'masuk',
                'description'=>$request->customer_name.' PAYMENT INVOICE : '.$request->invoice_code[$key],
                'memo'=>'PAYMENT FOR : '.$request->invoice_code[$key]
            ]);
            array_push($data_transaction_invoice_payment_piutang,[
                'amount'=>floatval(preg_replace('#[^0-9.]#', '', $request->amount[$key])),
                'sub_chart_account_id'=>34,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$request->invoice_id[$key],
                'source'=>$request->invoice_code[$key],
                'type'=>'keluar',
                'description'=>'INVOICE TO : '.$request->customer_name,
                'memo'=>''
            ]);
        }

        //insert to table purchase invoice payments
        \DB::table('sales_invoice_payments')->insert($data_invoice_payment);
        //insert to table transaction chart account
        \DB::table('transaction_chart_accounts')->insert($data_transaction_invoice_payment);
        //insert to table transaction chart account "hutang"
        \DB::table('transaction_chart_accounts')->insert($data_transaction_invoice_payment_piutang);

        foreach ($request->invoice_id as $key => $value) {
            //update to table purchase order invoices
            array_push($data_transfer_invoice_payment,[
                'bank_id'=>$request->bank_id,
                'sales_invoice_payment_id'=>\DB::table('sales_invoice_payments')->select('id')->where('sales_order_invoice_id',$request->invoice_id[$key])->latest()->first()->id,
            ]);
        }
        //insert to table cash purchase invoice payments
        \DB::table('bank_sales_invoice_payment')->insert($data_transfer_invoice_payment);

        // foreach ($data_cash_invoice_payment as $key) {
        //         print_r($key['purchase_invoice_payment_id']->id);
        // }
        // print_r($data_cash_invoice_payment);
        // exit();
        $bank_value = Bank::findOrFail($request->bank_id);
        $bank_value->value = $bank_value->value+floatval(preg_replace('#[^0-9.]#', '', $request->sum_amount));
        $bank_value->save();

        return redirect('customer')
            ->with('successMessage','payment customer invoice has been added');
    }

    public function store_invoice_payment_giro(StorePaymentInvoiceSalesGiroRequest $request)
    {
        $data_invoice_payment = [];
        $invoice_payment_id = [];
        $data_giro_invoice_payment = [];
        $data_transaction_invoice_payment = [];
        $data_transaction_invoice_payment_piutang = [];
        foreach ($request->invoice_id as $key => $value) {
            //update to table purchase order invoices
            \DB::table('sales_order_invoices')->where('sales_order_id',$request->sales_order_id[$key])->update(['paid_price'=>$request->paid_price[$key]+floatval(preg_replace('#[^0-9.]#', '', $request->amount[$key]))]);
            array_push($data_invoice_payment,[
                'sales_order_invoice_id'=>$request->invoice_id[$key],
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
                'source'=>$request->invoice_code[$key],
                'type'=>'masuk',
                'description'=>$request->customer_name.' PAYMENT INVOICE : '.$request->invoice_code[$key],
                'memo'=>'PAYMENT FOR : '.$request->invoice_code[$key]
            ]);
            array_push($data_transaction_invoice_payment_piutang,[
                'amount'=>floatval(preg_replace('#[^0-9.]#', '', $request->amount[$key])),
                'sub_chart_account_id'=>34,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
                'reference'=>$request->invoice_id[$key],
                'source'=>$request->invoice_code[$key],
                'type'=>'keluar',
                'description'=>'INVOICE TO : '.$request->customer_name,
                'memo'=>''
            ]);
        }

        //insert to table purchase invoice payments
        \DB::table('sales_invoice_payments')->insert($data_invoice_payment);
        //insert to table transaction chart account
        \DB::table('transaction_chart_accounts')->insert($data_transaction_invoice_payment);
        //insert to table transaction chart account "hutang"
        \DB::table('transaction_chart_accounts')->insert($data_transaction_invoice_payment_piutang);

        foreach ($request->invoice_id as $key => $value) {
            //update to table purchase order invoices
            array_push($data_giro_invoice_payment,[
                'no_giro'=>$request->no_giro[$key],
                'bank'=>$request->bank[$key],
                'tanggal_cair'=>$request->tanggal_cair[$key],
                'amount'=>floatval(preg_replace('#[^0-9.]#', '', $request->amount[$key])),
                'sales_invoice_payment_id'=>\DB::table('sales_invoice_payments')->select('id')->where('sales_order_invoice_id',$request->invoice_id[$key])->latest()->first()->id,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s'),
            ]);
        }
        //insert to table cash purchase invoice payments
        \DB::table('giro_sales_invoice_payment')->insert($data_giro_invoice_payment);

        // foreach ($data_cash_invoice_payment as $key) {
        //         print_r($key['purchase_invoice_payment_id']->id);
        // }
        // print_r($data_cash_invoice_payment);
        // exit();
        // $bank_value = Bank::findOrFail($request->bank_id);
        // $bank_value->value = $bank_value->value+floatval(preg_replace('#[^0-9.]#', '', $request->sum_amount));
        // $bank_value->save();

        return redirect('customer')
            ->with('successMessage','payment customer invoice has been added');
    }

}
