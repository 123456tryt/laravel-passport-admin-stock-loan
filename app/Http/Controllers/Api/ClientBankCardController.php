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
        $page = $request->input('page', 1);
        $keyword = $request->input('keyword');
        $agent_id = $request->input('agent_id');

        $cacke_key = "client_bank_card_search={$keyword}_agentID_{$agent_id}_page_{$page}";

        $list = \Cache::remember($cacke_key, 1, function () use ($keyword, $agent_id) {
            $query = ClientBankCard::orderByDesc('updated_time');
            if ($keyword) {
                $query = $query->orWhere('bank_reg_cellphone', 'like', "%$keyword%")
                    ->orWhere('cust_id', '=', "$keyword")
                    ->orWhere('open_province', 'like', "%$keyword%")
                    ->orWhere('open_district', 'like', "%$keyword%");
            }
            $data = $query->paginate(self::PAGE_SIZE);
            //TODO::根据关系表只显示本级以下代理商
            return $data;
        });
        return self::jsonReturn($list);
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

}