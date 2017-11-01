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

    /**
     * 母账户总资金
     */
    public function getCurrentTotalCapitalAttribute($value)
    {
        $value = $this->attributes['available_capital'] + $this->attributes['freezn_capital'];
        return sprintf("%.3f", $value);
    }

    /**
     * 密码
     *
     * @param  string $value
     * @return string
     */
    public function getPasswordAttribute($value)
    {
        $first = substr($value, 0, 1) ?: mt_rand(1, 9);
        $last = substr($value, -1) ?: mt_rand(1, 9);
        return $first . '**********' . $last;
    }

    /**
     * 密码
     *
     * @param  string $value
     * @return string
     */
    public function getCommunicationPwAttribute($value)
    {
        $first = substr($value, 0, 1) ?: mt_rand(1, 9);
        $last = substr($value, -1) ?: mt_rand(1, 9);
        return $first . '**********' . $last;
    }
}
