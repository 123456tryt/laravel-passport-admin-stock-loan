<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\Client;
use App\Http\Model\ClientFLow;
use App\Http\Model\ClientRecharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $size = $request->input('size', self::PAGE_SIZE);

        $query = ClientRecharge::orderByDesc('updated_time')->with('client');


        if ($keyword) {
            $client_ids = Client::orWhere('real_name', 'like', "%$keyword%")->orWhere('cellphone', 'like', "%$keyword%")
                ->pluck('id')->all();
            $query = $query->whereIn('cust_id', $client_ids);
        }

        $status = $request->status;
        if ($status) {
            $query->where(compact('status'));
        }

        $transfer_type = $request->transfer_type;
        if ($transfer_type) {
            $query->where(compact('transfer_type'));
        }

        $list = $query->paginate($size);
        return self::jsonReturn($list);
    }

    public function info(Request $request)
    {
        $info = ClientRecharge::find($request->id);
        return self::jsonReturn($info);
    }

    public function update(Request $request)
    {
        $info = ClientRecharge::find($request->id);

        DB::beginTransaction();
        try {
            $res = $info->fill($request->only('status', 'fee', 'remark'))->save();
            $code = $res ? 1 : 0;

            //如果状态是成功 就到流水表中添加数据
            if ($info->status == 1) {
                $cust_id = $info->cust_id;
                $amount_of_account = $info->amount_of_account - $info->fee;

                $last_money = round(ClientFLow::whereCustId($cust_id)->sum('amount_of_account'), 2);
                $account_left = $last_money + $amount_of_account;
                $user = Auth::user();
                $operator_id = $user->id;
                $description = '后台充值审核';

                $remark = "{$description}\r\n上期累计余额:{$last_money};修改金额:{$info->amount_of_account}元;手续费:{$info->fee}元;有效金额:{$amount_of_account}元;本次累计:{$account_left}元\r\n";
                $remark .= "操作者:{$user->real_name},ID:{$user->id},手机号码:{$user->phone};登陆账号:{$user->name}\r\n";
                $remark .= "备注:{$request->remark};审核充值ID:{$info->id}";
                $flow_type = 1;
                $flow_id = $info->id;
                $data = compact('operator_id', 'flow_type', 'description', 'amount_of_account', 'account_left', 'remark');
                ClientFLow::updateOrCreate(compact('cust_id', 'flow_id'), $data);
            }
            DB::commit();
            return self::jsonReturn($info, $code);
        } catch (\Exception $e) {
            return self::jsonReturn([], 0, $e->getMessage());

        }


    }

}