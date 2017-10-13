<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class MsgCheck extends Base
{
    protected $table = 'u_msg_check';
    public $timestamps = false;

    protected $fillable = ["cellphone", "check_code", "create_time", "invalid_time", "type_remark"];

}