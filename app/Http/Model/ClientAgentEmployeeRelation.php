<?php

namespace App\Http\Model;


class ClientAgentEmployeeRelation extends Base
{
    protected $table = "u_member_agent_relation";
    protected $guarded = ['id', 'create_time', 'updated_time'];


}
