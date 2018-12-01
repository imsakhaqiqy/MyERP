<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{

    protected $table = 'drivers';

    protected $fillable = ['code', 'name', 'contact_number'];
}
