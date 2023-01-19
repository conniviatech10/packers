<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Corcel\Model\Page;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            $pages=Page::orderBy('ID','desc')->where('post_status','!=','trash');
            return datatables()->of($pages)->addColumn('action',function($data){
                return '<div class="table-actions text-center">
                                <a href="'.url('admin/page/'.$data->ID).'/edit" ><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
                                <a href="'.url('admin/page/'.$data->ID).'" class="list-delete"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                            </div>';
            })->make(true);
        }
        return view('pages.page');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.create-page');
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
            'post_title'=>'required'
        ]);
        $page=new Page();
        $page->post_type='page';
        $page->post_author=auth()->id();
        $page->post_title=$request->post_title;
        $page->post_content=$request->post_content??'';
        $page->post_status=$request->post_status;
        $page->post_excerpt='';
        $page->post_content_filtered='';
        $page->to_ping='';
        $page->pinged=0;
        $page->post_name=link_rewrite('posts','ID',$request->post_title,0,'post_name');
        $page->save();
        if(!$page){
            return redirect()->back()->withInput()->withErrors('Unknown Error!');
        }
        $page->guid=url('/').'?page_id='.$page->ID;
        $page->save();
        $page->saveMeta([
            'meta_title'=>$request->get('meta_title'),
            'meta_description'=>$request->get('meta_description'),
            'meta_keyword'=>$request->get('meta_keyword'),
            '_thumbnail_id'=>$request->_thumbnail_id
        ]);
        return redirect()->to('admin/page')->with('success','Page created successfully.');
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
        $page=Page::find($id);
        return view('pages.edit-page',compact('page'));
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
        $this->validate($request, [
            'post_title'=>'required'
        ]);
        $page=Page::find($id);
        $page->post_type='page';
        $page->post_author=auth()->id();
        $page->post_title=$request->post_title;
        $page->post_content=$request->post_content??'';
        $page->post_status=$request->post_status;
        $page->post_name=link_rewrite('posts','ID',$request->post_title,$id,'post_name');
        $page->save();
        if(!$page){
            return redirect()->back()->withInput()->withErrors('Unknown Error!');
        }
        $page->saveMeta([
            'meta_title'=>$request->get('meta_title'),
            'meta_description'=>$request->get('meta_description'),
            'meta_keyword'=>$request->get('meta_keyword'),
            '_thumbnail_id'=>$request->_thumbnail_id
        ]);
        return redirect()->to('admin/page')->with('success','Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page=Page::find($id);//destroy((request()->has('id'))?request()->get('id'):$id);
        $page->post_status='trash';
        $page->save();
        if($page->post_status!='trash'){
            return response()->json(['error'=>'Unknown error']);
        }
        return response()->json(['success'=>'Page deleted successfully']);
    }
    
}
