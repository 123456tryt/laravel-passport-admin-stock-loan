<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class PostApiLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //是post方法就记录相关日志
        if ($request->isMethod("post")) {
            $url = $request->getUri();
            $param = $request->all();
            $data = [
                "url" => $url,
                "referer" => $request->header("Referer"),
                "time" => time(),
                "param" => $param,
                "token" => $request->header("Authorization")
            ];
            Redis::command("RPUSH", ["postApiLog", json_encode($data)]);

            $urlDatas = Redis::get("postUrlLog");
            $urlDataList = $urlDatas ? unserialize($urlDatas) : [];
            if (isset($urlDataList[$url])) {
                $urlDataList[$url] += 1;
            } else {
                $urlDataList[$url] = 1;
            }
            Redis::set("postUrlLog", serialize($urlDataList));
        }

        return $next($request);
    }
}
