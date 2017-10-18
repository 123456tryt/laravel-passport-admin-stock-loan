<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\Agent;
use App\Http\Model\AgentInfo;
use App\Http\Model\AgentProfitRateConfig;
use App\Http\Model\Client;
use App\Http\Model\ClientWithdraw;
use App\Http\Model\StockFeeConfig;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class StockFeeConfigController 股票费率配置
 * @package App\Http\Controllers\Api
 */
class StockFeeConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api")->except(['search']);
    }

    public function list()
    {
        $agent_id = \Auth::user()->agent_id;
        $data = StockFeeConfig::whereAgentId($agent_id)->get();
        return self::jsonReturn($data);
    }


    public function updateOrCreate(Request $request)
    {
        $agent_id = \Auth::user()->agent_id;
        $id = $request->id;
        //todo::需要检查客户ID 属于这个代理商
        $code = StockFeeConfig::updateOrInsert(compact('agent_id', 'id'), $request->except('id', 'agent_id', 'cust_id')) ? 1 : 0;
        return self::jsonReturn([], $code);
    }
}