<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\Agent;
use App\Http\Model\AgentProfitRateConfig;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
/**
 * Class AgentController 代理商
 * @package App\Http\Controllers\Api
 */
class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api")->except(['search']);
    }

    public function createAgent(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'bank_account' => 'required|unique:a_agent',
            'agent_name' => 'required|unique:a_agent',
            'owner_name' => 'required|unique:a_agent',
            'phone' => 'required|unique:a_agent',
            'phone' => 'required|unique:s_system_user',
            'name' => 'required|unique:s_system_user',//验证登陆用户名唯一
        ], [
            'agent_name.unique' => "代理商名称不能重复",
            'bank_account.unique' => "提现银行卡号重复",
            'phone.unique' => "联系人手机号码已注册",
            'name.unique' => "登陆用户名以重复",
            'owner_name.unique' => "代理商联系人姓名重复",

        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }


        $msg = '';
        DB::beginTransaction();
        try {


            //$user = Auth::user();
            //确定确定权限
            $parent_id = $request->input('parent_id');
            $parent_agent = Agent::find($parent_id);
            if ($parent_agent) {
                $agent_level = $parent_agent->agent_level + 1;
            } else {
                $agent_level = 1;
            }

            $data = $request->except(['password', 'confirm_password', 'login_user_name', 'day_percentage', 'month_percentage', 'commission_percentage', 'name']);
            $data['agent_level'] = $agent_level;
            //创建代理商
            $instance = Agent::create($data);
            //创建登陆用户
            $agent_id = $instance->id;


            //创建配置
            //天配
            $rateWhere = [
                'agent_id' => $agent_id,
                'employee_id' => 0,
                'type' => AgentProfitRateConfig::TypeDay,
            ];
            $percentage = $request->input('day_percentage');
            AgentProfitRateConfig::updateOrCreate($rateWhere, compact('percentage'));
            //月配
            $rateWhere = [
                'agent_id' => $agent_id,
                'employee_id' => 0,
                'type' => AgentProfitRateConfig::TypeMonth,
            ];
            $percentage = $request->input('month_percentage');
            AgentProfitRateConfig::updateOrCreate($rateWhere, compact('percentage'));
            //佣金oen
            $rateWhere = [
                'agent_id' => $agent_id,
                'employee_id' => 0,
                'type' => AgentProfitRateConfig::TypeCommissionOne
            ];
            $percentage = $request->input('commission_percentage');
            AgentProfitRateConfig::updateOrCreate($rateWhere, compact('percentage'));

            //创建登陆账号
            $hashedPassword = bcrypt($request->input('password'));
            $phone = $request->input('phone');
            $name = $request->input('name');
            $userWhere = compact('name', 'agent_id');
            $userData = ['password' => $hashedPassword, 'phone' => $phone, 'role_id' => 1, 'real_name' => $request->input('owner_name')];

            User::updateOrCreate($userWhere, $userData);


            DB::commit();
        } catch (\Exception $e) {
            $msg .= $e->getMessage();
            DB::rollBack();
            return self::jsonReturn($instance, self::CODE_FAIL, $msg);
        }

        return self::jsonReturn($instance, self::CODE_SUCCESS, '代理商创建成功');


    }

    public function search(Request $request)
    {
        $agent_name = $request->input('agent_name');
        $cacke_key = 'agent_search_list_' . $agent_name;

        $list = \Cache::remember($cacke_key, 1, function () use ($agent_name) {
            $query = Agent::where('is_locked', '!=', 1)->orderByDesc('updated_time')->limit(self::PAGE_SIZE);
            if ($agent_name) {
                $data = $query->where('agent_name', 'like', "%$agent_name%")->get();
            } else {
                //没有搜索条件就显示一级代理商
                //$data = $query->whereAgentLevel(1)->get();
                $data = $query->whereAgentLevel(1)->get();

            }

            return $data;
        });

        return self::jsonReturn($list);
    }

    /**
     * 代理商的信息信息 配置 附加信息
     * @return string
     */
    public function info(Request $request)
    {
        //代理商基本信息
        $basic = Agent::find($request->id);
        //代理商配置信息

        //代理商附加信息

        return self::jsonReturn(compact('basic'));
    }

    /**
     * 代理商的信息信息 配置 附加信息  编辑
     * @return string
     */
    public function update(Request $request)
    {

        return $request->all();
    }

    /**
     * 代理商列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $page = $request->input('page', 1);
        $name = $request->input('name');

        $cacke_key = "agent_list__{$page}_search_{$name}";

        $list = \Cache::remember($cacke_key, 1, function () use ($name) {
            $query = Agent::where('is_locked', '!=', 1)->orderByDesc('updated_time')->limit(self::PAGE_SIZE);
            if ($name) {
                $data = $query->where('name', 'like', "%$name%")->paginate(self::PAGE_SIZE);
            } else {
                $data = $query->paginate(self::PAGE_SIZE);
            }
            return $data;
        });

        return self::jsonReturn($list);
    }


}