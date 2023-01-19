@component('mail::message')
# Order Request from {!! ucwords(auth()->user()->name) !!} 
<b>{{ __('Invoice #'.$order['ID'])}}</b><br/>

<table width="600" cellspacing="0" cellpadding="0">
    <tr>
        <td width="50%">
            Customer Details
            <address>
                <strong>{{ __(ucwords(auth()->user()->name))}},</strong><br>{{ __('Phone: '.auth()->user()->mobile)}}<br>{{ __('Email: '.auth()->user()->email)}}
            </address> 
        </td>
        <td width="50%">
            <b>Moving From:</b> {{ __($order['source_address'])}}<br>
            <b>Moving To:</b> {{ __($order['destination_address'])}}<br>
            <b>{{ __('Shifting Date:')}}</b> {{ $order['shifting_date']}}<br>
            <b>{{ __('Shifting Time:')}}</b> {{ $order['shifting_time']}}<br>
            <b>{{ __('Status:')}}</b> {{ $order['post_status'] }}<br>
        </td>
    </tr>
    <tr>
        <td colspan="2" width="100%">
            <table align="center" class="table table-striped" width="100%">
                <thead>
                    <tr>
                        <th>{{ __('Sr. No')}}</th>
                        <th>{{ __('Item name')}}</th>
                        <th>{{ __('Qty')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @if($order['category'])
                        @foreach($order['category'] as $category)
                            @foreach($category['items'] as $index=>$items)
                                <tr>
                                    <td>{!! $index+1 !!}</td>
                                    <td>{!! ucwords($items['item']) !!}</td>
                                    <td>{!! ucwords($items['quantity']) !!}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endif
                </tbody>
            </table>
        </td>
    </tr>
</table>

<br/>
<br/>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
