<?php

namespace App\Http\Model;

class Client extends Base
{
    protected $table = "u_customer";

    public $timestamps = false;
    protected $fillable = ['key', 'agent_id', 'value'];

}
