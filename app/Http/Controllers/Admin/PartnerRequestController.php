<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Corcel\Model\Post;

class PartnerRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            $post=Post::with('meta')->type('partner_request')->orderBy('ID','desc')->where('post_status','!=','trash');
            $datatables= datatables()->of($post);
            if(request()->has('columns')){
                foreach(request()->columns as $column){
                    if($column['name']){
                        $datatables->addColumn($column['name'],function($orders)use($column){
                            return $orders->{$column['name']};
                        });
                    }
                }
            }
			$datatables->addColumn('action',function($data){
                return '<div class="table-actions text-center">
                                <a class="d-none" href="'.url('admin/partner-request/'.$data->ID).'/edit" ><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
                                <a href="'.url('admin/partner-request/'.$data->ID).'" class="list-delete"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                            </div>';
            });
            return $datatables->make(true);
        }
        return view('pages.partner-request');
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