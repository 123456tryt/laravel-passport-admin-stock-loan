<?php

namespace App\Http\Model;


/**
 * 推荐码
 * App\Http\Model\RecommendCode
 */
class RecommendCode extends Base
{
    const TYPE_CLIENT = 0;
    const TYPE_EMPLOYEE = 1;
    const TYPE_AGENT = 2;
    protected $table = "u_rec_code";
    protected $guarded = ['id', 'create_time', 'updated_time'];

}
