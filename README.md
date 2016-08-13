## Allpay
### Allpay - Laravel 5 version
<br>

**step 1 : Download the package**<br>
composer命令安裝
```
composer require scottchayaa/allpay dev-master
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

<br>
**step 2 : Modify config file**<br>
增加`config/app.php`中的`providers`和`aliases`的參數
```
'providers' => [
  // ...
  ScottChayaa\Allpay\AllpayServiceProvider::class,
]

'aliases' => [
  // ...
  'Allpay' => ScottChayaa\Allpay\Facade\Allpay::class,
]
```

<br>
**step 3 : Publish config to your project**<br>
執行下列命令，將package的config檔配置到你的專案中
```
php artisan vendor:publish
```

可至config/allpay.php中查看
預設是測試Allpay設定
```php
return [
    'ServiceURL' => 'http://payment-stage.allpay.com.tw/Cashier/AioCheckOut',
    'HashKey'    => '5294y06JbISpM5x9',
    'HashIV'     => 'v77hoKGq4kWxNNIS',
    'MerchantID' => '2000132',
];
```


---

### How To Use 
```php
use Allpay;
```
```php
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

PS : PaymentMethod前面一定要加反斜線 \ → 這目前我也沒辦法，如果有人知道怎麼樣可以不用加，請告訴我 <br>
You Need to add Backslash '\' before PaymentMethod → I have no idea how to take it off. If someone know how to remove, please tell me how to do. thx~

---

### Example (Localhost)
Example Link : 
http://localhost/[your-project]/public/allpay_demo_201608
