<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Customer;

class InvoiceTerm extends Model
{
    protected $table ='invoice_terms';

    protected $fillable = ['name', 'day_many'];

    protected function customers()
    {
    	return $this->hasMany('App\Customer');
    }
}
