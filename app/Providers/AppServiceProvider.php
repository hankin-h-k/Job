<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\WechatService; 
use App\Services\UploadService; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \Schema::defaultStringLength(191); 
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('wechatservice', function(){
            return new WechatService();
        });  
        $this->app->singleton('uploadservice', function(){
            return new UploadService();
        });
    }
}
