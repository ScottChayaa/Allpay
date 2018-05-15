## Allpay - Laravel 5 version

### step 1 : Download the package
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

### step 2 : Modify config file
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

### step 3 : Publish config to your project
執行下列命令，將package的config檔配置到你的專案中
```
php artisan vendor:publish
```

可至config/allpay.php中查看  
預設是Allpay的測試環境設定
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
    echo "歐付寶頁面導向中...";
    echo Allpay::i()->CheckOutString();
}
```
用laravel的人開發盡量使用`CheckOutString()`回傳String的方法  
當然使用`CheckOut()`也是可以  
但如果使用的話，我猜後面可能會碰到Get不到特定Session的問題  

PS : PaymentMethod前面一定要加反斜線 \ → 這目前我也沒辦法，如果有人知道怎麼樣可以不用加，請告訴我  
You Need to add Backslash '\' before PaymentMethod → I have no idea how to take it off. If someone know how to remove, please tell me how to do. thx~

---

### Example (Localhost)
Example Link : 
http://localhost/[your-project]/public/allpay_demo_201608


---

### Bug Fix Record
AllPay.Payment.Integration.php : (Latest commit e9278b9)<br>
https://github.com/allpay/PHP/commit/e9278b9cad76e6a71608bee3f5f4289982cfe16f

#### 1) 修正 CheckOutString
原檔
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
    $szHtml .=         "<form id=\"__allpayForm\" method=\"post\" target=\"{$target}\" action=\"{$ServiceURL}\">";
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
    $szHtml .=         "<form id=\"__allpayForm\" method=\"post\" target=\"{$target}\" action=\"{$ServiceURL}\">";

    foreach ($arParameters as $keys => $value) {
        $szHtml .=         "<input type=\"hidden\" name=\"{$keys}\" value='{$value}' />";
    }

    $szHtml .=             "<input type=\"hidden\" name=\"CheckMacValue\" value=\"{$szCheckMacValue}\" />";
    if (!isset($paymentButton)) {
        $szHtml .=  '<script type="text/javascript">document.getElementById("__allpayForm").submit();</script>';
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
主要是針對`$paymentButton`去做調整  
如果有比較現在版本跟以前版本的人會發現這個錯誤  
缺少`if (!isset($paymentButton))`的判斷  
如果官方的工程師有發現這個問題就趕快解吧  


#### 2) 修正CheckOutFeedback
原檔
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
歐付寶回傳頁面時會使用到這個方法  
使用方法 ex:  
```php
public function PayReturn(Request $request)
{
    /* 取得回傳參數 */
    $arFeedback = Allpay::i()->CheckOutFeedback($request->all());
    //...
}
```
注意要傳入`$request->all()`  
因為官方原本的方法是帶入`$_POST` → Laravel 5 不鳥你這個，所以會出錯  
固做此修正  
不過這部分沒有多做說明，留給大家試試看  
  
