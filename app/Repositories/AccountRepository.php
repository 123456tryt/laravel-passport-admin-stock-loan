<?php

namespace App\Repositories;

use App\Http\Model\CashFlow;
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
        DB::beginTransaction();

        try {
            $data = array_merge($data, [
                'apply_time' => date('Y-m-d H:i:s'),
                'cash_status' => 0,
                'cust_id' => $user->id,
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
        $ret = CashFlow::where('cust_id', $user->id)->get(['id', 'cash_amount', 'apply_time', 'cash_status']);
        return $ret ? $ret->toArray() : false;
    }
}