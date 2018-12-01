<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $table = "assets";
    protected $fillable = ['date_purchase','name','notes','amount','periode'];
}
