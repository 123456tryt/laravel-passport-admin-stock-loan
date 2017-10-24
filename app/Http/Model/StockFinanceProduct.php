<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class StockFinanceProduct extends Base
{
    use SoftDeletes;

    protected $table = "s_stock_finance_products";

    protected $guarded = ['id', 'deleted_at', 'created_time', 'updated_time'];

    protected $dates = ['deleted_at'];

    /**
     * belongsTo代理商
     */
    public function agent()
    {
        return $this->belongsTo(Agent::Class, 'agent_id', 'id');
    }
}
