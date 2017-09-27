<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * Class EmployController 员工表
 * @package App\Http\Controllers\Api
 */
class EmployController extends Controller
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


}