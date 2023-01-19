<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function reset(){
        $validator=validator(request()->all(),['email' => 'required|email']);
        if ($validator->fails()) {
            return response(['error'=>true,'message'=>$validator->errors()], 422);
        }
        $user=\App\Models\User::whereEmail(request()->email)->first();
        if(!$user){
            return response()->json([
                'error'=>true,
                'message'=>'No user found!'
            ],404);
        }
        //delete old data
        \DB::table('password_resets')->where('email', request()->email)->delete();
        //insert otp
        \DB::table('password_resets')->insert([
            'email' => request()->email,
            'token' => mt_rand(100000,999999),//\Str::random(6), 
            'created_at' => \Carbon\Carbon::now()
        ]);
        $password_reset = \DB::table('password_resets')->where('email', request()->email)->orderBy('created_at','desc')->first();
        //sending email
        \Mail::html('<p>Password reset OTP : '.$password_reset->token.'</p>', function ($message) {
            $message->to(request()->email)
                    ->subject('Password reset OTP');
        });
        return response()->json([
            'success'=>true,
            'message'=>'OTP sent to Email Address'
        ]);
    }
    public function verify(){
        $validator=validator(request()->all(),[
            'token' => 'required',
            'password'=>'required|min:4'
        ],[
            'token.required' => 'Please enter OTP',
            'password.required'=>'Please enter new password'
        ]);
        if ($validator->fails()) {
            return response(['error'=>true,'message'=>$validator->errors()], 422);
        }
        $password_reset = \DB::table('password_resets')->where('token', request()->token)->first();
        if(!$password_reset){
            return response()->json([
                'error'=>true,
                'message'=>'Invalid OTP'
            ],404);
        }
        $user = \App\Models\User::where('email', $password_reset->email)->first();
        $user->password = bcrypt(request()->password);
        $user->save();
        \DB::table('password_resets')->where('email', $user->email)->delete();
        return response()->json([
            'success'=>true,
            'message'=>'Password changed successfully.'
        ]);

    }
}
