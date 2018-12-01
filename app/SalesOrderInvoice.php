<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\SalesOrder;
use App\User;
use App\PaymentMethod;
use App\SalesInvoicePayment;
use App\BankSalesInvoicePayment;
use App\CashSalesInvoicePayment;
use App\Bank;
use App\InvoiceTerm;

class SalesOrderInvoice extends Model
{
    protected $table = 'sales_order_invoices';

    protected $fillable = ['code','sales_order_id', 'bill_price', 'paid_price', 'paid_at', 'created_by', 'status', 'notes' ,'due_date','persen_ppn','price_ppn','ppn_hidden'];

    public function creator()
    {
    	return $this->belongsTo('App\User', 'created_by');
    }

    public function sales_order()
    {
    	return $this->belongsTo('App\SalesOrder');
    }

    public function payment_method()
    {
        return $this->belongsTo('App\PaymentMethod');
    }

    public function sales_invoice_payment()
    {
        return $this->hasMany('App\SalesInvoicePayment', 'sales_order_invoice_id');
    }

    public function bank_sales_invoice_payment()
    {
        return $this->hasMany('App\BankSalesInvoicePayment','sales_invoice_payment_id');
    }

    public function cash_sales_invoice_payment()
    {
        return $this->hasMany('App\CashSalesInvoicePayment','sales_invoice_payment_id');
    }

    public function bank()
    {
        return $this->belongsTo('App\Bank');
    }

}
