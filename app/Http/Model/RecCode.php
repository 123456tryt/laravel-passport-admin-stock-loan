<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class RecCode extends Base
{
    protected $table = "u_rec_code";

    protected $fillable = ["user_type", "user_id", "rec_code"];

}