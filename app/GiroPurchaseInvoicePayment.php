<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\GiroPurchaseInvoicePayment;

class GiroPurchaseInvoicePayment extends Model
{
  protected $table = 'giro_purchase_invoice_payment';

  protected $fillable = ['no_giro','bank','tanggal_cair','amount','purchase_invoice_payment_id'];
}
