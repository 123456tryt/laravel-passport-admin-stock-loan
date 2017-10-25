<?php

namespace App\Http\Controllers\Api\System;

use App\Http\Controllers\Controller;

class StockFinanceRiskController extends Controller
{
    use \App\Http\Controllers\Load\ShowTrait;

    public static $model_name = 'StockFinanceRisk';

    public function __construct()
    {
        $this->middleware("auth:api");
    }
}