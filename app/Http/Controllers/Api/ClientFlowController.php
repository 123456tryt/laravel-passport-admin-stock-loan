<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\AgentCashFlow;
use App\Http\Model\Client;
use App\Http\Model\ClientFLow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class ClientFlowController 客户账户流水
 * @package App\Http\Controllers\Api
 */
class ClientFlowController extends Controller
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

        return self::jsonReturn([]);
    }


    public function add(Request $request)
    {
        $user = Auth::user();
        //todo:需要判断用户的role id 来确定

        $flow_type = 99999;
        if (str_contains($request->selectedType, '充值')) {
            $flow_type = ClientFLow::AdminIncreaseMoney;
        }
        if (str_contains($request->selectedType, '扣减')) {
            $flow_type = ClientFLow::AdminDecreaseMoney;
        }
        $cust_id = $request->cust_id;
        $amount_of_account = $request->money - $request->fee;

        $last_money = round(ClientFLow::whereCustId($cust_id)->sum('amount_of_account'), 2);
        $account_left = $last_money + $amount_of_account;
        $operator_id = $user->id;
        $remark = "{$request->selectedType}\r\n上期累计余额:{$last_money};修改金额:{$request->money}元;手续费:{$request->fee}元;有效金额:{$amount_of_account}元;本次累计:{$account_left}元\r\n";
        $remark .= "操作者:{$user->real_name},ID:{$user->id},手机号码:{$user->phone};登陆账号:{$user->name}\r\n";
        $remark .= "备注:{$request->remark}";
        $description = $request->selectedType;
        ClientFLow::create(compact('cust_id', 'operator_id', 'flow_type', 'description', 'amount_of_account', 'account_left', 'remark'));
        return self::jsonReturn([], self::CODE_SUCCESS, '添加客户流水成功成功');
    }

}