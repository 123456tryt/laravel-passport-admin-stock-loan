<?php

namespace App\Http\Model;

class SystemParams extends Base
{
    protected $table = "s_system_params";

    public $timestamps = false;
    protected $guards = ['id'];

}
