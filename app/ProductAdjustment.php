<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class ProductAdjustment extends Model
{
    protected $table = 'product_adjustment';

    protected $fillable = ['product_id','unit_cost','qty','total','adjustment_id'];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
