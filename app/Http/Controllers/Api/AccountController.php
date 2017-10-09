<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\AccountRepository;

class AccountController extends Controller
{
    private $account = null;

    public function __construct(AccountRepository $account)
    {
        $this->middleware('auth:api');

        $this->account = $account;
    }

    /**
     * 提现
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdraw(Request $request)
    {
        $user = $request->user();

        $validator = \Validator::make($request->all(), [
            'cash_amount' => 'required|numeric|min:2',
            'bankcard_id' => 'required|numeric|min:1',
            'withdraw_pw' => 'required|between:6,20'
        ], [
            'cash_amount.required' => "提款金额不能为空",
            'bankcard_id.required' => "请选择提现银行卡",
            'withdraw_pw.required' => "提款密码不能为空",
            'cash_amount.numeric' => "请填写正确的提款金额",
            'cash_amount.min' => '提款金额不得少于2元',//根据需求设置
            'bankcard_id.min' => '请选择提现的银行卡',
            'withdraw_pw.between' => '提款密码格式不合法',
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        //TODO:是否有提款时间限制
        if ($user->withdraw_pw != $request->get('withdraw_pw')) {
            return parent::jsonReturn([], parent::CODE_FAIL, '提现密码错误');
        }

        if ($user->is_cash_forbidden) {
            return parent::jsonReturn([], parent::CODE_FAIL, '用户暂时无法提现');
        }

        if ($request->get('cash_amount') > $user->cust_capital_amount) {
            return parent::jsonReturn([], parent::CODE_FAIL, '提款金额不能大于用户余额');
        }

        $ret = $this->account->withDraw($user, $request->only(['cash_amount', 'bankcard_id',
            'withdraw_pw', 'cust_remark']));
        return $ret ? parent::jsonReturn([], parent::CODE_SUCCESS, "提交成功") :
            parent::jsonReturn([], parent::CODE_FAIL, '提交失败');
    }

    /**
     * 提现记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdrawRecord(Request $request)
    {
        $ret = $this->account->withdrawRecord($request->user());
        return $ret ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, "查询失败");
    }
}