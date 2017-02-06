<?php

namespace flamelin\ECPay\Controllers;

use App\Http\Controllers\Controller;
use flamelin\ECPay\Facade\Ecpay;
use Illuminate\Http\Request;

class DemoController extends Controller
{

    //-------------------------------------------------------------------------

    private function GetPaymentWay($p)
    {
        $val = "";

        switch ($p) {
            case 'ALL':
                $val = \ECPay_PaymentMethod::ALL;
                break;
            case 'Credit':
                $val = \ECPay_PaymentMethod::Credit;
                break;
            case 'CVS':
                $val = \ECPay_PaymentMethod::CVS;
                break;
            default:
                $val = \ECPay_PaymentMethod::ALL;
                break;
        }

        return $val;
    }

    //-------------------------------------------------------------------------

    public function index()
    {
        return view('ecpay::demo');
    }

    public function checkout(Request $request)
    {
        //基本參數(請依系統規劃自行調整)
        Ecpay::i()->Send['ReturnURL'] = "http://www.ecpay.com.tw/receive.php";
        Ecpay::i()->Send['MerchantTradeNo'] = "Test" . time(); //訂單編號
        Ecpay::i()->Send['MerchantTradeDate'] = date('Y/m/d H:i:s'); //交易時間
        Ecpay::i()->Send['TotalAmount'] = 2000; //交易金額
        Ecpay::i()->Send['TradeDesc'] = "good to drink"; //交易描述
        Ecpay::i()->Send['ChoosePayment'] = $this->GetPaymentWay($request->payway); //付款方式

        //訂單的商品資料
        array_push(Ecpay::i()->Send['Items'], array('Name' => "歐付寶黑芝麻豆漿", 'Price' => (int) "2000",
            'Currency' => "元", 'Quantity' => (int) "1", 'URL' => "dedwed"));

        //Go to EcPay
        echo "緑界頁面導向中...";
        echo Ecpay::i()->CheckOutString();
        // Ecpay::i()->CheckOut();
    }
}
