<?php

namespace App\Http\Model;


class Employee extends Base
{
    protected $table = "a_agent_emp";
    protected $guarded = ['id', 'create_time', 'updated_time'];


}
