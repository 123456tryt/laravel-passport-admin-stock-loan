<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
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

    /**
     *
     * 角色扮演发放token
     * @param Request $request
     */
    public function rolePlayIssueToken(Request $request)
    {
        //todo::权限管理

        $agent_id = $request->agent_id;
        $employee_id = $request->employee_id;

        if ($employee_id) {
            //代理商员工
            $user = User::where(compact('employee_id'))->first();
        } else {
            //代理商扮演
            $user = User::WhereNull('employee_id')->where(compact('agent_id'))->first();
        }
        try {
            $token = $user->createToken('role_play');
            return self::jsonReturn($token);
        } catch (\Exception $e) {
            return self::jsonReturn([], 0, $e->getMessage());
        }
    }
}