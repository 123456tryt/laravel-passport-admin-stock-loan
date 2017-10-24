<?php

namespace App\Http\Model;

class CapitalPool extends Base
{
    protected $table = "s_capital_pool";

    protected $guarded = ['id', 'created_time', 'updated_time'];
}
