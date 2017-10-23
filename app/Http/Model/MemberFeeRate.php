<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class MemberFeeRate extends Base
{
    protected $table = "u_member_fee_rate";

    protected $fillable = ["cust_id", "direct_agent_id", "agent1", "agent2", "agent3", "agent4",
        "agent4", "agent5", "emp_id", "cust1", "cust2", "cust3", "cust1_rate", "cust2_rate", "cust3_rate",
        "agent1_rate", "agent2_rate", "agent3_rate", "agent4_rate", "agent5_rate", "emp_rate", "type"];

}