<?php

namespace App\Http\Model;

class UStockFinancing extends Base
{
    protected $table = "u_stock_financing";

    protected $guarded = ['id', 'created_time', 'updated_time'];

    /**
     * belongsTo客户
     */
    public function client()
    {
        return $this->belongsTo(Client::Class, 'cust_id', 'id');
    }

    /**
     * belongsTo收费
     */
    public function stock_fee()
    {
        return $this->belongsTo(StockFee::Class, 'relation_id', 'agent_id');
    }

    /**
     * 总资产
     *
     * @param  string $value
     * @return string
     */
    public function getTotalAssetsAttribute($value)
    {
        return 0;
    }
}
