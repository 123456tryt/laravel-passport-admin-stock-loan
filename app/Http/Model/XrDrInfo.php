<?php

namespace App\Http\Model;

class XrDrInfo extends Base
{
    protected $table = "s_xr_dr_info";

    protected $guarded = ['id', 'created_time', 'updated_time'];
}
