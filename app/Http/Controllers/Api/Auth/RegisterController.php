<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            // 'password' => Hash::make($data['password']),
        ]);
    }
    public function register(){
        $validator=validator(request()->all(),[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile' => ['required', 'numeric', 'min:10', 'unique:users'],
            'state' => ['required', 'string'],
            'city' => ['required', 'string'],
            // 'password' => ['required', 'string', 'min:4','confirmed'],
        ]);
        if ($validator->fails()){
            return response(['error'=>true,'message'=>$validator->errors()], 422);
        }
        
        $user=User::create([
            'name' => request()->name,
            'email' => request()->email,
            'mobile'=>request()->mobile,
            // 'password' => Hash::make(request()->password),
        ]);

        
        if(!$user){

          
            return response()->json(['error'=>true,'message'=>'Unknown Error!'],422);
        }
        $user->assignRole('User');
        $user->business()->create([
            'user_id'=>$user->id,
            'city'=>request()->city??'',
            'state'=>request()->state??'',
        ]);

        return response()->json([
            'success'=>true,
            'message'=>'User register successfully.'
        ],200);

    }
}
