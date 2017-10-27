<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class WexingConcern extends Base
{
    protected $table = "u_wexing_concern";

    protected $fillable = ["rec_code", "open_id", "appid", "nick_name", "head_pic", "cancel_time", "is_concern"];

}