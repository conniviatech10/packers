<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>{{ __('Sign Up | '.config('app.name'))}}</title>
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="icon" href="{{ asset('favicon.png') }}"/>

        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700,800" rel="stylesheet">
        
        <link rel="stylesheet" href="{{ asset('plugins/bootstrap/dist/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/ionicons/dist/css/ionicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/icon-kit/dist/css/iconkit.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}">
        <link rel="stylesheet" href="{{ asset('dist/css/theme.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('dist/css/theme-image.css') }}">
        <script src="{{ asset('src/js/vendor/modernizr-2.8.3.min.js') }}"></script>
    </head>

    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <div class="auth-wrapper">
            <div class="container-fluid h-100">
                <div class="row flex-row h-100 bg-white">
                    <div class="col-xl-8 col-lg-6 col-md-5 p-0 d-md-block d-lg-block d-sm-none d-none">
                        <div class="lavalite-bg">
                            <div class="lavalite-overlay"></div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-7 my-auto p-0">
                        <div class="authentication-form mx-auto mt-0">
                            <div class="logo-centered">
                                <a href="{{ url('/') }}">
                                   @if(config('setting.logo'))
                                   <img height="40" src="{{ asset(config('setting.logo','img/logo.png'))}}" class="header-brand-img" title="{{ucwords(config('app.name'))}}"> 
                                   @else 
                                   {!! config('app.name') !!}
                                   @endif
                                   
                                </a>
                            </div>
                            <p>{{ __('Join us today! It takes only few steps')}}</p>
                            <form action="{{url('register')}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <input type="name" class="form-control @error('name') is-invalid @enderror" placeholder="Name*" name="name" value="{{ old('name') }}" required>
                                    <i class="ik ik-user"></i>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="name" class="form-control @error('mobile') is-invalid @enderror" placeholder="Mobile*" name="mobile" value="{{ old('mobile') }}" required>
                                    <i class="ik ik-smartphone"></i>
                                    @error('mobile')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email*" name="email" value="{{ old('email') }}" required>
                                    <i class="fa fa-envelope"></i>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password*" name="password" required>
                                    <i class="ik ik-lock"></i>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="Confirm Password*" name="password_confirmation" required>
                                    <i class="ik ik-eye-off"></i>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-left">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="tnc" name="tnc" {{ old('tnc')?'checked':'' }}>
                                            <span class="custom-control-label">&nbsp;{{ __('I Accept')}} <a href="#">{{ __('Terms and Conditions')}}</a></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="sign-btn text-center">
                                    <button class="btn btn-theme">{{ __('Create Account')}}</button>
                                </div>
                            </form>
                            <div class="register">
                                <p>{{ __('Already have an account?')}} <a href="{{url('login')}}">{{ __('Sign In')}}</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="{{ asset('src/js/vendor/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ asset('plugins/popper.js/dist/umd/popper.min.js') }}"></script>
        <script src="{{ asset('plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('plugins/screenfull/dist/screenfull.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js"></script>
        <script>
            $(function(){
                var validate=$('.authentication-form form').validate({
                    rules:{
                        name:{required:true},
                        mobile:{
                            required:true,
                            number:true,
                            //matches   : "^(\\d|\\s)+$",
                            minlength : 10,
                            maxlength : 10
                        },
                        email:{required:true,email:true},
                        password:{required:true},
                        password_confirmation:{
                            required:true,
                            equalTo : "input[name='password']"
                        },
                    },
                    messages:{
                        name:{required:'Please enter name'},
                        mobile:{required:'Please enter mobile'},
                        email:{required:'Please enter email address'},
                        password:{required:'Please enter password'},
                        password_confirmation:{required:'Please re-enter password'},
                    },
                    errorElement: 'span',
                    errorPlacement: function (error, element) {
                        //element.siblings('.invalid-tooltip').remove();
                        //error.addClass('invalid-tooltip');
                        error.addClass('invalid-feedback');
                        console.log(error);
                        element.closest('.form-group').append(error);
                    },
                    highlight: function (element, errorClass, validClass) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function (element, errorClass, validClass) {
                        $(element).removeClass('is-invalid');
                    }
                });
                $('.sign-btn button').on('click',function(e){
                    if($('.authentication-form form').valid()){
                        $(this).prepend('<span class="spinner-border spinner-border-sm me-2" role="status"></span>');
                    }
                });
            });
        </script>
    </body>
</html>
