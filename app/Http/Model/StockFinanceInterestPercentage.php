<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class StockFinanceInterestPercentage extends Base
{
    protected $table = "u_stock_finance_interest_percentage";

    public function cust()
    {
        return $this->belongsTo('App\User', 'cust_id');
    }

}