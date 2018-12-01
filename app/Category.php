<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\MainProduct;
use App\Family;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['code','name','family_id'];

    //relation to Product
    public function main_products()
    {
    	return $this->hasMany('App\MainProduct');
    }

    public function family()
    {
        return $this->belongsTo('App\Family');
    }

}
