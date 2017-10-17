<?php

namespace App\Repositories\Sms;

class Feige
{
    private $defaultSmsConfig = [
        "account" => "18123953110",
        "pwd" => "1998c727970d4ab5792635f70",
        "captchaTemplateId" => "31047",
        "signId" => "33936",
        "templateSmsUrl" => "http://api.feige.ee/SmsService/Template"
    ];

    static private $requestHeaders = [
        "User-Agent" => "sms",
    ];

    public function __construct($config = null)
    {
        $this->defaultSmsConfig = array_merge($this->defaultSmsConfig,
            is_array($config) && isset($config["account"]) && $config["account"] ? $config : []);
    }

    public function sendTemplate($phone, $content)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request("post", $this->defaultSmsConfig["templateSmsUrl"],
            ["query" => [
                "Account" => $this->defaultSmsConfig["account"],
                "Pwd" => $this->defaultSmsConfig["pwd"],
                "Content" => $content,
                "Mobile" => $phone,
                "TemplateId" => $this->defaultSmsConfig["captchaTemplateId"],
                "signId" => $this->defaultSmsConfig["signId"],
            ]], static::$requestHeaders);
        $result = json_decode($response->getBody(), true);
        return $result && $result["Code"] === 0;
    }

    public function send($phone, $content)
    {

    }
}