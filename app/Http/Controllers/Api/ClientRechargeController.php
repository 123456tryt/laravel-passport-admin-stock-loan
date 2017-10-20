<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\Agent;
use App\Http\Model\AgentProfitRateConfig;
use App\Http\Model\Client;
use App\Http\Model\ClientAgentEmployeeRelation;
use App\Http\Model\ClientFeeRate;
use App\Http\Model\ClientFLow;
use App\Http\Model\ClientRecharge;
use App\Http\Model\Employee;
use App\Http\Model\EmployeeProfitRateConfig;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class ClientRechargeController 客户充值记录
 * @package App\Http\Controllers\Api
 */
class ClientRechargeController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }

    /**
     * 充值记录列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $keyword = $request->keyword;
        $status = $request->status;

        $transfer_type = $request->transfer_type;

        $query = ClientRecharge::orderByDesc('updated_time');

        $range = $request->range;
        if (count($range) === 2) {
            $from_time = Carbon::parse($range[0]);
            $to_time = Carbon::parse($range[1]);
            $query->whereBetween('created_time', [$from_time, $to_time]);
        }
        if ($keyword) {
            $query->with(['client' => function ($subQuery) use ($keyword) {
                $subQuery->orWhere('nick_name', $keyword)->orWhere('real_name', $keyword)->orWhere('cellphone', $keyword);
            }]);
        } else {
            $query->with('client');
        }
        if ($status) {
            $query->where(compact('status'));
        }
        if ($transfer_type) {
            $query->where(compact('transfer_type'));
        }

        $list = $query->paginate(self::PAGE_SIZE);
        return self::jsonReturn($list);
    }


    public function clientAcountRecharge(Request $request)
    {
        $operator_id = Auth::user()->id;
        //TODO::需要事务
        //TODO::验证
        $cust_id = $request->cust_id;
        $amount_of_account = $request->amount_of_account;
        $remark = "『后台管理员充值』:转账{$request->money}元,手续费{$request->fee}元,充值金额{$request->amount_of_account}";
        $nowTime = date('Y-m-d H:i:s');
        $data = [
            'operator_id' => $operator_id,
            'cust_id' => $cust_id,
            'type' => 2,//后台充值
            'status' => 1,//成功
            'transfer_type' => $request->transfer_type,
            'amount_of_account' => $amount_of_account,//如果金额出错有人故意搞破坏
            'fee' => $request->fee,
            'arrival_time' => $nowTime,
            'remark' => $remark
        ];

        ClientRecharge::create($data);


        $last_money = round(ClientFLow::whereCustId($cust_id)->sum('amount_of_account'), 2);
        $account_left = $last_money + $amount_of_account;

        $foo = [
            'cust_id' => $cust_id,
            'operator_id' => $operator_id,
            'flow_type' => 11,//看字段注释
            'amount_of_account' => $amount_of_account,
            'account_left' => $account_left,
            'remark' => $remark,
            'occur_time' => $nowTime
        ];
        ClientFLow::create($foo);

        return self::jsonReturn([], self::CODE_SUCCESS, '后台给用户充值成功');

    }
}