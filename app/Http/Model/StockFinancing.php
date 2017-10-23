<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class StockFinancing extends Base
{
    protected $table = "u_stock_financing";

    public function product()
    {
        return $this->hasOne('App\Http\Model\StockFinanceProducts', 'id', 'product_id');
    }

}