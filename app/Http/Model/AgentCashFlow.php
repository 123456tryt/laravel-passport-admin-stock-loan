<?php

namespace App\Http\Model;


class AgentCashFlow extends Base
{
    protected $table = "a_agent_cash_flow";

    public function agent()
    {
        return $this->belongsTo(Agent::Class, 'agent_id', 'id');
    }
}
