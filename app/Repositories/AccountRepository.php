<?php

namespace App\Repositories;

use App\Http\Model\CashFlow;

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
        $data = array_merge($data, [
            'apply_time' => date('Y-m-d H:i:s'),
            'cash_status' => 0,
            'cust_id' => $user->id,
        ]);

        return CashFlow::create($data);
    }

    /**
     * 提现记录
     * @param $user
     * @return mixed
     */
    public function withdrawRecord($user)
    {
        $ret = CashFlow::where('cust_id', $user->id)->get(['id', 'cash_amount', 'apply_time', 'cash_status']);
        return $ret ? $ret->toArray() : false;
    }
}