<?php

Route::group([
    'namespace' => 'ScottChayaa\Allpay\Controllers',
    'prefix'    => 'allpay_demo_201608'],
    function () {
        Route::get('/', 'DemoController@index');
        Route::get('/checkout', 'DemoController@checkout');
    }
);
