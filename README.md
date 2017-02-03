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
    'ServiceURL' => 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V2',
    'HashKey'    => '5294y06JbISpM5x9',
    'HashIV'     => 'v77hoKGq4kWxNNIS',
    'MerchantID' => '2000132',
];
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
<br>
PS : ECPay_PaymentMethod前面一定要加反斜線 \ → 這目前我也沒辦法，如果有人知道怎麼樣可以不用加，請告訴我 <br>
You Need to add Backslash '\' before ECPay_PaymentMethod → I have no idea how to take it off. If someone know how to remove, please tell me how to do. thx~

---

### Example (Localhost)
Example Link : 
http://localhost/[your-project]/public/ecpay_demo_201702

<br>
---

### Bug Fix Record
ECPay.Payment.Integration.php : (Latest commit e9278b9)<br>
https://github.com/ecpay/PHP/commit/e9278b9cad76e6a71608bee3f5f4289982cfe16f

**1. 修正 CheckOutString**<br>
原本
```php
static function CheckOutString($paymentButton,$target = "_self",$arParameters = array(),$arExtend = array(),$HashKey='',$HashIV='',$ServiceURL=''){
    
    $arParameters = self::process($arParameters,$arExtend);
    //產生檢查碼
    $szCheckMacValue = CheckMacValue::generate($arParameters,$HashKey,$HashIV,$arParameters['EncryptType']);
    
    $szHtml =  '<!DOCTYPE html>';
    $szHtml .= '<html>';
    $szHtml .=     '<head>';
    $szHtml .=         '<meta charset="utf-8">';
    $szHtml .=     '</head>';
    $szHtml .=     '<body>';
    $szHtml .=         "<form id=\"__ecpayForm\" method=\"post\" target=\"{$target}\" action=\"{$ServiceURL}\">";
    foreach ($arParameters as $keys => $value) {
        $szHtml .=         "<input type=\"hidden\" name=\"{$keys}\" value='{$value}' />";
    }
    $szHtml .=             "<input type=\"hidden\" name=\"CheckMacValue\" value=\"{$szCheckMacValue}\" />";
    $szHtml .=             "<input type=\"submit\" id=\"__paymentButton\" value=\"{$paymentButton}\" />";
    $szHtml .=         '</form>';
    $szHtml .=     '</body>';
    $szHtml .= '</html>';
    return  $szHtml ;
}
```
修正為
```php
static function CheckOutString($paymentButton,$target = "_self",$arParameters = array(),$arExtend = array(),$HashKey='',$HashIV='',$ServiceURL=''){
    
    $arParameters = self::process($arParameters,$arExtend);
    //產生檢查碼
    $szCheckMacValue = CheckMacValue::generate($arParameters,$HashKey,$HashIV,$arParameters['EncryptType']);
    
    $szHtml =  '<!DOCTYPE html>';
    $szHtml .= '<html>';
    $szHtml .=     '<head>';
    $szHtml .=         '<meta charset="utf-8">';
    $szHtml .=     '</head>';
    $szHtml .=     '<body>';
    $szHtml .=         "<form id=\"__ecpayForm\" method=\"post\" target=\"{$target}\" action=\"{$ServiceURL}\">";

    foreach ($arParameters as $keys => $value) {
        $szHtml .=         "<input type=\"hidden\" name=\"{$keys}\" value='{$value}' />";
    }

    $szHtml .=             "<input type=\"hidden\" name=\"CheckMacValue\" value=\"{$szCheckMacValue}\" />";
    if (!isset($paymentButton)) {
        $szHtml .=  '<script type="text/javascript">document.getElementById("__ecpayForm").submit();</script>';
    }
    else{
        $szHtml .=  "<input type=\"submit\" id=\"__paymentButton\" value=\"{$paymentButton}\" />";
    }
    $szHtml .=         '</form>';
    $szHtml .=     '</body>';
    $szHtml .= '</html>';
    return  $szHtml ;
}
```
主要是針對`$paymentButton`去做調整<br>
如果有比較現在版本跟以前版本的人會發現這個錯誤<br>
缺少`if (!isset($paymentButton))`的判斷<br>
如果官方的工程師有發現這個問題就趕快解吧<br>
<br>

**2. 修正CheckOutFeedback**<br>
原本
```php
function CheckOutFeedback() {
    return $arFeedback = CheckOutFeedback::CheckOut($_POST,$this->HashKey,$this->HashIV,0);   
}
```
修正為
```php
function CheckOutFeedback($allPost = null) {
    if($allPost == null) $allPost = $_POST;
    return $arFeedback = CheckOutFeedback::CheckOut($allPost,$this->HashKey,$this->HashIV,0);   
}
```
緑界回傳頁面時會使用到這個方法<br>
使用方法 ex: 
```php
public function PayReturn(Request $request)
{
    /* 取得回傳參數 */
    $arFeedback = Ecpay::i()->CheckOutFeedback($request->all());
    //...
}
```
注意要傳入`$request->all()`<br>
因為官方原本的方法是帶入`$_POST` → Laravel 5 不鳥你這個，所以會出錯<br>
固做此修正<br>
不過這部分沒有多做說明，留給大家試試看<br>
<br>
