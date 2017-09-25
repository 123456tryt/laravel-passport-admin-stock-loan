<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\User;
use Validator;


class FooController extends ApiController
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }

    public function say()
    {
        return "hello world";
    }
}