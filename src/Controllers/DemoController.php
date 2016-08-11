<?php 

namespace ScottChayaa\Allpay\Controllers;
 
use App\Http\Controllers\Controller;
 
class DemoController extends Controller 
{
    public function index() 
    {
        return view('allpay::demo',[
        	'msg' => config('allpay.message'),
        ]);
    }
}