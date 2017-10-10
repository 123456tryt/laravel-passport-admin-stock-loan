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
    public function __construct()
    {
        $this->middleware("auth:api")->except(['search']);
    }

    public function createAgent(Request $request)
    {
        $user = Auth::user();
        //确定确定权限

        $parent_id = $request->input('parent_id');
        $parent_agent = Agent::find($parent_id);
        $agent_level = $parent_agent->agent_level + 1;
        $data = $request->except(['password', 'comfirm_password']);

        //TODO: 根据owen_phone password 创建代理商的最高权限的账号

        $data['agent_level'] = $agent_level;
        $instance = Agent::create($data);
        return self::jsonReturn($instance, self::CODE_SUCCESS, '代理商创建成功');
    }

    public function search(Request $request)
    {
        $name = $request->input('name');
        $cacke_key = 'agent_search_list_' . $name;

        $list = \Cache::remember($cacke_key, 1, function () use ($name) {
            $query = Agent::where('is_locked', '!=', 1)->orderByDesc('updated_time')->limit(self::PAGE_SIZE);
            if ($name) {
                $data = $query->where('name', 'like', "%$name%")->get();
            } else {
                $data = $query->get();
            }
            return $data;
        });

        return self::jsonReturn($list);
    }

    public function register()
    {

        return "hello world";
    }


    public function list(Request $request)
    {
        $page = $request->input('page', 1);
        $name = $request->input('name');

        $cacke_key = "agent_list__{$page}_search_{$name}";

        $list = \Cache::remember($cacke_key, 1, function () use ($name) {
            $query = Agent::where('is_locked', '!=', 1)->orderByDesc('updated_time')->limit(self::PAGE_SIZE);
            if ($name) {
                $data = $query->where('name', 'like', "%$name%")->paginate(self::PAGE_SIZE);
            } else {
                $data = $query->paginate(self::PAGE_SIZE);
            }
            return $data;
        });

        return self::jsonReturn($list);
    }


}