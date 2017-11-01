<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class StockFinanceEntrust extends Base
{
    protected $table = "u_stock_finance_entrust";

    public function parentEntrust()
    {
        return $this->hasOne("App\Http\Model\ParentStockFinanceEntrust", "stock_finance_entrust_id");
    }

}