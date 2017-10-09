<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class AgentController 代理商
 * @package App\Http\Controllers\Api
 */
class AgentController extends Controller
{
    const PAGE_SIZE = 30;
    public function __construct()
    {
        $this->middleware("auth:api")->except(['search']);
    }

    public function createAgent(Request $request)
    {
        $user = Auth::user();
        $parent_agent_id = $user->agent_id;
        $agent_level = $user->agent->agent_level + 1;
        $data = $request->all();
        $data['agent_level'] = $agent_level;
        $data['parent_id'] = $parent_agent_id;
        $instance = Agent::create($data);
        return self::jsonReturn($instance);
    }

    public function search(Request $request)
    {

        $name = $request->input('name');
        $data = Agent::where('name', 'like', "%$name%")->orderByDesc('updated_time')->limit(self::PAGE_SIZE)->get();

        return self::jsonReturn($data);
    }

    public function register()
    {

        return "hello world";
    }

}