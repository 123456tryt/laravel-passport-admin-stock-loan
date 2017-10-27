<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\StockFinanceProducts;
use App\Http\Model\StockFinancing;
use App\User;
use Illuminate\Http\Request;
use App\Repositories\StockFinanceRepository;

class StockFinanceController extends Controller
{
    const JAVA_URL = "http://10.10.1.101:8000/finances-center";
    const STOCK_FINANCE_URL = self::JAVA_URL . "/trade/api/1.0/stockfinance";
    const POST_FINANCE_CAUTION_MONEY_URL = self::JAVA_URL . "/trade/api/1.0/postFinanceCautionMoney";
    const POST_ADD_CAUTION_MONEY_URL = self::JAVA_URL . "/trade/api/1.0/postAddCautionMoney";
    const MODIFY_AUTO_SUPPLY_CAUTION_MONEY_URL = self::JAVA_URL . "/trade/api/1.0/modifyAutoSupplyCautionMoney";
    const GET_ALLOW_MAX_EXTRACT_PROFIT_URL = self::JAVA_URL . "/trade/api/1.0/getAllowMaxExtractProfit";
    const EXTRACT_PROFIT_URL = self::JAVA_URL . "/trade/api/1.0/extractProfit";
    const REPAY_URL = self::JAVA_URL . "/trade/api/1.0/stockfinance/repay";
    const SETTLEUP_URL = self::JAVA_URL . "/trade/api/1.0/stockfinance/settleup";
    private $stockFinance = null;

    public function __construct(StockFinanceRepository $stockFinance)
    {
        $this->stockFinance = $stockFinance;
    }

    /**
     * 获取配资产品
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProducts()
    {
        $agent = getAgent();
        $ret = $this->stockFinance->getProducts($agent);
        return $ret ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, "查询错误");
    }

    /**
     * 获取配资列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStockFinances(Request $request)
    {
        $user = $request->user();
        $ret = $this->stockFinance->getStockFinances($user);
        return $ret ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, "查询错误");
    }

    /**
     * 获取配资详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStockFinance(Request $request)
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

        $ret = $this->stockFinance->getStockFinance($user, $request->get("id"));
        return $ret ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, "查询错误");
    }

    /**
     * 申请配资
     */
    public function stockFinance(Request $request)
    {
        $user = $request->user();
        $validator = \Validator::make($request->all(), [
            "productId" => "required|integer|min:1",
            "investMoney" => "required|numeric",
            "autoCautionMoney" => "required|integer|between:0,1",
        ], [
            "productId.required" => "请选择配资产品",
            "productId.integer" => "配资产品错误",
            "productId.min" => "配资产品错误",
            "investMoney.required" => "投入金额不能为空",
            "investMoney.numberic" => "投入金额不正确",
            "autoCautionMoney.required" => "请选择是否自动追加保证金",
            "autoCautionMoney.integer" => "是否自动追加保证金错误",
            "autoCautionMoney.between" => "是否自动追加保证金错误",
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        if ($user->is_stock_finance_forbidden) {
            return parent::jsonReturn([], parent::CODE_FAIL, "用户暂时无法配资");
        }

        if (!$user->real_name) {
            return parent::jsonReturn([], parent::CODE_FAIL, "请先实名认证再进行配资");
        }

        $data = [
            "custId" => $user->id,
            "productId" => $request->get("productId"),
            "investMoney" => $request->get("investMoney"),
            "autoCautionMoney" => $request->get("autoCautionMoney") ? "true" : "false",
        ];

        $ret = requestJava(self::STOCK_FINANCE_URL, $data);
        if (isset($ret["code"]) && $ret["code"]) {
            $financeId = $ret["data"]["stockFinance_id"];
            $this->stockFinance->makeContract($user, $request->get("productId"), $request->get("investMoney"), false,
                $financeId);
        }
        return isset($ret["code"]) && $ret["code"] ? parent::jsonReturn([], parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, $ret["msg"]);
    }

    public function stockFinanceOfFree(Request $request)
    {
        $user = $request->user();

        $freeProduct = StockFinanceProducts::where("product_type", 3)->where("product_times", 20)->where("disable", 0)
            ->first();
        if (!$freeProduct) {
            return parent::jsonReturn([], parent::CODE_FAIL, "产品信息错误");
        }

        $data = [
            "custId" => $user->id,
            "productId" => $freeProduct->id,
            "investMoney" => "100",
            "autoCautionMoney" => "false",
        ];

        $ret = requestJava(self::STOCK_FINANCE_URL, $data);
        return isset($ret["code"]) && $ret["code"] ? parent::jsonReturn([], parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, $ret["msg"]);
    }

    /**
     * 追加配资
     */
    public function postFinanceCautionMoney(Request $request)
    {
        $user = $request->user();
        $validator = \Validator::make($request->all(), [
            "id" => "required|integer|min:1",
            "postFinanceCautionMoney" => "required|numeric|min:100",
        ], [
            "id.required" => "配资信息错误",
            "id.integer" => "配资信息错误",
            "id.min" => "配资信息错误",
            "postFinanceCautionMoney.required" => "追配金额不能为空",
            "postFinanceCautionMoney.numberic" => "最少追加100元",
            "postFinanceCautionMoney.min" => "最少追加100元",
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        $postFinanceCautionMoney = $request->get("postFinanceCautionMoney");
        if ($postFinanceCautionMoney % 100 != 0) {
            return parent::jsonReturn([], parent::CODE_FAIL, "追配金额必须为100的倍数");
        }

        if ($user->is_stock_finance_forbidden) {
            return parent::jsonReturn([], parent::CODE_FAIL, "用户暂时无法追加配资");
        }

        $data = [
            "custId" => $user->id,
            "id" => $request->get("id"),
            "postFinanceCautionMoney" => $request->get("postFinanceCautionMoney"),
        ];
        $ret = requestJava(self::POST_FINANCE_CAUTION_MONEY_URL, $data);
        if (isset($ret["code"]) && $ret["code"]) {
            $stockFinanceInfo = StockFinancing::find($request->get("id"));
            $this->stockFinance->makeContract($user, $stockFinanceInfo->product_id, $request->get("postFinanceCautionMoney")
                , false, $request->get("id"));
        }

        return isset($ret["code"]) && $ret["code"] ? parent::jsonReturn([], parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, $ret["msg"]);

    }

    /**
     * 补充保证金
     */
    public function postAddCautionMoney(Request $request)
    {
        $user = $request->user();
        $validator = \Validator::make($request->all(), [
            "id" => "required|integer|min:1",
            "postAddCautionMoney" => "required|numeric|min:10",
        ], [
            "id.required" => "配资信息错误",
            "id.integer" => "配资信息错误",
            "id.min" => "配资信息错误",
            "postAddCautionMoney.required" => "追配保证金不能为空",
            "postAddCautionMoney.numberic" => "最少追加10元",
            "postAddCautionMoney.min" => "最少追加10元",
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        $postFinanceCautionMoney = $request->get("postFinanceCautionMoney");
        if ($postFinanceCautionMoney % 10 != 0) {
            return parent::jsonReturn([], parent::CODE_FAIL, "追配保证金必须为10的倍数");
        }

        if ($user->is_stock_finance_forbidden) {
            return parent::jsonReturn([], parent::CODE_FAIL, "用户暂时无法追加保证金");
        }

        $data = [
            "custId" => $user->id,
            "id" => $request->get("id"),
            "postAddCautionMoney" => $request->get("postAddCautionMoney"),
        ];
        $ret = requestJava(self::POST_ADD_CAUTION_MONEY_URL, $data);

        return isset($ret["code"]) && $ret["code"] ? parent::jsonReturn([], parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, $ret["msg"]);
    }

    /**
     * 修改追加保证金设置
     */
    public function modifyAutoSupplyCautionMoney(Request $request)
    {
        $user = $request->user();
        $validator = \Validator::make($request->all(), [
            "id" => "required|integer|min:1",
            "isAutoSupplyCautionMoney" => "required|integer|between:0,1",
        ], [
            "id.required" => "配资信息错误",
            "id.integer" => "配资信息错误",
            "id.min" => "配资信息错误",
            "isAutoSupplyCautionMoney.required" => "请选择是否自动追加保证金",
            "isAutoSupplyCautionMoney.integer" => "是否自动追加保证金错误",
            "isAutoSupplyCautionMoney.between" => "是否自动追加保证金错误",
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        $data = [
            "custId" => $user->id,
            "stockFinanceId" => $request->get("id"),
            "isAutoSupplyCautionMoney" => $request->get("isAutoSupplyCautionMoney"),
        ];
        $ret = requestJava(self::MODIFY_AUTO_SUPPLY_CAUTION_MONEY_URL, $data);
        return isset($ret["code"]) && $ret["code"] ? parent::jsonReturn([], parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, $ret["msg"]);
    }

    /**
     * 获取子账户最大可提利润
     */
    public function getAllowMaxExtractProfit(Request $request)
    {
        $user = $request->user();
        $validator = \Validator::make($request->all(), [
            "id" => "required|integer|min:1",
        ], [
            "id.required" => "配资信息错误",
            "id.integer" => "配资信息错误",
            "id.min" => "配资信息错误",
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        $data = [
            "custId" => $user->id,
            "stockFinanceId" => $request->get("id"),
        ];
        $ret = requestJava(self::GET_ALLOW_MAX_EXTRACT_PROFIT_URL, $data);
        return isset($ret["code"]) && $ret["code"] ? parent::jsonReturn($ret["data"], parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, $ret["msg"]);
    }

    /**
     * 提取利润
     */
    public function extractProfit(Request $request)
    {
        $user = $request->user();
        $validator = \Validator::make($request->all(), [
            "id" => "required|integer|min:1",
            "extractProfitVal" => "required|numeric",
        ], [
            "id.required" => "配资信息错误",
            "id.integer" => "配资信息错误",
            "id.min" => "配资信息错误",
            "extractProfitVal.required" => "提取利润金额不能为空",
            "extractProfitVal.numberic" => "请填写正确的利润金额",
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        $extractProfitVal = $request->get("extractProfitVal");
        if ($extractProfitVal <= 0) {
            return parent::jsonReturn([], parent::CODE_FAIL, "请输入正确的利润金额");
        }

        $data = [
            "custId" => $user->id,
            "stockFinanceId" => $request->get("id"),
            "extractProfitVal" => $request->get("extractProfitVal"),
        ];
        $ret = requestJava(self::EXTRACT_PROFIT_URL, $data);
        return isset($ret["code"]) && $ret["code"] ? parent::jsonReturn($ret["data"], parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, $ret["msg"]);
    }

    /**
     * 结算配资
     */
    public function settleup(Request $request)
    {
        $user = $request->user();
        $validator = \Validator::make($request->all(), [
            "id" => "required|integer|min:1",
        ], [
            "id.required" => "配资信息错误",
            "id.integer" => "配资信息错误",
            "id.min" => "配资信息错误",
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        $data = [
            "stockFinanceId" => $request->get("id"),
            "custRemark" => $request->get("custRemark"),
        ];
        $ret = requestJava(self::SETTLEUP_URL, $data);
        return isset($ret["code"]) && $ret["code"] ? parent::jsonReturn([], parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, $ret["msg"]);
    }

    /**
     * 获取合同
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContract(Request $request)
    {
        $user = null;
        $auth = parsePassportAuthorization($request);
        if ($auth) {
            $user = User::find($auth["sub"]);
        }
        $ret = $this->stockFinance->makeContract($user, $request->get("productId"), $request->get("investMoney"),
            true);
        return $ret ? parent::jsonReturn(["data" => $ret], parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, "获取失败");
    }

    /**
     * 穿仓与利息抵账
     */
    public function repay()
    {

    }


}
