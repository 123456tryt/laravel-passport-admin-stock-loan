<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //我在Apache nginx里面已经设置了跨域
        //
        //为passpart 路由添加自定义captcha
        //captcha.check

        Route::group(['middleware' => 'captcha.check'], function () {
            Passport::routes(); // <-- Replace this with your own version
        });

    }
}
