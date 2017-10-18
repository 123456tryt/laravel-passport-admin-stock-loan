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
    Route::post("/agentSearch", "Api\AgentController@search");


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


    Route::post("/clientFlowAdd", "Api\ClientFlowController@add");


    //ClientBankCardController
    Route::get("/bankCardList", "Api\ClientBankCardController@list");
    Route::post("/bankCardUpdate", "Api\ClientBankCardController@update");


    Route::get("/withdrawList", "Api\ClientWithdrawController@list");
    Route::post("/withdrawUpdate", "Api\ClientWithdrawController@update");

    Route::post("/getSystemAgentConfigs", "Api\SystemController@agentConfigs");
    Route::post("/updateSystemAgentConfigs", "Api\SystemController@agentConfigsUpdate");

});
