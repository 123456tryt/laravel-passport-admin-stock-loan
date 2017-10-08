<?php

namespace App\Http\Model;

class SysConfig extends Base
{
    protected $table = "s_system_params";

    public $timestamps = false;
    protected $fillable = ['key', 'agent_id', 'value'];

}
