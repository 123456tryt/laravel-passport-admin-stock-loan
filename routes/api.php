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

    Route::post("/bindBankCard", "Api\UserController@storeBankCard");
    Route::post("/updateBankCard/{id}", "Api\UserController@updateBankCard");
    Route::post("/deleteBankCard/{id}", "Api\UserController@deleteBankCard");

    Route::post("/createCaptcha", "Api\CaptchaController@generateCaptcha");
    Route::post("/verifyCaptcha", "Api\CaptchaController@verifyCaptcha");
});
