<?php

namespace App\Http\Controllers\Api\System;

use App\Http\Controllers\Controller;

class StockFinanceRiskLogController extends Controller
{
    use \App\Http\Controllers\Load\ShowTrait;

    public static $model_name = 'StockFinanceRiskLog';

    public function __construct()
    {
        $this->middleware("auth:api");
    }
}