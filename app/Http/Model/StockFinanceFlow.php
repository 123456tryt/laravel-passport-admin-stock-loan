<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class StockFinanceFlow extends Base
{
    protected $table = "u_stock_financing_flow";

    public function product()
    {
        return $this->hasOne('App\Http\Model\StockFinanceProducts', 'id', 'product_id');
    }

    public function entrust()
    {
        return $this->hasOne('App\Http\Model\StockFinanceEntrust', 'id', 'entrust_id');
    }

    public function entrushHistory()
    {
        return $this->hasOne('App\Http\Model\StockFinanceEntrustHistory',
            'id', 'entrust_id');
    }
}