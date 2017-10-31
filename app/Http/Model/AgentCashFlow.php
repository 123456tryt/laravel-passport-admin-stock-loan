<?php

namespace App\Http\Model;


class AgentCashFlow extends Base
{
    protected $table = "a_agent_cash_flow";
    protected $guarded = ['id'];

    public function agent()
    {
        return $this->belongsTo(Agent::Class, 'agent_id', 'id');
    }
}
