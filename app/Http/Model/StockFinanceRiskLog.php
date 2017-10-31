<?php

namespace App\Http\Model;

class StockFinanceRiskLog extends Base
{
    protected $table = "s_stock_finance_risk_control_log";

    protected $guarded = ['id', 'created_time', 'updated_time'];

    /**
     * belongsTo客户
     */
    public function client()
    {
        return $this->belongsTo(Client::Class, 'cust_id', 'id');
    }
}
