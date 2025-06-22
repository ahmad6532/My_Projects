<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from coderthemes.com/ubold/layouts/default/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 01 Jun 2020 04:57:20 GMT -->

<head>

    <meta charset="utf-8" />
    <title>Dashboard | UBold - Responsive Admin Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />


    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">

    <!-- fontello icon -->
    <link rel="stylesheet" href="{{asset('assets/font/fontello/css/fontello.css')}}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Plugins css -->
    <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"
        id="bs-default-stylesheet" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

    <link href="{{ asset('assets/css/bootstrap-dark.min.css') }}" rel="stylesheet" type="text/css"
        id="bs-dark-stylesheet" />
    <link href="{{ asset('assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet" />

    <!-- icons -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />



<!-- jquery-tabs -->



</head>
<body>
<div class="container-flud">
    <div class="content">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div>
                    <img class="img-fluid" src="{{asset('assets/images/login-left.jpeg')}}" alt="">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="login-content-wrape">
                    <div class="login-content border rounded-3">
                        <div class="content-logo text-center">
                            <img class="mb-4" src="{{asset('assets/images/logo.jpeg')}}" alt="">
                            <h1>Welcome</h1>
                            <p>Please enter the email address and password</p>
                        </div>
                        <div class="login-content-group mt-4">
                            <form action="{{ route('login') }}" method="POST" >
                                @csrf
                                <div class="mb-3">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Enter Email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>   
                                <div class="mb-3 position-relative eye-holder">
                                    <input id="pass_log_id" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                                    <i toggle="#password-field" class="fontello icon-eye-off toggle-password"></i>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                                <div class="mb-3 checkbox-component-group">
                                    <div class="d-flex checkbox-content">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                    <div class="forgot-link">
                                    @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                                    </div>
                                </div>
                                <div class="mt-4 text-center">
                                    <button  type="submit" class="btn btn-primary login-btn">{{ __('Login') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>