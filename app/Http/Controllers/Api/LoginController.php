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
        $this->validate($request, [
            "username" => ["regex:/^1[0-9]{10}$/"],
            "password" => "between:6,20"
        ], [
            "username.regex" => "手机号格式不合法",
            "password.between" => "请输入正确的密码"
        ]);

        $username = $request->get("username");
        $password = $request->get("password");

        //检测是否被禁用
        $user = User::where(CUSTOMER_USERNAME_FIELD, $username)->first();
        if (!$user || $user->is_login_forbidden) {
            return Response()->json(["error" => "auth error", "message" => "用户账号或密码错误"]);
        }

        return apiLogin($username, $password);
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

        return response()->json([]);
    }
}