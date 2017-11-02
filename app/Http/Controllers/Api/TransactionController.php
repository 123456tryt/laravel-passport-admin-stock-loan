<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private $transaction = null;

    public function __construct(TransactionRepository $transaction)
    {
        $this->middleware(["auth:api", "App\Http\Middleware\UserForbidden"]);
        $this->transaction = $transaction;
    }

    /**
     * 委托买入
     */
    public function entrustBuy(Request $request)
    {

    }

    /**
     * 委托卖出
     */
    public function entrustCell(Request $request)
    {

    }

    /**
     * 撤销委托卖出
     */
    public function cancelEntrustCell(Request $request)
    {

    }

    /**
     * 撤销委托买入
     */
    public function cancelEntrustBuy(Request $request)
    {

    }

    /**
     * 获得统计
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCount(Request $request)
    {
        $user = $request->user();
        $validator = \Validator::make($request->all(), [
            "id" => "required|integer|min:1"
        ], [
            "id错误"
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        $ret = $this->transaction->getCount($user, $request->get("id"));
        return $ret ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, "查询错误");
    }

    /**
     * 获取子账户持仓列表
     * @param Request $request
     */
    public function getHoldingsList(Request $request)
    {
        $user = $request->user();
        $validator = \Validator::make($request->all(), [
            "id" => "required|integer|min:1"
        ], [
            "id错误"
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }
        $ret = $this->transaction->getHoldingsList($user, $request->get("id"));
        return $ret ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, "查询错误");
    }

    /**
     * 获取用户股票池
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStockPool(Request $request)
    {
        $ret = $this->transaction->getStockPool($request->user());
        return $ret !== false ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, "查询错误");
    }

    /**
     * 添加股票进股票池
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addStockToPool(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "stock_code" => "required|numeric"
        ], [
            "请输入正确的证券代码"
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        $ret = $this->transaction->addStockToPool($request->user(), $request->get("stock_code"));
        return $ret ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, $this->transaction->getErrorMsg() ?:
                "添加错误");
    }

    /**
     * 删除股票池股池
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delStockFromPool(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "id" => "required|int|min:1"
        ], [
            "证券代码id错误"
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        $ret = $this->transaction->delStockFromPool($request->user(), $request->get("id"));
        return $ret ? parent::jsonReturn([], parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, $this->transaction->getErrorMsg() ?: "
            删除错误");
    }

    /**
     * 获取模糊搜索股票信息
     * @param Request $request
     */
    public function getStockList(Request $request)
    {
        $stockCode = $request->get("stock_code");
        if (strlen($stockCode) < 2) {
            return parent::jsonReturn([], parent::CODE_FAIL, "");
        }

        $data = getStockInfo($request->get("stock_code"));
        return parent::jsonReturn($data, parent::CODE_SUCCESS, "success");
    }

    /**
     * 获取可撤单的委托
     * @param Request $request
     */
    public function getRevocableEntrustList(Request $request)
    {
        $ret = $this->transaction->getTodayEntrustList($request->user(), $entrustStatus = [1, 2], $custStatus = [2]);
        return $ret ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, $this->transaction->getErrorMsg() ?: "
            获取错误");
    }

    /**
     * 获取资金流水
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetails(Request $request)
    {
        $ret = $this->transaction->getDetails($request->user());
        return $ret !== false ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, "data error");
    }

    /**
     * 获取当日委托
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEntrustList(Request $request)
    {
        $ret = $this->transaction->getTodayEntrustList($request->user(), $entrustStatus = [], $custStatus = []);
        return $ret ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, $this->transaction->getErrorMsg() ?: "
            获取错误");
    }

    /**
     * 获取当日成交
     * @param Request $request
     */
    public function getMakedealList(Request $request)
    {
        $ret = $this->transaction->getMakedealList($request->user(), $isToday = true);
        return $ret ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, $this->transaction->getErrorMsg() ?: "
            获取错误");
    }

    /**
     * 获取历史成交
     * @param Request $request
     */
    public function getMakedealListHistory(Request $request)
    {
        $ret = $this->transaction->getMakedealList($request->user(), $isToday = false);
        return $ret ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, $this->transaction->getErrorMsg() ?: "
            获取错误");
    }
}
