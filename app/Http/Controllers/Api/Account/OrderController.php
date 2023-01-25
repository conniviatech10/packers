<?php

namespace App\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use App\Mail\OrderRequest;
use App\Models\Item;
use Corcel\Model\Post;
use Illuminate\Http\Request;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(auth()->id());
       
        $per_page=request()->per_page??10;
        //$orders=Post::whereHas('author')->hasMeta('user_id',auth()->id())->type('order_request')->when('filter',function($q){
        $orders=Post::whereHas('author')->where('post_author',auth()->id())->type('order_request')->when('filter',function($q){
            if(request()->has('sort') && !empty(request()->sort)){
                $q->orderBy('id',request()->sort);
            }else{
                $q->orderBy('id','desc');
            }
            if(request()->has('status') && !empty(request()->status)){
                $q->where('post_status',request()->status);
            }
			else{
				$q->where('post_status','!=','trash');
			}
        })->paginate($per_page);
        $orders->getCollection()->transform(function ($items) {
            if($items->has('meta')){
                foreach($items->meta as $meta){
                    $items->{$meta->meta_key}=$meta->meta_value;
                    
                }

                
            }
            $category=Item::whereOrderId($items->ID)->get();
            $category=$category->map(function($meta_items){
                return [
                    $meta_items->order_item_type=>$meta_items->order_item_name,
                    'items'=>collect($meta_items->meta)->map(function($items_list){
                        return [
                            'item'=>$items_list->meta_key,
                            'quantity'=>$items_list->meta_value
                        ];
                    })
                ];
            });
            $items->category=$category;
            
            //unset unwanted value
            $items=collect($items)->forget(['meta','terms','keywords','main_category','keywords_str','taxonomies','ping_status','post_password','post_mime_type']);
            return $items;
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
        /*$this->validate($request,[
            'moving_type'=>'required',
            'shifting_date'=>'required',
            'source_address'=>'required',
            'destination_address'=>'required'
        ]);*/
        
        // dd("In");
        $order=new Post();
        $order->post_type='order_request';
        $order->post_author=auth()->id()??0;
        $order->post_title='Order Request';
        $order->post_content='<!-- Ordr request submission -->';
        $order->post_excerpt='';
        $order->to_ping='';
        $order->pinged=0;
        $order->post_content_filtered='';
        $order->post_status='Pending';
        $order->save();
		$order->saveMeta($request->except(['category']));

        $order->saveMeta([
            'user_id'=>auth()->id()??0,
            /*'moving_type'=>$request->moving_type,
            'moving_type_item'=>$request->moving_type_item,
            'shifting_date'=>$request->shifting_date,
            'shifting_time'=>$request->shifting_time,
            'source_address'=>$request->source_address,
            'source_latitude'=>$request->source_latitude,
            'source_longitude'=>$request->source_longitude,
            'destination_address'=>$request->destination_address,
            'destination_latitude'=>$request->destination_latitude,
            'destination_longitude'=>$request->destination_longitude,*/
            'ip_address'=>$request->ip(),
            'user_agent'=>$request->userAgent(),
            'assigned'=>false,
            'assigned_to'=>0

            // if($request->action=='update' && $request->type=='user'){
            //     $order=Post::find($id);
            //     $order->saveMeta([
            //         'assigned_to'=>$request->assignd_to,

        ]);
        if($request->has('category') && count($request->category)>0){
            foreach($request->category as $items){
                $item=new Item();
                $item->order_id=$order->ID;
                $item->order_item_type='category';
                $item->order_item_name=$items['name'];
                $item->save();
                if(count($items['items'])>0){
                    //dd($items['items']);
                    $item->saveMeta($items['items']);
                }
            }
        }
        //sending email
        \Mail::to(auth()->user()->email)->cc('enquiry@packerswala.com')->send(new OrderRequest($order->ID));
        //return (new OrderRequest($order->ID))->render();
        return response()->json([
            'success'=>true,
            'message'=>'Thank you for submit your Request, our Team Will get back to you shortly . or Directly call us'
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
       
        $order=Post::whereId($id)->type('order_request')->first();

       
        if($order->has('meta')){
            foreach($order->meta as $meta){
                $order->{$meta->meta_key}=$meta->meta_value;
            }

        }
        $category=Item::whereOrderId($order->ID)->get();
            $category=$category->map(function($meta_items){
                return [
                    $meta_items->order_item_type=>$meta_items->order_item_name,
                    'items'=>collect($meta_items->meta)->map(function($items_list){
                        return [
                            'item'=>$items_list->meta_key,
                            'quantity'=>$items_list->meta_value
                        ];
                    })
                ];
            });
        $order->category=$category;
        $order->assigned_to=\App\Models\User::find($order->assigned_to)??[];
        $order=collect($order)->forget(['meta','terms','keywords','main_category','keywords_str','taxonomies','ping_status','post_password','post_mime_type','comment_status']);
        return response()->json([
            'success'=>true,
            'data'=>$order
        ]);
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
    public function appointment(Request $request, $id){

        // dd($id);
        $order=Post::find($id);
        //$appointment=Post::wherePostParent($id)->type('order_appointment')->hasMeta('user_id',auth()->id())->orderBy('ID','desc')->get();
        $appointment=Post::wherePostParent($id)->type('order_appointment')->hasMeta('user_id')->orderBy('ID','desc')->get();
        $appointment=$appointment->map(function($items)use($order){
            // $user=\App\Models\User::find($order->assigned_to)??[];
            $user=\App\Models\User::find($items->user_id);

            // dd($items->user_id);
            if($user){
                $user->role=$user->roles()->first()->name;
                $user=collect($user)->except('roles');
            }
            $assigned_to= \App\Models\User::find($order->assigned_to)??[];
            $assigned_to->role=$assigned_to->roles()->first()->name;
            return [
                    'id'=>$items->ID,
                    'visit_type'=>$items->visit_type,
                    'date'=>$items->date,
                    'time'=>$items->time,
                    'note'=>$items->note,
                    'status'=> $order->post_status, //$items->post_status,
                    // 'assigned_to'=>$user??[],
                    'assigned_to'=>$assigned_to, //\App\Models\User::find($order->assigned_to)??[],
                    'qoutation_amount' => $items->qoutation_amount,
                    'advance_amount' => $items->advance_amount,
                    // dd($order->assigned_to),
                ];
        });
        if($appointment->isEmpty()){
            return response()->json([
                'error'=>true,
                'mesage'=>'No appointment!',
                'data'=>$appointment
            ]);    
        }
        return response()->json([
                'success'=>true,
                'data'=>$appointment
            ]);
    }
}
