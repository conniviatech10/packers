@extends('layouts.main') 
@section('title', auth()->user()->name)
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css"> 
         <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" /> 
        <style>
           .field-icon {
  float: right;
  margin-left: -18px;
  margin-top: -25px;
  position: relative;
  z-index: 2;
}
.field-icon1 {
  float: right;
  margin-left: -10px;
  margin-top: -25px;
  position: relative;
  z-index: 2;
}

        </style>
    @endpush



    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-user-plus bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Change Password')}}</h5>
                            <span>{{ __('Update user password')}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{url('/')}}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">{{ __('Profile')}}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <!-- clean unescaped data is to avoid potential XSS risk -->
                                {{ clean(auth()->user()->name, 'titles')}}
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
                <div class="card">
                    <div class="card-body">
                        <form class="forms-sample" method="POST" action="{{ url('admin/change-password') }}" >
                        @csrf
                            <input type="hidden" name="id" value="{{auth()->id()}}">
                            <div class="row">
                                <div class="col-sm-6">

                                    
                                   
                                <div class="form-group">
                                   <label class="col-md-4 control-label">{{ __('Password')}}</label>
                                <div class="col-md-12">
                                   <input id="password-field" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="" placeholder="Enter new password">
                                      <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                    
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                             </div>
                                 
                             <div class="form-group">
                                   <label class="col-md-4 control-label">{{ __('Confirm Password')}}</label>
                                <div class="col-md-12">
                                   <input id="password-field1" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" value="" placeholder="Re-enter password" >
                                      <span toggle="#password-field1" class="fa fa-fw fa-eye field-icon1 toggle-password1"></span>
                                      @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                             </div>
                                    
                                    
                                    
                                
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary form-control-right">{{ __('Update')}}</button>
                                    </div>
                                </div>
                            </div>
                        
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- push external js -->
    @push('script') 
        <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
        <!--get role wise permissiom ajax script-->
        <script src="{{ asset('js/get-role.js') }}"></script>
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->

        <script> 
 

// $("body").on('click', '.toggle-password', function() {
//   $(this).toggleClass("fa-eye fa-eye-slash");
//   var input = $("#password");
// //   var input = $("#password-confirm");
//   if (input.attr("type") === "password") {
//     input.attr("type", "text");
//   } else {
//     input.attr("type", "password");
//   }

//   $(this).toggleClass("fa-eye fa-eye-slash");
// //   var input = $("#password");
//   var input = $("#password-confirm");
//   if (input.attr("type") === "password") {
//     input.attr("type", "text");
//   } else {
//     input.attr("type", "password");
//   }
  

// });

// const togglePassword = document.querySelector("#togglePassword");
//         const password = document.querySelector("#password");

//         togglePassword.addEventListener("click", function () {
//             // toggle the type attribute
//             const type = password.getAttribute("type") === "password" ? "text" : "password";
//             password.setAttribute("type", type);
            
//             // toggle the icon
//             this.classList.toggle("bi-eye");
//         });

//         // prevent form submit
//         const form = document.querySelector("form");
//         form.addEventListener('submit', function (e) {
//             e.preventDefault();
//         });

$(".toggle-password").click(function() {

$(this).toggleClass("fa-eye fa-eye-slash");
var input = $($(this).attr("toggle"));
if (input.attr("type") == "password") {
  input.attr("type", "text");
} else {
  input.attr("type", "password");
}
});
$(".toggle-password1").click(function() {

$(this).toggleClass("fa-eye fa-eye-slash");
var input = $($(this).attr("toggle"));
if (input.attr("type") == "password") {
  input.attr("type", "text");
} else {
  input.attr("type", "password");
}
});
    </script>
        
    @endpush

   
@endsection
