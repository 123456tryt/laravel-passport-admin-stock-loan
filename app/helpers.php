<?php

use App\Http\Model\RecCode;

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

if (!function_exists("getIpInfo")) {
    function getIpInfo($ip)
    {
        $client = new \GuzzleHttp\Client();
        $res = $client->request("GET", "http://ip.taobao.com/service/getIpInfo.php?ip={$ip}");
        $res = json_decode($res->getBody(), true);
        return $res["code"] === 0 ? $res["data"] : false;
    }
}

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

        return \Route::dispatch($proxy);
    }
}
