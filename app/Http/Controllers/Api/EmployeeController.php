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
     * 创建代理商员工
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

    /*
     * 更新代理商员工信息
     */
    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            //'name' => 'required|unique:s_system_user',//验证登陆用户名唯一
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


        DB::beginTransaction();
        try {
            $agent_id = $request->input('agent_id');
            $employee_id = $request->input('employee_id');

            if (!Agent::find($agent_id)) {
                throw new \Exception('非法代理商ID');
                return;
            }
            $employeeData = $request->only(['agent_id', 'employee_name', 'phone', 'is_forbid', 'remark']);
            Employee::where('id', $request->input('employee_id'))->update($employeeData);


            $userData = $request->only(['phone', 'real_name' => $request->employee_name]);
            if (strlen($request->password) > 6) {
                $userData['password'] = bcrypt($request->input('password'));
            }

            User::whereId($request->user_id)->update($userData);


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

        return self::jsonReturn([], self::CODE_SUCCESS, '更新代理商员工成功');


    }


    /**
     * 代理商的信息信息 配置 附加信息
     * @return string
     */
    public function info(Request $request)
    {

        $employ = Employee::with('percentages', 'user')->find($request->employee_id);
        return self::jsonReturn($employ);
    }


    /**
     * 代理商员工表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $page = $request->input('page', 1);
        $staff_name = $request->input('employee_name');
        $agent_id = $request->agent_id;
        $cacke_key = "employee_list_search_{$staff_name}_agent_id_{$agent_id}_page_{$page}";

        $list = \Cache::remember($cacke_key, 1, function () use ($staff_name, $agent_id) {
            $query = Employee::orderByDesc('updated_time')->limit(self::PAGE_SIZE);
            if ($agent_id) {
                $query->where(compact('agent_id'));
            }
            if ($staff_name) {
                $data = $query->where('employee_name', 'like', "%$staff_name%")->paginate(self::PAGE_SIZE);
            } else {
                $data = $query->paginate(self::PAGE_SIZE);
            }
            return $data;
        });

        return self::jsonReturn($list);
    }


}