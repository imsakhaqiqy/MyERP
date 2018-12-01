<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\SubChartAccount;

class ChartAccount extends Model
{
    protected $table = 'chart_accounts';

    protected $fillable = ['name','account_number','description'];

    public function sub_chart_account()
    {
        return $this->hasMany('App\SubChartAccount');
    }
}
