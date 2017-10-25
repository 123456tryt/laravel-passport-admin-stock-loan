<?php

use Illuminate\Http\Request;

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


    //代理商 创建
    Route::post("/agentCreate", "Api\AgentController@createAgent");
    //代理商下拉 搜索
    Route::post("/agentSelectorList", "Api\AgentController@selectorOptionsList");


    //代理商下拉 搜索/列表
    Route::get("/agentList", "Api\AgentController@list");

    //代理商详细信息
    Route::post("/agentInfo", "Api\AgentController@info");




    //修改代理商管理员密码
    Route::post("/agentChangeAdminPassword", "Api\AgentController@changeAgentAdminUserPassword");

    //修改代理商基本信息
    Route::post("/agentChangeBasic", "Api\AgentController@updateAgentBasic");

    //修改代理附加信息
    Route::post("/agentChangeInfo", "Api\AgentController@updateAgentInfo");

    //修改代理商分成比例配置
    Route::post("/agentChangePercentage", "Api\AgentController@updateAgentPercentage");


    //代理商员工列表 分页 搜索员工
    Route::get("/employeeList", "Api\EmployeeController@list");
    Route::post("/employeeCreate", "Api\EmployeeController@create");
    Route::post("/employeeInfo", "Api\EmployeeController@info");
    Route::post("/employeeUpdate", "Api\EmployeeController@update");


    Route::get("/clientList", "Api\ClientController@list");
    Route::post("/clientUpdate", "Api\ClientController@update");
    //getSwapClientHeritSelectorList
    Route::post("/agentEmployeeSelectorList", "Api\ClientController@getSwapClientHeritSelectorList");

    //changeClientAgentEmployeeRelations
    Route::post("/swapClientHeritRelation", "Api\ClientController@changeClientAgentEmployeeRelations");


    //用户账户余额调整
    Route::post("/clientAcountFlowAdjust", "Api\ClientFlowController@clientAcountFlowAdjust");
    Route::get("/clientAccountList", "Api\ClientFlowController@list");




    //ClientBankCardController
    Route::get("/bankCardList", "Api\ClientBankCardController@list");
    Route::post("/bankCardUpdate", "Api\ClientBankCardController@update");


    Route::get("/withdrawList", "Api\ClientWithdrawController@list");
    Route::post("/withdrawUpdate", "Api\ClientWithdrawController@update");


    //客户充值列表
    Route::get("/clientRechargeList", "Api\ClientRechargeController@list");

    //用户账户充值
    Route::post("/clientAcountRecharge", "Api\ClientRechargeController@clientAcountRecharge");


    //系统设置
    Route::post("/systemParams", "Api\FinancingManage\SystemParamsController@index");
    Route::post("/systemParam/update/{id?}", "Api\FinancingManage\SystemParamsController@update");
    Route::post("/systemParam/{id?}", "Api\FinancingManage\SystemParamsController@show");
    //节假日维护
    Route::post("/holidayMaintain/update/{id}", "Api\FinancingManage\HolidayMaintainController@update");
    Route::post("/holidayMaintain/destroy/{id}", "Api\FinancingManage\HolidayMaintainController@destroy");
    Route::post("/holidayMaintains", "Api\FinancingManage\HolidayMaintainController@index");
    Route::post("/holidayMaintain/store", "Api\FinancingManage\HolidayMaintainController@store");
    Route::post("/holidayMaintain/{id}", "Api\FinancingManage\HolidayMaintainController@show");
    //股票配资产品
    Route::post("/stockFinanceProduct/update/{id}", "Api\FinancingManage\StockFinanceProductController@update");
    Route::post("/stockFinanceProduct/destroy/{id}", "Api\FinancingManage\StockFinanceProductController@destroy");
    Route::post("/stockFinanceProducts", "Api\FinancingManage\StockFinanceProductController@index");
    Route::post("/stockFinanceProduct/store", "Api\FinancingManage\StockFinanceProductController@store");
    Route::post("/stockFinanceProduct/{id}", "Api\FinancingManage\StockFinanceProductController@show");
    //股票信息
    Route::post("/stockInfo/update/{id}", "Api\FinancingManage\StockInfoController@update");
    Route::post("/stockInfo/destroy/{id}", "Api\FinancingManage\StockInfoController@destroy");
    Route::post("/stockInfos", "Api\FinancingManage\StockInfoController@index");
    Route::post("/stockInfo/store", "Api\FinancingManage\StockInfoController@store");
    Route::post("/stockInfo/{id}", "Api\FinancingManage\StockInfoController@show");
    //费用标准
    Route::post("/stockFee/update/{id}", "Api\FinancingManage\StockFeeController@update");
    Route::post("/stockFees", "Api\FinancingManage\StockFeeController@index");
    Route::post("/stockFee/store", "Api\FinancingManage\StockFeeController@store");
    Route::post("/stockFee/{id}", "Api\FinancingManage\StockFeeController@show");
    //母账户管理
    Route::post("/parentStockFinance/update/{id}", "Api\FinancingManage\ParentStockFinanceController@update");
    Route::post("/parentStockFinances", "Api\FinancingManage\ParentStockFinanceController@index");
    Route::post("/parentStockFinance/store", "Api\FinancingManage\ParentStockFinanceController@store");
    Route::post("/parentStockFinance/{id}", "Api\FinancingManage\ParentStockFinanceController@show");

    //资金池管理
    Route::post("/capitalPool/update/{id}", "Api\FinancingManage\CapitalPoolController@update");
    Route::post("/capitalPools", "Api\FinancingManage\CapitalPoolController@index");
    Route::post("/capitalPool/store", "Api\FinancingManage\CapitalPoolController@store");
    Route::post("/capitalPool/{id}", "Api\FinancingManage\CapitalPoolController@show");

    //管理后台用户相关
    Route::post('/logout', "Api\UserController@logoutApi");//注销
    Route::post('/rolePlayIssueToken', "Api\UserController@rolePlayIssueToken");//扮演用户
    Route::get("/userList", "Api\UserController@list");//用户列表


});
