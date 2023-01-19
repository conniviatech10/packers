<!doctype html>
<html class="no-js" lang="en">
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Login | {{config('app.name')}}</title>
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon" />

        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700,800" rel="stylesheet">
        
        <link rel="stylesheet" href="{{ asset('plugins/bootstrap/dist/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/ionicons/dist/css/ionicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/icon-kit/dist/css/iconkit.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}">
        <link rel="stylesheet" href="{{ asset('dist/css/theme.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <script src="{{ asset('src/js/vendor/modernizr-2.8.3.min.js') }}"></script>
    </head>

    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <div class="auth-wrapper">
            <div class="container-fluid h-100">
                <div class="row flex-row h-100">
                    <div class="col-xl-4 col-lg-4 col-md-4 m-auto">
                        <div class="authentication-form mx-auto">
                            <div class="logo-centered">
                                <a href="{{ url('/') }}">
                                   {{--<img height="40" src="{{ asset('img/logo.png') }}" alt="RADMIN" >--}}
                                   @if(config('setting.logo'))
                                   <img height="40" src="{{ asset(config('setting.logo','img/logo.png'))}}" class="header-brand-img" title="{{ucwords(config('app.name'))}}"> 
                                   @else 
                                   {!! config('app.name') !!}
                                   @endif
                                </a>
                            </div>
                            <p>Welcome back! </p>
                            <form method="POST" action="{{ route('login') }}">
                            @csrf
                                <div class="form-group">
                                    <input id="email" type="text" placeholder="Email*" class="form-control @error('email') is-invalid @enderror" name="email" value="" required autocomplete="email" autofocus>
                                    <i class="ik ik-user"></i>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input id="password" type="password" placeholder="Password*" class="form-control @error('password') is-invalid @enderror" name="password"  value="" required>
                                    <i class="ik ik-lock"></i>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                                <div class="row">
                                    <div class="col text-left">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <span class="custom-control-label">&nbsp;Remember Me</span>
                                        </label>
                                    </div>
                                    <div class="col text-right">
                                        <a class="btn text-danger" href="{{ route('password.request') }}">
                                            {{ __('Forgot Password?') }}
                                        </a>
                                    </div>
                                </div>
                                <div class="sign-btn text-center">
                                    <button class="btn btn-custom">Sign In</button>
                                </div>
                                {{-- <div class="register">
                                    <p>{{ __('No account?')}} <a href="{{url('register')}}">{{ __('Sign Up')}}</a></p>
                                </div>  --}}
                                
                            </form>
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
                        email:{required:true},
                        password:{required:true},
                    },
                    messages:{
                        email:{required:'Please enter email address'},
                        password:{required:'Please enter password'}
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
