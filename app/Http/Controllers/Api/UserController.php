<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }

    public function info()
    {
        $user = Auth::user();
        $user->agent;
        $navs = [

        ];
        return compact('user', 'navs');
    }


    /**
     * 注销登陆
     */
    public function logoutApi()
    {
        if (Auth::check()) {
            Auth::user()->AauthAcessToken()->delete();
        }
    }
}