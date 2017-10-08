<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
use \Lcobucci\JWT\Parser;

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
            $parameter = $request->all();
            $data = [
                "cust_id" => 0,
                "url" => $url,
                "referer" => (string)$request->header("Referer"),
                "create_time" => date("Y-m-d H:i:s"),
                "parameter" => json_encode($parameter),
            ];
            //记录用户id
            $jwtInfo = parsePassportAuthorization($request);
            if ($jwtInfo) {
                $data["cust_id"] = $jwtInfo["sub"];
            }

            Redis::command("RPUSH", ["postApiLog", serialize($data)]);

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
