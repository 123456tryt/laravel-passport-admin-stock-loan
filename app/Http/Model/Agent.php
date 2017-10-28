<?php

namespace App\Http\Model;

use App\User;


/**
 * App\Http\Model\Agent
 */
class Agent extends Base
{
    protected $table = "a_agent";
    protected $guarded = ['id', 'create_time', 'updated_time'];


    /**
     * 代理机构 拥有的登录用户
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function users()
    {
        return $this->hasMany(User::Class, 'agent_id', 'id');
    }

    public function employees()
    {

        return $this->hasMany(Employee::Class, 'agent_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne(self::Class, 'id', 'parent_id');
    }

    public function info()
    {
        return $this->hasOne(AgentInfo::Class, 'id', 'id');
    }

    public function percentages()
    {
        return $this->hasMany(AgentProfitRateConfig::Class, 'agent_id', 'id');

    }
}
