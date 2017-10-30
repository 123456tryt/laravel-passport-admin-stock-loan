<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use Closure;

class UserForbidden
{
    /**
     * 检测禁用用户中间件
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Contracts\Routing\ResponseFactory|mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if ($user) {
            if ($user->is_login_forbidden == 1) return response('', 401);
        }

        return $next($request);
    }
}
