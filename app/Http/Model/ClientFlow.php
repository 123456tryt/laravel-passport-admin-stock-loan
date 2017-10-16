<?php

namespace App\Http\Model;

class ClientFLow extends Base
{
    protected $table = "u_cust_account_flow";
    protected $guarded = ['id'];
    const AdminIncreaseMoney = 10;//后台调整金额
    const AdminDecreaseMoney = 9;//后台调整金额
    const CREATED_AT = "created_time";
    const UPDATED_AT = "occur_time";
}
