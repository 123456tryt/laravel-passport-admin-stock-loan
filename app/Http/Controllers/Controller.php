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

    const CAPTCHA_PREFIX = "captcha_";
    const CAPTCHA_CACHE = "redis";

    /**
     * 获取验证码 重新获取验证码
     * @param $captchaId ,$captchaCode
     * @return bool
     */
    static function verifyCaptchaCode($captchaId, $captchaCode): bool
    {
        $cacheKey = self::CAPTCHA_PREFIX . $captchaId;
        $cachedCode = Cache::store(self::CAPTCHA_CACHE)->get($cacheKey);
        //Cache::forget($cacheKey);
        return $cachedCode == $captchaCode;
    }

    /**
     * 设置图片验证码
     * @param $captchaId
     * @return string 返回图片base64 string
     */
    static function generateCaptchaImage($captchaId): string
    {
        $builder = new CaptchaBuilder();
        $builder->build();
        Cache::store(self::CAPTCHA_CACHE)->put(self::CAPTCHA_PREFIX . $captchaId, $builder->getPhrase(), 10);
        return $builder->inline();
    }


}
