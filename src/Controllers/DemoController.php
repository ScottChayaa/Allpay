<?php 

namespace ScottChayaa\Allpay\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ScottChayaa\Allpay\Facade\Allpay;

class DemoController extends Controller 
{	

	//-------------------------------------------------------------------------

	private function GetPaymentWay($p)
	{
		$val = "";

		switch ($p) {
			case 'ALL':
				$val = \PaymentMethod::ALL;
				break;
			case 'Credit':
				$val = \PaymentMethod::Credit;
				break;
			case 'CVS':
				$val = \PaymentMethod::CVS;
				break;
			default:
				$val = \PaymentMethod::ALL;
				break;
		}

		return $val;
	}

	//-------------------------------------------------------------------------

    public function index() 
    {
    	return view('allpay::demo');
    }

    public function checkout(Request $request) 
    {        
        //基本參數(請依系統規劃自行調整)
        Allpay::i()->Send['ReturnURL']         = "http://www.allpay.com.tw/receive.php" ;
        Allpay::i()->Send['MerchantTradeNo']   = "Test".time() ;           //訂單編號
        Allpay::i()->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');      //交易時間
        Allpay::i()->Send['TotalAmount']       = 2000;                     //交易金額
        Allpay::i()->Send['TradeDesc']         = "good to drink" ;         //交易描述
        Allpay::i()->Send['ChoosePayment']     = $this->GetPaymentWay($request->payway);     //付款方式

        //訂單的商品資料
        array_push(Allpay::i()->Send['Items'], array('Name' => "歐付寶黑芝麻豆漿", 'Price' => (int)"2000",
                   'Currency' => "元", 'Quantity' => (int) "1", 'URL' => "dedwed"));

        //Go to AllPay
        echo "歐付寶頁面導向中...";
        echo Allpay::i()->CheckOutString();
    }
}