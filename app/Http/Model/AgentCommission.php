<?php

namespace App\Http\Model;


class AgentCommission extends Base
{
    public $timestamps = false;
    protected $table = "u_stock_finance_interest_percentage";
    protected $guarded = ['id', 'create_time'];


}
