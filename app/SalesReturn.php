<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\SalesOrder;
use App\SalesReturn;
use App\User;
use App\Product;
use App\ProductSalesOrder;

class SalesReturn extends Model
{
    protected $table = 'sales_returns';

    public function creator()
    {
        return $this->belongsTo('App\User');
    }

    public function sales_order()
    {
        return $this->belongsTo('App\SalesOrder','sales_order_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

}
