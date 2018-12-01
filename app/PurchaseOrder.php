<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\PurchaseOrder;
use App\Supplier;
use App\User;
use App\MainProduct;
use App\Product;
use App\PurchaseOrderInvoice;
use App\PurchaseReturn;
use App\PurchaseInvoicePayment;
use App\BankPurchaseInvoicePayment;

class PurchaseOrder extends Model
{
    protected $table = 'purchase_orders';

    protected $fillable = ['code', 'supplier_id', 'creator', 'status', 'notes'];

    public function supplier()
    {
    	return $this->belongsTo('App\Supplier');
    }

    public function created_by()
    {
    	return $this->belongsTo('App\User', 'creator');
    }

    public function products()
    {
        return $this->belongsToMany('App\Product')->withPivot('quantity','price','price_per_unit','purchase_order_id');
    }

    public function main_products()
    {
        return $this->belongsTo('App\MainProduct');
    }

    //relation to purchase order invoice
    public function purchase_order_invoice()
    {
        return $this->hasOne('App\PurchaseOrderInvoice');
    }

    //relaation to purhcase return
    public function purchase_returns(){
        return $this->hasMany('App\PurchaseReturn');
    }

    public function purchase_invoice_payments()
    {
        return $this->belongsTo('App\PurchaseInvoicePayment','id');
    }

    public function bank_purchase_invoice_payment()
    {
        return $this->hasMany('App\BankPurchaseInvoicePayment','purchase_invoice_payment_id');
    }


}
