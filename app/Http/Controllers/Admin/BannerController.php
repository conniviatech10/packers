<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Corcel\Model\Page;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            $banners=\Corcel\Model\Taxonomy::where('taxonomy', 'slider');
            return datatables()->of($banners)->addColumn('status',function($data){
                return ($data->term->meta->status==1)?'Enabled':'Disabled';
            })->addColumn('action',function($data){
                return '<div class="table-actions">
                                    <a href="'.url('admin/banner/'.$data->term_taxonomy_id).'/edit"><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
                                    <a href="'.url('admin/banner/'.$data->term_taxonomy_id).'"  class="d-none list-delete"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                                </div>';
            })->make(true);
        }
        return view('banner.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('banner.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //upload image profile_picture
        if($request->has('type') && $request->type=='image' && $request->action=='upload'){
            $upload=new \App\Helpers\UploadHandler(['param_name'=>'file','upload_dir'=>'uploads/banner/','upload_url'=>asset('uploads/banner/').'/','image_versions'=>[],'print_response'=>false,'accept_file_types' => '/\.(gif|jpe?g|png|webp)$/i',]);
            if($upload->get_response() && count($upload->get_response()['file'])>0){
                $url=ltrim((parse_url($upload->get_response()['file'][0]->url, PHP_URL_PATH)),'/');
                $post=\Corcel\Model\Post::create([
                    'post_author'=>auth()->id(),
                    'post_title'=>$upload->get_response()['file'][0]->name,
                    'guid'=>$url,
                    'post_mime_type'=>$upload->get_response()['file'][0]->type,
                    'post_type'=>'attachment',
                    'post_content'=>'',
                    'post_excerpt'=>'',
                    'post_content_filtered'=>'',
                    'to_ping'=>'',
                    'pinged'=>0
                ]);
                //$post=\Corcel\Model\Post::find($post->ID);
                $post->guid=$url;
                $post->save();
                return response()->json($post);
            }
        }
        
        $this->validate($request,[
                'post_title'=>'required',
            ],[
                'post_title.required'=>'Please enter banner name'
                ]);
        $slides=[];
        if(count($request->banner_image)>0){
            foreach($request->banner_image as $banner_image){
                $slider=\Corcel\Model\Post::create([
                    'post_author'=>auth()->id(),
                    'post_title'=>$banner_image['title'],
                    'post_type'=>'banner_image',
                    'post_content'=>'',
                    'post_excerpt'=>'',
                    'post_content_filtered'=>'',
                    'to_ping'=>'',
                    'pinged'=>0,
                    'menu_order'=>$banner_image['sort_order']??0
                ]);
                $slider->saveMeta([
                        'link'=>$banner_image['link']??'#',
                        '_thumbnail_id'=>$banner_image['image'],
                    ]);
                $slides[]=$slider->ID;    
            }
        }
        $term=new \Corcel\Model\Term();
        $term->name=$request->post_title;
        $term->slug=\Str::slug($request->post_title,'-');
        $term->save();
        $term->saveMeta(['status'=>$request->status??0]);      
        $taxonomy=new \Corcel\Model\Taxonomy();
        $taxonomy->	term_id=$term->term_id;
        $taxonomy->taxonomy='slider';
        $taxonomy->description='';
        $taxonomy->save();
        $taxonomy->posts()->attach($slides);
        if(!$taxonomy){
            return redirect()->back()->withErrors(['message'=>'Unknown Error']);
        }
        return redirect()->back()->with('success', 'Sider added succesfully!');
        
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
        $taxonomy=\Corcel\Model\Taxonomy::find($id);
        return view('banner.edit',compact('taxonomy'));
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
        //delete a banner
        if($request->type=='slide' && $request->action=='delete' && !empty($request->slide_id)){
            $taxonomy=\Corcel\Model\Taxonomy::find($id);
            $slide=$taxonomy->posts()->detach((int)$request->slide_id);
            if(!$slide){
                return response()->json([
                        'error'=>true,
                        'message'=>'Unknown Error!'
                    ],404);
            }
            return response()->json(['success'=>'Item(s) deleted successfully']);
        }
        
        $this->validate($request, [
            'post_title'=>'required'
        ]);
        
        $slides=[];
        
        if($request->has('banner_image') && count($request->banner_image)>0){
            foreach($request->banner_image as $banner_image){
                if($banner_image['image']){
                    
                    $slider=\Corcel\Model\Post::updateOrCreate([
                            'ID'=>$banner_image['id']??0
                        ],
                        [
                        'post_author'=>auth()->id(),
                        'post_title'=>$banner_image['title']??'',
                        'post_type'=>'banner_image',
                        'post_content'=>'',
                        'post_excerpt'=>'',
                        'post_content_filtered'=>'',
                        'to_ping'=>'',
                        'pinged'=>0,
                        'menu_order'=>$banner_image['sort_order']??0
                    ]);
                    $slider->saveMeta([
                            'link'=>$banner_image['link']??'#',
                            '_thumbnail_id'=>$banner_image['image'],
                        ]);
                    $slides[]=$slider->ID;    
                }
            }
        }
        $taxonomy=\Corcel\Model\Taxonomy::find($id);
        $taxonomy->taxonomy='slider';
        $taxonomy->description='';
        $taxonomy->save();
        $term=\Corcel\Model\Term::find($taxonomy->term_id);
        $term->name=$request->post_title;
        $term->slug=\Str::slug($request->post_title,'-');
        $term->save();
        $term->saveMeta(['status'=>$request->status??0]);    
        $taxonomy->posts()->sync($slides);
        return redirect()->to('admin/banner')->with('success','Banner updated successfully.');
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
        return response()->json(['success'=>'Item(s) deleted successfully']);
    }
    
}