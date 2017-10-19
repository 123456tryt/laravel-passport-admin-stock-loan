<?php

namespace App\Http\Model;


class ClientFeeRate extends Base
{
    protected $table = "u_member_fee_rate";
    protected $guarded = ['id', 'create_time', 'updated_time'];


}
