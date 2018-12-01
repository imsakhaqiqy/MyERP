<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\CashSalesInvoicePayment;

class CashSalesInvoicePayment extends Model
{
    protected $table = 'cash_sales_invoice_payment';

    protected $fillable = ['cash_id','sales_invoice_payment_id'];
}
