<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\Agent;
use App\Http\Model\AgentCashFlow;
use Illuminate\Http\Request;

/**
 * Class AgentCashController 代理商提现
 * @package App\Http\Controllers\Api
 */
class AgentCashController extends Controller
{


    public function __construct()
    {
        $this->middleware("auth:api")->except(['search']);
    }

    public function list(Request $request)
    {
        //权限
        $per_page = $request->input('size', self::PAGE_SIZE);
        $keyword = $request->input('keyword');
        $query = AgentCashFlow::orderByDesc('id')->with('agent');
        if ($keyword) {
            $agent_ids = Agent::orWhere('agent_number', 'like', "%$keyword%")
                ->orWhere('agent_name', 'like', "%$keyword%")->orWhere('id', 'like', "%$keyword%")->pluck('id')->all();
            $query->whereIn('agent_id', array_values($agent_ids));
        }
        $data = $query->paginate($per_page);
        return self::jsonReturn($data);
    }


    public function info(Request $request)
    {
        $data = AgentCashFlow::find($request->id);
        return self::jsonReturn($data);
    }


    public function update(Request $request)
    {
        $instance = AgentCashFlow::find($request->id);
        $data = $request->only('cash_status', 'fee', 'remark');
        $data['in_amount'] = $instance->cash_amount - $request->fee;
        $rest = $instance->fill($data)->save();
        return self::jsonReturn($data);
    }


}