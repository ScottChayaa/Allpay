<?php
 
Route::group(['namespace' => 'ScottChayaa\Allpay\Controllers', 'prefix'=>'allpay_demo'], function() {
    
    Route::get('/', 'DemoController@index');

});