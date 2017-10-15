<?php

namespace App\Http\Model;

class Client extends Base
{
    protected $table = "u_customer";

    public $timestamps = false;
    protected $guarded = ['id', 'created_time', 'updated_time'];

}
