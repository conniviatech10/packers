@extends('layouts.main') 
@section('title', 'New Page')
@section('content')
    <!-- push external head elements to head -->
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
                        <i class="ik ik-edit bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Page')}}</h5>
                            <span>{{ __('Manage Page')}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{url('admin')}}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">{{ __('Pages')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('New')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <form method="post" action="{!! url('admin/page') !!}">
                    @csrf
                <div class="card">
                    <div class="card-header"><h3>{{ __('Title')}}</h3></div>
                    <div class="card-body">
                        <input type="text" class="form-control" id="post_title" name="post_title" placeholder="Title">
                    </div>
                    <div class="card-header"><h3>{{ __('Content')}}</h3></div>
                    <div class="card-body">
                        <div class="form-group">
                            <textarea class="form-control html-editor" rows="10" name="post_content"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3-col-form-label">Status</label>
                            @php
                            $post_status=[
                                'Draft','Pending','Publish'
                            ];
                            @endphp
                            <select name="post_status" class="form-control">
                                @if(count($post_status)>0)
                                    @foreach($post_status as $key=>$value)
                                    <option>{{$value}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success pull-right">Save</button>
                    </div>
                </div>
                </form>
            </div>
        </div>

        
    </div>

    <!-- push external js -->
    @push('script')
        <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
        <script src="{{ asset('plugins/summernote/dist/summernote-bs4.min.js') }}"></script>
        <script src="{{ asset('plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
        <!--<script src="{{ asset('plugins/jquery.repeater/jquery.repeater.min.js') }}"></script>-->
        <!--<script src="{{ asset('plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>-->
      
        <script src="{{ asset('js/form-advanced.js') }}"></script>
    @endpush
@endsection