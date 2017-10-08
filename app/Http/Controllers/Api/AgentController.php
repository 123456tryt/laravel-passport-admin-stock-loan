<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * Class AgentController 代理商
 * @package App\Http\Controllers\Api
 */
class AgentController extends Controller
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