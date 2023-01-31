<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $roles = Role::where('name','!=','Super Admin')->pluck('name', 'id');
			return view('create-user', compact('roles'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile' => ['required', 'numeric', 'min:10', 'unique:users'],
            //'state' => ['required', 'string'],
            //'city' => ['required', 'string'],
            'password' => ['required', 'string', 'min:4','confirmed'],
        ]);
        // dd($request->all());
        try {
            // store user information
            $user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'mobile'=>$request->mobile??'',
                        'password' => Hash::make($request->password),
                    ]);

            // assign new role to the user
            $user->syncRoles($request->role);

            if ($user) {
                return redirect('admin/users')->with('success', 'New user created!');
            } else {
                return redirect('admin/users')->with('error', 'Failed to create new user! Try again.');
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();

            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $user = User::with('roles', 'permissions')->find($id);

            if ($user) {
                $user_role = $user->roles->first();
                $roles = Role::where('name','!=','Super Admin')->pluck('name', 'id');
				return view('user-edit', compact('user', 'user_role', 'roles'));
            } else {
                return redirect('404');
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();

            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function update(Request $request, $id)
    public function update(Request $request)
    {
        // update user info
        $validator = Validator::make($request->all(), [
            'id'       => 'required',
            'name'     => 'required | string ',
            'email'    => 'required | email|unique:users,email,'.$request->id.',id',
            'role'     => 'required'
        ]);

        // check validation for password match
        if(isset($request->password)){
            $validator = Validator::make($request->all(), [
                'password' => 'required | confirmed'
            ]);
        }
        
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }

        try{
            
            $user = User::find($request->id);

            $update = $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // update password if user input a new password
            if(isset($request->password)){
                $update = $user->update([
                    'password' => Hash::make($request->password)
                ]);
            }

            // sync user role
            $user->syncRoles($request->role);

            return redirect()->back()->with('success', 'User information updated succesfully!');
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
    
    public function delete($id){
        $user   = User::find($id);
        if($user){
            $user->delete();
            return redirect('admin/users')->with('success', 'User removed!');
        }else{
            return redirect('admin/users')->with('error', 'User not found');
        }
    }
    public function getUserList(Request $request)
    {
        $data = \App\Models\User::get();

        return Datatables::of($data)
                ->addColumn('roles', function ($data) {
                    $roles = $data->getRoleNames()->toArray();
                    $badge = '';
                    if ($roles) {
                        $badge = implode(' , ', $roles);
                    }

                    return $badge;
                })
                ->addColumn('permissions', function ($data) {
                    $roles = $data->getAllPermissions();
                    $badges = '';
                    foreach ($roles as $key => $role) {
                        $badges .= '<span class="badge badge-dark m-1">'.$role->name.'</span>';
                    }

                    return $badges;
                })
                ->addColumn('action', function ($data) {
                    if ($data->name == 'Super Admin') {
                        return '';
                    }
                    if (\Auth::user()->can('manage_user')) {
                        return '<div class="table-actions" style="width:80px;">
                                <a href="'.url('admin/user/'.$data->id).'" class="btn btn-sm btn-icon btn-outline-dark"><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
                                <a href="'.url('admin/user/delete/'.$data->id).'" class="btn btn-sm btn-icon btn-outline-dark btn-delete"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                            </div>';
                    } else {
                        return '';
                    }
                })
                ->rawColumns(['roles','permissions','action'])
                ->make(true);
    }

    public function profile(){
        return view('pages.profile');
    }
    public function profile_update(){
        $this->validate(request(),[
            'email'=>'required|email|unique:users,email,'.request()->id,',id',
            'mobile'=>'required|unique:users,mobile,'.request()->id,',id'
        ]);
        $user=User::find(request()->id);
        $user->name=request()->name;
        $user->email=request()->email;
        $user->mobile=request()->mobile;
        $user->save();
        $user->business()->updateOrCreate([
            'user_id'=>auth()->id()
        ],[
            'city'=>request()->city??'',
            'state'=>request()->state??''
        ]);
        if(!$user){
            return redirect()->back()->with('error', 'Unknown Error!');
        }
        return redirect('admin/profile')->with('success', 'Profile updated successfully');
    }
    public function change_password(){
        return view('pages.change-password');
    }
    public function update_password(){
        $this->validate(request(),[
            'password' => ['required', 'string', 'min:4', 'confirmed'],
            // 'password_confirmation' => ['same:password'],
        ]);
        
        $user=User::find(request()->id);
        $user->password=Hash::make(request()->password);
        // $user->password= request()->password;
        $user->save();
        // dd($user);
        if(!$user){
            return redirect()->back()->with('error', 'Unknown Error!');
        }
        return redirect('admin/change-password')->with('success', 'Password updated successfully');
    }
}
