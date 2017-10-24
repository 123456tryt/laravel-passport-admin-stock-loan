<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\Agent;
use App\Http\Model\Employee;
use App\User;
use Carbon\Carbon;
use function foo\func;
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
        if (Auth::check() && !config('app.debug')) {
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


    public function list(Request $request)
    {

        $keyword = $request->keyword;
        $page_size = $request->input('size', self::PAGE_SIZE);


        $query = User::orderByDesc('created_at')->with('agent')->with('role');
        $range = $request->range;
        if (count($range) == 2 && strlen($range[0]) > 10 && strlen($range[1]) > 10) {
            $from_time = Carbon::parse($range[0]);
            $to_time = Carbon::parse($range[1]);
            $query->whereBetween('created_at', [$from_time, $to_time]);
        }
        if ($keyword) {
            $likeString = "%$keyword%";
            $agent_dis = Agent::where('agent_name', 'like', $likeString)
                ->pluck('id')->all();
            $meployee_ids = Employee::where('employee_name', 'like', $likeString)
                ->pluck('id')->all();
            $query->whereIn('agent_id', array_values($agent_dis))
                ->orWhereIn('employee_id', $meployee_ids)
                ->orWhere('phone', 'like', $likeString)
                ->orWhere('real_name', 'like', $likeString);
            $_GET['page'] = 1;
        }
        $list = $query->paginate($page_size);
        return self::jsonReturn($list);
    }
}