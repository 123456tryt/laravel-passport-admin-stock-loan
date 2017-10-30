<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\Client;
use App\Http\Model\ClientRecharge;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $size = $request->input('size', self::PAGE_SIZE);
        $transfer_type = $request->transfer_type;

        $query = ClientRecharge::orderByDesc('updated_time');

        $range = $request->range;
        if (count($range) == 2 && strlen($range[0]) > 10 && strlen($range[1]) > 10) {
            $from_time = Carbon::parse($range[0]);
            $to_time = Carbon::parse($range[1]);
            $query->whereBetween('created_time', [$from_time, $to_time]);
            $_GET['page'] = 1;
        }
        if ($keyword) {
            $_GET['page'] = 1;
            $likeString = "%$keyword%";
            $client_ids = Client::orWhere('nick_name', 'like', $likeString)
                ->orWhere('real_name', 'like', $likeString)
                ->orWhere('cellphone', 'like', $likeString)
                ->pluck('id')->all();
            $query = $query->whereIn('cust_id', $client_ids);

        } else {
            $query->with('client');
        }
        if ($status) {
            $query->where(compact('status'));
        }
        if ($transfer_type) {
            $query->where(compact('transfer_type'));
        }

        $list = $query->paginate($size);
        return self::jsonReturn($list);
    }


}