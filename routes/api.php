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
    Route::get("/foo", "Api\FooController@say");

    Route::post("/getUserInfo", "Api\UserDataController@getUserInfo");

    Route::post("/getIndexData", "Api\OthersController@getIndexData");

    Route::post("/register", "Api\RegisterController@register");
    Route::post("/getBackPassword", "Api\RegisterController@getBackPassword");
    Route::post("/getRegisterSms", "Api\RegisterController@sendSms")->middleware("App\Http\Middleware\CaptchaCheck");
    Route::post("/getGetBackSms", "Api\RegisterController@sendGetBackSms")->middleware("App\Http\Middleware\CaptchaCheck");
    Route::post("/login", "Api\LoginController@login");
    Route::post("/logout", "Api\LoginController@logout")->middleware("auth:api");

    Route::post("/bankCards", "Api\UserDataController@bankCards");
    Route::post("/getBankCard", "Api\UserDataController@getBankCard");
    Route::post("/createBankCard", "Api\UserDataController@storeBankCard");
    Route::post("/updateBankCard", "Api\UserDataController@updateBankCard");
    Route::post("/deleteBankCard", "Api\UserDataController@deleteBankCard");
    Route::post("/updatePhone", "Api\UserDataController@updatePhone");
    Route::post("/updatePassword", "Api\UserDataController@updatePassword");
    Route::post("/getBackWithdrawPassword", "Api\UserDataController@getBackWithdrawPassword");
    Route::post("/getSms", "Api\UserDataController@sendSms")->middleware("App\Http\Middleware\CaptchaCheck");
    Route::post("/updateNickname", "Api\UserDataController@updateNickname");
    Route::post("/createCertification", "Api\UserDataController@storeCetification");
    Route::post("/uploadAvatar", "Api\UserDataController@uploadAvatar");

    Route::post("/offlineTransfer", "Api\RechargeController@offlineTransfer");

    Route::post("/createWithdrawPassword", "Api\UserDataController@storeWithdrawPassword");
    Route::post("/updateWithdrawPassword", "Api\UserDataController@updateWithdrawPassword");

    Route::post("/createCaptcha", "Api\CaptchaController@generateCaptcha");
    Route::post("/verifyCaptcha", "Api\CaptchaController@verifyCaptcha");

    Route::post('/withdraw', 'Api\AccountController@withdraw');
    Route::post('/withdrawRecord', 'Api\AccountController@withdrawRecord');
    Route::post('/checkBackWithdraw', 'Api\AccountController@checkBackWithdraw');
    Route::post('/getAccountCount', 'Api\AccountController@getCount');

    Route::post('/getShareCount', 'Api\ShareController@getShareCount');
    Route::post('/getPromotionUsers', 'Api\ShareController@getPromotionUsers');
    Route::post('/getPromotionPercentages', 'Api\ShareController@getPromotionPercentages');

    Route::post('/getFundsDetails', 'Api\FundsDetailController@getFundsDetails');
});
