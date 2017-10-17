<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\ClientWithdraw;
use Illuminate\Http\Request;

/**
 * 客户提现控制器
 * Class ClientWithdrawController
 * @package App\Http\Controllers\Api
 */
class ClientWithdrawController extends Controller
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
        $cacke_key = "client_withdraw_search_{$keyword}_agentID_{$agent_id}_page_{$page}";

//        $list = \Cache::remember($cacke_key, 1, function () use ($keyword, $agent_id) {
//            $query = ClientWithdraw::orderByDesc('updated_time');
//            if ($keyword) {
//                $query = $query->orWhere('nick_name', 'like', "%$keyword%")
//                    ->orWhere('cellphone', 'like', "%$keyword%")
//                    ->orWhere('id', "%$keyword%")
//                    ->orWhere('real_name', 'like', "%$keyword%");
//            }
//            $data = $query->paginate(self::PAGE_SIZE);
//            //TODO::根据关系表只显示本级以下代理商
//            return $data;
//        });
        $list = ClientWithdraw::paginate();
        return self::jsonReturn($list);
    }


    public function update(Request $request)
    {
        $withdrawInfo = ClientWithdraw::find($request->id)
            ->fill($request->only('fee', 'remark', 'cash_status'));
        $withdrawInfo->in_amount = $withdrawInfo->cash_amount - $withdrawInfo->fee;
        $withdrawInfo->save();
        return self::jsonReturn($withdrawInfo, self::CODE_SUCCESS, '修改提现状态成功');
    }

}