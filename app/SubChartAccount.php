<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Cash;
use App\Bank;
use App\TransactionChartAccount;

class SubChartAccount extends Model
{
    protected $table = 'sub_chart_accounts';

    protected $fillable = ['reference','account_number','chart_account_id'];

    public function transaction_chart_account()
    {
        return $this->hasMany('App\TransactionChartAccount');
    }

}
