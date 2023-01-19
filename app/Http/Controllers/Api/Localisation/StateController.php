<?php

namespace App\Http\Controllers\Api\Localisation;

use App\Http\Controllers\Controller;
use App\Models\Localisation\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //default country
        $country='India';
        if(request()->has('country') && !empty(request()->country)){
            $country=request()->country;
        }
        $states=State::when('request',function($q)use($country){
            if(request()->has('q') && !empty(request()->q)){
                $q->where('name','like','%'.request()->q.'%');
            }
            //if(request()->has('country') && !empty(request()->country)){
            if($country){    
                $q->whereHas('country',function($qs)use($country){
                    $qs->where('name',$country);
                });
            }
        })->get();
        return response()->json([
            'success'=>true,
            'count'=>$states->count(),
            'data'=>$states
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
