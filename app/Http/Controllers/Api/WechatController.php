<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use App\Repositories\WechatRepository;

class WechatController extends Controller
{
    private $app = null;
    private $config = [
        'app_id' => 'wx5fb8a69f323c231a',
        'secret' => '38c5d386a5d80a4d0dc16c2fa8890a69',
        'token' => 'yingli',
    ];

    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
        $options = [
            'debug' => true,
            'app_id' => $this->config["app_id"],
            'secret' => $this->config["secret"],
            'token' => $this->config["token"],
            'log' => [
                'level' => 'debug',
                'file' => '/tmp/easywechat.log', // XXX: 绝对路径！！！！
            ],
            "oauth" => [
                'scopes' => ['snsapi_base'],
            ],
        ];

        $this->app = new Application($options);
    }

    public function index()
    {
        $server = $this->app->server;
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
                    return "hello world";
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

    public function makeQrCode($value = "")
    {
        $qrCode = $this->app->qrcode;
        $result = $qrCode->forever($value);
        $ticket = $result->ticket;
        $url = $qrCode->url($ticket);
        $content = file_get_contents($url);
        return $content;
    }

    public function createMenu()
    {
        $menu = $this->app->menu;
        $button = [
            [
                "name" => "配资",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "我要配资",
                        "url" => "http://dev.591wmj.com/2.html"
                    ],
                    [
                        "type" => "view",
                        "name" => "免息体验",
                        "url" => "http://dev.591wmj.com/2.html"
                    ],
                ],
            ],
            [
                "name" => "福利",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "专属海报",
                        "url" => "http://dev.591wmj.com/2.html"
                    ],
                    [
                        "type" => "view",
                        "name" => "有利可图",
                        "url" => "http://dev.591wmj.com/2.html"
                    ],
                ],
            ],
            [
                "name" => "我的",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "我要交易",
                        "url" => "http://dev.591wmj.com/2.html"
                    ],
                    [
                        "type" => "view",
                        "name" => "我的推广",
                        "url" => "http://dev.591wmj.com/2.html"
                    ],
                    [
                        "type" => "view",
                        "name" => "股宝教程",
                        "url" => "http://dev.591wmj.com/2.html"
                    ],
                ],
            ],
        ];
        $menu->add($button);
    }

    public function redirectOauthUrl($url)
    {
        return $this->app->oauth->setRedirectUrl($url)->redirect();
    }

    public function getOauthUserInfo()
    {
        return $this->app->oauth->user();
    }

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
        return "欢迎关注";
    }

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
}