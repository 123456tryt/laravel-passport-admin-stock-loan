<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    'prefix' => 'v1',
    'middleware' => ['api']
], function () {

    Route::post("/createCaptcha", "Api\CaptchaController@generateCaptcha");
    Route::post("/verifyCaptcha", "Api\CaptchaController@verifyCaptcha");
    //获取认证用户信息
    Route::post("/userInfo", "Api\UserController@info");


    //代理商相关
    Route::post("/agentCreate", "Api\AgentController@createAgent");//代理商 创建
    Route::post("/agentSelectorList", "Api\AgentController@selectorOptionsList");//代理商下拉 搜索
    Route::get("/agentList", "Api\AgentController@list"); //代理商列表
    Route::post("/agentInfo", "Api\AgentController@info");//代理商详细信息
    Route::post("/agentChangeAdminPassword", "Api\AgentController@changeAgentAdminUserPassword");//修改代理商管理员密码
    Route::post("/agentChangeBasic", "Api\AgentController@updateAgentBasic");//修改代理商基本信息
    Route::post("/agentChangeInfo", "Api\AgentController@updateAgentInfo");//修改代理附加信息
    Route::post("/agentChangePercentage", "Api\AgentController@updateAgentPercentage");//修改代理商分成比例配置
    Route::get("/agentMoneyPeakList", "Api\AgentPeakController@list");//代理商峰值


    //代理商佣金分成
    Route::get('/agentCommissionList', 'Api\AgentCommissionController@list');//代理商利息佣金

    Route::get('/agentCashList', 'Api\AgentCashController@list');//代理商提现记录 for 审核

    Route::post('/recommendCode', 'Api\RecommentCodeController@info');//获取代理商或者员工的推荐码


    //代理商员工列表 分页 搜索员工
    Route::get("/employeeList", "Api\EmployeeController@list");
    Route::post("/employeeCreate", "Api\EmployeeController@create");
    Route::post("/employeeInfo", "Api\EmployeeController@info");
    Route::post("/employeeUpdate", "Api\EmployeeController@update");

    //客户相关
    Route::get("/clientList", "Api\ClientController@list");//客户列表
    Route::post("/clientInfo", "Api\ClientController@info");//客户详情
    Route::post("/clientUpdate", "Api\ClientController@update");//客户更新
    Route::post("/agentEmployeeSelectorList", "Api\ClientController@getSwapClientHeritSelectorList");//跟换客户关系,下拉刷新
    Route::post("/swapClientHeritRelation", "Api\ClientController@changeClientAgentEmployeeRelations");//更改客户关系


    //用户账户余额调整
    Route::post("/clientAcountFlowAdjust", "Api\ClientFlowController@clientAcountFlowAdjust");
    Route::get("/clientAccountList", "Api\ClientFlowController@list");


    //银行卡信息
    Route::get("/bankCardList", "Api\ClientBankCardController@list");//列表
    Route::post("/bankCardUpdate", "Api\ClientBankCardController@update");//更新
    Route::post("/bankCardInfo", "Api\ClientBankCardController@info");//获取单挑银行卡信息

    Route::get("/withdrawList", "Api\ClientWithdrawController@list");
    Route::post("/withdrawUpdate", "Api\ClientWithdrawController@update");
    Route::post("/withdrawInfo", "Api\ClientWithdrawController@info");


    //客户充值列表
    Route::get("/clientRechargeList", "Api\ClientRechargeController@list");

    //用户账户充值
    Route::post("/clientAcountRecharge", "Api\ClientRechargeController@clientAcountRecharge");


    //系统设置
    Route::post("/systemParams", "Api\System\SystemParamsController@index");
    Route::post("/systemParam/update/{id?}", "Api\System\SystemParamsController@update");
    Route::post("/systemParam/{id?}", "Api\System\SystemParamsController@show");
    //节假日维护
    Route::post("/holidayMaintain/update/{id}", "Api\System\HolidayMaintainController@update");
    Route::post("/holidayMaintain/destroy/{id}", "Api\System\HolidayMaintainController@destroy");
    Route::post("/holidayMaintains", "Api\System\HolidayMaintainController@index");
    Route::post("/holidayMaintain/store", "Api\System\HolidayMaintainController@store");
    Route::post("/holidayMaintain/{id}", "Api\System\HolidayMaintainController@show");
    //股票配资产品
    Route::post("/stockFinanceProduct/update/{id}", "Api\System\StockFinanceProductController@update");
    Route::post("/stockFinanceProduct/destroy/{id}", "Api\System\StockFinanceProductController@destroy");
    Route::post("/stockFinanceProducts", "Api\System\StockFinanceProductController@index");
    Route::post("/stockFinanceProduct/store", "Api\System\StockFinanceProductController@store");
    Route::post("/stockFinanceProduct/{id}", "Api\System\StockFinanceProductController@show");
    //股票信息
    Route::post("/stockInfo/update/{id}", "Api\System\StockInfoController@update");
    Route::post("/stockInfo/destroy/{id}", "Api\System\StockInfoController@destroy");
    Route::post("/stockInfos", "Api\System\StockInfoController@index");
    Route::post("/stockInfo/store", "Api\System\StockInfoController@store");
    Route::post("/stockInfo/{id}", "Api\System\StockInfoController@show");
    //费用标准
    Route::post("/stockFee/update/{id}", "Api\System\StockFeeController@update");
    Route::post("/stockFees", "Api\System\StockFeeController@index");
    Route::post("/stockFee/store", "Api\System\StockFeeController@store");
    Route::post("/stockFee/{id}", "Api\System\StockFeeController@show");
    //风控规则
    Route::post("/transRistControlRule/update/{id}", "Api\System\TransRistControlRuleController@update");
    Route::post("/transRistControlRules", "Api\System\TransRistControlRuleController@index");
    Route::post("/transRistControlRule/store", "Api\System\TransRistControlRuleController@store");
    Route::post("/transRistControlRule/{id}", "Api\System\TransRistControlRuleController@show");


    //操盘账户（子账户/以及风控管理）
    Route::post("/uStockFinancing/update/{id}", "Api\System\UStockFinancingController@update");
    Route::post("/uStockFinancings", "Api\System\UStockFinancingController@index");
    Route::post("/uStockFinancing/{id}", "Api\System\UStockFinancingController@show");
    //风控日志管理
    Route::post("/stockFinanceRiskLogs", "Api\System\StockFinanceRiskLogController@index");
    Route::post("/stockFinanceRiskLog/{id}", "Api\System\StockFinanceRiskLogController@show");
    //子账户流水记录（配资记录）
    Route::post("/uStockFinancingFlows", "Api\System\UStockFinancingFlowController@index");
    Route::post("/uStockFinancingFlow/{id}", "Api\System\UStockFinancingFlowController@show");
    //配资付息记录
    Route::post("/uStockFinanceInterestPercentages", "Api\System\UStockFinanceInterestPercentageController@index");
    Route::post("/uStockFinanceInterestPercentage/{id}", "Api\System\UStockFinanceInterestPercentageController@show");
    //业务审核（结算审核）
    Route::post("/uStockFinanceFettleup", "Api\System\UStockFinanceFettleup@index");
    Route::post("/uStockFinanceFettleup/{id}", "Api\System\UStockFinanceFettleup@show");
    //除权降息管理
    Route::post("/xrDrInfos", "Api\System\XrDrInfoController@index");
    Route::post("/xrDrInfo/{id}", "Api\System\XrDrInfoController@show");
    //资金池管理
    Route::post("/capitalPool/update/{id}", "Api\System\CapitalPoolController@update");
    Route::post("/capitalPools", "Api\System\CapitalPoolController@index");
    Route::post("/capitalPool/store", "Api\System\CapitalPoolController@store");
    Route::post("/capitalPool/{id}", "Api\System\CapitalPoolController@show");
    //操盘母账户管理
    Route::post("/parentStockFinance/update/{id}", "Api\System\ParentStockFinanceController@update");
    Route::post("/parentStockFinances", "Api\System\ParentStockFinanceController@index");
    Route::post("/parentStockFinance/store", "Api\System\ParentStockFinanceController@store");
    Route::post("/parentStockFinance/{id}", "Api\System\ParentStockFinanceController@show");


    //管理后台用户相关
    Route::post('/logout', "Api\UserController@logoutApi");//注销
    Route::post('/rolePlayIssueToken', "Api\UserController@rolePlayIssueToken");//扮演用户
    Route::get("/userList", "Api\UserController@list");//用户列表


});
