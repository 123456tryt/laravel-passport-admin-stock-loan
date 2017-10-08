<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * Class ClientController 客户表
 * @package App\Http\Controllers\Api
 */
class ClientController extends Controller
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