<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserDataRepository;

/**
 * 用户资料
 * Class UserDataController
 * @package App\Http\Controllers\Api
 */
class UserDataController extends Controller
{
    private $userData = null;

    public function __construct(UserDataRepository $userData)
    {
        $this->middleware("auth:api");

        $this->userData = $userData;
    }

    /**
     * 获取用户信息
     */
    public function getUserInfo(Request $request)
    {
        $ret = $this->userData->getUserInfo($request->user());
        return $ret ? response()->json($ret) :
            response()->json(["error" => "query error", "message" => "查询失败"]);
    }

    /**
     * 银行卡列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bankCards(Request $request)
    {
        $list = $this->userData->bankCards($request->user());
        return response()->json(["data" => $list]);
    }

    /**
     * 获取银行卡详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBankCard(Request $request)
    {
        $ret = $this->userData->GetBankCard($request->user(), $request->get("id"));
        return $ret ? response()->json($ret) :
            response()->json(["error" => "add error", "message" => "查询失败"]);
    }

    /**
     * 保存银行卡
     * @param Request $requests
     * TODO:绑定卡数量限制,字段限制,实名认证
     */
    public function storeBankCard(Request $request)
    {
        $this->validate($request, [
            "bank_card" => "between:16,19",
            "bank_name" => "between:1,30",
            "bank_reg_cellphone" => "regex:/^1[0-9]{10}$/",
        ], [
            "bank_card.between" => "请填写正确的银行卡号",
            "bank_name.between" => "请填写银行名称",
            "bank_reg_cellphone.regex" => "请填写正确的银行预留手机号"
        ]);

        $ret = $this->userData->storeBankCard($request->user(), $request->all());
        return $ret ? response()->json([]) :
            response()->json(["error" => "add error", "message" => "添加失败"]);
    }

    /**
     * 修改绑定银行卡
     * @param Request $request
     */
    public function updateBankCard(Request $request)
    {
        $this->validate($request, [
            "bank_card" => "between:16,19",
            "bank_name" => "between:1,30",
            "bank_reg_cellphone" => "regex:/^1[0-9]{10}$/",
        ], [
            "bank_card.between" => "请填写正确的银行卡号",
            "bank_name.between" => "请填写银行名称",
            "bank_reg_cellphone.regex" => "请填写正确的银行预留手机号"
        ]);

        $ret = $this->userData->updateBankCard($request->user(), $request->get("id"), $request->all());
        return $ret ? response()->json([]) :
            response()->json(["error" => "update error", "message" => "更新失败"]);
    }

    /**
     * 删除银行卡
     * @param Request $request
     */
    public function deleteBankCard(Request $request)
    {
        $ret = $this->userData->deleteBankCard($request->user(), $request->id);
        return $ret ? response()->json([]) :
            response()->json(["error" => "delete error", "message" => "删除失败"]);
    }

    /**
     * 修改昵称
     * @param Request $request
     * TODO:昵称过滤
     */
    public function updateNickname(Request $request)
    {
        $this->validate($request, [
            "nick_name" => "between:1,20",
        ], [
            "nick_name.between" => "新昵称格式应该为1-20字符"
        ]);

        $ret = $this->userData->updateNickname($request->user(), $request->get("nick_name"));
        return $ret ? response()->json([]) :
            response()->json(["error" => "update error", "message" => "修改失败"]);
    }

    /**
     * 实名认证
     * @param Request $request
     * @return mixed
     */
    public function storeCetification(Request $request)
    {
        $this->validate($request, [
            "real_name" => "required|between:1,20",
            "id_card" => "required|between:15,18"
        ], [
            "real_name.between" => "请填写正确的真实姓名",
            "id_card.between" => "请填写正确格式的身份证号码"
        ]);

        $ret = $this->userData->storeCetification($request->user(), $request->get("real_name"), $request->get("id_card"));
        return $ret ? response()->json([]) :
            response()->json(["error" => "set error", "message" => "设置失败"]);
    }

    /**
     * 设置提款密码
     * @param Request $request
     * @return mixed
     * TODO 支付密码加密
     */
    public function storeWithdrawPassword(Request $request)
    {
        $this->validate($request, [
            "withdraw_pw" => "between:6,20"
        ], [
            "withdraw_pw.between" => "密码长度应为6-20位"
        ]);

        $ret = $this->userData->storeWithdrawPassword($request->user(), $request->get("withdraw_pw"));

        return $ret ? response()->json([]) :
            response()->json(["error" => "set error", "message" => "设置失败"]);
    }

    /**
     * 修改提款密码
     * @param Request $request
     * @return mixed
     */
    public function updateWithdrawPassword(Request $request)
    {
        $this->validate($request, [
            "old_withdraw_pw" => "between:6,20",
            "withdraw_pw" => "between:6,20",
        ], [
            "old_withdraw_pw.between" => "旧密码长度应为6-20位",
            "withdraw_pw.between" => "新密码长度应为6-20位",
        ]);

        $ret = $this->userData->updateWithdrawPassword($request->user(), $request->get("old_withdraw_pw"),
            $request->get("withdraw_pw"));
        return $ret ? response()->json([]) :
            response()->json(["error" => "update error", "message" => "修改失败"]);
    }

    /**
     * 手机绑定
     */

    /**
     * 登录密码
     */

    /**
     * 忘记提款密码
     */
}