<?php

namespace flamelin\ECPay;

class EcpayFactory extends \ECPay_AllInOne
{
    //產生訂單html code
    public function CheckOutString($paymentButton = null, $target = "_self")
    {
        $arParameters = array_merge(array('MerchantID' => $this->MerchantID), $this->Send);
        return EcpaySend::CheckOutString($paymentButton, $target = "_self", $arParameters, $this->SendExtend, $this->HashKey, $this->HashIV, $this->ServiceURL);
    }

    //取得付款結果通知的方法
    public function CheckOutFeedback($ecpayPost = null)
    {
        $ecpayPost = $ecpayPost ? $ecpayPost : $_POST;
        return $arFeedback = \ECPay_CheckOutFeedback::CheckOut($ecpayPost, $this->HashKey, $this->HashIV, 0);
    }

    //取得收到付款或取號結果通知後給緑界的回應值
    public function getResponse($arFeedback = null)
    {
        $arFeedback = collect($arFeedback);
        if (preg_match('/BARCODE|CVS/i', $arFeedback->get('PaymentType'))) {
            return ($arFeedback->get('RtnCode') === '10100073' || $arFeedback->get('RtnCode') === '1') ? '1|OK' : '0|fail'; // 取號成功 or 失敗
        }
        if (preg_match('/ATM/i', $arFeedback->get('PaymentType'))) {
            return ($arFeedback->get('RtnCode') === '2' || $arFeedback->get('RtnCode') === '1') ? '1|OK' : '0|fail'; // 取號成功 or 失敗
        }
        return $arFeedback->get('RtnCode') === '1' ? '1|OK' : '0|fail'; // 付款成功 or 失敗
    }
}
