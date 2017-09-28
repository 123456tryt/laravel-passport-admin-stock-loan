<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Model\CustBankCard;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }

    /**
     * 保存银行卡
     * @param Request $requests
     * TODO:绑定卡数量限制,字段限制
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
}