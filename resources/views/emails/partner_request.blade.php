@component('mail::message')

# Partnewrship Request from {!! ucwords($post->name) !!} 

@component('mail::table')
| Name       | Value  |
| ------------- | --------:|
@if($post->meta()->count()>0)
@foreach($post->meta as $meta )
| {{ucwords($meta->meta_key)}} | {{$meta->meta_value}} |
@endforeach
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent