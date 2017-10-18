<?php

namespace App\Repositories;

use App\Http\Model\CustRecharge;
use Illuminate\Support\Facades\DB;

class RechargeRepository extends Base
{
    /**
     * 线下转账
     * @param $user
     * @param $data
     * @return mixed
     */
    public function offlineTransfer($user, $data)
    {
        $data = array_merge($data, [
            "cust_id" => $user->id,
            "type" => 1,
            "status" => 4,
        ]);

        return CustRecharge::create($data);
    }
}