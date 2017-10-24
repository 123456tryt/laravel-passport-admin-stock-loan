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
use Illuminate\Support\Facades\Auth;
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
            'name' => 'required|unique:a_agent_emp',//验证登陆用户名唯一
            'email' => 'required|unique:a_agent_emp',//验证登陆用户名唯一
            'phone' => 'required|unique:a_agent_emp',
            'employee_name' => 'required',
            'is_forbid' => [
                'required',
                Rule::in(['0', '1']),
            ],
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
            $agent_id = Auth::user()->agent_id;

            $employeeData = $request->only('employee_name', 'phone', 'is_forbid', 'remark', 'name', 'role_id', 'email');
            $employeeData['password'] = bcrypt($request->input('password'));
            $employeeData['agent_id'] = $agent_id;
            $employ = Employee::firstOrCreate($employeeData);

            //创建配置
            $rateWhere = [
                'employee_id' => $employ->id,
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
            'id' => 'required',
            'is_forbid' => [
                'required',
                Rule::in(['0', '1']),
            ],
        ], [
            'name.unique' => "登陆用户名不能重复",
            'email.unique' => "登陆用户名不能重复",
            'employee_name' => '员工真实姓名不能为空',
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }


        DB::beginTransaction();
        try {

            $employeeData = $request->only('employee_name', 'phone', 'is_forbid', 'remark', 'role_id', 'email');

            if (strlen($request->password) > 6) {
                $employeeData['password'] = bcrypt($request->input('password'));
            }
            Employee::where($request->only('id'))->update($employeeData);



            //创建配置
            $rateWhere = [
                'employee_id' => $request->id,
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
        $employ = Employee::with('percentages')->find($request->employee_id);
        return self::jsonReturn($employ);
    }


    /**
     * 代理商员工表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $page_size = $request->input('size', self::PAGE_SIZE);

        $staff_name = $request->input('keyword');
        $agent_id = $request->agent_id;

        $query = Employee::orderByDesc('updated_time')->with('agent', 'role');
        if ($agent_id) {
            $query->where(compact('agent_id'));
        }
        if ($staff_name) {
            $_GET['page'] = 1;
            $query->where('employee_name', 'like', "%$staff_name%")->orWhere('phone', 'like', "%$staff_name%");
        }
        $list = $query->paginate($page_size);


        return self::jsonReturn($list);
    }


}