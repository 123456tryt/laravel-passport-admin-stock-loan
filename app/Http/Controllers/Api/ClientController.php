<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\Agent;
use App\Http\Model\AgentProfitRateConfig;
use App\Http\Model\Client;
use App\Http\Model\ClientAgentEmployeeRelation;
use App\Http\Model\ClientFeeRate;
use App\Http\Model\Employee;
use App\Http\Model\EmployeeProfitRateConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ClientController 客户表
 * @package App\Http\Controllers\Api
 */
class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }

    /**
     * 客户列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $page = $request->input('page', 1);
        $keyword = $request->input('keyword');
        $agent_id = $request->input('agent_id');
        $cacke_key = "client_search={$keyword}_agentID_{$agent_id}_page_{$page}";

        $list = \Cache::remember($cacke_key, 1, function () use ($keyword, $agent_id) {
            $query = Client::orderByDesc('updated_time');
            if ($keyword) {
                $query = $query->orWhere('nick_name', 'like', "%$keyword%")
                    ->orWhere('cellphone', 'like', "%$keyword%")
                    ->orWhere('id', "%$keyword%")
                    ->orWhere('real_name', 'like', "%$keyword%");
            }
            $data = $query->paginate(self::PAGE_SIZE);
            //TODO::根据关系表只显示本级以下代理商
            return $data;
        });
        return self::jsonReturn($list);
    }


    public function update(Request $request)
    {
        $client = Client::find($request->id)
            ->fill($request->only('is_login_forbidden', 'is_cash_forbidden', 'is_charge_forbidden', 'is_stock_finance_forbidden'));
        $client->save();
        return self::jsonReturn($client, self::CODE_SUCCESS, '修改客户成功');
    }


    public function changeClientAgentEmployeeRelations(Request $request)
    {
        $user = \Auth::user();
        //TODO::验证客户是否有权限修改归属关系

        $client_relation_id = $request->input('id');
        $client_id = $request->input('client_id');
        $direct_agent_id = $request->input('agent_Id');
        $direct_employee_id = $request->input('employee_id', 0);

        //a.检验代理商和员工关系是否合法
        $employee = null;
        if ($direct_employee_id) {
            $employee = Employee::where(['id' => $direct_agent_id, 'agent_id' => $direct_agent_id, 'is_forbid' => 0])->first();
        }
        if (!$employee) {
            return parent::jsonReturn([], parent::CODE_FAIL, '员工不属于这个代理商或员工已被禁用');
        }
        $ghost_client_id = 1;

        $clientRelation = ClientAgentEmployeeRelation::where(['id' => $client_relation_id, 'cust_id' => $client_id])->first();
        //清空之前代理商的关系
        $clientRelation->agent1 = null;
        $clientRelation->agent2 = null;
        $clientRelation->agent3 = null;
        $clientRelation->agent4 = null;
        $clientRelation->agent5 = null;

        //递归设置代理商的层级
        //这一级代理商不存在就设置为0
        $agent_id = $direct_agent_id;
        while ($agent_id) {
            $someAgent = Agent::find($direct_agent_id);
            $key = "agent{$someAgent->agent_level}";
            $clientRelation->$key = $someAgent->id;
            $agent_id = $someAgent->parent_id;
        }
        //填充直属代理商
        $clientRelation->direct_cust_id = $direct_agent_id;

        if ($clientRelation->cust1) {
            $clientRelation->cust1 = $ghost_client_id;
        }

        if ($clientRelation->cust2) {
            $clientRelation->cust2 = $ghost_client_id;
        }

        if ($clientRelation->cust3) {
            $clientRelation->cust3 = $ghost_client_id;
        }

        //填充员工直属代理商
        if ($employee) {
            $clientRelation->belong_to_agent = $employee->agent_id;
            $clientRelation->direct_emp_id = $employee->id;
        } else {
            $clientRelation->belong_to_agent = null;
            $clientRelation->direct_emp_id = null;
        }
        DB::beginTransaction();
        try {
            /**
             * 一下代码设置修改分成比例
             */
            //一个客户产生3分成数据
            //分成比例设置
            foreach ([1, 2, 3] as $type) {
                $feeRate = ClientFeeRate::where(['cust_id' => $client_id, 'type' => $type])->first();
                //遍历1~5级代理并设置分成
                foreach ([1, 2, 3, 4, 5] as $level) {
                    $pName = "agent{$level}";
                    $pRateName = "agent{$level}_rate";
                    $feeRate->$pName = $clientRelation->$pName;
                    $rateConfig = AgentProfitRateConfig::where(['agent_id' => $clientRelation->$pName, 'type' => $type])->first();
                    $feeRate->$pRateName = $rateConfig ? $rateConfig->percentage : 0;
                }

                //设置员工分成比例
                $whereEmployRateConfig = ['employee_id' => $clientRelation->direct_emp_id, 'type' => $type];
                $employFeeConfig = EmployeeProfitRateConfig::where($whereEmployRateConfig)->first();
                $feeRate->emp_id = $employFeeConfig ? $clientRelation->direct_emp_id : 0;
                $feeRate->emp_rate = $employFeeConfig ? $employFeeConfig->percentage : 0;


                //设置客户邀请关系分成比例
                //设置客户推广佣金
                foreach ([3, 4, 5] as $clientRateType) {
                    //提取直系代理商数据 佣金数据 配置
                    $clientRateWhere = ['agent_id' => $clientRelation->direct_agent_id, 'type' => $clientRateType];
                    $rateConfig = AgentProfitRateConfig::where($clientRateWhere)->first();
                    $foo = $clientRateType - 2;
                    $clientPropertyName = "cust{$foo}";
                    $clientPropertyRateName = "cust{$foo}_rate";

                    $feeRate->$clientPropertyName = $clientRelation->$clientPropertyName;
                    $feeRate->$clientPropertyRateName = $rateConfig->percentage;
                }

                $feeRate->save();
            }
            //保存修改的数据
            $clientRelation->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return self::jsonReturn([], self::CODE_FAIL, '');
        }

    }

}