<?php

namespace App\Http\Model;

class UStockFinanceHolding extends Base
{
    protected $table = "u_stock_finance_holdings";

    protected $guarded = ['id', 'created_time', 'updated_time'];

    /**
     * belongsTo股票
     */
    public function stock_info()
    {
        return $this->belongsTo(StockInfo::Class, 'stock_code', 'stock_code');
    }

    /**
     * belongsTo客户
     */
    public function client()
    {
        return $this->belongsTo(Client::Class, 'cust_id', 'id');
    }

    /**
     * 配送数量
     *
     * @param  string $value
     * @return string
     */
    public function getPresentNumsAttribute($value)
    {
        if (!$this->stock_info->attributes['ex_rights_stock_times'] || !$this->attributes['holdings_quantity']) {
            return 0;
        }
        return $this->stock_info->attributes['ex_rights_stock_times'] * $this->attributes['holdings_quantity'];
    }

    /**
     * 均价
     *
     * @param  string $value
     * @return string
     */
    public function getAvarageAttribute($value)
    {
        if (!$this->attributes['total_bought_amount']) {
            return 0;
        }
        $value = $this->attributes['total_bought_amount'] / ($this->attributes['total_sold_amount'] + $this->attributes['holdings_quantity']);
        return sprintf("%.3f", $value);
    }
}
