<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\RegisterRepository;

/**
 * 注册
 * Class RegisterController
 * @package App\Http\Controllers\Api
 */
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
        $validator = \Validator::make($request->all(), [
            "cellphone" => ["required", "regex:/^1[0-9]{10}$/", "unique:u_customer,cellphone"],
            "nick_name" => "required|between:1,20",
            "password" => "required|between:6,20",
        ], [
            "cellphone.required" => "手机号码不能为空",
            "nick_name.required" => "昵称不能为空",
            "password.required" => "密码不能为空",
            "cellphone.regex" => "请填写正确的手机号码",
            "cellphone.unique" => "手机号码已经被注册",
            "nick_name.between" => "昵称格式应该为1-20字符",
            "password.between" => "密码长度应为6-20位"
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

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
        return $ret ? parent::jsonReturn([], parent::CODE_SUCCESS, "注册成功") :
            parent::jsonReturn([], parent::CODE_FAIL, "注册失败");
    }

}