@extends('layouts.main') 
@section('title', 'Add Banners')
@section('content')
    @push('head')

        <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/summernote/dist/summernote-bs4.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/mohithg-switchery/dist/switchery.min.css') }}">

    @endpush
<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-file-text bg-blue"></i>
                    <div class="d-inline">
                        <h5>{{ __('Add Banners')}}</h5>
                        <span>{{ __('Manage Banner')}}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <nav class="breadcrumb-container" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{url('admin')}}"><i class="ik ik-home"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Add Banners')}}</li>
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
            <div class="card">
                <div class="card-header"><h3>Add Banners</h3></div>
                <div class="card-body">
                    <form method="post" action="{!! route('admin.banner.store') !!}">
                        @csrf
                        <div class="form-group row">
                            <label for="checklistname" class="col-sm-2 col-form-label">Title</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="checklistname" name="post_title" placeholder="Title">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="checklistname" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                                <input type="checkbox" class="js-small" name="status" value="1" checked/>
                            </div>
                        </div>
                        <h4 class="sub-title">Item</h4>
                        <div class="form-group row slide">
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="banner_image[0][title]" placeholder="Title">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="banner_image[0][link]" placeholder="Link">
                            </div>
                            <div class="col-sm-2">
                                <a href="#" id="thumb-image0" data-toggle="image" class="img-thumbnail">
                                    <img class="img-fluid" src="{{ asset('img/portfolio-8.jpg') }}" alt="" title="" data-placeholder="{{ asset('img/portfolio-8.jpg') }}">
                                </a>
                                <input type="hidden" name="banner_image[0][image]" value="" id="input-image0">
                                <input type="file" name="file" class="d-none">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="banner_image[0][sort_order]" placeholder="Sort Order">
                            </div>
                            <div class="col-sm-2">
                                <a href="#" class="btn btn-icon btn-outline-danger delete-banner_image"><i class="ik ik-trash"></i></a>
                            </div>    
                        </div>  
                        <div class="form-group row add-more pb-2">
                            <div class="col-sm-2 offset-sm-10">
                            <a href="javascript:void(0);" class="add-banner_image"><i class="fa fa-plus-circle"></i> Add More</a>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        <button class="btn btn-light">Cancel</button>  
                    </form>
                </div>    
            </div>
        </div>
    </div>

</div>
@stop
<!-- push external js -->
@push('script')
        <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
        <script src="{{ asset('plugins/summernote/dist/summernote-bs4.min.js') }}"></script>
        <script src="{{ asset('plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
        <script src="{{ asset('plugins/jquery.repeater/jquery.repeater.min.js') }}"></script>
        <script src="{{ asset('plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>
        <script>
            $(function(){
                var elemsingle = document.querySelector('.js-small');
                var switchery = new Switchery(elemsingle, {
                    color: '#4099ff',
                    jackColor: '#fff'
                });
                //
                var count=1;//$('.slide').length;
                $('.add-banner_image').on('click',function(e){
                    e.preventDefault();
                    var html  = '<div class="form-group row slide">'
                                    +'<div class="col-sm-3">'
                                        +'<input type="text" class="form-control" name="banner_image['+count+'][title]" placeholder="Title">'
                                    +'</div>'
                                    +'<div class="col-sm-3">'
                                        +'<input type="text" class="form-control" name="banner_image['+count+'][link]" placeholder="Link">'
                                    +'</div>'
                                    +'<div class="col-sm-2">'
                                        +'<a href="#" id="thumb-image'+count+'" data-toggle="image" class="img-thumbnail">'
                                            +'<img class="img-fluid" src="{{ asset('img/portfolio-8.jpg') }}" alt="" title="" data-placeholder="{{ asset('img/portfolio-8.jpg') }}">'
                                        +'</a>'
                                        +'<input type="hidden" name="banner_image['+count+'][image]" value="" id="input-image'+count+'">'
                                        +'<input type="file" name="file" class="d-none">'
                                    +'</div>'
                                    +'<div class="col-sm-2">'
                                        +'<input type="text" class="form-control" name="banner_image['+count+'][sort_order]" placeholder="Sort Order">'
                                    +'</div>'
                                    +'<div class="col-sm-2">'
                                        +'<a href="#" class="btn btn-icon btn-outline-danger delete-banner_image"><i class="ik ik-trash"></i></a>'
                                    +'</div> '   
                                '</div>';
                    $(this).parents('.add-more').before(html);
                    count++;
                    console.log(count);
                });
                $(document).on('click','[data-toggle="image"]',function(e){
                    e.preventDefault();
                    $(this).parent().find('input[type="file"]').trigger('click');
                });
                $(document).on('change','input[type="file"]',function(e){
                    var $this=this;
                    var file = this.files[0];
                    let formData = new FormData();           
                        //formData.append("_method", 'put');
                        formData.append("_token", '{!! csrf_token() !!}');
                        formData.append("file", file);
                        formData.append("type", 'image');
                        formData.append("action", 'upload');
                        $(this).parent().find('a img').attr('src','https://via.placeholder.com/600x425.png?text=Loading...');
                    $.ajax({
                        type:'post',
                        url:'{!! route("admin.banner.store") !!}',
                        contentType: false,
                        cache: false,
                        processData: false,
                        data:formData,
                        success:function(data){
                            if(data.url){
                               $($this).parent().find('img').attr('src','{!! url('/') !!}/'+data.url);
                               $($this).parent().find('input[type="hidden"]').val(data.ID);
                            }
                        }
                    });
                });
                $(document).on('click','.delete-banner_image',function(e){
                    e.preventDefault();
                    $(this).parents('.slide').remove();
                });
            });
            
        </script>
    @endpush