<?php

namespace App\Repositories;

use App\Http\Model\CashFlow;
use App\Http\Model\CustBankCard;
use App\Http\Model\StockFinancing;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;

class AccountRepository extends Base
{
    /**
     * 提现
     * @param $user
     * @param $data
     * @return mixed
     */
    public function withdraw($user, $data)
    {
        $bankcardInfo = CustBankCard::find($data["bankcard_id"]);
        if (!$bankcardInfo) return false;

        DB::beginTransaction();
        try {
            $data = array_merge($data, [
                'cash_status' => 0,
                'cust_id' => $user->id,
                'bank_card' => $bankcardInfo->bank_card,
            ]);
            $ret1 = CashFlow::create($data);

            $ret2 = $user->update(["cust_capital_amount" => ($user->cust_capital_amount - $data["cash_amount"])]);
            if ($ret1 && $ret2) {
                DB::commit();
                return true;
            }
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * 提现记录
     * @param $user
     * @return mixed
     */
    public function withdrawRecord($user)
    {
        $ret = CashFlow::where('cust_id', $user->id)->orderBy("created_time", "desc")
            ->get(['id', 'cash_amount', 'created_time', 'cash_status', "bank_card"]);
        if ($ret) {
            $ret = $ret->toArray();
            array_walk($ret, function ($v, $k) use (&$ret) {
                $ret[$k]["bank_card"] = half_replace($v["bank_card"]);
            });
        }
        return $ret;
    }

    /**
     * 撤回提现
     * @param $user
     * @param $id
     * @return bool
     */
    public function checkBackWithdraw($user, $id)
    {
        //TODO 确定什么状态下可以撤销提现
        $cashRecode = CashFlow::where("cust_id", $user->id)->where("id", $id)->where("cash_status", 0)->first();
        if (!$cashRecode) return false;

        DB::beginTransaction();
        try {
            //乐观锁
            $ret1 = CashFlow::where("id", $cashRecode->id)->where("cash_status", 0)->update(["cash_status" => 4]);

            $ret2 = \app\User::where("id", $user->id)->where("cust_capital_amount", $user->cust_capital_amount)->
            update(["cust_capital_amount" => ($user->cust_capital_amount + $cashRecode->cash_amount)]);
            if ($ret1 && $ret2) {
                DB::commit();
                return true;
            }
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function getCount($user)
    {
        $data = [
            "cash" => $user->cust_capital_amount,       //可用资金
            "freeze_cash" => 0.00,                  //冻结资金
            "securitiesNetWorth" => 0,              //证券净值
        ];

        $list = StockFinancing::where("cust_id", $user->id)->whereIn("status", [1, 2, 3])->get();
        foreach ($list as $v) {
            $data["freeze_cash"] += (float)$v->init_caution_money + (float)$v->post_finance_caution_money +
                (float)$v->post_add_caution_money;
        }
        $data["securitiesNetWorth"] = $data["freeze_cash"]; //TODO:计算股票净值 合约总资产+盈亏

        //总资产=现金+股票净值
        $totalAssets = $data["cash"] + $data["securitiesNetWorth"];
        $t = $totalAssets == 0 ? 1 : $totalAssets;  //分母为0
        $data = array_merge($data, [
            "totalAssets" => $totalAssets,              //我的资产
            "securitiesNetWorthRate" => $data["securitiesNetWorth"] / $t * 100,
            "cashRate" => $user->cust_capital_amount / $t * 100,
        ]);

        $data["securitiesNetWorthRate"] = $data["securitiesNetWorthRate"] > 100 ? 100 : ($data["securitiesNetWorthRate"]
        < 0 ? 0 : $data["securitiesNetWorthRate"]);
        $data["cashRate"] = $data["cashRate"] > 100 ? 100 : ($data["cashRate"]
        < 0 ? 0 : $data["cashRate"]);
        foreach ($data as $k => $v) {
            $data[$k] = sprintf("%.2f", $v);
        }

        return $data;
    }
}