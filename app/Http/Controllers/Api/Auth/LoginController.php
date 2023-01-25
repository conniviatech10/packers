<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


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

    // public function login(Request $request){
    //     $validator=validator(request()->all(),[
    //         // 'email' => 'required|string|max:255',
    //         // 'password' => 'required',
            
    //     ]);
    //     if ($validator->fails()){
    //         return response(['error'=>true,'message'=>$validator->errors()], 422);
    //     }
    //     if(\auth::User()->attempt(['mobile'],$request->mobile)){
    //     // if(Auth::attempt('mobile' => $request->mobile)){
    //         return response()->json([
    //             'error'=>true,
    //             'message'=>'Please check your email/mobile number or password!'
    //         ]);
    //     }
    //     $user = \Auth::user(); 
    //     // dd($user);
    //     $token = $user->createToken(config('app.name').' Password Grant Client')->accessToken;
    //     return response()->json([
    //         'success'=>true,
    //         'token'=>$token
    //     ]);
    // }
    // protected function credentials(Request $request)
    //     {

    //             if(is_numeric($request->get('email'))){
    //               return ['mobile'=>$request->get('email'), 'password'=>request()->password??'',];
    //             }
    //             elseif (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
    //               return ['email' => $request->get('email'), 'password'=>request()->password??'',];
    //             }
    //             return ['username' => $request->get('email'), 'password'=>request()->password??'',];

    //     }

    public $successStatus = 200;

    public function login_otp(Request $request)
    {
        $user= User::where('mobile',$request->mobile)->first();

        if($user){
            Auth::login($user);
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(['success' => $success], $this->successStatus);

        }
        return response()->json(['error' => 'Something went wrong',],422);
       
         

        // return response()->json([
        //     'success' => true,
        //     'user'  => $user,
            
        // ]);

       

    }


    // public function authenticate(Request $request)
    // {
    //     // $validator = Validator::make($request->all(), [
    //     //     'password' => 'required'
    //     // ]);
    //     //Send failed response if request is not valid
    //     // if ($validator->fails()) {
    //     //     return response()->json(collect(['error'=>true,'message'=>'Please fill all details!'])->merge(collect($validator->messages())->map(function($items){ return $items[0]; })), 422);
    //     // }
    //     $User = User::select('id','name','store_owner_name','executive_name','store_id','mobile','image','password','gst_document','medical_registration_document','medical_registration','gst_number','pincode','otp','account_number','ifsc_code','account_name')->where('mobile',$request->mobile)->where('status','1')->first(); 
    //     if(!$User){
    //       return response()->json(['error' => 'Access Denied',],422);
    //     }
    //     //Request is validated
    //     //Create token
    //     // if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ old way with email
    //     if(Hash::check($request->password, $User->password)){
    //         // $user = Auth::user();
    //         \Auth::login($User); 
    //         $User->token =  $User->createToken('api')->plainTextToken;
    //         $User->type  = $User->roles->first()->name;
    //         $User->getPermissionsViaRoles()->pluck('names');
    //         User::where('id',auth()->user()->id)->update(['last_login' => Carbon::now()]);
    //     }
    //     else{
    //         return response()->json([
    //             'error' => 'Credentials Wrong',
    //         ],422);
    //     }
    //     // unset($User->roles);
    //     unset($User->device);
    //     //Token created, return with success response and jwt token
    //     return response()->json([
    //         'success' => true,
    //         'user'  => $User,
    //     ]);
    // }
        



    public function check_user(Request $request)

    {
        $user= User::where('mobile',$request->mobile)->first();
        if(!$user)
        {
            return response()->json(['error'=>true,'message'=>'User not register'],422);  

           
        }
        return response()->json(['success'=>true,'message'=>'User already exist'],200);  
           
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
    
    


    $xml_data = 'user=PACKERSW&key=335b3a52d6XX&mobile=+91'.$request->mobile.'&message=YOUR OTP FOR LOGIN '.$otp.' NSL&senderid=KRINCN&accusage=1&entityid=1201164562188563646&tempid=1207165710971535642';
    
       
    $URL = "http://mobicomm.dove-sms.com//submitsms.jsp?"; 
       
                   $ch = curl_init($URL);
                   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                   curl_setopt($ch, CURLOPT_POST, 1);
                   curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');			
                   curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
                   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                   $output = curl_exec($ch);
                   $err = curl_error($ch);
                   curl_close($ch);
       
       //print_r($output); 

       if ($err) {
         return response()->json(['error'=>true,'message'=>'Something went wrong'],422);
       } else {
         return response()->json(['success'=>true,'mobile'=> $request->mobile,'otp'=>$otp],200);
       }
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
       return response()->json(['error'=>true,'message'=>'Otp validation failed'],422);
     }
}
