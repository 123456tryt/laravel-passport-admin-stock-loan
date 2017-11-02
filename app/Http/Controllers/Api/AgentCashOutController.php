<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\Agent;
use App\Http\Model\AgentCashFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class AgentCashOutController 代理提现申请
 * @package App\Http\Controllers\Api
 */
class AgentCashOutController extends Controller
{


    public function __construct()
    {
        $this->middleware("auth:api")->except(['search', 'childrenAgent']);
    }


    public function info()
    {
        return self::jsonReturn($this->fetchNumbers());
    }

    private function fetchNumbers()
    {

        $agent = Auth::user()->agent;
        $agent_id = $agent->id;
        $agent_level = $agent->agent_level;
        $agentColumn = "agent{$agent_level}_id";
        $agentInterestColumn = "agent{$agent_level}_interests";

        //利息金额
        $allInterest = DB::table('u_stock_finance_interest_percentage')->where([$agentColumn => $agent_id])->sum($agentInterestColumn);
        $allInterest = round($allInterest, 2);
        //欠利息金额
        $dueInterest = DB::table('u_stock_finance_interest_percentage')->where([$agentColumn => $agent_id, 'is_paid_over' => 0])->sum($agentInterestColumn);
        $dueInterest = round($dueInterest, 2);

        //佣金金额
        $agentFeeColumn = "agent{$agent_level}_fee";
        $manageFee = DB::table('u_stock_finance_fee_report')->where([$agentColumn => $agent_id])->sum($agentFeeColumn);
        $manageFee = round($manageFee, 2);


        //穿仓金额
        $lossAmount = DB::table('u_stock_finance_settleup')->where(['agent_id' => $agent_id])->sum('loss_amount');
        $lossAmount = round($lossAmount, 2);

        //已提现金额
        //a_agent_cash_flow
        $cash_status = 1;
        $totalCashedOutAmount = AgentCashFlow::where(compact('agent_id', 'cash_status'))->sum('cash_amount');
        $totalCashedOutAmount = round($totalCashedOutAmount, 2);

        $parent = Agent::find($agent->parent_id);
        $parent_name = $parent->agent_name ?? '无父级代理商';
        $agent_name = $agent->agent_name;
        $agent_level = $agent->agent_level;


        $cashable_amount = $allInterest - $dueInterest + $manageFee - $lossAmount - $totalCashedOutAmount;

        return compact('allInterest', 'dueInterest', 'manageFee', 'lossAmount', 'agent_name', 'agent_level', 'parent_name', 'totalCashedOutAmount', 'cashable_amount');
    }

    public function addCashOutRecord(Request $request)
    {
        $agent = Auth::user()->agent;
        $agent_id = $agent->id;
        $instance = AgentCashFlow::whereDate('created_time', '=', Carbon::today()->toDateString())->where(compact('agent_id'))->first();
        if ($instance) {
            return self::jsonReturn([], 0, '每天只能提交一次申请');
        }
        $remark = "{$agent->bank_account_name}##{$agent->bank_account}##{$agent->bank_name}##{$agent->bank_branch}";
        $data = [
            'agent_id' => $agent_id,
            'cash_amount' => $request->cashOutAmount,
            'cash_status' => 4,
            'cashable_amount' => $request->cashable_amount,
            'due_interest_amount' => $request->dueInterest,
            'stock_loss_amount' => $request->lossAmount,
            'remark' => $remark
        ];
        $result = AgentCashFlow::create($data);
        return self::jsonReturn($result);

    }


}