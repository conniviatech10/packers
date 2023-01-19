@component('mail::message')
# Get Quotation from {{ucwords($order->author->name)}}

@component('mail::table')
| name | value |
| :---- |------:|
@foreach($data as $key=>$item)
|{{ucwords($key)}}|{{$item}}|  
@endforeach
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
