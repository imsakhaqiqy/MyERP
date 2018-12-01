<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PaymentMethod;
use App\Bank;
use App\BankPurchaseInvoicePayment;

class PurchaseInvoicePayment extends Model
{
    protected $table = 'purchase_invoice_payments';

    protected $fillable = ['purchase_order_invoice_id', 'amount', 'receiver'];

    public function bank(){

    }

    public function bank_purchase_invoice_payment()
    {
        return $this->hasMany('App\BankPurchaseInvoicePayment');
    }

    public function payment_method()
    {
    	return $this->belongsTo('App\PaymentMethod');
    }

}
