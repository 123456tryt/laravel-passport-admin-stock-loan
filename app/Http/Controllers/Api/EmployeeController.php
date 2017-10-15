<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\Agent;
use App\Http\Model\AgentInfo;
use App\Http\Model\AgentProfitRateConfig;
use App\Http\Model\Employee;
use App\Http\Model\EmployeeProfitRateConfig;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Mockery\Exception;

/**
 * Class EmployeeController 代理商员工控制器
 * @package App\Http\Controllers\Api
 */
class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api")->except(['search']);
    }

    /*
     * 创建代理商
     */
    public function create(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|unique:s_system_user',//验证登陆用户名唯一
            'employee_name' => 'required',
            'agent_id' => 'required',
            'is_forbid' => [
                'required',
                Rule::in(['0', '1']),
            ],
            'phone' => 'required|unique:a_agent_emp',
            'phone' => 'required|unique:s_system_user',
        ], [
            'phone.unique' => "联系人手机号码已注册",
            'name.unique' => "登陆用户名不能重复",
            'employee_name' => '员工真实姓名不能为空',
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }


        $msg = '';
        DB::beginTransaction();
        try {
            $agent_id = $request->input('agent_id');
            if (!Agent::find($agent_id)) {
                throw new \Exception('非法代理商ID');
                return;
            }
            $employeeData = $request->only(['agent_id', 'employee_name', 'phone', 'is_forbid', 'remark']);
            $employ = Employee::firstOrCreate($employeeData);
            $employee_id = $employ->id;


            $userData = $request->only(['phone', 'name', 'agent_id']);
            $userData['real_name'] = $request->input('employee_name');
            $userData['employee_id'] = $employee_id;
            $userData['role_id'] = 2;
            $password = bcrypt($request->input('password'));

            User::firstOrCreate($userData, compact('password'));




            //创建配置
            $rateWhere = [
                'agent_id' => $agent_id,
                'employee_id' => $employee_id,
            ];
            //天配

            $rateWhere['type'] = EmployeeProfitRateConfig::TypeDay;
            $percentage = $request->input('day_percentage');
            EmployeeProfitRateConfig::updateOrCreate($rateWhere, compact('percentage'));
            //月配
            $rateWhere['type'] = EmployeeProfitRateConfig::TypeMonth;
            $percentage = $request->input('month_percentage');
            EmployeeProfitRateConfig::updateOrCreate($rateWhere, compact('percentage'));
            //佣金oen
            $rateWhere['type'] = EmployeeProfitRateConfig::TypeCommissionOne;
            $percentage = $request->input('commission_percentage');
            EmployeeProfitRateConfig::updateOrCreate($rateWhere, compact('percentage'));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return self::jsonReturn([], self::CODE_FAIL, $e->getMessage());
        }

        return self::jsonReturn([], self::CODE_SUCCESS, '创建代理商员工成功');


    }

    /**
     * 下拉列表搜索代理商
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
        $agent_id = $request->input('id');
        $basic = Agent::find($agent_id);
        //代理商配置信息s
        $employee_id = 0;
        $configs = AgentProfitRateConfig::where(compact('agent_id', 'employee_id'))->get();

        //代理商附加信息
        $info = AgentInfo::firstOrNew(['id' => $agent_id]);
        //代理商管理员
        $user = User::where(['agent_id' => $agent_id, 'role_id' => User::RoleAdmin])->first();

        $parent = Agent::find($basic->parent_id);

        return self::jsonReturn(compact('basic', 'info', 'configs', 'user', 'parent'));
    }


    /**
     * 代理商员工表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $page = $request->input('page', 1);
        $staff_name = $request->input('staff_name');
        $agent_id = $request->input('agent_id');
        $cacke_key = "employee_list_search_{$staff_name}_agent_id_{$agent_id}_page_{$page}";

        $list = \Cache::remember($cacke_key, 1, function () use ($staff_name, $agent_id) {
            $query = Employee::where(compact('agent_id'))->orderByDesc('updated_time')->limit(self::PAGE_SIZE);
            if ($staff_name) {
                $data = $query->where('employee_name', 'like', "%$staff_name%")->paginate(self::PAGE_SIZE);
            } else {
                $data = $query->paginate(self::PAGE_SIZE);
            }
            return $data;
        });

        return self::jsonReturn($list);
    }

    /**
     * 修改代理商的管理员密码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeAgentAdminUserPassword(Request $request)
    {
        $password = $request->input('password');
        $confirm_password = $request->input('confirm_password');
        $agent_id = $request->input('agent_id');
        $role_id = $request->input('role_id');
        $id = $request->input('id');


        if ($password != $confirm_password) {
            return self::jsonReturn([], self::CODE_FAIL, '两次输入密码不一样');
        }

        try {
            $pp = bcrypt($password);
            User::where(compact('id', 'agent_id', 'role_id'))->update(['password' => $pp]);
            return self::jsonReturn([], self::CODE_SUCCESS, '修改密码成功');

        } catch (\Exception $e) {
            $message = $e->getMessage();
            return self::jsonReturn([], self::CODE_FAIL, $message);
        }


    }

    /**
     * 修改a_agent信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAgentBasic(Request $request)
    {
//        $validator = \Validator::make($request->all(), [
//            'bank_account' => 'required|unique:a_agent',
//            'agent_name' => 'required|unique:a_agent',
//            'agent_number' => 'required|unique:a_agent',
//            'owner_name' => 'required|unique:a_agent',
//            'phone' => 'required|unique:a_agent',
//
//        ], [
//            'agent_number.unique' => "代理商编号不能重复",
//            'agent_name.unique' => "代理商名称不能重复",
//            'bank_account.unique' => "提现银行卡号重复",
//            'phone.unique' => "联系人手机号码已注册",
//            'owner_name.unique' => "代理商联系人姓名重复",
//
//        ]);
//        if ($validator->fails()) {
//            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
//        }

        try {
            //todo::修改归属关系表
            $agent = Agent::find($request->id)->fill($request->except('id'));
            $code = $agent->save();
            return self::jsonReturn($agent, $code, '修改代理商基本信息成功');

        } catch (\Exception $eee) {
            return parent::jsonReturn([], parent::CODE_FAIL, $eee->getMessage());

        }
    }

    /**
     * 修改a_agent_extra_info 表信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAgentInfo(Request $request)
    {
        try {
            $agentInfo = AgentInfo::updateOrInsert($request->only('id'), $request->all());
            return self::jsonReturn($agentInfo, self::CODE_SUCCESS, '修改代理商附加信息信息成功');

        } catch (\Exception $eee) {
            return parent::jsonReturn([], parent::CODE_FAIL, $eee->getMessage());

        }
    }

    /**
     * 修改 a_agent_percentage_setting 修改代理商分成表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAgentPercentage(Request $request)
    {


        try {
            //todo::修改归属关系表
            $agent_id = $request->input('agent_id');

            $id = $request->input('day_id');
            $type = 0;
            $percentage = $request->input('day_percentage');
            AgentProfitRateConfig::updateOrInsert(compact('agent_id', 'id', 'type'), compact('percentage'));

            $id = $request->input('month_id');
            $type = 1;
            $percentage = $request->input('day_percentage');
            AgentProfitRateConfig::updateOrInsert(compact('agent_id', 'id', 'type'), compact('percentage'));

            $id = $request->input('commission_id');
            $type = 2;
            $percentage = $request->input('commission_percentage');
            AgentProfitRateConfig::updateOrInsert(compact('agent_id', 'id', 'type'), compact('percentage'));

            return self::jsonReturn([], self::CODE_SUCCESS, '修改代理商分成配置成功');

        } catch (\Exception $eee) {
            return parent::jsonReturn([], parent::CODE_FAIL, $eee->getMessage());

        }
    }

}