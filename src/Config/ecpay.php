<?php

return [
    'ServiceURL' => env('PAY_SERVICE_URL', 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V2'),
    'HashKey' => env('PAY_HASH_KEY', '5294y06JbISpM5x9'),
    'HashIV' => env('PAY_HASH_IV', 'v77hoKGq4kWxNNIS'),
    'MerchantID' => env('PAY_MERCHANT_ID', '2000132'),
];
