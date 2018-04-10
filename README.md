## Ecpay
### Ecpay - Laravel 5 version
<br>

**step 1 : Download the package**<br>
composer命令安裝
```
composer require flamelin/ecpay dev-master
```
或者是新增package至composer.json
```
"require": {
  "flamelin/ecpay": "dev-master"
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

<br>
**step 2 : Modify config file**<br>
增加`config/app.php`中的`providers`和`aliases`的參數
```
'providers' => [
  // ...
  flamelin\ECPay\EcpayServiceProvider::class,
]

'aliases' => [
  // ...
  'Ecpay' => flamelin\ECPay\Facade\Ecpay::class,
]
```

<br>
**step 3 : Publish config to your project**<br>
執行下列命令，將package的config檔配置到你的專案中
```
php artisan vendor:publish
```

可至config/ecpay.php中查看
預設是測試Ecpay設定
```php
return [
    'ServiceURL' => env('PAY_SERVICE_URL', 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V2'),
    'HashKey' => env('PAY_HASH_KEY', '5294y06JbISpM5x9'),
    'HashIV' => env('PAY_HASH_IV', 'v77hoKGq4kWxNNIS'),
    'MerchantID' => env('PAY_MERCHANT_ID', '2000132'),
];
```

<br>
**step 4 : .env中新增參數**<br>
```ini
#付款測試 true : 直接使用測試的特店參數, false : 使用config/ecpay.php中的參數.
APP_PAY_TEST=true

PAY_SERVICE_URL=https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V2
PAY_HASH_KEY=5294y06JbISpM5x9
PAY_HASH_IV=v77hoKGq4kWxNNIS
PAY_MERCHANT_ID=2000132
```

---

### How To Use 
```php
use Ecpay;
```
```php
public function Demo()
{
    //Official Example : 
    //https://github.com/ECPay/ECPayAIO_PHP/blob/master/AioSDK/example/sample_Credit_CreateOrder.php
    
    //基本參數(請依系統規劃自行調整)
    Ecpay::i()->Send['ReturnURL']         = "http://www.ecpay.com.tw/receive.php" ;
    Ecpay::i()->Send['MerchantTradeNo']   = "Test".time() ;           //訂單編號
    Ecpay::i()->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');      //交易時間
    Ecpay::i()->Send['TotalAmount']       = 2000;                     //交易金額
    Ecpay::i()->Send['TradeDesc']         = "good to drink" ;         //交易描述
    Ecpay::i()->Send['ChoosePayment']     = \ECPay_PaymentMethod::ALL ;     //付款方式

    //訂單的商品資料
    array_push(Ecpay::i()->Send['Items'], array('Name' => "緑界黑芝麻豆漿", 'Price' => (int)"2000",
               'Currency' => "元", 'Quantity' => (int) "1", 'URL' => "dedwed"));

    //Go to ECPay
    echo "緑界頁面導向中...";
    echo Ecpay::i()->CheckOutString();
}
```
用laravel的人開發盡量使用`CheckOutString()`回傳String的方法<br>
當然使用`CheckOut()`也是可以<br>
但如果使用的話，我猜後面可能會碰到Get不到特定Session的問題<br>

```php
//付款成功後緑界背景callback
public function doneDemo(Request $request)
{
    $arFeedback = Ecpay::i()->CheckOutFeedback($request->all());
    print Ecpay::i()->getResponse($arFeedback);
}
```

<br>
---

### Example (Localhost)
Example Link : 
http://localhost/[your-project]/public/ecpay_demo_201702

<br>
---
