<?php

namespace App\Http\Controllers\Api\System;

use App\Http\Controllers\Controller;

class StockFinanceProductController extends Controller
{
    use \App\Http\Controllers\Load\ShowTrait, \App\Http\Controllers\Load\UpdateTrait, \App\Http\Controllers\Load\StoreTrait, \App\Http\Controllers\Load\DestroyTrait;

    public static $model_name = 'StockFinanceProduct';

    public function __construct()
    {
        $this->middleware("auth:api");
    }
}