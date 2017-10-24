<?php

namespace App\Http\Model;

class ParentStockFinance extends Base
{
    protected $table = "s_parent_stock_finance";

    protected $guarded = ['id', 'created_time', 'updated_time'];


    /**
     * belongsTo资金池
     */
    public function capital_pool()
    {
        return $this->belongsTo(CapitalPool::Class, 'capital_id', 'id');
    }
}
