<?php

namespace App\Http\Model;

class StockFinanceProduct extends Base
{
    protected $table = "s_stock_finance_products";

    public $timestamps = false;
    protected $guards = ['id'];

}
