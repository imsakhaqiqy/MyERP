<?php

namespace App;
use App\Role;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
	
    public function roles(){

    	return $this->belongsToMany('App\Role');
    }
}
