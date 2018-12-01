<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\PurchaseOrder;
use App\User;
use App\PaymentMethod;
use App\PurchaseInvoicePayment;
use App\BankPurchaseInvoicePayment;
use App\CashPurchaseInvoicePayment;
use App\Bank;

class PurchaseOrderInvoice extends Model
{
    protected $table = 'purchase_order_invoices';

    protected $fillable = ['code','purchase_order_id', 'bill_price', 'paid_price', 'paid_at', 'created_by', 'status', 'notes','term'];

    public function creator()
    {
    	return $this->belongsTo('App\User', 'created_by');
    }

    public function purchase_order()
    {
    	return $this->belongsTo('App\PurchaseOrder');
    }

    public function payment_method()
    {
        return $this->belongsTo('App\PaymentMethod');
    }

    public function purchase_invoice_payment()
    {
        return $this->hasMany('App\PurchaseInvoicePayment', 'purchase_order_invoice_id');
    }

    public function bank_purchase_invoice_payment()
    {
        return $this->hasMany('App\BankPurchaseInvoicePayment','purchase_invoice_payment_id');
    }

    public function cash_purchase_invoice_payment()
    {
        return $this->hasMany('App\CashPurchaseInvoicePayment','purchase_invoice_payment_id');
    }


}
