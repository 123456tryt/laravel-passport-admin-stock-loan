<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\Agent;
use App\Http\Model\AgentProfitRateConfig;
use App\Http\Model\Client;
use App\Http\Model\ClientAgentEmployeeRelation;
use App\Http\Model\ClientFeeRate;
use App\Http\Model\ClientRecharge;
use App\Http\Model\Employee;
use App\Http\Model\EmployeeProfitRateConfig;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ClientRechargeController 客户充值记录
 * @package App\Http\Controllers\Api
 */
class ClientRechargeController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }

    /**
     * 充值记录列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $keyword = $request->keyword;
        $status = $request->status;

        $transfer_type = $request->transfer_type;

        $query = ClientRecharge::orderByDesc('updated_time');

        $range = $request->range;
        if (count($range) === 2) {
            $from_time = Carbon::parse($range[0]);
            $to_time = Carbon::parse($range[1]);
            $query->whereBetween('created_time', [$from_time, $to_time]);
        }
        if ($keyword) {
            $query->with(['client' => function ($subQuery) use ($keyword) {
                $subQuery->orWhere('nick_name', $keyword)->orWhere('real_name', $keyword)->orWhere('cellphone', $keyword);
            }]);
        } else {
            $query->with('client');
        }
        if ($status) {
            $query->where(compact('status'));
        }
        if ($transfer_type) {
            $query->where(compact('transfer_type'));
        }

        $list = $query->paginate(self::PAGE_SIZE);
        return self::jsonReturn($list);
    }


}