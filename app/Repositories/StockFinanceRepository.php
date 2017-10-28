<?php

namespace App\Repositories;

use App\Http\Model\Agent;
use App\Http\Model\StockFinanceContract;
use App\Http\Model\StockFinanceHoldings;
use App\Http\Model\StockFinanceProducts;
use App\Http\Model\StockFinanceSettleup;
use App\Http\Model\StockFinancing;
use Illuminate\Support\Facades\DB;

class StockFinanceRepository extends Base
{
    const PAGE_SIZE = 10;
    const ONE_MONTH_DAYS = 30;

    public function getProducts($agent)
    {
        $ret = StockFinanceProducts::where("agent_id", $agent->id)->orderBy("product_times", "asc")
            ->where("disable", 0)->get();
        $data = ["1" => [], "2" => []];
        foreach ($ret as $v) {
            if (isset($data[$v->product_type])) {
                $v = $v->toArray();
                $data[$v["product_type"]][] = $v;
            }
        }
        return $data;
    }

    public function getStockFinances($user)
    {
        $ret = StockFinancing::where("cust_id", $user->id)->orderBy("status")->orderBy("created_time", "desc")->
        select(["id", "product_id", "product_type", "status", "init_caution_money", "post_finance_caution_money",
            "post_add_caution_money", "current_finance_amount", "stock_finance_begin_time", "stock_finance_settleup",
            "available_amount", "precautious_line_amount", "liiquidation_line_amount", "next_interest_charge_time",
            "is_auto_supply_caution_money", "created_time", "interest_charged_day"])->paginate(self::PAGE_SIZE);
        $rets = $ret->getCollection();
        $data = [];
        foreach ($rets as $v) {
            $t = $this->getInfo($v);
            $data[] = $t;
        }
        $ret = $ret->toArray();
        $ret["data"] = $data;
        return $ret;
    }

    public function getStockFinance($user, $id)
    {
        $ret = StockFinancing::where("cust_id", $user->id)->where("id", $id)->
        select(["id", "product_id", "product_type", "status", "init_caution_money", "post_finance_caution_money",
            "post_add_caution_money", "current_finance_amount", "stock_finance_begin_time", "stock_finance_settleup",
            "available_amount", "precautious_line_amount", "liiquidation_line_amount", "next_interest_charge_time",
            "is_auto_supply_caution_money", "created_time", "interest_charged_day"])->first();
        if (!$ret) return false;

        $data = $this->getInfo($ret);

        //持仓详情
        return $data;
    }

    public function makeContract($user, $productId, $money, $isReturn = false, $stockFinanceId = "")
    {
        $data = [];
        $data["realName"] = $user ? $user->real_name : "__";
        $data["idCard"] = $user ? $user->id_card : "";
        $data["cellphone"] = $user ? $user->cellphone : "";

        $productInfo = StockFinanceProducts::where("id", $productId)->first();
        $agentInfo = \DB::table("a_agent")->leftJoin("a_agent_extra_info", "a_agent.id", "=", "a_agent_extra_info.id")
            ->where("a_agent.id", $productInfo->agent_id)->where("a_agent.is_independent", 1)->first();
        $startTime = date("Y-m-d");
        $endTime = $productInfo->product_type == 2 ? date("Y-m-d", strtotime("+" . self::ONE_MONTH_DAYS . " days")) :
            date("Y-m-d", strtotime(caclTransactionDays(time(), 2)));
        $data = array_merge($data, [
            "companyName" => $agentInfo->agent_name,
            "companyPhone" => $agentInfo->service_phone,
            "money" => $money,
            "moneyOfChinese" => num_to_rmb($money),
            "siteName" => $agentInfo->platform_name,
            "siteUrl" => $agentInfo->web_domain,
            "startTime" => date("Y月m月d日", strtotime($startTime)),
            "endTime" => date("Y月m月d日", strtotime($endTime)),
            "fee" => formatMoney($money * $productInfo->product_times * $productInfo->interests_rate),
            "feeCycle" => $productInfo->product_type == 2 ? "月" : "天",
        ]);

        $view = view("contract", $data);
        $html = response($view)->getContent();

        if ($isReturn) {
            return $html;
        } else {
            $count = StockFinanceContract::where("stock_finance_id", $stockFinanceId)->count();
            if ($count == 0) {
                $filename = $stockFinanceId . ".docx";
            } else {
                $filename = $stockFinanceId . "_" . ($count + 1) . ".docx";
            }
            $ret = ossUpload($filename, $html, "contracts");
            return StockFinanceContract::create([
                "stock_finance_id" => $stockFinanceId,
                "cust_id" => $user->id,
                "agent_id" => $productInfo->agent_id,
                "finance_begin_time" => $startTime,
                "finance_end_time" => $endTime,
                "contract_no" => $stockFinanceId,
                "contract_url" => $ret,
            ]);
        }
    }

    private function getInfo($stockFinance)
    {
        $t = $stockFinance->toArray();
        $t["product_name"] = "配资";

        $product = $stockFinance->product()->first();
        $t["interest_per_day"] = 0;
        if ($product) {
            $t["interest_per_day"] = $product->interests_rate * $t["current_finance_amount"];
        }

        //时间
        $t["start_time"] = date("Y-m-d", strtotime($t["created_time"]));
        if ($t["product_type"] == 2) {
            $t["end_time"] = date("Y-m-d", strtotime($t["next_interest_charge_time"]) - 3600 * 24);
        } else {
            if (in_array($t["status"], [1, 2, 3])) {
                $t["end_time"] = date("Y-m-d");
            } else {
                $t["end_time"] = date("Y-m-d", strtotime($t["stock_finance_settleup"]));
            }
        }

        //收息天数
        switch ($stockFinance->product_type) {
            case 2:
                $t["interest_charged_day"] = $stockFinance["interest_charged_day"] / self::ONE_MONTH_DAYS;
                break;
            case 3:
                $t["interest_charged_day"] = (strtotime($t["end_time"]) - strtotime($t["start_time"])) / 3600 / 24 + 1;
                break;
            default:
                $t["interest_charged_day"] = date("Y-m-d", strtotime($stockFinance["created_time"])) == date("Y-m-d") ?
                    1 : $stockFinance["interest_charged_day"];
        }
        $t["stock_finance_settleup"] = date("Y-m-d", strtotime($t["stock_finance_settleup"]));

        //产品名称
        $product = $stockFinance->product()->first();
        if ($product) {
            $t["product_name"] = $product->product_name;
        }

        //冻结资金
        $t["freeze_money"] = $t["init_caution_money"] + $t["post_finance_caution_money"] +
            $t["post_add_caution_money"];


        $financeInfo = self::getStockFinanceAbout($stockFinance);

        $t["totalFinanceAmount"] = $t["freeze_money"] + $t["current_finance_amount"];
        $t["totalAssets"] = $financeInfo["totalAssets"];
        $t["profitAndLoss"] = $financeInfo["profitAndLoss"];
        $t["profitAndLossRate"] = $t["profitAndLoss"] / $t["totalFinanceAmount"] * 100;
        //TODO: 持仓市值
        $t["positionValue"] = $financeInfo["value"];
        //TODO: 持仓比例  持仓市值/合约总资产
        $t["positionRate"] = sprintf("%.2f", $t["positionValue"] / ($t["totalAssets"] == 0 ? 1 : $t["totalAssets"]) * 100);

        $t = $this->filterData($t);
        return $t;
    }

    private function filterData($data)
    {
        unset($data["product_id"]);
        unset($data["init_caution_money"]);
        unset($data["post_finance_caution_money"]);
        unset($data["post_add_caution_money"]);
        unset($data["stock_finance_begin_time"]);
        unset($data["next_interest_charge_time"]);

        $data["current_finance_amount"] = formatMoney($data["current_finance_amount"]);
        $data["available_amount"] = formatMoney($data["available_amount"]);
        $data["precautious_line_amount"] = formatMoney($data["precautious_line_amount"]);
        $data["liiquidation_line_amount"] = formatMoney($data["liiquidation_line_amount"]);
        $data["interest_per_day"] = formatMoney($data["interest_per_day"]);
        $data["freeze_money"] = formatMoney($data["freeze_money"]);
        $data["totalFinanceAmount"] = formatMoney($data["totalFinanceAmount"]);
        $data["totalAssets"] = formatMoney($data["totalAssets"]);
        $data["profitAndLoss"] = formatMoney($data["profitAndLoss"]);
        $data["profitAndLossRate"] = formatMoney($data["profitAndLossRate"]);
        $data["positionValue"] = formatMoney($data["positionValue"]);
        $data["positionRate"] = formatMoney($data["positionRate"]);
        return $data;
    }

    //获取合约总资产和利润
    static public function getStockFinanceAbout($stockFinance)
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
            $about["value"] = self::getStockFinanceValue($stockFinance->id);
            //TODO: 合约总资产 可用余额+冻结买入资金+冻结手续费资金+股票市值
            $about["totalAssets"] = $stockFinance->available_amount + $stockFinance->freeze_buying_money + $stockFinance->
                freeze_charge_money + $about["value"];
            //TODO：收益 合约总资产-总配资金额
            $about["profitAndLoss"] = $about["totalAssets"] - ($stockFinance->current_finance_amount + $stockFinance->init_caution_money +
                    $stockFinance->post_finance_caution_money + $stockFinance->post_add_caution_money);
        }

        return $about;
    }

    /**
     * 获取持仓市值
     * @param $id
     * @param string $type 1：根据配资id 2：根据持仓id
     */
    static public function getStockFinanceValue($id, $type = "1")
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
            $stockInfo = getStockInfo($v->stock_code);
            $stockInfo = $stockInfo[0] ?? [];
            $value += $v->total_sold_amount + $stockInfo["price"] * $v->holdings_quantity;
        }
        return $value;
    }

}