@extends('layouts.main') 
@section('title', 'Request Detail')
@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-file-text bg-blue"></i>
                    <div class="d-inline">
                        <h5>{{ __('Request')}}</h5>
                        <span>{{ __('View Request')}}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <nav class="breadcrumb-container" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{route('admin')}}"><i class="ik ik-home"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Request')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card">
            <div class="card-header"><h3 class="d-block w-100">{{ __('#'.$order->ID)}}<small class="float-right">{{ __('Date: '.$order->post_date)}}</small></h3></div>
            <div class="card-body">
                <div class="row invoice-info">
                    <div class="col-sm-3 invoice-col">
                        Customer Details
                        <address>
                            <strong>{{ __(ucwords($order->user->name))}},</strong><br>{{ __('Phone: '.$order->user->mobile)}}<br>{{ __('Email: '.$order->user->email)}}
                        </address>
                    </div>
                    <div class="col-sm-3 invoice-col">
                        <b>{{ __('Shifting Type:')}}</b> {{ ucwords($order->moving_type)}}<br>
                        <strong>Moving From</strong>
                        <address>
                            {{ __($order->source_address)}}
							@if(!empty($order->meta->source_floor))
                            <br/><strong>Source Floor: </strong>{{$order->meta->source_floor}}
                            @endif
							@if(!empty($order->meta->source_service_lift))
                            <br/><strong>Service Lift: </strong>{{$order->meta->source_service_lift}}
                            @endif
                            @if(!empty($order->meta->source_normal_lift))
                            <br/><strong>Normal Lift: </strong>{{$order->meta->source_normal_lift}}
                            @endif
                        </address>
                    </div>
                    <div class="col-sm-3 invoice-col">
                        <b>{{ __('Shifting Item:')}}</b> {{ ucwords($order->moving_type_item)}}<br>
                        <strong>Moving To</strong>
                        <address>
                            {{ __($order->destination_address)}}
							@if(!empty($order->meta->destination_floor))
                            <br/><strong>Destination Floor: </strong>{{$order->meta->destination_floor}}
                            @endif
							@if(!empty($order->meta->destination_service_lift))
                            <br/><strong>Service Lift: </strong>{{$order->meta->destination_service_lift}}
                            @endif
                            @if(!empty($order->meta->destination_normal_lift))
                            <br/><strong>Normal Lift: </strong>{{$order->meta->destination_normal_lift}}
                            @endif
                        </address>
                    </div>
                    <div class="col-sm-3 invoice-col">
                        <b>{{ __('Request No. #'.$order->ID)}}</b><br/>
                        <b>{{ __('Shifting Date:')}}</b> {{ $order->shifting_date}}<br>
                        <b>{{ __('Shifting Time:')}}</b> {{ $order->shifting_time}}<br>
                        <b>{{ __('Date:')}}</b> {{ $order->post_date }}<br>
                        <b>{{ __('Status:')}}</b> {{ $order->post_status }}<br>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Category')}}</th>
                                    <th>{{ __('Item name')}}</th>
                                    <th>{{ __('Qty')}}</th>
                                    <th>{{ __('Volume(Cft)')}}</th>
                                    
                                </tr>
                            </thead>
                            
                            <tbody>
                                @if($order->category)
                                @php  $sum = 0; @endphp
                                @php  $cftsum = 0; @endphp
                                    @foreach($order->category as $category)
                                        @foreach($category['items'] as $items)
                                        @php  $sum += $items['quantity'];  @endphp
                                        @php  $cftsum += $items['cft'];  @endphp
                                            <tr>
                                                <td>{!! ucwords($category['category']) !!}</td>
                                                <td>{!! ucwords($items['item']) !!}</td>
                                                <td>{!! ucwords($items['quantity']) !!}</td>
                                                <td>{!! ucwords($items['cft']) !!}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    <thead>
                                <tr>
                                    <th>{{ __('')}}</th>
                                    <th>{{ __('')}}</th>
                                    <th>{{ __('Total Qty')}} : {{$sum}}</th>
                                    <th>{{ __('Total Volume(Cft)')}} : {{$cftsum}}</th>
                                </tr>    
                            <thead>
                                @endif
                            </tbody>
                            
                        </table>
                    </div>
                </div>
       
               
                <div class="row">
                    <div class="col-6">
                        <p class="lead">Assigned To:</p>
                        @if($order->assigned_to)
                            @php
                            $user=\App\Models\User::find($order->assigned_to)??'';
                            @endphp
                            @if($user)
                            <b>{{ __('Name')}}: </b> {{ $user->name}}<br>
                            <b>{{ __('Email')}}: </b> {{ $user->email}}<br>
                            <b>{{ __('Mobile')}}: </b> {{ $user->mobile}}<br>
                            @endif
                        @endif
                        @if(auth()->user()->hasRole('Super Admin')) 
                        <div class="form-group row">
                            <div class="col-md-6">
                                <select name="assign_to" class="form-control">
                                    <option>Assign/ Change</option>
                                    @if($users)
                                    @foreach($users as $options)
                                    <option value="{!! $options->id !!}" {!! ($order->assigned_to==$options->id)?'selected':'' !!}>{!! ucwords($options->name) !!}</option>
                                    @endforeach
                                    @endif
                                </select>   
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="col-6">
                        <p class="lead">{{ __('Status')}}</p>
                        <b>{{ __('Status')}}: </b> {{ ucwords($order->status)}}<br>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <select name="status" class="form-control">
                                    <option>Change / Update Status </option>
                                    @if($order_status)
                                    @foreach($order_status as $key=>$val)
                                    <option value="{{$val->post_name}}" {!! ($order->post_status==$val->post_name)?'selected':'' !!}>{{$val->post_title}}</option>
                                    @endforeach
                                    @endif
                                </select>   
                            </div>
                            <div class="col-md-6"><a href="#" class="btn btn-success" data-toggle="modal" data-target="#appointmentModalCenter"><i class="ik ik-clock"></i> Add Appointment</a></div>
                        </div>
                        
                        <p class="lead">{{ __('Appointment')}}</p>
                        @forelse($order->appointments as $appointment)
                        
                        <b>{{ __('Date')}}: </b> {{ $appointment->date }} 
                        <b>{{ __('Time')}}: </b> {{ $appointment->time }}
                        <b>{{ __('Note')}}: </b> {{ $appointment->note }}<br>
                        <b>{{ __('qoutation_amount')}}: </b> {{ $appointment->qoutation_amount }}<br>
                        <b>{{ __('advance_amount')}}: </b> {{ $appointment->advance_amount }}<br>
                       
                        @empty
                        @endforelse
                    </div>
                </div>
                
            

                <div class="row no-print">
                    <div class="col-12">
                        <button type="button" class="btn btn-success pull-right d-none"><i class="fa fa-credit-card"></i> {{ __('Submit')}}</button>
                        <button type="button" class="btn btn-primary pull-right" onclick="window.history.go(-1); return false;"><i class="fa fa-reply"></i> {{ __('Back')}}</button>
                    </div>
                </div>
            </div>
        </div>
</div>
<div class="modal fade" id="appointmentModalCenter" tabindex="-1" role="dialog" aria-labelledby="appointmentModalCenterLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="appointmentModalCenterLabel">Add Appointment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="control-label" for="date">Preferred Date</label>
                                <input id="date" name="date" type="date" placeholder="Preferred Date" class="form-control input-md">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="date">Preferred Time</label>
                                <input id="time" name="time" type="time" placeholder="Preferred Time" class="form-control input-md">
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label" for="date">Quotation Amount</label>
                                <input  class="control-label" id="number" name="qoutation_amount" type="number" placeholder="enter quotation amount" class="form-control input-md"> 
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label class="control-label" for="date">Advance Amount</label>
                                <input  class="control-label" id="number" name="advance_amount" type="number" placeholder="enter 10% advance amount" class="form-control input-md"> 
                            </div>
                            
                            <!-- <div class="form-group col-md-6">
                                <label class="control-label" for="date">Remaining Amount : </label>
                                <input  class="remaining_amount" id="number" name="remaining_amount" type="number" placeholder="enter 10% advance amount" class="form-control input-md">
                            </div> -->
                          

                            <div class="form-group col-md-12">
                                <label for="inputnote">Note(Optional)</label>
                                <textarea class="form-control" id="inputnote" name="note" placeholder="Note" rows="1"></textarea>
                              </div>
                        </div>    
                    </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
@push('script')
<script>
    $(function(){
        var notyf = new Notyf();
        $('select[name="assign_to"]').on('change',function(e){
            e.preventDefault();
            var user=$('select[name="assign_to"]').val();
            $.ajax({
                type:'post',
                url:'{!! url()->current() !!}',
                data:{assignd_to:user,_method:'patch',_token:'{!! csrf_token() !!}',action:'update',type:'user'},
                success:function(data){
                    if(data.success){
                        notyf.success({
                            message:data.message,
                            position: {
                                x: 'center',
                                y: 'top',
                            }
                        });
                        location.reload();
                    }
                }
            });
        });
        $('select[name="status"]').on('change',function(e){
            e.preventDefault();
            var status=$('select[name="status"]').val();
            $.ajax({
                type:'post',
                url:'{!! url()->current() !!}',
                data:{status:status,_method:'patch',_token:'{!! csrf_token() !!}',action:'update',type:'status'},
                success:function(data){
                    if(data.success){
                        notyf.success({
                            message:data.message,
                            position: {
                                x: 'center',
                                y: 'top',
                            }
                        });
                        location.reload();
                    }
                }
            });
        });
        $('#appointmentModalCenter').on('click','button[type="submit"]',function(e){
            e.preventDefault();
            $.ajax({
                type:'post',
                url:'{!! url('admin/request-appointment') !!}',
                data:{_token:'{!! csrf_token() !!}',order_id:{{$order->ID}},date:$('#appointmentModalCenter form input[name="date"]').val(),time:$('#appointmentModalCenter form input[name="time"]').val(),note:$('#appointmentModalCenter form textarea[name="note"]').val(),qoutation_amount:$('#appointmentModalCenter form input[name="qoutation_amount"]').val(),advance_amount:$('#appointmentModalCenter form input[name="advance_amount"]').val(),assign_to:$('select[name="assign_to"]').val()},
                success:function(data){
                    if(data.success){
                        notyf.success({
                            message:data.message,
                            position: {
                                x: 'center',
                                y: 'top',
                            }
                        });
                        location.reload();
                    }
                }
            });


            
        });

       

        // $('#status').on('click','button[type="submit"]',function(e){
        //     e.preventDefault();
        //     $.ajax({
        //         type:'post',
        //         url:'{!! url('admin/request') !!}',
        //         data:{_token:'{!! csrf_token() !!}',order_id:{{$order->ID}},date:$('#status form input[name=""]').val(),time:$('#appointmentModalCenter form input[name="time"]').val(),note:$('#appointmentModalCenter form textarea[name="note"]').val(),assign_to:$('select[name="assign_to"]').val()},
        //         success:function(data){
        //             if(data.success){
        //                 notyf.success({
        //                     message:data.message,
        //                     position: {
        //                         x: 'center',
        //                         y: 'top',
        //                     }
        //                 });
        //                 location.reload();
        //             }
        //         }
        //     });
            
        // });

    });
</script>

@endpush
@stop