<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function setVerify($id)
    {
        $data = parent::setVerify($id);
        return response()->json(["data" => $data]);
    }

    public function verify(Request $request)
    {
        $inputs = $request->only(["verifyId", "verify"]);
        $return = ["status" => 0, "msg" => ""];

        if (!$inputs["verifyId"] || !$inputs["verify"] ||
            parent::getVerify($inputs["verifyId"]) != $inputs["verify"]) {
            $return["msg"] = "验证码错误";
        } else {
            $return["status"] = 1;
            $return["msg"] = "验证成功";
        }

        return response()->json($return);
    }
}