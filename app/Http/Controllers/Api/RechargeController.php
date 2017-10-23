<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\RechargeRepository;

class RechargeController extends Controller
{
    private $recharge = null;

    public function __construct(RechargeRepository $recharge)
    {
        $this->middleware('auth:api');

        $this->recharge = $recharge;
    }

    /**
     * 线下转账
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function offlineTransfer(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "amount_of_account" => "required|Numeric|min:100",
            "transfer_type" => "required|integer",
            "cust_remark" => "required|min:1"
        ], [
            "amount_of_account.required" => "转账金额不能为空",
            "amount_of_account.Numeric" => "转账金额不能少于100元",
            "amount_of_account.min" => "转账金额不能少于100元",
            "transfer_type.required" => "转账方式不能为空",
            "transfer_type.integer" => "转账方式错误",
            "cust_remark.required" => "备注不能为空",
            "cust_remark.min" => "备注不能为空",
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        $ret = $this->recharge->offlineTransfer($request->user(), $request->only(["amount_of_account",
            "transfer_type", "cust_remark"]));
        return $ret ? parent::jsonReturn([], parent::CODE_SUCCESS, "提交成功") :
            parent::jsonReturn([], parent::CODE_FAIL, "提交失败");
    }
}