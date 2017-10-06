<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Model\CustBankCard;

class UserDataController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }

    /**
     * 保存银行卡
     * @param Request $requests
     * TODO:绑定卡数量限制,字段限制,实名认证
     */
    public function storeBankCard(Request $request)
    {
        $user = $request->user();
        $this->validate($request, [
            "bank_card" => "required|between:16,19",
            "bank_name" => "required|max:125",
            "bank_reg_cellphone" => "required|numeric",
        ]);

        $data = $request->all();
        $data["cust_id"] = $user->id;
        $ret = CustBankCard::create($data);
        return $ret ? response()->json([], REST_CREATE_SUCCESS) :
            response()->json(["error" => FAIL_TO_CREATE_POST, "message" => "添加失败"]);
    }

    /**
     * 修改绑定银行卡
     * @param Request $request
     */
    public function updateBankCard(Request $request, $id)
    {
        $user = $request->user();
        $cardRecord = CustBankCard::find($id);
        if (!$cardRecord || $cardRecord->cust_id != $user->id) {
            return response()->json(["error" => POST_NOT_FOUND, "message" => "数据不存在"]);
        }

        $this->validate($request, [
            "bank_card" => "required|between:16,19",
            "bank_name" => "required|max:125",
            "bank_reg_cellphone" => "required|numeric",
        ]);

        $data = $request->all();
        $ret = $cardRecord->update($data);
        return $ret ? response()->json([], REST_UPDATE_SUCCESS) :
            response()->json(["error" => FAIL_TO_UPDATE_POST, "message" => "更新失败"]);
    }

    /**
     * 删除银行卡
     * @param Request $request
     * @param $id
     */
    public function deleteBankCard(Request $request, $id)
    {
        $user = $request->user();
        $cardRecord = CustBankCard::find($id);
        if (!$cardRecord || $cardRecord->cust_id != $user->id) {
            return response()->json(["error" => POST_NOT_FOUND, "message" => "数据不存在"]);
        }

        $ret = $cardRecord->delete();
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
        $user = $request->user();
        $this->validate($request, [
            "nick_name" => "required|between:1,20",
        ]);

        $data = $request->only(["nick_name"]);
        $ret = $user->update($data);
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
        $user = $request->user();

        //已绑定
        if ($user->real_name || $user->id_card) {
            return response()->json(["error" => POST_NOT_FOUND, "message" => "数据不存在"]);
        }

        $this->validate($request, [
            "real_name" => "required|between:1,20",
            "id_card" => "required|between:15,18"
        ]);

        $data = $request->only(["real_name", "id_card"]);
        $ret = $user->update($data);

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
        $user = $request->user();

        //已设置
        if ($user->withdraw_pw) {
            return response()->json(["error" => POST_NOT_FOUND, "message" => "数据不存在"]);
        }

        $this->validate($request, [
            "withdraw_pw" => "required|between:6,20"
        ]);

        $data = $request->only("withdraw_pw");
        $ret = $user->update($data);

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
        $user = $request->user();

        $this->validate($request, [
            "old_withdraw_pw" => "required|between:6,20",
            "withdraw_pw" => "required|between:6,20",
        ]);

        if ($user->withdraw_pw != $request->input("old_withdraw_pw")) {
            return response()->json(["error" => ORIGIN_PASSWORD_ERROR, "message" => "原密码错误"]);
        }

        $data = $request->only(["withdraw_pw"]);
        $ret = $user->update($data);

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