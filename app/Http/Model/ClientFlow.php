<?php

namespace App\Http\Model;

/**
 * App\Http\Model\ClientFLow
 */
class ClientFLow extends Base
{
    protected $table = "u_cust_account_flow";
    protected $guarded = ['id'];
    const AdminIncreaseMoney = 10;//后台调整金额
    const AdminRechargeMoney = 11;//后台充值金额
    const CREATED_AT = "created_time";
    const UPDATED_AT = "occur_time";

    public function client()
    {
        return $this->belongsTo('\App\Http\Model\Client', 'cust_id', 'id');
    }

}
