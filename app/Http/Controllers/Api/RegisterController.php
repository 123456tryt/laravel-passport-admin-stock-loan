<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\RegisterRepository;

class RegisterController extends Controller
{
    private $register = null;

    public function __construct(RegisterRepository $register)
    {
        $this->register = $register;
    }

    /**
     * 注册
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            "cellphone" => ["regex:/^1[0-9]{10}$/"],
            "nick_name" => "between:1,20",
            "password" => "between:6,20",
        ], [
            "cellphone.regex" => "请填写正确的手机号码",
            "nick_name.between" => "昵称格式应该为1-20字符",
            "password.between" => "密码长度应为6-20位"
        ]);

        $this->validate($request, [
            "cellphone" => "unique:u_customer,cellphone",
        ], [
            "cellphone.unique" => "手机号已被注册"
        ]);

        $data = $request->only(["cellphone", "nick_name", "password", "recCode"]);
        $data = array_merge($data, [
            "reg_source" => 0,
            "reg_ip" => $request->ip(),
        ]);
        $ipInfo = getIpInfo($data["reg_ip"]);
        if ($ipInfo) {
            $data["ip_location"] = $ipInfo["country"] . $ipInfo["region"] . $ipInfo["city"] . $ipInfo["isp"];
        }

        $ret = $this->register->register($data);
        if (!$ret) response()->json(["error" => "register error", "message" => "注册失败"]);

        //调用登录
        return apiLogin($data["cellphone"], $data["password"]);
    }

}