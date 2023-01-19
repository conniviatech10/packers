<?php

namespace App\Mail;

use App\Models\Item;
use Corcel\Model\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $order_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order_id)
    {
        $this->order_id=$order_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //build data
        $order=Post::whereId($this->order_id)->type('order_request')->first();
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
        $order=collect($order)->forget(['meta','terms','keywords','main_category','keywords_str','taxonomies']);
        return $this->markdown('emails.orders.request')->with('order',$order);
    }
}
