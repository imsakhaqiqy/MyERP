<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Customer;
use App\InvoiceTerm;

class Customer extends Model
{
    protected $table = 'customers';
    protected $fillable = ['code', 'name', 'phone_number', 'address', 'invoice_term_id'];


    public function invoice_term()
    {
    	return $this->belongsTo('App\InvoiceTerm');
    }
    
}
