<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\ProductAdjustment;
class Adjustment extends Model
{
    protected $table = 'adjustment';

    protected $fillable = ['code','in_out','notes'];

    public function product_adjustment()
    {
        return $this->hasMany('App\ProductAdjustment');
    }
}
