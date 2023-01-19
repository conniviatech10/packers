<?php

namespace App\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user=User::with('business')->find(auth()->id());
        $user->profile_avatar=profile_picture();
        return response()->json([
            'success'=>true,
            'data'=>$user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=validator(request()->all(),[
            'name' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string'],
            'city' => ['required', 'string'],
        ]);
        if ($validator->fails()){
            return response(['error'=>true,'message'=>$validator->errors()], 422);
        }
        $user=User::find(auth()->id());
        
        $user->name=$request->name;
        $user->save();
        $user->business()->update([
            'city'=>$request->city,
            'state'=>$request->state
        ]);
        if(!$user){
            return response(['error'=>true,'message'=>'Unknown Error!'], 422);
        }
        return response([
                'success'=>true,
                'message'=>'Profile updated successfully'
            ]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function imageUploader(Request $request){
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')){
            $folderPath = "uploads/users/";
            list($type, $data) = explode(';', $request->profile_avatar); // exploding data for later checking and validating

            if (preg_match('/^data:image\/(\w+);base64,/', $request->profile_avatar, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif

                if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
                    throw new \Exception('invalid image type');
                }

                $data = base64_decode($data);

                if ($data === false) {
                    throw new \Exception('base64_decode failed');
                }
            } else {
                throw new \Exception('did not match data URI with image data');
            }
            $name=$request->name??time();
            $fullname =$folderPath .$name.'.'.$type;
            
            if(file_put_contents($fullname, $data)){
                $result = $fullname;
            }else{
                $result =  "error";
            }
            /* it will return image name if image is saved successfully
            or it will return error on failing to save image. */
            if($result=='error'){
                return response()->json([
                    'error'=>true,
                    'message'=>'Unknown Error'
                ]);
            }
            //save to attchment
            $post=\Corcel\Model\Post::create([
                    'post_author'=>auth()->id(),
                    'post_title'=>ucwords($name),
                    'post_content'=>'',
                    'post_excerpt'=>'',
                    'guid'=>asset($result),
                    'post_mime_type'=>'image/'.$type,
                    'post_type'=>'attachment',
                    'to_ping'=>'',
                    'pinged'=>'',
                    'post_content_filtered'=>''
            ]);
            $post->guid=asset($result);
            $post->post_mime_type='image/'.$type;
            $post->save();
            $user=User::find(auth()->id());
            $user->saveMeta([
                    'profile_avatar'=>$post->ID
                ]);
            return response()->json([
                'success'=>true,
                'url'=>asset($result)
            ]);
        }
    }
}
