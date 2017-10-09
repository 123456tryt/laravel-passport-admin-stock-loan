<?php

namespace App\Repositories;

use App\Http\Model\CashFlow;
use Prettus\Repository\Eloquent\BaseRepository;

class AccountRepository extends BaseRepository
{
    public function model()
    {
        return "App\\User";
    }

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
        return CashFlow::where('cust_id', $user->id)->get(['id', 'cash_amount', 'apply_time', 'cash_status']);
    }
}