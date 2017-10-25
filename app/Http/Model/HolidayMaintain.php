<?php

namespace App\Http\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class HolidayMaintain extends Base
{
    use SoftDeletes;

    protected $table = "s_holiday_maintain";

    protected $guarded = ['id', 'deleted_at', 'created_time', 'updated_time'];

    protected $dates = ['deleted_at'];
}
