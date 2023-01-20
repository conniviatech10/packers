<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(){
        $validator=validator(request()->all(),[
            'email' => 'required|string|max:255',
            'password' => 'required|string|min:4',
        ]);
        if ($validator->fails()){
            return response(['error'=>true,'message'=>$validator->errors()], 422);
        }
        if(!auth()->attempt($this->credentials(request()))){
            return response()->json([
                'error'=>true,
                'message'=>'Please check your email/mobile number or password!'
            ]);
        }
        $user = \Auth::user(); 
        $token = $user->createToken(config('app.name').' Password Grant Client')->accessToken;
        return response()->json([
            'success'=>true,
            'token'=>$token
        ]);
    }
    protected function credentials(Request $request)
        {
          if(is_numeric($request->get('email'))){
            return ['mobile'=>$request->get('email'),'password'=>$request->get('password')];
          }
          elseif (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            return ['email' => $request->get('email'), 'password'=>$request->get('password')];
          }
          return ['username' => $request->get('email'), 'password'=>$request->get('password')];
        }

    /**
    * User generate otp api
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function generate_otp(Request $request)
    {
       $request->validate([
           'mobile' => 'required|digits:10|numeric',
            
       ]);
       $otp = random_int(100000, 999999);
    //    $time = DB::table('settings')->select('otpTime')->where('id',1)->first();
       $user_otp = DB::insert('insert into register_otp (mobile, otp, status,created_at) values (?, ?, ?, ?)', [$request->mobile, $otp,'Checking' ,carbon::now()]);
    //    dd($user_otp);
    //    $curl = curl_init();
    //    curl_setopt_array($curl, array(
    //      CURLOPT_URL => 'https://api.authkey.io/request?authkey=b7634c3d6d6a0079&mobile='.$request->mobile.'&country_code=91&sid=5910&otp='.$otp,
    //      CURLOPT_RETURNTRANSFER => true,
    //      CURLOPT_ENCODING => '',
    //      CURLOPT_MAXREDIRS => 10,
    //      CURLOPT_TIMEOUT => 0,
    //      CURLOPT_FOLLOWLOCATION => true,
    //      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //      CURLOPT_CUSTOMREQUEST => 'GET',
    //    ));
    //    $response = curl_exec($curl);
    //    $err = curl_error($curl);
    //    curl_close($curl);
    //    if ($err) {
    //      return response()->json(['error'=>true,'message'=>'Something went wrong'],422);
    //    } else {
         return response()->json(['success'=>true,'mobile'=> $request->mobile,'otp'=>$otp],200);
    //    }
    }
/**
    * User validate otp api
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
     public function validate_otp(Request $request)
     {
         
       if($request->mobile ){
           $verify = DB::table('register_otp')->where('mobile',$request->mobile)->where('otp',$request->otp)->first();
        
           if($verify){
               DB::table('register_otp')->where('mobile',$request->mobile)->where('otp',$request->otp)->update(['status' => 'Verified']);
               return response()->json(['success'=>true,'message'=> 'Verified Successfully'],200);
           }
       }
    // else{
    //        $verify = DB::table('register_otp')->where('mobile',$request->mobile)->where('otp',$request->otp)->first();
    //        if($verify){
    //            if(Carbon::now() <= Carbon::parse($verify->created_at)->addMinutes(10)){
    //                DB::table('register_otp')->where('mobile',$request->mobile)->where('otp',$request->otp)->update(['status' => 'Verified']);
    //                return response()->json(['success'=>true,'message'=> 'Verified Successfully'],200);
    //            }
            // else{
            //         return response()->json(['success'=>true,'message'=> 'Time exceeded 10 mins'],422);
            //    }
        //    }
    //    }
    //    return response()->json(['error'=>true,'message'=>'Otp validation failed'],422);
     }
}
