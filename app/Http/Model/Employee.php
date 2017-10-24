<?php

namespace App\Http\Model;


class Employee extends Base
{
    protected $table = "a_agent_emp";
    protected $guarded = ['id', 'create_time', 'updated_time'];
    protected $hidden = ['password'];


    public function agent()
    {
        return $this->belongsTo('App\Http\Model\Agent');
    }

    public function role()
    {
        return $this->belongsTo('App\Http\Model\Role');
    }

    public function percentages()
    {
        return $this->hasMany('App\Http\Model\EmployeeProfitRateConfig', 'employee_id');
    }
}
