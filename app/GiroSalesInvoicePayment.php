<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\GiroSalesInvoicePayment;

class GiroSalesInvoicePayment extends Model
{
    protected $table = 'giro_sales_invoice_payment';

    protected $fillable = ['no_giro','bank','tanggal_cair','amount','sales_invoice_payment_id'];
}
