<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class MemberAgentRelation extends Base
{
    protected $table = "u_member_agent_relation";

    protected $fillable = ["cust_id", "direct_cust_id", "direct_agent_id", "agent1", "agent2", "agent3", "agent4",
        "agent4", "agent5", "direct_emp_id", "belong_to_agent", "cust1", "cust2", "cust3"];

}