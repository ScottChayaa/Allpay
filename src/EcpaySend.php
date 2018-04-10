<?php

namespace flamelin\ECPay;

class EcpaySend extends \ECPay_Send
{
    static function CheckOutString($paymentButton,$target = "_self",$arParameters = array(),$arExtend = array(),$HashKey='',$HashIV='',$ServiceURL=''){
        
        $arParameters = self::process($arParameters,$arExtend);
        //產生檢查碼
        $szCheckMacValue = \ECPay_CheckMacValue::generate($arParameters,$HashKey,$HashIV,$arParameters['EncryptType']);
        
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
            $szHtml .=             '<script type="text/javascript">document.getElementById("__ecpayForm").submit();</script>';
        } else {
            $szHtml .=             "<input type=\"submit\" id=\"__paymentButton\" value=\"{$paymentButton}\" />";
        }
        $szHtml .=         '</form>';
        $szHtml .=     '</body>';
        $szHtml .= '</html>';
        return  $szHtml ;
    }
}
