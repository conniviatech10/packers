<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Corcel\Model\Post;
use App\Mail\PartnerRequest;

class PartnerRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>'required',
            'email'=>'required|email',
            'mobile'=>'required'
        ]);
        $post=new Post();
        $post->post_type='partner_request';
        $post->post_author=auth()->id()??0;
        $post->post_title='Partner Request';
        $post->post_content='<!-- Partner Request -->';
        $post->post_status=$request->post_status??'';
        $post->post_excerpt='';
        $post->post_content_filtered='';
        $post->to_ping='';
        $post->pinged=0;
        $post->save();
        $post->saveMeta($request->all());
        if(!$post){
            return response()->json([
                'error'=>true,
                'message'=>'Unable to sent Partnership Request!'
            ]);
        }
        \Mail::to(config('setting.admin_email'))->cc('enquiry@packerswala.com')->send(new PartnerRequest($post));
        //return (new PartnerRequest($post))->render();
        
        return response()->json([
            'success'=>true,
            'message'=>'Partnership Request sent successfully'
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
        
    }
    
}