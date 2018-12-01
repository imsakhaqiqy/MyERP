<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\MainProduct;
use App\Family;
use App\Category;
use App\Unit;
use App\Product;
class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['name','stock', 'minimum_stock', 'description','main_product_id'];

    //relation main product
    public function main_product()
    {
        return $this->belongsTo('App\MainProduct','main_product_id');
    }

    //relation family
    public function family()
    {
        return $this->belongsTo('App\Family','name');
    }
    //relation to Category
    public function category(){
    	return $this->belongsTo('App\Category','name');
    }

    //relation to Unit
    public function unit(){
    	return $this->belongsTo('App\Unit','name');
    }

}
