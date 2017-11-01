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


    /**
     * 获取状态
     *
     * @param  string $value
     * @return string
     */
    public function getRiskControlTypeAttribute($value)
    {
        switch ($value) {
            case 1:
                return '付息欠费';
                break;
            case 2:
                return '超预警线';
                break;
            case 3:
                return '超平仓线';
                break;
            case 4:
                return '补保失败';
                break;
            default:
                return '未知状态';
        }
    }

}
