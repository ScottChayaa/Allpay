<?php

namespace flamelin\ECPay;

class EcpayFactory extends \ECPay_AllInOne
{
    //取得付款結果通知的方法
    function CheckOutFeedback($ecpayPost = null) {
        $ecpayPost = $ecpayPost? $ecpayPost : $_POST;
        return $arFeedback = \ECPay_CheckOutFeedback::CheckOut($ecpayPost,$this->HashKey,$this->HashIV,0);   
    }
}
