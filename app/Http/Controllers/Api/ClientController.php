<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\Client;
use Illuminate\Http\Request;

/**
 * Class ClientController 客户表
 * @package App\Http\Controllers\Api
 */
class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }

    /**
     * 客户列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $page = $request->input('page', 1);
        $keyword = $request->input('keyword');
        $agent_id = $request->input('agent_id');
        $cacke_key = "client_search={$keyword}_agentID_{$agent_id}_page_{$page}";

        $list = \Cache::remember($cacke_key, 1, function () use ($keyword, $agent_id) {
            $query = Client::orderByDesc('updated_time');
            if ($keyword) {
                $query = $query->orWhere('nick_name', 'like', "%$keyword%")
                    ->orWhere('cellphone', 'like', "%$keyword%")
                    ->orWhere('id', "%$keyword%")
                    ->orWhere('real_name', 'like', "%$keyword%");
            }
            $data = $query->paginate(self::PAGE_SIZE);
            //TODO::根据关系表只显示本级以下代理商
            return $data;
        });
        return self::jsonReturn($list);
    }

}