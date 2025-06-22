<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Unity</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon_unity.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/font/fontawesome-free-6.4.0/css/all.min.css') }}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        :root {
                --heading-color: {{ $themeColor[0]['value'] }};
                --text-color: {{ $themeColor[1]['value'] }};
                --sidebar-color: {{ $themeColor[2]['value'] }};
                --sidebar-bg-color: {{ $themeColor[3]['value'] }};
                --body-color: {{ $themeColor[4]['value'] }};
                --header-bg: {{ $themeColor[5]['value'] }};
                --header-text-color: #809FB8;
                --sidebar-hover: {{ $themeColor[6]['value'] }};
                --btn-bg: {{ $themeColor[7]['value'] }};
                --btn-color: {{ $themeColor[8]['value'] }};
                --btn-shadow: 0px 5px 20px rgba(30, 133, 255, 0.25);
                --btn-border-color: {{ $themeColor[9]['value'] }};
                --pagination-active-bg: {{ $themeColor[10]['value'] }};
                --pagination-active-color: {{ $themeColor[11]['value'] }};
                --tabs-active-bg: {{ $themeColor[13]['value'] }};
                --tabs-active-color: {{ $themeColor[14]['value'] }};
                --tabs-color: {{ $themeColor[12]['value'] }};
                --tabs-shadow: 0px 5px 24px rgba(30, 133, 255, 0.4);
                --icon-color: {{ $themeColor[15]['value'] }};
                --font-GothamBold: GothamBold;
                --font-GothamMedium: GothamMedium;
                --font-GothamRegular: GothamRegular;
                --font-GothamLight: GothamLight;
        }

        .field-icon {
            float: right;
            top: -24px;
            right: 10px;
            position: relative;
            z-index: 2;
        }

        .container {
            padding-top: 50px;
            margin: auto;
        }

        .login-content-wrape {
            display: flex;
            justify-content: center;
            background-color: #ffffff;
            align-items: center;
            align-content: center;
            height: 100%;
        }

        .login-content {
            padding: 40px;
        }

        .form-control {
            font-weight: 400;
            font-size: 0.9rem;
            line-height: 1;
            color: var(--input-color) !important;
        }

        .fontello {
            font-size: 0.9rem;
        }

        .eye-holder i {
            position: absolute;
            top: 2px;
            right: 13px;

        }

        .container-checkbox_vt {
            display: block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 22px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Hide the browser's default checkbox */
        .container-checkbox_vt input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 20px;
            width: 20px;
            background-color: white;
            border: 1px solid #1E85FF;
            border-radius: 5px;
        }


        /* When the checkbox is checked, add a blue background */
        .container-checkbox_vt input:checked~.checkmark {
            background-color: #1E85FF;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .container-checkbox_vt input:checked~.checkmark:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        .container-checkbox_vt .checkmark:after {
            left: 7px;
            top: 3px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);

        }

        /* checkbox-styling-ended */

        .checkbox-component-group {
            display: flex;
            justify-content: space-between;
        }

        .checkbox-content p {
            font-weight: 400;
            font-size: 1rem;
            line-height: 1;
            color: #3B3B3B;
        }

        .forgot-link {
            font-weight: 500;
            font-size: 1rem;
            line-height: 1;
            color: var(--a-tag-color);
            text-decoration: underline;
        }

        body {
            background-image: url("{{ asset('assets/images/login-left.jpeg') }}");
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .height-vt {
            height: 100vh;
        }

        @media screen and (max-width:1220px) {
            .navtab-bg .nav-link {
                width: 17%;
            }
        }

        @media screen and (max-width:868px) {
            .navtab-bg .nav-link {
                width: 15%;
            }

            .nav-link {
                padding: 0.5rem 0rem !important;
            }
        }

        @media screen and (max-width:768px) {
            .navtab-bg .nav-link {
                width: 100%;
            }
        }

        @media screen and (max-width:576px) {
            .pt-xs-15 {
                padding-top: 15px;
            }

            .flex-direction {
                flex-direction: column-reverse;
            }

            .text-overlap_vt {
                margin: 0;
                width: auto !important;
            }

            .login-content {
                padding: 10px;
            }
        }
        .form-control:invalid:focus{
            border-color: #86b7fe !important;
            box-shadow: 0 0 0 0.2rem rgba(52, 144, 220, 0.25) !important;
        }
        .left_div{
            position: absolute;
            margin: 20% 7% 5% !important;
            text-align: center;
        }
        .left_bottom_content h3{
            color:#ffffff;
            font-weight: 500;
            font-size:24px;
            font-family: var(--font-GothamRegular);
        }
        .left_bottom_content p{
            color:#ffffff;
            font-weight: 500;
            font-size:14px;
            font-family: var(--font-GothamRegular);
        }
        .left_footer p{
            color:#ffffff;
            font-size:14px;
            font-family: var(--font-GothamRegular);
        }
    </style>
</head>
@php
    $history = DB::table('version_history')->orderBy('id','desc')->first();
    $version = $history?$history->version:'0.1';
@endphp

<body class="login-bg">
    <div class="container-flud">
        <div class="content">
            <div class="row mx-0">
                <div class="col-lg-6 col-md-6 col-sm-12 px-0">
                    <div class="left_div">
                        <div class="left_logo mb-4">
                            <img class="img-fluid" src="{{ asset('assets/images/unity_login_side_logo.png') }}" alt="">
                        </div>
                        <div class="left_bottom_content">
                            <h3>Effortless Attendance Management</h3>
                            <p>Transform attendance management, boost productivity, and optimize workforce operations with our seamless solution.</p>
                        </div>
                        <div class="left_footer mt-5 d-flex justify-content-between">
                            <p></p>
                            <p>&copy{{date('Y')}} Unity. All Rights Reserved.</p>
                            <p>V.{{$version}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 px-0">
                    <div class="height-vt">
                        <div class="login-content-wrape">
                            <div class="login-content border">
                                <div class="content-logo text-center">
                                    <img class="mb-4" src="{{ asset('assets/images/theme/' . $setting[16]['value']) }}" style="max-width: 50%;" alt="">
                                    <h1>Welcome</h1>
                                    <p>Please enter the email address and password</p>
                                </div>

                                @if ($errors->any())
                                    <div class="content-logo text-center alert alert-danger" style="max-width: 100% !important;right:0px !important;">
                                        <div class="row d-flex justify-content-center">
                                            <p class="mb-0" style="font-size:13px;">{{ $errors->first() }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if(session()->has('message'))
                                    <div class="content-logo text-center alert alert-success" style="max-width: 100% !important;right:0px !important;">
                                        <div class="row d-flex justify-content-center">
                                        <p class="mb-0" style="font-size:13px;"> {{ session()->get('message') }}</p>
                                        </div>
                                    </div>
                                @endif
                                <div class="login-content-group mt-4">
                                    <form class="form" action="{{ route('login.submit') }}" method="POST">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <input type="email"
                                                class="form-control @error('email') is-invalid @enderror border-0"
                                                name="email" placeholder="Email Address" value="{{ old('email') }}"
                                                required autocomplete="none" autofocus>
                                            {{-- @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <p>{{ $message }}</p>
                                                </span>
                                            @enderror --}}

                                        </div>
                                        <div class="form-group mb-3 position-relative eye-holder">
                                            <input id="password-field" type="password"
                                                class="form-control @error('password') is-invalid @enderror border-0"
                                                name="password" required autocomplete="none"
                                                placeholder="Password">
                                            <span toggle="#password-field"
                                                class="fa fa-fw fa-eye-slash field-icon toggle-password"
                                                style="font-size:14px;"></span>

                                            {{-- @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <p>{{ $message }}</p>
                                                </span>
                                            @enderror --}}
                                        </div>
                                        <div class="mb-3 checkbox-component-group">
                                            <div class="d-flex checkbox-content align-items-center">
                                                <input type="checkbox" name="remember_me" id="remember"
                                                    {{ old('remember') ? 'checked' : '' }}>
                                                <label for="remember"
                                                    style="margin-bottom:0; padding-left:5px;">Remember me</label>
                                            </div>
                                            <div class="forgot-link_vt">
                                                    <a href="{{ route('forget.password') }}">Forgot Password?</a>
                                            </div>
                                        </div>
                                        <!-- <div class="mb-3 checkbox-component-group">
                                            <div class="d-flex checkbox-content">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                <label class="form-check-label" for="remember">
                                                    {{ __('Remember Me') }}
                                                </label>
                                            </div>
                                            <div class="forgot-link float-end">
                                            @if (Route::has('password.request'))
                                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                                    {{ __('Forgot Your Password?') }}
                                                </a>
                                            @endif
                                            </div>
                                        </div> -->
                                        <div class="mt-4 text-center mb-4">
                                            <button type="submit" class="login-btn">{{ __('Login') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script type="text/javascript">
    $(document).ready(function() {
        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye-slash fa-eye");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    });
</script>

