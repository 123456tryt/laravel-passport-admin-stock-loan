<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Agent extends Base
{
    protected $table = "a_agent";

    public function extraInfo()
    {
        return $this->hasOne('App\Http\Model\AgentExtraInfo', 'id', 'id');
    }
}