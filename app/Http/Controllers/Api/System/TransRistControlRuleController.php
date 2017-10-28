<?php

namespace App\Http\Controllers\Api\System;

use App\Http\Controllers\Controller;

class TransRistControlRuleController extends Controller
{
    use \App\Http\Controllers\Load\ShowTrait, \App\Http\Controllers\Load\UpdateTrait, \App\Http\Controllers\Load\StoreTrait;

    public static $model_name = 'TransRistControlRule';

    public function __construct()
    {
        $this->middleware("auth:api");
    }
}