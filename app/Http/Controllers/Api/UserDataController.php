<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserDataRepository;

class UserDataController extends Controller
{
    private $userData = null;

    public function __construct(UserDataRepository $userData)
    {
        $this->middleware("auth:api");

        $this->userData = $userData;
    }

    /**
     * 保存银行卡
     * @param Request $requests
     * TODO:绑定卡数量限制,字段限制,实名认证
     */
    public function storeBankCard(Request $request)
    {
        $this->validate($request, [
            "bank_card" => "required|between:16,19",
            "bank_name" => "required|max:125",
            "bank_reg_cellphone" => "required|numeric",
        ]);

        $ret = $this->userData->storeBankCard($request->user(), $request->all());
        return $ret ? response()->json([], REST_CREATE_SUCCESS) :
            response()->json(["error" => FAIL_TO_CREATE_POST, "message" => "添加失败"]);
    }

    /**
     * 修改绑定银行卡
     * @param Request $request
     */
    public function updateBankCard(Request $request)
    {
        $this->validate($request, [
            "bank_card" => "required|between:16,19",
            "bank_name" => "required|max:125",
            "bank_reg_cellphone" => "required|numeric",
        ]);

        $ret = $this->userData->updateBankCard($request->user(), $request->get("id"), $request->all());
        return $ret ? response()->json([], REST_UPDATE_SUCCESS) :
            response()->json(["error" => FAIL_TO_UPDATE_POST, "message" => "更新失败"]);
    }

    /**
     * 删除银行卡
     * @param Request $request
     */
    public function deleteBankCard(Request $request)
    {
        $ret = $this->userData->deleteBankCard($request->user(), $request->id);
        return $ret ? response()->json([], REST_DELETE_SUCCESS) :
            response()->json(["error" => FAIL_TO_DELETE_POST, "message" => "删除失败"]);
    }

    /**
     * 修改昵称
     * @param Request $request
     * TODO:昵称过滤
     */
    public function updateNickname(Request $request)
    {
        $this->validate($request, [
            "nick_name" => "required|between:1,20",
        ]);

        $ret = $this->userData->updateNickname($request->user(), $request->get("nick_name"));
        return $ret ? response()->json([], REST_UPDATE_SUCCESS) :
            response()->json(["error" => FAIL_TO_UPDATE_POST, "message" => "修改失败"]);
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
        ]);

        $ret = $this->userData->storeCetification($request->user(), $request->get("real_name"), $request->get("id_card"));
        return $ret ? response()->json([], REST_CREATE_SUCCESS) :
            response()->json(["error" => FAIL_TO_CREATE_POST, "message" => "设置失败"]);
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
            "withdraw_pw" => "required|between:6,20"
        ]);

        $ret = $this->userData->storeWithdrawPassword($request->user(), $request->get("withdraw_pw"));

        return $ret ? response()->json([], REST_CREATE_SUCCESS) :
            response()->json(["error" => FAIL_TO_CREATE_POST, "message" => "设置失败"]);
    }

    /**
     * 修改提款密码
     * @param Request $request
     * @return mixed
     */
    public function updateWithdrawPassword(Request $request)
    {
        $this->validate($request, [
            "old_withdraw_pw" => "required|between:6,20",
            "withdraw_pw" => "required|between:6,20",
        ]);

        $ret = $this->userData->updateWithdrawPassword($request->user(), $request->get("old_withdraw_pw"),
            $request->get("withdraw_pw"));
        return $ret ? response()->json([], REST_UPDATE_SUCCESS) :
            response()->json(["error" => FAIL_TO_UPDATE_POST, "message" => "修改失败"]);
    }

    /**
     * 手机绑定
     */

    /**
     * 登录密码
     */
}