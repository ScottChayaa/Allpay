<?php

Route::group([
    'namespace' => 'flamelin\ECPay\Controllers',
    'prefix' => 'ecpay_demo_201702'],
    function () {
        Route::get('/', 'DemoController@index');
        Route::get('/checkout', 'DemoController@checkout');
    }
);
