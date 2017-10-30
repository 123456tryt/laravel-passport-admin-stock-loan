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
        $query = ClientWithdraw::orderByDesc('id')->with('client', 'bankcard');

        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        if (strlen($fromDate) > 9) {
            $fromDate = Carbon::parse($fromDate);
            $query->where('created_time', '>', $fromDate);
        }
        if (strlen($toDate) > 9) {
            $query->where('created_time', '<', $toDate);
        }

        $cash_status = intval($request->cash_status);
        if ($cash_status) {
            $query->where(compact('cash_status'));
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


    public function info(Request $request)
    {
        $withdrawInfo = ClientWithdraw::find($request->id);
        return self::jsonReturn($withdrawInfo);
    }

}