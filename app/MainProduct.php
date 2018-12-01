<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\MainProduct;
use App\Product;
use App\Family;
use App\Category;
use App\Unit;

class MainProduct extends Model
{
    protected $table = 'main_products';

    protected $fillable = ['code','name','image','family_id','category_id','unit_id'];

    public function product()
    {
        return $this->hasMany('App\Product');
    }

    public function family(){
        return $this->belongsTo('App\Family');
    }

    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function unit(){
        return $this->belongsTo('App\Unit');
    }
}
