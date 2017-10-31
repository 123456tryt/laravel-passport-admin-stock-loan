<?php

namespace App\Http\Model;

/**
 * 用户短信记录
 * App\Http\Model\UMsg
 *
 */
class UMsg extends Base
{


    public $timestamps = false;
    protected $table = "u_msg";
    protected $guarded = ['id'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'cust_id', 'id');
    }

    public function relation()
    {
        return $this->hasOne(ClientAgentEmployeeRelation::class, 'cust_id', 'cust_id');
    }


}
