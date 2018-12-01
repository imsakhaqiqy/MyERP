<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\PurchaseOrder;
use App\Supplier;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $fillable = ['code', 'name', 'pic_name', 'primary_email', 'primary_phone_number', 'address'];

    public function purchase_order(){
        return $this->hasMany('App\PurchaseOrder');
    }
}
