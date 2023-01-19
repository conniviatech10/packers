<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Items;
use Illuminate\Http\Request;

class CategoryItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page=request()->per_page??10;
        $category_items=Items::when('filter',function($q){
            if(request()->has('q') && !empty(request()->q)){
                $q->where('item_title','like','%'.request()->q.'%');
                $q->orWhereHas('category',function($qs){
                    $qs->where('item_name','like','%'.request()->q.'%');
                });
            }
            if(request()->has('sort') && !empty(request()->sort)){
                $q->orderBy('ID',request()->sort);
            }
            
        })->paginate($per_page);
        $categories=Category::where('status',1)->get();
        return view('category.category-item',compact('category_items','categories'));
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
            'item_title'=>'required',
            'category'=>'required'
        ]);


        
        $item_form_name=\Str::slug($request->item_title.'_'.\App\Models\Category::find($request->category)->item_name,'_');
        $category_items=new Items();
        $category_items->item_title=$request->item_title;
        $category_items->item_form_name=$request->item_form_name??$item_form_name;
        $category_items->item_category=$request->category;
        $category_items->cft = $request->cft??"-";
        $category_items->status=$request->status??0;
        $category_items->extra_hours_needed=$request->extra_hours_needed??0;
        $category_items->save();
        // dd($category_items);
        if(!$category_items){
            return response()->json([
                'error'=>true,
                'message'=>'Unknown Error!'
            ]);
        }
        return response()->json([
            'success'=>true,
            'message'=>'Category Item Added successfully'
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
        $this->validate($request,[
            'item_title'=>'required',
            'category'=>'required'
        ]);
        $item_form_name=\Str::slug($request->item_title.'_'.\App\Models\Category::find($request->category)->item_name,'_');
        $category_items=Items::find($request->item_id);
        $category_items->item_title=$request->item_title;
        $category_items->item_form_name=$request->item_form_name??$item_form_name;
        $category_items->item_category=$request->category;
        $category_items->cft = $request->cft;
        $category_items->status=$request->status??0;
        $category_items->extra_hours_needed=$request->extra_hours_needed??0;
        $category_items->save();
        if(!$category_items){
            return response()->json([
                'error'=>true,
                'message'=>'Unknown Error!'
            ]);
        }
        return response()->json([
            'success'=>true,
            'message'=>'Category Item updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category_item=Items::find($id);
        $category_item->delete();
        return response()->json([
            'success'=>true,
            'message'=>'Category item deleted successfully'
        ]);
    }
}
