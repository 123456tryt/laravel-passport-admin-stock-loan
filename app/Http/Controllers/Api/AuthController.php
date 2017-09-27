<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * Class AuthController 用户名注册登陆相关信息
 * @package App\Http\Controllers\Api
 */
class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }

    public function resetPassword()
    {

        return "hello world";
    }

    public function login()
    {

        return "hello world";
    }

    public function register()
    {

        return "hello world";
    }

}