<?php

namespace App\Http\Controllers\Api\System;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

/**
 * Class SystemController 系统代理商
 * @package App\Http\Controllers\Api
 */
class JavaApiController extends Controller
{

    public function __construct()
    {
        //$this->middleware("auth:api");
    }

    //子账户一键平仓
    public function eveningUp()
    {
        $Clinet = new Client();
        $params = request()->all();
        if (empty($params['id'])) return;
        $option = [
            'form_params' => [
                'id' => $params['id'],
                'ip' => $this->getClientIp()

            ],
        ];
        $rs = json_decode($Clinet->request('POST', 'http://www.baidu.com/backend/api/1.0/stockfinance/eveningup', $option)->getBody(), true);
        if ($rs['code'] == 200) {
            return self::jsonReturn($rs);
        } else {
            return self::jsonReturn([], 0, '请求失败！');
        }
    }

    //子账户分笔平仓
    public function eveningupPerHolding()
    {
        $Clinet = new Client();
        $params = request()->all();
        if (empty($params['id']) || empty($params['stock_code'])) return;
        $option = [
            'form_params' => [
                'id' => $params['id'],
                'stock_code' => $params['stock_code'],
                'ip' => $this->getClientIp()
            ],
        ];
        $rs = json_decode($Clinet->request('POST', 'http://www.baidu.com/backend/api/1.0/stockfinance/eveningupPerHolding', $option)->getBody(), true);
        if ($rs['code'] == 200) {
            return self::jsonReturn($rs);
        } else {
            return self::jsonReturn([], 0, '请求失败！');
        }
    }

    //手动除权除息
    public function xrdr()
    {
        $Clinet = new Client();
        $params = request()->all();
        if (empty($params['stockFinanceHoldId']) || empty($params['stockCode']) || empty($params['addXrStockAmount']) || empty($params['addDrAmount'])) return;
        $option = [
            'form_params' => [
                'stockFinanceHoldId' => $params['stockFinanceHoldId'],
                'stockCode' => $params['stockCode'],
                'addXrStockAmount' => $params['addXrStockAmount'],
                'addDrAmount' => $params['addDrAmount'],
            ],
        ];
        $rs = json_decode($Clinet->request('POST', 'http://www.baidu.com/backend/api/1.0/xrdr', $option)->getBody(), true);
        if ($rs['code'] == 200) {
            return self::jsonReturn($rs);
        } else {
            return self::jsonReturn([], 0, '请求失败！');
        }
    }

    //获取客户IP
    private function getClientIp()
    {
        $ip = 'unknow';
        foreach (array(
                     'HTTP_CLIENT_IP',
                     'HTTP_X_FORWARDED_FOR',
                     'HTTP_X_FORWARDED',
                     'HTTP_X_CLUSTER_CLIENT_IP',
                     'HTTP_FORWARDED_FOR',
                     'HTTP_FORWARDED',
                     'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER)) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    //会过滤掉保留地址和私有地址段的IP，例如 127.0.0.1会被过滤
                    //也可以修改成正则验证IP
                    if ((bool)filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                        return $ip;
                    }
                }
            }
        }
        return $ip;
    }


}