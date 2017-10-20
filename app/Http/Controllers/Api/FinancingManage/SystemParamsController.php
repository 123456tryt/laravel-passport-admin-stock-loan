<?php

namespace App\Http\Controllers\Api\FinancingManage;

use App\Http\Controllers\Controller;

/**
 * Class SystemController 系统代理商
 * @package App\Http\Controllers\Api
 */
class SystemParamsController extends Controller
{
    use \App\Http\Controllers\Load\ShowTrait, \App\Http\Controllers\Load\UpdateTrait;

    public static $model_name = 'SystemParams';

    public function __construct()
    {
        $this->middleware("auth:api");
    }
}