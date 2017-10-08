<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuthController 用户名注册登陆相关信息
 * @package App\Http\Controllers\Api
 */
class NavMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }

    //和vuejs-router 对应
    const MENU_TOP_SYS = 'sys';
    const MENU_SUB_SYS = 'top';


    const MENU_TOP_STAFF = 'staff';


    const MENU_TOP_USER = 'user';

    const MENU_TOP_BIZ = 'biz';


    const MENU_TOP_SHORT = 'flash';


    const MENU_TOP_STAT = 'statistic';


    public function getMenu()
    {
        $user = Auth::user();
        return $user;
    }


}