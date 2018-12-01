<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Product;
use App\User;
class StockBalance extends Model
{
    protected $table = 'stock_balances';

    protected $fillable = ['code','created_by','created_at','updated_at'];

    public function products()
    {
        return $this->belongsToMany('App\Product')->withPivot('product_id','system_stock','real_stock');
    }

    public function creator()
    {
        return $this->belongsTo('App\User','created_by');
    }

}
