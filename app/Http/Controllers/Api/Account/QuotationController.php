<?php

namespace App\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use App\Mail\GetQuotation;
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
        $per_page=request()->per_page??10;
        $orders=Post::wherePostAuthor(auth()->id())->type('get_quotation')->when('filter',function($q){
            if(request()->has('sort') && !empty(request()->sort)){
                $q->orderBy('id',request()->sort);
            }else{
                $q->orderBy('id','desc');
            }
            if(request()->has('status') && !empty(request()->status)){
                $q->where('post_status',request()->status);
            }
        })->paginate($per_page);
        $orders->getCollection()->transform(function ($items) {
            $data=new \stdClass;
            if($items->has('meta')){
                foreach($items->meta as $meta){
                    $data->{$meta->meta_key}=$meta->meta_value;
                }
            }
            return $data;
         });
        return response()->json($orders);
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
        $validator=validator(request()->all(),[
            'email'=>'required|email',
            'mobile'=>'required',
        ]);
        if ($validator->fails()){
            return response(['error'=>true,'message'=>$validator->errors()], 422);
        }
        $order=new Post();
        $order->post_type='get_quotation';
        $order->post_author=auth()->id()??0;
        $order->post_title='Get Quotation';
        $order->post_content='<!-- Get quotation submission -->';
        $order->post_excerpt='';
        $order->to_ping='';
        $order->pinged=0;
        $order->post_content_filtered='';
        $order->post_status='Pending';
        $order->save();
        $request->request->add([
            'ip_address'=>$request->ip(),
            'user_agent'=>$request->userAgent(),
            'assigned'=>false,
            'assigned_to'=>0
        ]);
        $order->saveMeta($request->all());
        \Mail::to(auth()->user()->email)->cc('enquiry@packerswala.com')->send(new GetQuotation($order->ID));
        //return (new GetQuotation($order->ID))->render();
        return response()->json([
            'success'=>true,
            'message'=>'Quotation sent successfully'
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
        //
    }
}
