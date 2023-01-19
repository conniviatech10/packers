<?php

namespace App\Mail;

use Corcel\Model\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GetQuotation extends Mailable
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
        $order=Post::whereId($this->order_id)->type('get_quotation')->first();
        $data=new \stdClass;
        if($order->has('meta')){
            foreach($order->meta as $meta){
                $data->{$meta->meta_key}=$meta->meta_value;
            }
        }
        $data=collect($data)->except(['ip_address','user_agent','assigned','assigned_to']);
        return $this->markdown('emails.orders.quotation')->with('order',$order)->with('data',$data);
    }
}
