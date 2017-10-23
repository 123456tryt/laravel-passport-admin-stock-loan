<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class StockFinanceContract extends Base
{
    protected $table = "u_stock_finance_contract";

    protected $fillable = ["stock_finance_id", "cust_id", "agent_id", "finance_begin_time", "finance_end_time", "interest_charge_standard",
        "contract_no", "contract_url"];
}