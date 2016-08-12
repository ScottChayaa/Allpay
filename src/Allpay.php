<?php

namespace ScottChayaa\Allpay;

class Allpay
{

    private $instance = null;

    //--------------------------------------------------------

    public function __construct()
    {
        $this->instance = new \AllInOne();

        $this->instance->ServiceURL = config('allpay.ServiceURL');
        $this->instance->HashKey    = config('allpay.HashKey');
        $this->instance->HashIV     = config('allpay.HashIV');
        $this->instance->MerchantID = config('allpay.MerchantID');
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
