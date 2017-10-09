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

        $this->validate($request, [
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

        //TODO:是否有提款时间限制
        if ($user->withdraw_pw != $request->get('withdraw_pw')) {
            return response()->json(['error' => 'password error', 'message' => '提现密码错误']);
        }

        if ($user->is_cash_forbidden) {
            return response()->json(['error' => 'forbidden', 'message' => '用户暂时无法提现']);
        }

        if ($request->get('cash_amount') > $user->cust_capital_amount) {
            return response()->json(['error' => 'Insufficient balance', 'message' => '提款金额不能大于用户余额']);
        }

        $ret = $this->account->withDraw($user, $request->only(['cash_amount', 'bankcard_id',
            'withdraw_pw', 'cust_remark']));
        return $ret ? response()->json([]) : response()->json(['error' => 'set error', 'message' => '提交失败']);
    }

    /**
     * 提现记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdrawRecord(Request $request)
    {
        $ret = $this->account->withdrawRecord($request->user());
        return response()->json($ret);
    }
}