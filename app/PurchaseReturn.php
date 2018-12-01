<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\PurchaseOrder;
use App\PurchaseReturn;
use App\User;
use App\Product;

class PurchaseReturn extends Model
{
    protected $table = 'purchase_returns';


    public function creator(){
    	return $this->belongsTo('App\User');
    }

    public function purchase_order(){

    	return $this->belongsTo('App\PurchaseOrder', 'purchase_order_id');
    }

    public function product(){

    	return $this->belongsTo('App\Product');
    }
    
}
