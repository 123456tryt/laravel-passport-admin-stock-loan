<?php

namespace App\Repositories;

use App\Http\Model\MemberAgentRelation;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Http\Model\CustAccountFlow;
use App\Http\Model\StockFinanceInterestPercentage;

class ShareRepository extends Base
{
    const PROMOTION_TYPE = 9;
    const PAGE_SIZE = 15;

    /**
     * 获取推广统计
     * @param $user
     * @return array
     */
    public function getShareCount($user)
    {
        $data = [];
        $data["promotionMoneyCount"] = CustAccountFlow::where("cust_id", $user->id)->
        where("flow_type", self::PROMOTION_TYPE)->sum("amount_of_account");
        $data["promotionMoneyCount"] = sprintf("%.2f", $data["promotionMoneyCount"]);

        //TODO:假设一级推荐人为最近的
        $data["promotionUserCount"] = MemberAgentRelation::where("cust2", $user->id)->
        orWhere("cust1", $user->id)->count();
        return $data;
    }

    /**
     * 获取推广用户列表
     * @param $user
     * @param $level
     * @return array
     */
    public function getPromotionUsers($user, $level)
    {
        $paginate = MemberAgentRelation::where("cust{$level}", $user->id)->orderBy(MemberAgentRelation::CREATED_AT, "desc")
            ->select(["cust_id"])->paginate(self::PAGE_SIZE);
        $custs = $paginate->getCollection();
        $data = [];
        foreach ($custs as $cust) {
            $cust = $cust->cust;
            if ($cust) {
                $time = "";
                if ($cust->created_time) {
                    $time = ((array)$cust->created_time)["date"];
                }
                $data[] = ["nickname" => half_replace($cust->nick_name), "cellphone" => half_replace($cust->cellphone),
                    "registerTime" => substr($time, 0, strpos($time, "."))];
            }
        }
        $paginate = $paginate->toArray();
        $paginate["data"] = $data;
        return $paginate;
    }

    /**
     * 获取推广收益列表
     * @param $user
     * @param $level
     * @return array
     */
    public function getPromotionPercentages($user, $level)
    {
        $incomeField = "cust{$level}_interests";
        $paginate = StockFinanceInterestPercentage::where("cust{$level}_id", $user->id)
            ->orderBy(MemberAgentRelation::CREATED_AT, "desc")->select(["cust_id", "cust{$level}_interests"])
            ->paginate(self::PAGE_SIZE);
        $percentages = $paginate->getCollection();
        $data = [];
        foreach ($percentages as $percentage) {
            $data[] = [
                "nickname" => $user->nick_name,
                "cellphone" => half_replace($user->cellphone),
                "income" => $percentage->{$incomeField},
                "expenses" => 0,
            ];
        }
        $paginate = $paginate->toArray();
        $paginate["data"] = $data;
        return $paginate;
    }

}