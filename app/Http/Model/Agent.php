<?php

namespace App\Http\Model;


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
        return $this->hasMany('App\User', 'agent_id', 'id');
    }

    public function employees()
    {

        return $this->hasMany('\App\Http\Model\Employee', 'agent_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne('\App\Http\Model\Agent', 'id', 'parent_id');
    }

    public function info()
    {
        return $this->hasOne('\App\Http\Model\AgentInfo', 'id', 'id');
    }
}
