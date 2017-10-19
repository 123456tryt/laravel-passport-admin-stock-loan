<?php

namespace App\Http\Model;

/**
 * App\Http\Model\Client
 *
 */
class Client extends Base
{
    protected $table = "u_customer";

    public $timestamps = false;
    protected $guarded = ['id', 'created_time', 'updated_time'];

    public function relation()
    {
        return $this->hasOne('\App\Http\Model\ClientAgentEmployeeRelation', 'cust_id', 'id');
    }

}
