@extends('layouts.main') 
@section('title', 'Customer Request')
@section('content')
<!-- push external head elements to head -->
@push('head')
    <link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
@endpush
    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-shopping-cart bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Payment Logs')}}</h5>
                            <span>{{ __('List of Users ')}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('admin')}}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">{{ __('Users')}}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- start message area-->
            @include('include.message')
            <!-- end message area-->
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="card-header"><h3>{{ __('Payment Logs')}}</h3></div>
                    <div class="card-body">
                        <table id="lead_table" class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('#ID')}}</th>
                                    <th>{{ __('Name')}}</th>
                                    <th>{{ __('Mobile')}}</th>
                                    <th>{{ __('Email')}}</th>
									<th>{{ __('Type')}}</th>
                                     <th>{{ __('Source Address')}}</th>  
                                    <th>{{ __('Destination Address')}}</th>
                                    <th>{{ __('Shiftng Date')}}</th>  
                                    <th>{{ __('Payment Status')}}</th>
                                    <!-- <th>{{ __('Assigned To')}}</th>-->
                                    <!-- <th>{{ __('Status')}}</th> -->
									<th>{{ __('Date')}}</th> 
                                    <!-- <th>{{ __('Action')}}</th> -->
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- push external js -->
    @push('script')
    <script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script>
        $(function(){
            var searchable = [];
            var selectable = []; 
            

            var dTable = $('#lead_table').DataTable({

                order: [],
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                processing: true,
                responsive: false,
                serverSide: true,
                processing: true,
                language: {
                processing: '<i class="ace-icon fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>'
                },
                scroller: {
                    loadingIndicator: false
                },
                pagingType: "full_numbers",
                dom: "<'row'<'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tipr",
                ajax: {
                    url: '{{ url()->current() }}',
                    type: "get"
                },
                columns: [
                    {data:'ID', name: 'ID',render: function (data, type, row, meta) {return meta.row + meta.settings._iDisplayStart + 1;}},
                    {data:'name', name: 'name', orderable: false, searchable: false},
                    {data:'mobile', name: 'moble', orderable: false, searchable: false},
                    {data:'email', name: 'email', orderable: false, searchable: false,visible:false},
					{data:'moving_type', name: 'moving_type', orderable: false, searchable: false},
                    {data:'source_address', name: 'source_address', orderable: false, searchable: false},
                    {data:'destination_address', name: 'destination_address', orderable: false, searchable: false},
                    {data:'shifting_date', name: 'shifting_date', orderable: false, searchable: false},
                    {data:'payment_status', name: 'payment_status', orderable: false, searchable: false},
                    // {data:'assigned_to', name: 'assigned_to', orderable: false, searchable: false},
                    // {data:'post_status', name: 'post_status', orderable: false, searchable: false},
					{data:'post_date', name: 'post_date', orderable: false, searchable: false},
                    //only those have manage_user permission will get access
                    // {data:'action', name: 'action', orderable: false, searchable: false}

                ],
				columnDefs: [
                    {render: function (data, type, row, meta) {
                            return moment(new Date(data)).format('d MMMM YYYY');
                        },
                        "targets":-2,
                    },
                ],
                buttons: [
                    {
                        extend: 'copy',
                        className: 'btn-sm btn-info',
                        title: 'Users',
                        header: false,
                        footer: true,
                        exportOptions: {
                            // columns: ':visible'
                        }
                    },
                    {
                        extend: 'csv',
                        className: 'btn-sm btn-success',
                        title: 'Users',
                        header: false,
                        footer: true,
                        exportOptions: {
                            // columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'btn-sm btn-warning',
                        title: 'Users',
                        header: false,
                        footer: true,
                        exportOptions: {
                            // columns: ':visible',
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'btn-sm btn-primary',
                        title: 'Users',
                        pageSize: 'A2',
                        header: false,
                        footer: true,
                        exportOptions: {
                            // columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn-sm btn-default',
                        title: 'Users',
                        // orientation:'landscape',
                        pageSize: 'A2',
                        header: true,
                        footer: false,
                        orientation: 'landscape',
                        exportOptions: {
                            // columns: ':visible',
                            stripHtml: false
                        }
                    }
                ],
                initComplete: function () {
                    var api =  this.api();
                    api.columns(searchable).every(function () {
                        var column = this;
                        var input = document.createElement("input");
                        input.setAttribute('placeholder', $(column.header()).text());
                        input.setAttribute('style', 'width: 140px; height:25px; border:1px solid whitesmoke;');

                        $(input).appendTo($(column.header()).empty())
                        .on('keyup', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });

                        $('input', this.column(column).header()).on('click', function(e) {
                            e.stopPropagation();
                        });
                    });

                    api.columns(selectable).every( function (i, x) {
                        var column = this;

                        var select = $('<select style="width: 140px; height:25px; border:1px solid whitesmoke; font-size: 12px; font-weight:bold;"><option value="">'+$(column.header()).text()+'</option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function(e){
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column.search(val ? '^'+val+'$' : '', true, false ).draw();
                                e.stopPropagation();
                            });

                        $.each(dropdownList[i], function(j, v) {
                            select.append('<option value="'+v+'">'+v+'</option>')
                        });
                    });
                }
            });
            //delete
            $('#lead_table').on('click','a.btn-delete',function(e){
                e.preventDefault();
                if(confirm("Are you sure you want to delete this?")){
                    $.ajax({
                        type:'post',
                        url:$(this).attr('href'),
                        data:{_method:'delete',_token:'{{ csrf_token() }}'},
                        success:function(data){
                            dTable.ajax.reload( null, false ); 
                        }
                    });
                }
                else{
                    return false;
                }
                 
            });
        });
    </script>
    @endpush

@stop