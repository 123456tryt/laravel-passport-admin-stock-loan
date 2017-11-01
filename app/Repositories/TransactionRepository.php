<?php

namespace App\Repositories;

use App\Http\Model\CustStock;
use App\Http\Model\StockFinanceEntrust;
use App\Http\Model\StockFinanceHoldings;
use App\Http\Model\StockFinanceMakedealHistory;
use App\Http\Model\StockFinancing;
use Illuminate\Foundation\Application;

class TransactionRepository extends Base
{
    const PAGE_SIZE = 15;

    /**
     * 获取持仓列表
     * @param $stockFinanceId
     * @return mixed
     */
    public function getHoldingsList($stockFinanceId)
    {
        $list = StockFinanceHoldings::where("stock_finance_id", $stockFinanceId)->orderBy("created_time", "desc")
            ->get();
        $newList = [];
        foreach ($list as $v) {
            $data = $v->toArray();
            $allFee = $data["stocker_total_commission"] + $data["total_commission"] + $data["total_stamp_duty"] +
                $data["total_transfer_fee"] + $data["total_brokerage"] + $data["total_witness_fee"] + $data["total_fees"];
            $stockInfo = getStockInfo($v->stock_code, true);
            $data["value"] = $stockInfo["price"] ?? "0.00";
            $data["positionValue"] = $this->getStockFinanceValue($data["id"], 2);
            $data["profitAndLoss"] = $data["positionValue"] - $data["total_bought_amount"] - $allFee;
            $data["profitAndLossRate"] = $data["profitAndLoss"] / ($data["positionValue"] - $data["profitAndLoss"]) * 100;
            //成本均价 =（买入累计金额  - 卖出累计金额 + 总佣金 + 总印花税 + 总过户费 + 总经手费 + 总见证费 + 总规费)/持仓数量
            $data["averagePrice"] = ($data["total_bought_amount"] - $data["total_sold_amount"] + $allFee) / $data["holdings_quantity"];

            $data["value"] = formatMoney($data["value"]);
            $data["positionValue"] = formatMoney($data["positionValue"]);
            $data["profitAndLoss"] = formatMoney($data["profitAndLoss"]);
            $data["profitAndLossRate"] = formatMoney($data["profitAndLossRate"]);
            $data["averagePrice"] = formatMoney($data["averagePrice"]);
            $newList[] = $data;
        }
        return $newList;
    }

    /**
     * 历史交易
     * @param $stockFinanceId
     * @return mixed
     */
    public function getMakeDealList($stockFinanceId)
    {
        $ret = StockFinanceMakedealHistory::where("stock_finance_id", $stockFinanceId)->orderBy("created_time", "desc")
            ->paginate(self::PAGE_SIZE);
        return $ret;
    }

    /**
     * 获取统计
     * @param $user
     * @param $stockFinanceId
     * @return bool
     */
    public function getCount($user, $stockFinanceId)
    {
        $ret = StockFinancing::where("cust_id", $user->id)->where("id", $stockFinanceId)->
        select(["id", "product_id", "product_type", "status", "init_caution_money", "post_finance_caution_money",
            "post_add_caution_money", "current_finance_amount", "stock_finance_begin_time",
            "available_amount", "next_interest_charge_time",
            "is_auto_supply_caution_money", "created_time", "interest_charged_day", "freeze_buying_money",
            "freeze_charge_money"])->first();
        if (!$ret) return false;

        $about = $this->getStockFinanceAbout($ret);
        $ret = array_merge($ret->toArray(), $about);

        unset($ret["product_id"]);
        unset($ret["product_type"]);
        unset($ret["interest_charged_day"]);
        unset($ret["freeze_buying_money"]);
        unset($ret["freeze_charge_money"]);
        $ret["totalAssets"] = formatMoney($ret["totalAssets"]);
        $ret["value"] = formatMoney($ret["value"]);
        $ret["profitAndLoss"] = formatMoney($ret["profitAndLoss"]);
        $ret["accountAvailableAmount"] = formatMoney($ret["accountAvailableAmount"]);
        return $ret;
    }

    /**
     * 获取用户股票池
     * @param $user
     */
    public function getStockPool($user)
    {
        $ret = CustStock::where("cust_id", $user->id)->limit(15)->orderBy("created_time", "desc")->get();
        return $ret ? $ret->toArray() : [];
    }

    /**
     * 添加股票进股票池
     * @param $user
     * @param $stockCode
     */
    public function addStockToPool($user, $stockCode)
    {
        $stockInfo = getStockInfo($stockCode, true);

        if (!$stockInfo) {
            $this->setErrorMsg("请填写正确的证券代码");
            return false;
        }

        if (CustStock::where("stock_code", $stockCode)->count() > 0) {
            $this->setErrorMsg("该股票您已选入自选股");
            return false;
        }

        return CustStock::create([
            "stock_code" => $stockCode,
            "stock_name" => $stockInfo["name"],
            "cust_id" => $user->id
        ]) ? $stockInfo : false;
    }

    /**
     * 从股票池删除股票
     * @param $user
     * @param $id
     */
    public function delStockFromPool($user, $id)
    {
        $ret = CustStock::where("id", $id)->where("cust_id", $user->id)->first();
        return $ret ? $ret->delete() : true;
    }

    /**
     * 获取委托记录
     * @param $user
     * @param array $entrustStatus
     * @param array $custStatus
     */
    public function getTodayEntrustList($user, $entrustStatus = [], $custStatus = [])
    {
        $query = StockFinanceEntrust::where("cust_id", $user->id)->orderBy("created_time", "desc");
        if ($entrustStatus) {
            $query = $query->whereIn("stock_finance_entrust_status", $entrustStatus);
        }
        if ($custStatus) {
            $query = $query->whereIn("cust_action", $custStatus);
        }
        $ret = $query->paginate(self::PAGE_SIZE);
        $data = $ret->getCollection();
        $newData = [];
        foreach ($data as $v) {
            $parentEntrust = $v->parentEntrust()->first();
            $v = $v->toArray();
            $v["bargain_amount"] = $parentEntrust ? $parentEntrust->bargain_amount : 0;
            $v["bargain_average_price"] = $parentEntrust ? $parentEntrust->bargain_average_price : 0;
            $newData[] = $v;
        }
        $ret = $ret->toArray();
        $ret["data"] = $newData;
        return $ret;
    }

    /**
     * 获取合约总资产，利润，市值，账户余额
     * @param $stockFinance
     * @return array
     */
    public function getStockFinanceAbout($stockFinance)
    {
        $about = [];
        if ($stockFinance->status == 4) {
            $settleup = $stockFinance->settleup()->first();
            if ($settleup) {
                $about["value"] = 0;
                $about["totalAssets"] = 0;
                $about["profitAndLoss"] = $settleup->gain_loss ? -$settleup->gain_loss_amount : $settleup->gain_loss_amount;
            } else {
                $about["value"] = 0;
                $about["totalAssets"] = 0;
                $about["profitAndLoss"] = 0;
            }
        } else {
            $about["value"] = $this->getStockFinanceValue($stockFinance->id);
            //TODO: 合约总资产 可用余额+冻结买入资金+冻结手续费资金+股票市值
            $about["totalAssets"] = $stockFinance->available_amount + $stockFinance->freeze_buying_money + $stockFinance->
                freeze_charge_money + $about["value"];
            //TODO：收益 合约总资产-总配资金额
            $about["profitAndLoss"] = $about["totalAssets"] - ($stockFinance->current_finance_amount + $stockFinance->init_caution_money +
                    $stockFinance->post_finance_caution_money + $stockFinance->post_add_caution_money);
        }
        //账户余额：可用余额 + 冻结买入资金 + 冻结手续费
        $about["accountAvailableAmount"] = $stockFinance->available_amount + $stockFinance->freeze_buying_money +
            $stockFinance->freeze_charge_money;

        return $about;
    }

    /**
     * 获取持仓市值
     * @param $id
     * @param string $type 1：根据配资id 2：根据持仓id
     */
    public function getStockFinanceValue($id, $type = "1")
    {
        //TODO 股票市值=卖出累计金额+持仓数量*股票现价
        if ($type == 1) {
            $ret = StockFinanceHoldings::where("stock_finance_id", $id);
        } else {
            $ret = StockFinanceHoldings::where("id", $id);
        }
        $ret = $ret->get();
        $value = 0;
        foreach ($ret as $v) {
            //TODO 先用持仓数量
            $stockInfo = getStockInfo($v->stock_code, true);
            $value += $v->total_sold_amount + ($stockInfo["price"] ?? 0) * $v->holdings_quantity;
        }
        return $value;
    }

}