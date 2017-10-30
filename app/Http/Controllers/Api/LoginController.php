<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\WechatRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\WechatController;

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
            "password" => "required"
        ], [
            "username.required" => "手机号不能为空",
            "password.required" => "密码不能为空",
            "username.regex" => "手机号格式不合法",
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

    /**
     * 获取用户openid并进行相应操作
     * @param Request $request
     * @param Response $response
     * @return $this
     */
    public function loginFromOpenId(Request $request, Response $response)
    {
        $url = $request->get("callbackUrl");

        $wechat = WechatController::instance($request);
        $userInfo = $wechat->getOauthUserInfo();
        $openId = $userInfo["original"]["openid"];

        $user = User::where("openid", "like", "%$openId%")->where("is_login_forbidden", 0)->first();
        if ($user) {
            $ret = apiLogin($user->{CUSTOMER_USERNAME_FIELD}, $user->password);
            //TODO 假设回调url上没有其他参数
            $url = "http://dev.591wmj.com/3.html?access_token=" . $ret["access_token"] . "&callbackUrl=" . $url;
        } else {
            $url = "http://dev.591wmj.com/3.html?openid=" . $openId;
        }

        return $response->header("Location", $url);
    }


}