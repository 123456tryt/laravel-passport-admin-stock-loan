<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\Client;
use App\Http\Model\ClientBankCard;
use Illuminate\Http\Request;

/**
 * Class ClientBankCardController 客户银行卡
 * @package App\Http\Controllers\Api
 */
class ClientBankCardController extends Controller
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
        $keyword = $request->input('keyword');
        $page_size = $request->input('size', self::PAGE_SIZE);

        $agent_id = $request->input('agent_id');

        $query = ClientBankCard::orderByDesc('id')->with('client');
        if ($keyword) {
            $clientIds = Client::orwhere('id', 'like', "%$keyword%")
                ->orWhere('real_name', 'like', "%$keyword%")
                ->orWhere('cellphone', 'like', "%$keyword%")->pluck('id')->all();
            $clientIds = array_values($clientIds);
            $query = $query->whereIn('cust_id', $clientIds);
        }
        $data = $query->paginate($page_size);
        //TODO::根据关系表只显示本级以下代理商
        return self::jsonReturn($data);
    }


    public function update(Request $request)
    {
        $cardInfo = ClientBankCard::find($request->id)
            ->fill($request->only([
                'is_cash_bankcard',
                'is_open_netbank',
                'bank_name', 'bank_card', 'open_bank', 'open_district', 'open_province', 'bank_reg_cellphone'
            ]));
        $cardInfo->save();
        return self::jsonReturn($cardInfo, self::CODE_SUCCESS, '修改客户成功');
    }


    public function info(Request $request)
    {
        $cardInfo = ClientBankCard::find($request->id);
        return self::jsonReturn($cardInfo, self::CODE_SUCCESS, '修改客户成功');
    }

}