<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\CashPurchaseInvoicePayment;

class CashPurchaseInvoicePayment extends Model
{
    protected $table = 'cash_purchase_invoice_payment';

    protected $fillable = ['cash_id','purchase_invoice_payment_id'];
}
