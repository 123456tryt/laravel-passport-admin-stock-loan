<?php

namespace App\Http\Model;


/**
 * App\Http\Model\Role
 */
class Role extends Base
{
    protected $table = "s_system_role";
    protected $guarded = ['id'];
    public $timestamps = false;


    const ROLE_ADMIN_SYSTEM = 1;
    const ROLE_ADMIN_AGENT = 2;


}
