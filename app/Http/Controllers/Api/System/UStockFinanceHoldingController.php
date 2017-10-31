<?php

namespace App\Http\Controllers\Api\System;

use App\Http\Controllers\Controller;

class UStockFinanceHoldingController extends Controller
{
    use \App\Http\Controllers\Load\ShowTrait, \App\Http\Controllers\Load\UpdateTrait, \App\Http\Controllers\Load\StoreTrait;

    public static $model_name = 'UStockFinanceHolding';

    public function __construct()
    {
        $this->middleware("auth:api");
    }

    public function index_stock_finance($stock_finance_id = '', $data = [])
    {
        $agent_id = \Auth::user()->agent_id;
        if (!$agent_id || !$stock_finance_id) return;
        $params = $data ?: request()->all();
        $params['where'][] = ['stock_finance_id', $stock_finance_id];
        $keyWord = config('select.' . static::$model_name . '.keyWord');
        if ($keyWord == 'agent_id') {
            $params['where'][] = ['agent_id', $agent_id];
        }
        $rs = static::_run_orm($params);
        return self::jsonReturn($rs);
    }
}