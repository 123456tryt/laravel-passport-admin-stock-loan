<?php

namespace App\Http\Model;


class AgentPeak extends Base
{
    protected $table = "a_agent_performance";
    protected $guarded = ['id', 'create_time', 'updated_time'];

    public function agent()
    {
        $this->hasOne(Agent::Class, 'agent_id', 'id');
    }

}
