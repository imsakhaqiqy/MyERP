<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\SubChartAccount;

class TransactionChartAccount extends Model
{
    protected $table = 'transaction_chart_accounts';

    protected $fillable = ['amount','sub_chart_account_id'];

    public function sub_chart_account()
    {
        return $this->belongsTo('App\SubChartAccount','sub_chart_account_id');
    }
}
