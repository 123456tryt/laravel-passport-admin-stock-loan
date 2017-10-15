<?php

namespace App\Http\Model;


class Employee extends Base
{
    protected $table = "a_agent_emp";
    protected $guarded = ['id', 'create_time', 'updated_time'];


    public function user()
    {
        return $this->hasOne('App\User', 'employee_id');
    }

    public function percentages()
    {
        return $this->hasMany('App\Http\Model\EmployeeProfitRateConfig', 'employee_id');
    }
}
