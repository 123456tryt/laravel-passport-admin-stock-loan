<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class StockInfo extends Base
{
    use SoftDeletes;

    protected $table = "s_stock_info";

    protected $guarded = ['id', 'deleted_at', 'created_time', 'updated_time'];

    protected $dates = ['deleted_at'];

}
