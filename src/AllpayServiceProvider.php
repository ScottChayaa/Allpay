<?php

namespace ScottChayaa\Allpay;

use Illuminate\Support\ServiceProvider;

class AllpayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //Route
        include __DIR__ . '/routes.php';

        //Language
        $this->loadTranslationsFrom(__DIR__ . '/Lang', 'allpay');

        //Publish Config
        $this->publishes([
            __DIR__ . '/Config/allpay.php' => config_path('allpay.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //Config
        $this->mergeConfigFrom(__DIR__ . '/Config/allpay.php', 'allpay');

        //View
        $this->loadViewsFrom(__DIR__ . '/Views', 'allpay');

        //Facade => Custom Class
        $this->app['allpay'] = $this->app->share(function ($app) {
            return new Allpay;
        });

    }
}
