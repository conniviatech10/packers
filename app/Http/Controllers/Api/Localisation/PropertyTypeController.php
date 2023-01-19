<?php

namespace App\Http\Controllers\Api\Localisation;

use App\Http\Controllers\Controller;
use Corcel\Model\Taxonomy;
use Illuminate\Http\Request;

class PropertyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $property_types=Taxonomy::where('taxonomy','mover_type')->get();
        if($property_types->count()>1){
            $property_types=$property_types->map(function($items){
                $items->mover_type_item=Taxonomy::where('parent',$items->term_taxonomy_id)->get()
                        ->map(function($val){
                            return [
                                'id'=>$val->term_taxonomy_id,
                                'name'=>$val->term->name,
                                'slug'=>$val->term->slug,
                                'type'=>$val->taxonomy
                            ];
                        });

                return [
                    'id'=>$items->term_taxonomy_id,
                    'name'=>$items->term->name,
                    'slug'=>$items->term->slug,
                    'type'=>$items->taxonomy,
                    'items'=>$items->mover_type_item
                ];
            });
        }
        return response()->json([
            'success'=>true,
            'total'=>$property_types->count(),
            'data'=>$property_types
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
