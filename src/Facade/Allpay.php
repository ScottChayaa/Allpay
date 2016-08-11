<?php 
namespace ScottChayaa\Allpay\Facade;
 
use Illuminate\Support\Facades\Facade;
 
class Allpay extends Facade {
 
    protected static function getFacadeAccessor() { return 'allpay'; }
 
}