<?php

namespace App\Repositories;

use App\Http\Model\CustAccountFlow;

class FundsDetailRepository extends Base
{
    const PAGE_SIZE = 15;

    static public $fundsDetailsTypeList = [
        "全部" => [],
        "充值提款" => [0, 1, 2],
        "借款明细" => [3, 5, 7],
        "服务费明细" => [4],
        "利润提取" => [6]
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
        return $fundsDetail;
    }

}