<?php

namespace App\Http\Controllers\Api\FinancingManage;

use App\Http\Controllers\Controller;

class HolidayMaintainController extends Controller
{
    use \App\Http\Controllers\Load\ShowTrait, \App\Http\Controllers\Load\UpdateTrait, \App\Http\Controllers\Load\StoreTrait, \App\Http\Controllers\Load\DestroyTrait;

    public static $model_name = 'HolidayMaintain';

    public function __construct()
    {
        $this->middleware("auth:api");
    }
}