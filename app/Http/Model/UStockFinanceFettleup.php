<?php

namespace App\Http\Model;

class UStockFinanceFettleup extends Base
{
    protected $table = "u_stock_finance_settleup";

    protected $guarded = ['id', 'created_time', 'updated_time'];
}
