<?php

namespace App\Http\Controllers\Api;

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
        $banner=\Corcel\Model\Taxonomy::where('taxonomy', 'slider')->first();
        $banner->slides=$banner->posts->map(function($items){
            return [
                    'id'=>$items->ID,
                    'name'=>$items->post_title,
                    'link'=>$items->link,
                    'image'=>asset($items->image),
                    'order'=>$items->menu_order
                ];
        });
        return response()->json([
                'success'=>true,
                'data'=>[
                        'banner'=>[
                                'id'=>$banner->term->term_id,
                                'name'=>$banner->term->name,
                                'status'=>$banner->term->meta->status,
                                'slides'=>$banner->slides
                            ]
                    ]
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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