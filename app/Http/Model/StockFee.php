<?php

namespace App\Http\Model;

class StockFee extends Base
{
    protected $table = "s_stock_fees";

    protected $guarded = ['id', 'created_time', 'updated_time'];

}
