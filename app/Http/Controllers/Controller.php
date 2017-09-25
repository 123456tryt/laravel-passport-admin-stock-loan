<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Cache;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $verifyPrefix = "verify_";
    protected $verifyCache = "redis";

    /**
     * 获取验证码
     * @param $id
     * @return mixed
     */
    protected function getVerify($id)
    {
        return Cache::store("redis")->get($this->verifyPrefix . $id);
    }

    /**
     * 设置图片验证码
     * @param $id
     * @return string 返回图片base64
     */
    protected function setVerify($id)
    {
        $builder = new CaptchaBuilder();
        $builder->build();

        Cache::store($this->verifyCache)->put($this->verifyPrefix . $id, $builder->getPhrase(), 60);

        return $builder->inline();
    }

    /**
     * 更新验证码
     * @param $id
     * @return strings 返回图片base64
     */
    protected function updateVerify($id)
    {
        return $this->setVerify($id);
    }

}
