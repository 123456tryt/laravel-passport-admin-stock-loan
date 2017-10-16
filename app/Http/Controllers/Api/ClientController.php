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

    public function info(Request $request)
    {
        $client = Client::find($request->id);
        return self::jsonReturn($client);
    }

    public function update(Request $request)
    {
        $client = Client::find($request->id)
            ->fill($request->only('is_login_forbidden', 'is_cash_forbidden', 'is_charge_forbidden', 'is_stock_finance_forbidden'));
        $client->save();
        return self::jsonReturn($client, self::CODE_SUCCESS, '修改客户成功');
    }

}