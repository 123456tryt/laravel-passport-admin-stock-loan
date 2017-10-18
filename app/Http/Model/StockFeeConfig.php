<?php

namespace App\Http\Model;

class StockFeeConfig extends Base
{
    protected $table = "s_stock_fees";
    protected $guarded = ['id', 'create_time', 'updated_time'];


}
