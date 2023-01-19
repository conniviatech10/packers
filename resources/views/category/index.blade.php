@extends('layouts.main') 
@section('title', 'Categories')
@section('content')
    <!-- push external head elements to head --> 
    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-list bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Categories')}}</h5>
                            <span>Add, remove or edit categories</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{url('admin')}}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">{{ __('Categories')}}</a>
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
                <div class="mb-2 clearfix">
                    <a class="btn pt-0 pl-0 d-md-none d-lg-none" data-toggle="collapse" href="#displayOptions" role="button" aria-expanded="true" aria-controls="displayOptions">
                        {{ __('Display Options')}}
                        <i class="ik ik-chevron-down align-middle"></i>
                    </a>
                    <div class="collapse d-md-block display-options" id="displayOptions">
                        <span class="mr-3 d-inline-block float-md-left dispaly-option-buttons">
                            <a href="#" class="mr-1 view-thumb active">
                                <i class="ik ik-list view-icon"></i>
                            </a>
                            <a href="#" class="mr-1 view-grid">
                                <i class="ik ik-grid view-icon"></i>
                            </a>
                        </span>
                        <div class="d-block d-md-inline-block">
                            <div class="btn-group float-md-left mr-1 mb-1">
                                <button class="btn btn-outline-dark btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ __('
                                    Order By')}} 
                                    <i class="ik ik-chevron-down mr-0 align-middle"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url()->current().'?'.http_build_query(array_merge(request()->all(),['sort' => 'desc'])) }}">{{ __('DESC')}}</a>
                                    <a class="dropdown-item" href="{{ url()->current().'?'.http_build_query(array_merge(request()->all(),['sort' => 'asc'])) }}">{{ __('ASC')}}</a>
                                </div>
                            </div>
                            <div class="search-sm d-inline-block float-md-left mr-1 mb-1 align-top">
                                <form action="">
                                    <input type="text" class="form-control" name="q" placeholder="Search.." required>
                                    <button type="submit" class="btn btn-icon"><i class="ik ik-search"></i></button>
                                    <button type="button" id="adv_wrap_toggler" class="adv-btn ik ik-chevron-down dropdown-toggle" data-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                    <div class="adv-search-wrap dropdown-menu dropdown-menu-right" aria-labelledby="adv_wrap_toggler">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Category Title">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Category Code">
                                        </div>
                                        <button class="btn btn-theme">{{ __('Search')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="float-md-right">
                            <span class="text-muted text-small mr-2">{{ __('Displaying 1-10 of 210 items')}} </span>
                            <button class="btn btn-outline-dark btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                20
                                <i class="ik ik-chevron-down mr-0 align-middle"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ url()->current().'?'.http_build_query(array_merge(request()->all(),['per_page' => 10])) }}">10</a>
                                <a class="dropdown-item" href="{{ url()->current().'?'.http_build_query(array_merge(request()->all(),['per_page' => 20])) }}">20</a>
                                <a class="dropdown-item" href="{{ url()->current().'?'.http_build_query(array_merge(request()->all(),['per_page' => 30])) }}">30</a>
                                <a class="dropdown-item" href="{{ url()->current().'?'.http_build_query(array_merge(request()->all(),['per_page' => 50])) }}">50</a>
                                <a class="dropdown-item" href="{{ url()->current().'?'.http_build_query(array_merge(request()->all(),['per_page' => 100])) }}">100</a>
                            </div>
                            <button class="btn btn-outline-primary btn-rounded-20" href="#categoryAdd" data-toggle="modal" data-target="#categoryAdd">
                                Add Category
                            </button>
                        </div>
                    </div>
                </div>
                <div class="separator mb-20"></div>

                <div class="row layout-wrap" id="layout-wrap">
                    @if($categories->count()>0)
                        @foreach($categories as $items)
                        {{-- <div class="col-xl-3 col-lg-4 col-12 col-sm-6 mb-4 list-item list-item-grid"> --}}
                        <div class="col-12 list-item list-item-thumb">
                            <div class="card d-flex flex-row mb-3">
                                <a class="d-flex card-img" href="#categoryView" data-toggle="modal" data-target="#categoryView">
                                    <img src=" ../img/portfolio-1.jpg " alt="Donec sit amet est at sem iaculis aliquam." class="list-thumbnail responsive border-0 d-none">
                                    
                                    
                                    <span class="badge badge-pill badge-success position-absolute badge-top-left">{{$items->items()->count()}} items</span>
                                </a>
                                <div class="d-flex flex-grow-1 min-width-zero card-content">
                                    <div class="card-body align-self-center d-flex flex-column flex-md-row justify-content-between min-width-zero align-items-md-center mb-0">
                                        <a class="mb-1 list-item-heading  truncate w-40 w-xs-100" href="#categoryView" data-toggle="modal" data-target="#categoryView">
                                            <b>{{ $items->item_name }}
                                            </b> 
                                            <span class="text-muted">
                                            </span>
                                            
                                        </a>
                                        <p class="mb-1 text-muted text-small date w-15 w-xs-100">
                                            #ID: {{ $items->id }}
                                        </p>
                                        <div class="w-15 w-xs-100">
                                            <span class="badge badge-pill badge-secondary">{{$items->items()->count()}}</span>
                                        </div>
                                        <div class="w-15 w-xs-100">
                                            <span class="badge badge-pill {{ ($items->status==1)?'badge-success':'badge-danger'}}">{{ ($items->status==1)?'Active':'Inactive'}}</span>
                                        </div>
                                    </div>
                                    <div class="list-actions">
                                        <a href="#categoryView" data-toggle="modal" data-target="#categoryView" data-edit='{!! $items !!}'><i class="ik ik-edit-2"></i></a>
                                        <a href="{{ route('category.show',$items->id) }}" class="list-delete"><i class="ik ik-trash-2"></i></a>
                                    </div>
                                    <div class="custom-control custom-checkbox pl-1 align-self-center">
                                        <label class="custom-control custom-checkbox mb-0">
                                            <input type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        @endforeach          
                    @endif
                    
                    
                </div>
                {{ $categories->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
    <!-- category add modal-->
    <div class="modal fade edit-layout-modal pr-0 " id="categoryAdd" tabindex="-1" role="dialog" aria-labelledby="categoryAddLabel" aria-hidden="true">
        <div class="modal-dialog w-300" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryAddLabel">{{ __('Add Category')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form>    
                        @csrf                
                    {{-- <div class="form-group">
                        <label class="d-block">Category Image</label>
                        <input type="file" name="category_image" class="form-control">
                    </div> --}}
                    <div class="form-group">
                        <label class="d-block">Category Title</label>
                        <input type="text" name="category_title" class="form-control" placeholder="Enter Category Title">
                    </div>
                    <div class="form-group">
                        <label class="d-block">Form Name Prefix(Optional)</label>
                        <input type="text" name="form_name_prefix" class="form-control" placeholder="Enter Name Prefix">
                    </div>
                    <div class="form-group">
                        <label class="d-block">Status</label>
                        <select name="status" class="form-control select2 ">
                            <option selected="selected" value="" data-select2-id="3">Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Disable</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input class="btn btn-primary" type="submit" name="Save" value="Save">
                    </div>
                    </form>    
                </div>
            </div>
        </div>
    </div>

    <!-- category edit modal -->
    <div class="modal fade edit-layout-modal pr-0 " id="categoryView" tabindex="-1" role="dialog" aria-labelledby="categoryViewLabel" aria-hidden="true">
        <div class="modal-dialog w-300" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryViewLabel">{{ __('Edit Category')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                <form>    
                        @csrf      
                    {{-- <div class="form-group">
                        <label class="d-block">Category Image</label>
                        <input type="file" name="category_image" class="form-control">
                    </div> --}}
                    <div class="form-group">
                        <label class="d-block">Category Title</label>
                        <input type="text" name="category_title" class="form-control" placeholder="Enter Category Title">
                    </div>
                    <div class="form-group">
                        <label class="d-block">Form Name Prefix(Optional)</label>
                        <input type="text" name="form_name_prefix" class="form-control" placeholder="Enter Name Prefix">
                    </div>
                    <div class="form-group">
                        <label class="d-block">Status</label>
                        <select name="status" class="form-control select2 ">
                            <option selected="selected" value="" data-select2-id="3">Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Disable</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input class="btn btn-primary" type="submit" name="Update" value="Update">
                    </div>
                    </form>    
                </div>
            </div>
        </div>
    </div>
    @push('script')
        <script>
            $(function(){
                var notyf = new Notyf();
                $('#categoryAdd form').on('submit',function(e){
                    e.preventDefault();
                    var formData=$( this ).serialize() ;
                    $.ajax({
                        type:'post',
                        url:'{!! url()->current() !!}',
                        data:formData,
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
                //edit category modal
                $('#categoryView').on('show.bs.modal', function (e) {
                    var data=$(e.relatedTarget).data('edit');
                    $('#categoryView form').append('<input type="hidden" name="category_id" value="'+data.id+'">');
                    $('#categoryView form input[name="category_title"]').val(data.item_name);
                    $('#categoryView form input[name="form_name_prefix"]').val(data.form_name_prefix);
                    $('#categoryView form select[name="status"] option[value="'+data.status+'"]').prop('selected', 'selected').change();
                });
                $('#categoryView').on('hide.bs.modal', function (e) {
                    var data=$(e.relatedTarget).data('edit');
                    $('#categoryView form input[name="category_title"]').val('');
                    $('#categoryView form input[name="form_name_prefix"]').val('');
                    $('#categoryView form select[name="status"] option[value=""]').prop('selected', 'selected').change();
                });
                $('#categoryView form').on('submit',function(e){
                    e.preventDefault();
                    var formData=$( this ).serialize() ;
                    $.ajax({
                        type:'post',
                        url:'{!! url()->current() !!}/0',
                        data:formData+'&_method=put',
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
                //$('.list-actions a.list-delete').on('click',function(e){
                $(document).on('click','.list-delete',function(e){
                    e.preventDefault();
                    if(confirm("Are you sure you want to delete this?")){
                        $.ajax({
                            type:'post',
                            url:$(this).attr('href'),
                            data:'_method=delete&_token={!! csrf_token() !!}',
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
                    }
                });
            });
        </script>                        
    @endpush
@endsection