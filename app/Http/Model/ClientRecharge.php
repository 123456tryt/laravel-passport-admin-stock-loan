<?php

namespace App\Http\Model;

/**
 * App\Http\Model\ClientRecharge
 *
 */
class ClientRecharge extends Base
{
    protected $guarded = ['id', 'created_time', 'updated_time'];
    protected $table = "u_cust_recharge";

    public function client()
    {
        return $this->belongsTo('\App\Http\Model\Client', 'cust_id', 'id');
    }


}
