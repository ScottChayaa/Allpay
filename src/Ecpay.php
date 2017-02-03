<?php

namespace flamelin\ECPay;

class Ecpay
{

    private $instance = null;

    //--------------------------------------------------------

    public function __construct()
    {
        $this->instance = new \ECPay_AllInOne();

        $this->instance->ServiceURL = config('ecpay.ServiceURL');
        $this->instance->HashKey = config('ecpay.HashKey');
        $this->instance->HashIV = config('ecpay.HashIV');
        $this->instance->MerchantID = config('ecpay.MerchantID');
    }

    public function instance()
    {
        return $this->instance;
    }

    public function i()
    {
        return $this->instance;
    }

}
