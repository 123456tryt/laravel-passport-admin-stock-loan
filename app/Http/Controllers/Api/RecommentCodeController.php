<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\Agent;
use App\Http\Model\RecommendCode;
use App\Http\Model\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class RecommentCodeController  代理商和员工推荐码
 * @package App\Http\Controllers\Api
 */
class RecommentCodeController extends Controller
{


    public function __construct()
    {
        $this->middleware("auth:api")->except(['search']);
    }


    public function info(Request $request)
    {

        $user = Auth::user();
        //确定当前用户的角色
        if ($user->role_id <= Role::ROLE_ADMIN_AGENT) {
            //这个用户可以直接代表代理商
            //他的推荐码就是代理商推荐码

            $condition = [
                'user_type' => RecommendCode::TYPE_AGENT,
                'user_id' => $user->agent_id,
            ];
            $recommendCodeInstance = RecommendCode::where($condition)->first();
        } else {
            //这个用户是代理杀过的员工他的
            //推荐码是员工推荐码

            $condition = [
                'user_type' => RecommendCode::TYPE_EMPLOYEE,
                'user_id' => $user->id,
            ];
            $recommendCodeInstance = RecommendCode::where($condition)->first();
        }
        //如没有推荐码记录就为他们创建一条记录
        if (!$recommendCodeInstance) {
            //如果没有推荐码就给他创建爱推荐码
            //写入数据库
            $rec_code = rand(100000, 999999);
            //找到不重复的rec_code
            $flag = true;
            while ($flag) {
                $one = RecommendCode::where(compact('rec_code'))->first();
                if ($one) {
                    $flag = true;
                    $rec_code = rand(100000, 999999);
                } else {
                    $flag = false;
                }
            }
            $condition['rec_code'] = $rec_code;
            $recommendCodeInstance = RecommendCode::create($condition);
        }

        $web_host = null;
        $mobile_host = null;

        $agent = $user->agent;
        //递归查找网络域名
        $TOKEN = 'jjjerk';
        while (!$web_host) {
            if ($agent->is_independent > 0 && $agent->info) {
                $web_host = $agent->info->web_domain;
                $mobile_host = $agent->info->mobile_domain;
            } else {
                $agent = Agent::find($agent->parent_id);
                $web_host = $agent ? null : $TOKEN;
            }
        }
        if ($web_host == $TOKEN) {
            $web_host = 'https://www.gubao.com';
            $mobile_host = 'https://m.gubao.com';
        }
        $code = $recommendCodeInstance->rec_code;
        $data = [
            'code' => $code,
            'web_host' => "{$web_host}?code={$code}",
            'mobile_host' => "{$mobile_host}?code={$code}",
        ];


        return self::jsonReturn($data);
    }

}