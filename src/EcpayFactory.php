<?php

namespace flamelin\ECPay;

class EcpayFactory extends \ECPay_AllInOne
{
    //產生訂單html code
    function CheckOutString($paymentButton = null, $target = "_self") {
        $arParameters = array_merge( array('MerchantID' => $this->MerchantID) ,$this->Send);
        return EcpaySend::CheckOutString($paymentButton,$target = "_self",$arParameters,$this->SendExtend,$this->HashKey,$this->HashIV,$this->ServiceURL);
    }
    	
    //取得付款結果通知的方法
    function CheckOutFeedback($ecpayPost = null) {
        $ecpayPost = $ecpayPost? $ecpayPost : $_POST;
        return $arFeedback = \ECPay_CheckOutFeedback::CheckOut($ecpayPost,$this->HashKey,$this->HashIV,0);   
    }
}
