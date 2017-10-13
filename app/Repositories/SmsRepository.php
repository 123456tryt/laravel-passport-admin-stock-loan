<?php

namespace App\Repositories;

use App\Repositories\Sms\Feige;
use App\Http\Model\MsgCheck;

class SmsRepository extends Base
{
    const VERIFY_CODE_INVALID_MINUTES = 10;
    const VERIFY_CODE_EXPIRE_MINUTES = 1;

    //发送验证码
    public function sendVerify($phone, $agent = null, $remark = "")
    {
        $sendRecord = MsgCheck::where("cellphone", $phone)->orderBy("create_time", "desc")->first();
        if ($sendRecord && $sendRecord->create_time) {
            if (strtotime($sendRecord->create_time) + self::VERIFY_CODE_EXPIRE_MINUTES * 60 > time()) {
                $this->setErrorMsg("用户发送短信太频繁");
                return false;
            }
        }

        $agentId = null;
        $agentSmsConfig = [];
        if ($agent) {
            $agentId = $agent->id;
            $agentSmsConfig = [
                "account" => $agent->sms_account,
                "pwd" => $agent->sms_pwd,
                "captchaTemplateId" => $agent->sms_captcha_template_id,
                "signId" => $agent->sms_sign_id,
            ];
        }

        $sms = new Feige($agentSmsConfig);
        $code = $this->createCode();
        $ret = $sms->sendTemplate($phone, $code);
        if (!$ret) return false;

        $data = [
            "cellphone" => $phone,
            "check_code" => $code,
            "create_time" => date("Y-m-d H:i:s"),
            "invalid_time" => date("Y-m-d H:i:s",
                strtotime("+" . self::VERIFY_CODE_INVALID_MINUTES . " minutes")),
            "type_remark" => $remark,
            "agent_id" => $agentId,
        ];
        return MsgCheck::create($data);
    }

    //检测验证码
    public function checkVerify($phone, $code)
    {
        $sendRecord = MsgCheck::where("cellphone", $phone)->orderBy("id", "desc")->first();
        if (!$sendRecord || $sendRecord->check_code != $code ||
            strtotime($sendRecord->invalid_time) < time()) {
            return false;
        }

        return true;
    }

    //创建验证码
    private function createCode()
    {
        $str = "0123456789";
        $code = "";
        for ($i = 0; $i < 4; $i++) {
            $code .= $str{rand(0, strlen($str) - 1)};
        }
        return $code;
    }
}