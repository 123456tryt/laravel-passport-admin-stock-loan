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

    Route::post("/createBankCard", "Api\UserDataController@storeBankCard");
    Route::post("/updateBankCard", "Api\UserDataController@updateBankCard");
    Route::post("/deleteBankCard", "Api\UserDataController@deleteBankCard");

    Route::post("/updateNickname", "Api\UserDataController@updateNickname");

    Route::post("/createCertification", "Api\UserDataController@storeCetification");

    Route::post("/createWithdrawPassword", "Api\UserDataController@storeWithdrawPassword");
    Route::post("/updateWithdrawPassword", "Api\UserDataController@updateWithdrawPassword");

    Route::post("/createCaptcha", "Api\CaptchaController@generateCaptcha");
    Route::post("/verifyCaptcha", "Api\CaptchaController@verifyCaptcha");
});
