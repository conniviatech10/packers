<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\User;
use Corcel\Model\Post;
use Illuminate\Http\Request;
use DB;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // dd("In");
        if(request()->ajax()){
            $sort=request()->sort??'desc';
            $orders=Post::whereHas('author')->where('post_status','!=','trash')->type('order_request')->orderBy('id',$sort)->when('request',function($q){
                if(auth()->user()->hasRole('Admin')){
                    $q->hasMeta('assigned_to',auth()->id());
                }
                if(auth()->user()->hasRole('User')){
                    $q->wherePostAuthor(auth()->id());
                }
                if(request()->has('status') && !empty(request()->status)){
                    $q->where('post_status',request()->status);
                }
            });
			return datatables()->of($orders)->addColumn('name',function($orders){
                return \App\Models\User::find($orders->post_author)->name;
            })->addColumn('mobile',function($orders){
                return \App\Models\User::find($orders->post_author)->mobile;
            })->addColumn('email',function($orders){
                return \App\Models\User::find($orders->post_author)->email;
            })->addColumn('moving_type',function($orders){
                return $orders->moving_type_item;
            })->addColumn('source_address',function($orders){
                return $orders->source_address;
            })->addColumn('destination_address',function($orders){
                return $orders->destination_address;
            })->addColumn('shifting_date',function($orders){
                return $orders->shifting_date.' '.$orders->shifting_time ;
            })->addColumn('assigned_to',function($orders){
                return User::find($orders->assigned_to)->name??'N/A';
            })->addColumn('action',function($orders){
                return '<div class="table-actions">
                                <a href="'.url('admin/request/'.$orders->ID).'" ><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
                                <a href="'.url('admin/request/'.$orders->ID).'" class="btn-delete"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                            </div>';
            })->make(true);
        }
        return view('lead.index');
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
        // dd("In");
        $order=Post::find($id);
        $order->user=User::find($order->post_author);
        $category=Item::whereOrderId($order->ID)->get();
        
        $category=$category->map(function($meta_items){
            return [
                $meta_items->order_item_type=>$meta_items->order_item_name,
                'items'=>collect($meta_items->meta)->map(function($items_list){
                    //  dd($items_list);
                    $item=\App\Models\Items::where('item_title',$items_list->meta_key)->first();
                   
                    return [
                        'item'=>$items_list->meta_key,
                        'quantity'=>$items_list->meta_value,
                        'cft'=>$items_list->meta_value*$item->cft??'',
                        // 'totalquantity'=> sum($items_list->meta_value)  
                    ];
                }) 
            ];
        });
        // $total_qty  = DB::table('order_itemmeta')->where('order_item_id',$order->ID)->get();
        // dd($total_qty, $order->id);
        $order->category=$category;
        $users=User::role('Team Member')->get();
		$order_status=Post::type('order_status')->published()->get();
        $order->appointments=Post::wherePostParent($id)->type('order_appointment')->orderBy('ID','desc')->get();
        return view('lead.show',compact('order','users','order_status'));
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
        // dd("In");
        if($request->action=='update' && $request->type=='user'){
            $order=Post::find($id);
            $order->saveMeta([
                'assigned_to'=>$request->assignd_to,
               
               

            ]);
            return response()->json([
                'success'=>true,
                'message'=>'Updated successfully'
            ]);
        }
        if($request->action=='update' && $request->type=='status'){
            $order=Post::find($id);
            $order->post_status=$request->status;
            $order->save();
            return response()->json([
                'success'=>true,
                'message'=>'Updated successfully'
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
    
    public function storeAppointment(Request $request){
        $this->validate($request,[
            'date'=>'required',
        ]);
        $appointment=new Post();
        $appointment->post_type='order_appointment';
        $appointment->post_author=auth()->id()??0;
        $appointment->post_title='Order Appointment';
        $appointment->post_content='<!-- Ordr request appointment -->';
        $appointment->post_excerpt='';
        $appointment->to_ping='';
        $appointment->pinged=0;
        $appointment->post_content_filtered='';
        $appointment->post_status='Pending';
        $appointment->post_parent=$request->order_id;
        $appointment->save();
        $appointment->saveMeta([
                'user_id'=>$request->assign_to??0,    
                'visit_type'=>$request->visit_type??'Quotations',
                'date'=>$request->date,
                'time'=>$request->time,
                'note'=>$request->note,
                'currency'=>'inr',
                'qoutation_amount' =>$request->qoutation_amount,
                'advance_amount' => $request->advance_amount,
                // 'remaining_amount'=>$request->remaining_amount
            ]);
            
        return response()->json([
            'success'=>true,
            'message'=>'Appointment added successfully'
        ]);
        
    }
}
