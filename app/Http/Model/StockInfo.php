<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class StockInfo extends Base
{
    use SoftDeletes;

    protected $table = "s_stock_info";

    protected $guarded = ['id', 'deleted_at', 'created_time', 'updated_time'];

    protected $dates = ['deleted_at'];

    /**
     * 停牌天数
     *
     * @param  string $value
     * @return string
     */
    public function getHaltDaysAttribute($value)
    {
        if (!$this->attributes['trading_halt_time'] || !$this->attributes['resumption_time']) {
            return 0;
        }
        return (strtotime($this->attributes['resumption_time']) - strtotime($this->attributes['stock_code'])) / (24 * 3600);
    }

}
