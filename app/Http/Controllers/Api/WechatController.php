<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\Agent;
use App\Http\Model\WexingConcern;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use EasyWeChat\Message\Text;
use EasyWeChat\Message\News;
use Illuminate\Support\Facades\DB;

class WechatController extends Controller
{
    private $app = null;
    private $agentId = 0;
    private $config = [
        'app_id' => 'wx5fb8a69f323c231a',
        'secret' => '38c5d386a5d80a4d0dc16c2fa8890a69',
        'token' => 'yingli',
    ];

    /**
     * 获取实例
     * @param $request
     * @return WechatController|bool
     */
    static public function instance($request)
    {
        $agentId = $request->get("agentId");
        $agent = \DB::table("a_agent")->leftJoin("a_agent_extra_info", "a_agent.id", "=", "a_agent_extra_info.id")
            ->where("a_agent.id", $agentId)->where("a_agent.is_independent", 1)->first();
        if (!$agent) return false;

        file_put_contents("../storage/2.txt", var_export([
            'app_id' => $agent->appid,
            'secret' => $agent->public_key,
            'token' => $agent->wechat_token,
            'agentId' => $agentId,
        ], true));
        $wechat = new self([
            'app_id' => $agent->appid,
            'secret' => $agent->public_key,
            'token' => $agent->wechat_token,
            'agentId' => $agentId,
        ]);
        return $wechat;
    }

    public function __construct($config = [])
    {
        $this->config = $config;
        $this->agentId = $config["agentId"];
        $options = [
            'debug' => false,
            'app_id' => $this->config["app_id"],
            'secret' => $this->config["secret"],
            'token' => $this->config["token"],
            'log' => [
                'level' => 'debug',
                'file' => '/tmp/easywechat.log',
            ],
            "oauth" => [
                'scopes' => ['snsapi_base'],
            ],
        ];

        $this->app = new Application($options);
    }

    /**
     * 微信服务端
     */
    public function index()
    {
        $server = $this->app->server;
        $this->createMenu();
        $server->setMessageHandler(function ($message) {
            switch ($message->MsgType) {
                case 'event':
                    switch ($message->Event) {
                        case "subscribe":
                            return $this->subscribe($message);
                            break;
                        case "unsubscribe":
                            return $this->unSubscribe($message);
                            break;
                        default:
                            break;
                    }
                    break;
                case 'text':
                    return $this->responseText($message);
                    break;
                case 'image':
                    break;
                case 'voice':
                    break;
                case 'video':
                    break;
                case 'location':
                    break;
                case 'link':
                    break;
                default:
                    break;
            }
        });

        $response = $server->serve();
        $response->send();
    }

    /**
     * 创建二维码
     * @param string $value
     * @return bool|string
     */
    public function makeQrCode($value = "")
    {
        $qrCode = $this->app->qrcode;
        $result = $qrCode->forever($value);
        $ticket = $result->ticket;
        $url = $qrCode->url($ticket);
        $content = file_get_contents($url);
        return $content;
    }

    /**
     * 创建目录
     */
    public function createMenu()
    {
        $menu = $this->app->menu;
        $url = $this->getOauthUrl("http://dev.591wmj.com/2.html");
        $button = [
            [
                "name" => "配资",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "我要配资",
                        "url" => $url
                    ],
                    [
                        "type" => "view",
                        "name" => "免息体验",
                        "url" => $url
                    ],
                ],
            ],
            [
                "name" => "福利",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "专属海报",
                        "url" => $url
                    ],
                    [
                        "type" => "view",
                        "name" => "有利可图",
                        "url" => $url
                    ],
                ],
            ],
            [
                "name" => "我的",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "我要交易",
                        "url" => $url
                    ],
                    [
                        "type" => "view",
                        "name" => "我的推广",
                        "url" => $url
                    ],
                    [
                        "type" => "view",
                        "name" => "股宝教程",
                        "url" => $url
                    ],
                ],
            ],
        ];
        $menu->add($button);
    }

    /**
     * 获取网页授权的用户信息
     * @return array
     */
    public function getOauthUserInfo()
    {
        return $this->app->oauth->user()->toArray();
    }

    /**
     * 订阅
     * @param $message
     * @return Text
     */
    private function subscribe($message)
    {
        if (isset($message["EventKey"])) {
            list($t, $data["rec_code"]) = explode("_", $message["EventKey"]);
        }
        $openId = $message->FromUserName;
        $data["open_id"] = $openId;
        $data["appid"] = $this->config["app_id"];

        $userService = $this->app->user;
        $userInfo = $userService->get($openId);
        $data["nick_name"] = $userInfo->nickname;
        $data["head_pic"] = $userInfo->headimgurl;

        $record = WexingConcern::where("open_id", $data["open_id"])->first();
        if ($record) {
            $record->update(array_merge($data, [
                "is_concern" => 1,
            ]));
        } else {
            WexingConcern::create($data);
        }
        return $this->getDefaultResponseText();
    }

    /**
     * 取消订阅
     * @param $message
     */
    private function unSubscribe($message)
    {
        $openId = $message->FromUserName;
        $record = WexingConcern::where("open_id", $openId)->first();
        if ($record) {
            $record->update([
                "is_concern" => 0,
                "cancel_time" => date("Y-m-d H:i:s")
            ]);
        }
    }

    /**
     * 回复客户的文字信息
     * @param $message
     * @return array|News|Text
     */
    private function responseText($message)
    {
        $msg = $message->Content;
        if ($this->agentId == 1) {
            switch ($msg) {
                case "1":
                    $mater = new News([
                        "title" => "（1）配资和入金出金流程",
                        "description" => "配资和入金出金流程",
                        "image" => "http://www.gubao668.com/upload/201709/01/201709011417203091.jpg",
                    ]);
                    $mater2 = new News([
                        "title" => "（2）配资和入金出金流程2",
                        "description" => "配资和入金出金流程2",
                        "image" => "http://www.gubao668.com/upload/201709/01/201709011417203091.jpg",
                    ]);
                    return [$mater, $mater2];
                case "2":
                    $mater = new News([
                        "title" => "配资和入金出金流程",
                        "description" => "配资和入金出金流程",
                        "image" => "http://www.gubao668.com/upload/201709/01/201709011417203091.jpg",
                    ]);
                    return $mater;
                default:
                    return $this->getDefaultResponseText();
            }
        }
    }

    private function getDefaultResponseText()
    {
        return $this->agentId == 1 ? new Text([
            "content" => "尊敬的客户：
 您可以通过回复以下内容获取相关信息哦~~~
 回复“1”：配资和入金出金流程
 回复“2”：股票下单流程
 股宝网客服1（gubao1991）
 股宝网客服2（gubao1993）
（注：gubao1996添加人数已上限）"
        ]) : "";
    }

    /**
     * 获取网页授权地址
     * @param $url
     * @return string
     */
    private function getOauthUrl($url)
    {
        $url = PC_SITE_URL . "v1/loginFromOpenId?callbackUrl=" . $url . "&agentId=" . $this->agentId;
        $response = $this->app->oauth->setRedirectUrl($url)->redirect();
        return $response->getTargetUrl();
    }
}