<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('fonts/icomoon/style.css')}}">
    <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/loader.css?v=1')}}">
    <link rel="stylesheet" href="{{asset('css/colors.css?v=1')}}">
    <link rel="stylesheet" href="{{asset('css/custom.css?v=1')}}">

    <title>Reset your Password</title>

    <style>
        .btn-close {
            position: absolute;
            right: 20px;
    --bs-btn-close-color: #000;
    --bs-btn-close-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e");
    --bs-btn-close-opacity: 0.5;
    --bs-btn-close-hover-opacity: 0.75;
    --bs-btn-close-focus-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    --bs-btn-close-focus-opacity: 1;
    --bs-btn-close-disabled-opacity: 0.25;
    --bs-btn-close-white-filter: invert(1) grayscale(100%) brightness(200%);
    box-sizing: content-box;
    width: 1em;
    height: 1em;
    padding: 0.25em 0.25em;
    color: var(--bs-btn-close-color);
    background: transparent var(--bs-btn-close-bg) center / 1em auto no-repeat;
    border: 0;
    border-radius: 0.375rem;
    opacity: var(--bs-btn-close-opacity);
}
        .customFormSelect {
        display: block;
        width: 100%;
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.5rem;
        -webkit-transition: border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
        transition: border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
        -o-transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;

        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
        font-size: .875rem;
    }
    .btn-block{
        background: #6ebfd5;
    }
    .clouds-wrapper{
        background: url('{{asset("images/login-cloud2.png")}}') no-repeat;
        background-size: 20%;
        background-position: bottom left;
        position: relative;
    }
    .clouds-wrapper::before{
        position: absolute;
        content: '';
        width: 100%;
        height: 100%;
        background: url('{{asset("images/login-cloud1.png")}}') no-repeat;
        background-size: 45%;
        background-position: bottom right;
    }
    </style>
</head>

<body>


<div class="app-container">

    <div class="app-content">
        <div ng-include="'views/back.html'"></div>

        <div class="login-wrapper container-fluid ">
            <div class="row shadow-box-wrapper clouds-wrapper">
                <div class="col-lg-12 col-sm-12">
                    <div class="d-flex align-items-center justify-content-between w-100 login-nav" style="padding-right: 0.6rem;">
                        <a href="/"><img ng-class="{'logo-login-per': logo}" src='{{isset($ho->logo) ? asset($ho->logo) : "images/svg/logo_blue.png"}}' class="logo-login ml-4"></a>
                        <a ng-style="{'color': themeData.primary_color }" class="back-button-login mr-4" href="/login">
                            <img class="back" src="images/arrow-narrow-left.svg"> Back
                        </a>
                    </div>
                    
                    <div class=" center mx-auto">
                        <div class="">
                            <div class="center">
                                <p class="m-0" style="font-weight: 500;">Let's help you</p>
                                <h2 class="right-login-heading primary mt-0" style="color: #6ebed5;">Reset password</h2>
                            </div>
                            @include('layouts.error')
                            <form id="reset-form" action="{{route('reset_password')}}" class="close-form ml-auto" method="post">
                                @csrf
                                {{-- <div class="form-field">
                                    <label>Account Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="1">Location</option>
                                        <option value="2">User</option>
                                    </select>
                                </div> --}}
                                <div class="form-field">
                                    <label style="color: #999; font-size:12px;font-weight:500;">Email Address or Username</label>
                                    <input class="form-control customFormSelect" type="text"  name="email" id="email" required>
                                </div>
                                <div class="form-field " style="margin-top: 4rem;">
                                    <input type="submit" value="Continue" class="btn btn-info btn-block" style="{{isset($ho->sign_button_color) ? 'background: ' . $ho->sign_button_color : ''}};{{isset($ho->sign_btn_text_color) ? 'color: ' . $ho->sign_btn_text_color : ''}}">
                                </div>
                                <div class="center">
                                    Don't have an account? <a href="app.html#!/signup" class="small-underline" style="color: black;"><b>Sign up</b></a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/alertify.min.js"></script>
<script src="js/angular.min.js"></script>
<script src="js/angular-route.min.js"></script>

<!-- App Scripts -->
<script src="js/angular_app.js?v=1"></script>
<script src="js/route.js?v=1"></script>

<!-- Services -->
<script src="js/services/AppService.js?v=1"></script>
<script src="js/services/UIService.js?v=1"></script>
<script src="js/services/ApiService.js?v=1"></script>

<!-- Controllers -->
<script src="js/controllers/AppController.js?v=1"></script>
<script src="js/controllers/Auth/LoginController.js?v=1"></script>
<script src="js/controllers/Auth/MainSignupController.js?v=1"></script>
<script src="js/controllers/Auth/LocationSignupController.js?v=1"></script>
<script src="js/controllers/Auth/HeadOfficeSignupController.js?v=1"></script>
<script src="js/controllers/Auth/UserSignupController.js?v=1"></script>


</body>

</html>
