<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\Agent;
use App\Http\Model\AgentPeak;
use Illuminate\Http\Request;

/**
 * Class AgentPeakController 代理商资金峰值表
 * @package App\Http\Controllers\Api
 */
class AgentPeakController extends Controller
{

    const AgentSelectorListCackeKey = 'agent.selector.list.cache.key';

    public function __construct()
    {
        $this->middleware("auth:api")->except(['search']);
    }


    public function list(Request $request)
    {
        $per_page = $request->input('size', self::PAGE_SIZE);
        $keyword = $request->input('keyword');
        $query = AgentPeak::orderByDesc('updated_time')->with('agent');
        if ($keyword) {
            $agent_ids = Agent::orWhere('phone', 'like', "%$keyword%")
                ->orWhere('agent_name', 'like', "%$keyword%")
                ->orWhere('id', 'like', "%$keyword%")->pluck('id')->all();
            $query->orWhereIn('agent_id', array_values($agent_ids));
        }
        $data = $query->paginate($per_page);
        return self::jsonReturn($data);
    }


}