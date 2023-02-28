<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\payment_log;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Razorpay\Api\Api;
use Session;
use Redirect;

class Payment_logController extends Controller
{
    public function payment(Request $request)
    {        
        $input = $request->all();        
        $api = new Api(env('rzp_test_NytLbUx4t56jfb'), env('TiwebK2DZG9MgLqiar5qzuDd'));
        $payment = $api->payment->fetch($input['payment_id']);

        if(count($input)  && !empty($input['payment_id'])) 
        {
            try 
            {
                $response = $api->payment->fetch($input['payment_id'])->capture(array('advance_amount'=>$payment['advance_amount'])); 

            } 
            catch (\Exception $e) 
            {
                return  $e->getMessage();
                Session::put('error',$e->getMessage());
                return redirect()->back();
            }            
        }
        
        Session::put('success', 'Payment successful.');
        return redirect()->back();
    }

}
