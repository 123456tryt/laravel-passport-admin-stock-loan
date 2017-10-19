<?php

namespace App\Repositories;

use App\Http\Model\CustAccountFlow;

class FundsDetailRepository extends Base
{
    const PAGE_SIZE = 15;

    static public $fundsDetailsTypeList = [
        "全部" => [0, 1, 2, 3, 4, 5, 6, 7, 9, 10],
        "充值提款" => [0, 1, 2, 9, 10],
        "借款明细" => [3, 5, 7],
        "服务费明细" => [4],
        "利润提取" => [6]
    ];

    static public $typeList = [
        "", "充值", "提现", "充值退回", "配资支出", "利息支出", "保证金支出", "利润提取", "配资撤回", "推广收益", "代理商调整",
        "代理商充值"
    ];

    /**
     * 资金明细列表
     * @param $user
     * @param $type
     * @return bool
     */
    public function getFundsDetails($user, $type)
    {
        if (!isset(self::$fundsDetailsTypeList[$type])) return false;
        $where = self::$fundsDetailsTypeList[$type];

        $fundsDetail = CustAccountFlow::where("cust_id", $user->id)->orderBy("occur_time", "desc");
        if ($where) {
            $fundsDetail->whereIn("flow_type", $where);
        }
        $fundsDetail = $fundsDetail->paginate(self::PAGE_SIZE)->toArray();

        $data = $fundsDetail["data"];
        foreach ($data as $k => $v) {
            $data[$k]["flow_type"] = self::$typeList[$v["flow_type"]] ?? $v["flow_type"];
        }
        $fundsDetail["data"] = $data;

        return $fundsDetail;
    }

}