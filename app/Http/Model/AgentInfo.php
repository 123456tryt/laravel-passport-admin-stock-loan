<?php

namespace App\Http\Model;


/**
 * App\Http\Model\AgentInfo
 * @mixin \Eloquent
 */
class AgentInfo extends Base
{
    protected $table = "a_agent_extra_info";
    protected $guarded = ['id', 'create_time', 'updated_time'];


}
