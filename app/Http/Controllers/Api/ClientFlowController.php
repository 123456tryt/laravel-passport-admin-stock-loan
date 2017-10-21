<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\AgentCashFlow;
use App\Http\Model\Client;
use App\Http\Model\ClientFLow;
use App\Http\Model\ClientRecharge;
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
     * 客户资金
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $keyword = $request->keyword;

        $flow_type = $request->flow_type;
        $query = ClientFLow::orderByDesc('created_time');

        $range = $request->range;
        if (count($range) === 2) {
            $from_time = \Carbon::parse($range[0]);
            $to_time = \Carbon::parse($range[1]);
            $query->whereBetween('created_time', [$from_time, $to_time]);
        }
        if ($keyword) {
            $query->with(['client' => function ($subQuery) use ($keyword) {
                $subQuery->orWhere('nick_name', $keyword)->orWhere('real_name', $keyword)->orWhere('cellphone', $keyword);
            }]);
        } else {
            $query->with('client');
        }
        if ($flow_type) {
            $query->where(compact('flow_type'));
        }

        $list = $query->paginate(self::PAGE_SIZE);
        return self::jsonReturn($list);
    }


    //手动给客户调整余额
    public function clientAcountFlowAdjust(Request $request)
    {
        $user = Auth::user();
        //todo:需要判断用户的role id 来确定


        $validator = \Validator::make($request->all(), [
            'cust_id' => 'exists:u_customer,id',
        ], [
            'cust_id.exists' => "要修改的客户不存在",
        ]);
        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }


        $flow_type = ClientFLow::AdminRechargeMoney;

        $cust_id = $request->cust_id;
        $amount_of_account = $request->money - $request->fee;

        $last_money = round(ClientFLow::whereCustId($cust_id)->sum('amount_of_account'), 2);
        $account_left = $last_money + $amount_of_account;
        $operator_id = $user->id;
        $remark = "{$request->description}\r\n上期累计余额:{$last_money};修改金额:{$request->money}元;手续费:{$request->fee}元;有效金额:{$amount_of_account}元;本次累计:{$account_left}元\r\n";
        $remark .= "操作者:{$user->real_name},ID:{$user->id},手机号码:{$user->phone};登陆账号:{$user->name}\r\n";
        $remark .= "备注:{$request->remark}";
        $description = $request->description;

        ClientFLow::create(compact('cust_id', 'operator_id', 'flow_type', 'description', 'amount_of_account', 'account_left', 'remark'));
        return self::jsonReturn([], self::CODE_SUCCESS, '添加客户流水成功成功');
    }

}