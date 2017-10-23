<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class CustRecharge extends Base
{
    protected $table = 'u_cust_recharge';

    protected $fillable = ["amount_of_account", "transfer_type", "cust_remark", "type", "status", "cust_id"];

}