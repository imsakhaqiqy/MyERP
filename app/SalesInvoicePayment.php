<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\SalesInvoicePayment;
use App\PaymentMethod;

class SalesInvoicePayment extends Model
{
    protected $table = 'sales_invoice_payments';
    protected $fillable = ['sales_order_invoice_id', 'amount', 'receiver'];

    public function payment_method()
    {
    	return $this->belongsTo('App\PaymentMethod');
    }
}
