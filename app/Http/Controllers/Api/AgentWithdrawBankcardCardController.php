<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\AgentInfo;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Class AgentWithdrawBankcardCardController 代理商提现银行卡管理
 * @package App\Http\Controllers\Api
 */
class AgentWithdrawBankcardCardController extends Controller
{


    const MsgCodePrefix = 'jerk_twat_douchebag.';

    public function __construct()
    {
        $this->middleware("auth:api");
    }

    public function sendMsmCode()
    {
        $phone = Auth::user()->agent->phone;
        Cache::remember(self::MsgCodePrefix . $phone, 10, function () use ($phone) {
            $defaultSmsConfig = AgentInfo::find(1);//使用谷宝网发送短息
            $code = rand(1000, 9999);
            //发送验证码
            $client = new Client();
            $response = $client->request("post", 'http://api.feige.ee/SmsService/Template',
                [
                    "form_params" => [
                        "Account" => $defaultSmsConfig->sms_account,
                        "Pwd" => $defaultSmsConfig->sms_pwd,
                        "Content" => $code,
                        "Mobile" => $phone,
                        "TemplateId" => 31047,//飞哥管理后台查询到的ID
                        "signId" => $defaultSmsConfig->sms_sign_id,
                    ]
                ]
            );
            $result = json_decode($response->getBody(), true);
            if ($result && $result["Code"] != 0) {
                Log::info('发送短信验证码失败: ' . $phone);
            }
            return $code;
        });
        return self::jsonReturn([], 1, '验证码发送成功!');
    }


    public function info()
    {
        $info = Auth::user()->agent;
        return self::jsonReturn($info);
    }


    public function update(Request $request)
    {
        $phone = $request->phone;
        $key = self::MsgCodePrefix . $phone;
        $code = Cache::get($key);

        if ($code != $request->code) {
            return self::jsonReturn([], self::CODE_FAIL, '验证码错误');
        }

        $instance = Auth::user()->agent;
        $flag = $instance->update($request->only('bank_name', 'bank_account_name', 'bank_account', 'bank_branch'));
        Cache::forget($key);
        return self::jsonReturn([], $flag);
    }

}