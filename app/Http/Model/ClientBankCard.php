<?php

namespace App\Http\Model;

class ClientBankCard extends Base
{
    protected $table = "u_cust_bankcard";
    protected $guarded = ['id', 'created_time', 'updated_time'];

}
