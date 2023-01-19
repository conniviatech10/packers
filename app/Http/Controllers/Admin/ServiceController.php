<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Corcel\Model\Taxonomy;
use Corcel\Model\Post;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            $sort=request()->sort??'desc';
            $services=Post::where('post_status','!=','trash')->type('service_request')->orderBy('id',$sort)->when('request',function($q){
                if(auth()->user()->hasRole('Admin')){
                    $q->hasMeta('assigned_to',auth()->id());
                }
            });
            $tables=datatables()->of($services);
            if(request()->has('columns')){
                foreach(request()->columns as $column){
                    if($column['name']){
                        $tables->addColumn($column['name'],function($services)use($column){
                            return $services->{$column['name']};
                        });
                    }
                }
            }
            $tables->editColumn('post_date','{{\Carbon\Carbon::parse($post_date)->format("d/m/Y")}}')
                    ->addColumn('action',function($services){
                        return '<div class="table-actions">
                                        <a class="d-none" href="'.url('admin/service/'.$services->ID).'" ><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
                                        <a href="'.url('admin/service/'.$services->ID).'" class="btn-delete"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                                    </div>';
            });
            return  $tables->make(true);
        }    
        return view('service.index');
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
        $this->validate($request,[
                'location'=>'required',
            ]);
        $post=new Post;   
        $post->post_type='service_request';
        $post->post_author=auth()->id()??0;
        $post->post_title='Service Request';
        $post->post_content='<!-- Service Request -->';
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
                'message'=>'Unable to sent Service Request!'
            ]);
        }
        return response()->json([
            'success'=>true,
            'message'=>'Service Request sent successfully'
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$post=Post::find($id);
        $post->post_status='trash';
        $post->save();
        if(!$post){
            return response()->json([
                'error'=>true,
                'message'=>'Unknown Error!'
            ]);
        }
        return response()->json([
            'success'=>true,
            'message'=>'Item(s) deleted successfully'
        ]);
    }
}