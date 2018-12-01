<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Unit;
use App\Product;
class Unit extends Model
{
    protected $table = 'units';
    protected $fillable = ['name'];

    public function products()
    {
    	return $this->hasMany('App\Product');
    }
}
