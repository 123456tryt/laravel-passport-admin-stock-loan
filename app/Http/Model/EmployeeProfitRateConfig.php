<?php

namespace App\Http\Model;


class EmployeeProfitRateConfig extends Base
{
    protected $table = "a_agent_emp_percentage_setting";
    const TypeDay = 0;
    const TypeMonth = 1;
    const TypeCommissionOne = 2;
    const TypeCommissionTwo = 3;
    const TypeCommissionThree = 4;
    protected $guarded = ['id', 'create_time', 'updated_time'];


}
