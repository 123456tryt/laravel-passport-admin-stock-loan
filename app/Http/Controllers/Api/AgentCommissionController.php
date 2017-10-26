<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\Agent;
use App\Http\Model\AgentCommission;
use Illuminate\Http\Request;

/**
 * Class AgentCommissionController 代理商佣金明细
 * @package App\Http\Controllers\Api
 */
class AgentCommissionController extends Controller
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
        $query = AgentCommission::orderByDesc('id');
        if ($keyword) {
            $agent_ids = Agent::orWhere('phone', 'like', "%$keyword%")->orWhere('agent_name', 'like', "%$keyword%")->orWhere('id', 'like', "%$keyword%")->pluck('id')->all();
            $query->orWhereIn('agent1', array_values($agent_ids))
                ->orWhereIn('agent2', array_values($agent_ids))
                ->orWhereIn('agent3', array_values($agent_ids))
                ->orWhereIn('agent4', array_values($agent_ids))
                ->orWhereIn('agent5', array_values($agent_ids));
        }
        $data = $query->paginate($per_page);
        return self::jsonReturn($data);
    }


}