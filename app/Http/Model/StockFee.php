<?php

namespace App\Http\Model;

class StockFee extends Base
{
    protected $table = "s_stock_fees";

    protected $guarded = ['id', 'created_time', 'updated_time'];

    /**
     * belongsTo代理商
     */
    public function agent()
    {
        return $this->belongsTo(Agent::Class, 'agent_id', 'id');
    }

    /**
     * belongsTo客户
     */
    public function client()
    {
        return $this->belongsTo(Client::Class, 'cust_id', 'id');
    }
}
