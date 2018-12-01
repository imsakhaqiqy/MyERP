<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\BankPurchaseInvoicePayment;

class BankPurchaseInvoicePayment extends Model
{
    protected $table = 'bank_purchase_invoice_payment';

    protected $fillable = ['bank_id','purchase_invoice_payment_id'];

    
}
