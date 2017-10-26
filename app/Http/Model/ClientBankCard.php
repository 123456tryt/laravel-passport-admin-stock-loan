<?php

namespace App\Http\Model;
class ClientBankCard extends Base
{
    protected $table = "u_cust_bankcard";
    protected $guarded = ['id', 'created_time', 'updated_time'];

    public function client()
    {
        return $this->belongsTo(Client::Class, 'cust_id', 'id');
    }

}
