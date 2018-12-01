<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Category;

class Family extends Model
{
    protected $table = 'families';

    protected $fillable = ['code', 'name'];

    public function category(){
        return $this->hasMany('App\Category');
    }
}
