<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Corcel\Model\Post;
use Illuminate\Http\Request;

class QuotationController extends Controller
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
            $orders=Post::where('post_status','!=','trash')->type('get_quotation')->orderBy('id',$sort)->when('request',function($q){
                if(auth()->user()->hasRole('Admin')){
                    $q->hasMeta('assigned_to',auth()->id());
                }
                if(auth()->user()->hasRole('User')){
                    $q->wherePostAuthor(auth()->id());
                }
            });
            $tables=datatables()->of($orders);
            if(request()->has('columns')){
                foreach(request()->columns as $column){
                    if($column['name']){
                        $tables->addColumn($column['name'],function($orders)use($column){
                            return $orders->{$column['name']};
                        });
                    }
                }
            }
            $tables->editColumn('post_date','{{\Carbon\Carbon::parse($post_date)->format("d/m/Y")}}')->addColumn('assigned_to',function($orders){
                return User::find($orders->assigned_to)->name??'N/A';
            })->addColumn('assigned_user_id',function($orders){
                return User::find($orders->assigned_to)->id??'';
            })->addColumn('action',function($orders){
                return '<div class="table-actions">
                                <a class="d-none" href="'.url('admin/request/'.$orders->ID).'" ><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
                                <a href="'.url('admin/request/'.$orders->ID).'" class="btn-delete"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                            </div>';
            });
            return  $tables->make(true);
        }    
        return view('quotation.index');
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
        $order=Post::find($id);
        if($request->action=='update' && !empty($request->assigned_to)){
            $order->saveMeta([
                'assigned_to'=>$request->assigned_to??0
            ]);
            return response()->json([
                'success'=>true,
                'message'=>'Assigned successfully'
            ]);
        }
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
