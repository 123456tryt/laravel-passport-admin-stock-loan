<?php

namespace App\Http\Model;

class UStockFinancing extends Base
{
    protected $table = "u_stock_financing";

    protected $guarded = ['id', 'created_time', 'updated_time'];
}
