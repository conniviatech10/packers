<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

     public function otp_login(Request $request)
        {
            $validator=validator(request()->all(),[
                'mobile' => 'required|string|max:255',
                'otp' => 'required',
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
}
