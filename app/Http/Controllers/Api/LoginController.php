<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    private $register = null;

    /**
     * 登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "username" => ["required", "regex:/^1[0-9]{10}$/"],
            "password" => "required|between:6,20"
        ], [
            "username.required" => "手机号不能为空",
            "password.required" => "密码不能为空",
            "username.regex" => "手机号格式不合法",
            "password.between" => "请输入正确的密码"
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        $username = $request->get("username");
        $password = $request->get("password");

        //检测是否被禁用
        $user = User::where(CUSTOMER_USERNAME_FIELD, $username)->first();
        if (!$user || $user->is_login_forbidden) {
            return parent::jsonReturn([], parent::CODE_FAIL, "用户账号或密码错误");
        }

        $ret = apiLogin($username, $password);
        return $ret ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "登录成功") :
            parent::jsonReturn([], parent::CODE_FAIL, "用户账号或密码错误");
    }

    /**
     * 登出
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        //注销token
        $jwtInfo = parsePassportAuthorization($request);
        if ($jwtInfo) {
            $jti = $jwtInfo["jti"];
            DB::table("oauth_access_tokens")->where("id", $jti)->delete();
            DB::table("oauth_refresh_tokens")->where("access_token_id", $jti)->delete();
        }

        return parent::jsonReturn([], parent::CODE_SUCCESS, "success");
    }
}