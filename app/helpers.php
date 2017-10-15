<?php

use App\Http\Model\RecCode;
use Lcobucci\JWT\Parser;
use App\Http\Model\Agent;

/**
 * 创建推荐码
 */
if (!function_exists("createRecCode")) {
    function createRecCode($length = 10)
    {
        $str = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $count = strlen($str);

        $code = "";
        for ($i = 0; $i < $length; $i++) {
            $code .= $str[rand(0, $count - 1)];
        }
        return RecCode::where("rec_code", $code)->first() ? createCode($length) : $code;
    }
}

/**
 * 获取ip信息，调淘宝ip接口
 */
if (!function_exists("getIpInfo")) {
    function getIpInfo($ip)
    {
        $client = new \GuzzleHttp\Client();
        $res = $client->request("GET", "http://ip.taobao.com/service/getIpInfo.php?ip={$ip}");
        $res = json_decode($res->getBody(), true);
        return $res["code"] === 0 ? $res["data"] : false;
    }
}

/**
 * 登录
 */
if (!function_exists("apiLogin")) {
    function apiLogin($username, $password)
    {
        $request = request();
        $request->request->add([
            'grant_type' => "password",
            'client_id' => "2",
            'client_secret' => "cPAZO6gdD6wUt60nCr2p7mQLyfJo6CXTMhBiAThl",
            'username' => $username,
            'password' => $password,
            'scope' => ''
        ]);
        $proxy = Request::create(
            'oauth/token',
            'POST'
        );

        $ret = json_decode(\Route::dispatch($proxy)->getContent(), true);
        if ($ret && isset($ret['access_token'])) {
            return $ret;
        }
        return false;
    }
}

/**
 * 解析登录token
 */
if (!function_exists("parsePassportAuthorization")) {
    function parsePassportAuthorization($request)
    {
        $authorization = $request->header("Authorization");
        $jwt = trim(preg_replace('/^(?:\s+)?Bearer\s/', '', $authorization));
        try {
            $token = (new Parser())->parse($jwt);
            $data = [
                "sub" => $token->getClaim("sub"),
                "jti" => $token->getClaim("jti"),
                //要其他数据自己取
            ];
        } catch (\Exception $e) {
            return false;
        }

        return $data;
    }
}

/**
 * 密码加密
 */
if (!function_exists("encryptPassword")) {
    function encryptPassword($password)
    {
        return md5(md5(md5(md5($password))));
    }
}

/**
 * 获取默认代理
 */
if (!function_exists("getDefaultAgent")) {
    function getDefaultAgent()
    {
        $defaultAgent = Agent::where("agent_level", 1)->first();
        return $defaultAgent;
    }
}

/**
 * 获取请求当前代理
 */
if (!function_exists("getAgent")) {
    function getAgent()
    {
        $user = request()->user();
        $agentId = 0;
        if ($user) {
            $relation = \App\Http\Model\MemberAgentRelation::where("cust_id", $user->id)->first();
            $agentId = $relation->direct_agent_id;
        }

        if ($agentId) {
            $ret = \App\Http\Model\AgentExtraInfo::where("id", $agentId)->first();
        } else {
            $host = request()->header("Host");
            $ret = \App\Http\Model\AgentExtraInfo::where("web_domain", $host)->orWhere("mobile_domain", $host)->first();
        }

        return $ret;
    }
}