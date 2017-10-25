<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\Client;
use App\Http\Model\ClientWithdraw;
use Carbon\Carbon;
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
        $page_size = $request->input('size', self::PAGE_SIZE);
        $agent_id = $request->input('agent_id');

        $query = ClientWithdraw::orderByDesc('id')->with('client');

        $range = $request->range;
        if (count($range) == 2 && strlen($range[0]) > 10 && strlen($range[1]) > 10) {
            $from_time = Carbon::parse($range[0]);
            $to_time = Carbon::parse($range[1]);
            $query->whereBetween('created_time', [$from_time, $to_time]);
            $request->page = 1;
        }
        $keyword = $request->input('keyword');
        if ($keyword) {
            $likeString = "%$keyword%";
            $client_ids = Client::orWhere('nick_name', 'like', $likeString)
                ->orWhere('real_name', 'like', $likeString)
                ->orWhere('cellphone', 'like', $likeString)
                ->pluck('id')->all();
            $query = $query->whereIn('cust_id', $client_ids);
            $request->page = 1;
        }
        //TODO::根据关系表只显示本级以下代理商

        $list = $query->paginate($page_size);
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