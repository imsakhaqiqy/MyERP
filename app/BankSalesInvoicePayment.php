<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\BankSalesInvoicePayment;

class BankSalesInvoicePayment extends Model
{
    protected $table = 'bank_sales_invoice_payment';

    protected $fillable = ['bank_id','sales_invoice_payment_id'];
}
