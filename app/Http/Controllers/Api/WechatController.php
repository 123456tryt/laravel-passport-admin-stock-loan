<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;

class WechatController extends Controller
{
    private $app = null;

    public function __construct()
    {
        $options = [
            'debug' => true,
            'app_id' => 'wx5fb8a69f323c231a',
            'secret' => '38c5d386a5d80a4d0dc16c2fa8890a69',
            'token' => 'yingli',
            'log' => [
                'level' => 'debug',
                'file' => '/tmp/easywechat.log', // XXX: 绝对路径！！！！
            ],
        ];

        $this->app = new Application($options);

        if (request()->get("echostr")) {
            $response = $this->app->server->serve();
            $response->send();
        }

    }

    public function index()
    {

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

}