<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Corcel\Model\Post;
use Illuminate\Http\Request;

class ShiftingTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            $order_status=Post::type('shifting_type');
            return datatables()->of($order_status)->addColumn('action',function($data){
                return '<div class="table-actions">
                                    <a href="'.url('admin/setting/shifting-type/edit/'.$data->ID).'" class="d-none"><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
                                    <a href="'.url('admin/setting/shifting-type/'.$data->ID).'"  class="list-delete"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                                </div>';
            })->make(true);
        }
        return view('setting.shifting.index');
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
            'post_title'=>'required'
        ],[
            'post_title.required'=>'Enter Shifting Type'
        ]);
        $order_status=new Post();
        $order_status->post_author=auth()->id();
        $order_status->post_title=$request->post_title;
        $order_status->post_name=\Str::slug($request->post_title,'-');
        $order_status->post_content='';
        $order_status->post_excerpt='';
        $order_status->to_ping='';
        $order_status->pinged=0;
        $order_status->post_content_filtered='';
        $order_status->post_type='shifting_type';
        $order_status->save();
        if(!$order_status){
            return redirect()->back()->withErrors(['message'=>'Unknown Error']);
        }
        return redirect()->back()->with('success', 'Type added succesfully!');
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
        //
    }
}
