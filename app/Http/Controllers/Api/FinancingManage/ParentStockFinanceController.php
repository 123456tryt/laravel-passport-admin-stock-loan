<?php

namespace App\Http\Controllers\Api\FinancingManage;

use App\Http\Controllers\Controller;

class ParentStockFinanceController extends Controller
{
    use \App\Http\Controllers\Load\ShowTrait, \App\Http\Controllers\Load\UpdateTrait, \App\Http\Controllers\Load\StoreTrait;

    public static $model_name = 'ParentStockFinance';

    public function __construct()
    {
        $this->middleware("auth:api");
    }
}