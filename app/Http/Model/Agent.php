<?php

namespace App\Http\Model;


class Agent extends Base
{
    protected $table = "a_agent";


    /**
     * 代理机构 拥有的登录用户
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function users()
    {
        return $this->hasMany('App\User', 'agent_id', 'id');
    }

}
