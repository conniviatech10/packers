@extends('layouts.main') 
@section('title', 'Partner Request')
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
                        <i class="ik ik-inbox bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Partner Request')}}</h5>
                            <span>{{ __('Partner Request List')}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('admin')}}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Partner Request</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h3>{{ __('Partner Request')}}</h3></div>
                    <div class="card-body">
						<div class="table-responsive rounded card-table">
                        <table id="page_table" class="table" style="margin-left: 0px;">
                            <thead>
                                <tr>
                                    <th>{{ __('Id')}}</th>
                                    <th>{{ __('Name')}}</th>
                                    <th>{{ __('Company')}}</th>
                                    <th>{{ __('City')}}</th>
                                    <th>{{ __('Contact')}}</th>
                                    <th>{{ __('Email')}}</th>
                                    <th>{{ __('Type')}}</th>
                                    <th>{{ __('Website')}}</th>
                                    <th>{{ __('GST')}}</th>
                                    <th class="nosort">{{ __('Action')}}</th>
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


        
    </div>
               

    <!-- push external js -->
    @push('script')
    <script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
    <script>
        $(function(){
            var searchable = [];
            var selectable = []; 
            

            var dTable = $('#page_table').DataTable({

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
                    {data:'ID', name: 'ID'},
                    {data:'name', name: 'name'},
                    {data:'company', name: 'company'},
                    {data:'city', name: 'city'},
                    {data:'mobile', name: 'mobile'},
                    {data:'email', name: 'email'},
                    {data:'option', name: 'option'},
                    {data:'website', name: 'website'},
                    {data:'gst', name: 'gst'},
                    //only those have manage_user permission will get access
                    {data:'action', name: 'action',orderable: false, searchable: false,visible:true}
                ],
                columnDefs:[
                    {
                         'targets': 0,
                          'data': 'ID',
                          'render': function (data, type, row, meta) {
                               return meta.row + meta.settings._iDisplayStart + 1;
                          }
                    }
                    /*{
                        'targets': -2,
                        'data': 'status',
                        'render': function ( data, type, row, meta ) {
                            if(data==1){
                                return '<span class="badge badge-pill badge-success mb-1">Active</span>';
                            }
                            else{
                                return '<span class="badge badge-pill badge-danger mb-1">Inactive</span>';
                            }
                            return data;
                        }
                    }*/
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
            $(document).on('click','.list-delete',function(e){
                e.preventDefault();
				var result = confirm("Are you sure you want to Remove?");
                if (!result) {
                    return false;
                }
				var $row = $(this).closest('tr');
                var data = dTable.row($row).data();
                $.ajax({
                    type:'post',
                    url:$(this).attr('href'),
                    data:'_method=delete&_token={!! csrf_token() !!}',
                    success:function(data){
                        if(data.success){
                            //location.reload();
							dTable
                            .row( $row )
                            .remove()
                            .draw();
                        }
                    }
                });
            });
            
        });
    </script>
    @endpush
@endsection