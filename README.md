## Allpay

Allpay - Laravel 5 version
---
step 1 : Download the package
composer命令安裝
```
composer require latrell/alipay dev-master
```
或者是新增package至composer.json
```
"require": {
  "scottchayaa/allpay": "dev-master"
},
```
然後更新安裝
```
composer update
```
或全新安裝
```
composer install
```

step 2 : Modify config file
增加config/app.php中的providers的參數
```
'providers' => [
  // ...
  'Latrell\Alipay\AlipayServiceProvider',
]
```

step 3 : Publish config to your project
執行下列命令，將package的config檔配置到你的專案中
```
php artisan vendor:publish
```
可至config/中查看

### How To Use 

```
public function Demo()
{
    //Official Example : 
    //https://github.com/allpay/PHP/blob/master/AioSDK/example/sample_Credit_CreateOrder.php
    
    //基本參數(請依系統規劃自行調整)
    Allpay::i()->Send['ReturnURL']         = "http://www.allpay.com.tw/receive.php" ;
    Allpay::i()->Send['MerchantTradeNo']   = "Test".time() ;           //訂單編號
    Allpay::i()->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');      //交易時間
    Allpay::i()->Send['TotalAmount']       = 2000;                     //交易金額
    Allpay::i()->Send['TradeDesc']         = "good to drink" ;         //交易描述
    Allpay::i()->Send['ChoosePayment']     = \PaymentMethod::ALL ;     //付款方式

    //訂單的商品資料
    array_push(Allpay::i()->Send['Items'], array('Name' => "歐付寶黑芝麻豆漿", 'Price' => (int)"2000",
               'Currency' => "元", 'Quantity' => (int) "1", 'URL' => "dedwed"));

    //Go to AllPay
    Allpay::i()->CheckOut();
}
```

PS : PaymentMethod前面一定要加反斜線 \ → 這目前我也沒辦法，如果有人知道怎麼樣可以不用加，請告訴我 
You Need to add Backslash '\' before PaymentMethod → I have no idea how to take it off. If someone know how to remove, please tell me how to do. thx~

### Example (Localhost)
Example Link : 
http://localhost/[your-project]/public/allpay_demo_201608
