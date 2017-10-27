<?php

use App\Http\Model\RecCode;
use Lcobucci\JWT\Parser;
use App\Http\Model\Agent;
use Illuminate\Support\Facades\Redis;
use OSS\OssClient;

/**
 * 创建推荐码
 */
if (!function_exists("createRecCode")) {
    function createRecCode($length = 6)
    {
        $str = "0123456789";
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
        //TODO: 以后会多个一级代理
        $defaultAgent = \DB::table("a_agent")->leftJoin("a_agent_extra_info", "a_agent.id", "=", "a_agent_extra_info.id")
            ->where("a_agent.agent_level", 1)->first();
        return $defaultAgent;
    }
}

/**
 * 获取请求当前代理(只区分独立代理商、一级代理商)
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
            $ret = \DB::table("a_agent")->leftJoin("a_agent_extra_info", "a_agent.id", "=", "a_agent_extra_info.id")
                ->where("a_agent.id", $agentId)->where("a_agent.is_independent", 1)->first();
        } else {
            $host = request()->header("Host");
            $ret = \DB::table("a_agent")->leftJoin("a_agent_extra_info", "a_agent.id", "=", "a_agent_extra_info.id")
                ->where("a_agent.is_independent", 1)->where("a_agent_extra_info.web_domain", $host)
                ->orWhere("a_agent_extra_info.mobile_domain", $host)->first();
        }

        if (!$ret) {
            $ret = getDefaultAgent();
        }

        return $ret;
    }
}

/**
 * 隐藏字符串中间部分
 */
if (!function_exists("half_replace")) {
    function half_replace($str)
    {
        $len = ceil(mb_strlen($str, "utf8") / 2);
        $prefix = mb_substr($str, 0, ceil($len / 2), "utf8");
        $suffix = mb_substr($str, $len + ceil($len / 2), null, "utf8");
        return $prefix . str_repeat("*", $len) . $suffix;
    }
}

if (!function_exists("getStockInfo")) {
    function getStockInfo($code)
    {
        $name = "stockmarket";
        $keys = Redis::hkeys($name);
        $keyList = [];
        foreach ($keys as $v) {
            if (strpos($v, (string)$code) !== false) $keyList[] = $v;
        }
        $dataList = $keyList ? Redis::hmGet($name, $keyList) : [];
        $newData = [];
        array_walk($dataList, function ($v, $k) use (&$newData) {
            if ($t = json_decode($v)) $newData[] = $t;
        });
        return $newData;
    }
}

if (!function_exists("requestJava")) {
    function requestJava($url, $data)
    {
        $client = new \GuzzleHttp\Client();
        $url = $url . "?" . http_build_query($data);
        $res = $client->request("GET", $url);
        $res = json_decode($res->getBody(), true);
        return $res;
    }
}

if (!function_exists("num_to_rmb")) {
    function num_to_rmb($num)
    {
        $c1 = "零壹贰叁肆伍陆柒捌玖";
        $c2 = "分角元拾佰仟万拾佰仟亿";
        //精确到分后面就不要了，所以只留两个小数位
        $num = round($num, 2);
        //将数字转化为整数
        $num = $num * 100;
        if (strlen($num) > 10) {
            return "金额太大，请检查";
        }
        $i = 0;
        $c = "";
        while (1) {
            if ($i == 0) {
                //获取最后一位数字
                $n = substr($num, strlen($num) - 1, 1);
            } else {
                $n = $num % 10;
            }
            //每次将最后一位数字转化为中文
            $p1 = substr($c1, 3 * $n, 3);
            $p2 = substr($c2, 3 * $i, 3);
            if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
                $c = $p1 . $p2 . $c;
            } else {
                $c = $p1 . $c;
            }
            $i = $i + 1;
            //去掉数字最后一位了
            $num = $num / 10;
            $num = (int)$num;
            //结束循环
            if ($num == 0) {
                break;
            }
        }
        $j = 0;
        $slen = strlen($c);
        while ($j < $slen) {
            //utf8一个汉字相当3个字符
            $m = substr($c, $j, 6);
            //处理数字中很多0的情况,每次循环去掉一个汉字“零”
            if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
                $left = substr($c, 0, $j);
                $right = substr($c, $j + 3);
                $c = $left . $right;
                $j = $j - 3;
                $slen = $slen - 3;
            }
            $j = $j + 3;
        }
        //这个是为了去掉类似23.0中最后一个“零”字
        if (substr($c, strlen($c) - 3, 3) == '零') {
            $c = substr($c, 0, strlen($c) - 3);
        }
        //将处理的汉字加上“整”
        if (empty($c)) {
            return "零元整";
        } else {
            return $c . "整";
        }
    }
}

if (!function_exists("formatMoney")) {
    function formatMoney($money)
    {
        return sprintf("%.2f", round((float)$money, 2));
    }
}

if (!function_exists("ossUpload")) {
    function ossUpload($object, $content, $type = "")
    {
        $accessKeyId = "LTAI88B5zjDXRpPq";
        $accessKeySecret = " txb80snXCJrpBEnTupDlfd8Kbiw6k0";
        $endpoint = OSS_END_POINT_URL;
        $bucket = "yingli";
        $object = $object;
        $content = $content;
        if ($type) {
            $object = $type . "/" . $object;
        }
        try {
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $ossClient->putObject($bucket, $object, $content);
        } catch (OssException $e) {
            return false;
        }

        return OSS_BUCKET_URL . "/" . $object;
    }
}

function caclTransactionDays($startTime, $changeDayNum)
{
    if ($changeDayNum == 0) return $startTime;

    $changeTime = $changeDayNum * 24 * 3600;
    //误差时间
    $mistakeDay = ceil($changeDayNum / 3) + 30;
    if ($changeDayNum < 0) {
        $endTime = $startTime;
        $startTime = $startTime - $mistakeDay * 3600 * 24;
    } else {
        $endTime = $startTime + $mistakeDay * 3600 * 24;
    }
    $date = [];
    $tmpTime = $startTime;
    for ($i = 0; $i < abs($changeDayNum) + $mistakeDay - 2; $i++) {
        $date[] = date("Y-m-d", $tmpTime);
        $tmpTime += 3600 * 24;
    }
    $holidays = DB::table("s_holiday_maintain")->where("holiday", ">", $date[0])
        ->where("holiday", "<=", $date[count($date) - 1])->get();
    foreach ($holidays as $holiday) {
        $keys = array_keys($date, $holiday->holiday);
        if (isset($keys[0])) unset($date[$keys[0]]);
    }
    $date = array_values($date);

    return $changeDayNum > 0 ? $date[$changeDayNum] : $date[count($date) - 1 - abs($changeDayNum)];
}

