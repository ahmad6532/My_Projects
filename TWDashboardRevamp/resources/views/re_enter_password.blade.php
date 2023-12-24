<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title>Dashmix - Bootstrap 5 Admin Template &amp; UI Framework</title>

    <meta name="description"
          content="Dashmix - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">

    <!-- Open Graph Meta -->
    <meta property="og:title" content="Dashmix - Bootstrap 5 Admin Template &amp; UI Framework">
    <meta property="og:site_name" content="Dashmix">
    <meta property="og:description"
          content="Dashmix - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="media/favicons/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="media/favicons/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="media/favicons/apple-touch-icon-180x180.png">
    <!-- END Icons -->

    <!-- Stylesheets -->
    <!-- Fonts and Dashmix framework -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" id="css-main" href="css/dashmix.css">
    <link rel="stylesheet" id="css-main" href="assets/css/custom.css">

    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/xwork.min.css"> -->
    <!-- END Stylesheets -->
</head>
<body>
<!-- Page Container -->
<!--
  Available classes for #page-container:

  GENERIC

    'remember-theme'                            Remembers active color theme and dark mode between pages using localStorage when set through
                                                - Theme helper buttons [data-toggle="theme"],
                                                - Layout helper buttons [data-toggle="layout" data-action="dark_mode_[on/off/toggle]"]
                                                - ..and/or Dashmix.layout('dark_mode_[on/off/toggle]')

  SIDEBAR & SIDE OVERLAY

    'sidebar-r'                                 Right Sidebar and left Side Overlay (default is left Sidebar and right Side Overlay)
    'sidebar-mini'                              Mini hoverable Sidebar (screen width > 991px)
    'sidebar-o'                                 Visible Sidebar by default (screen width > 991px)
    'sidebar-o-xs'                              Visible Sidebar by default (screen width < 992px)
    'sidebar-dark'                              Dark themed sidebar

    'side-overlay-hover'                        Hoverable Side Overlay (screen width > 991px)
    'side-overlay-o'                            Visible Side Overlay by default

    'enable-page-overlay'                       Enables a visible clickable Page Overlay (closes Side Overlay on click) when Side Overlay opens

    'side-scroll'                               Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (screen width > 991px)

  HEADER

    ''                                          Static Header if no class is added
    'page-header-fixed'                         Fixed Header


  FOOTER

    ''                                          Static Footer if no class is added
    'page-footer-fixed'                         Fixed Footer (please have in mind that the footer has a specific height when is fixed)

  HEADER STYLE

    ''                                          Classic Header style if no class is added
    'page-header-dark'                          Dark themed Header
    'page-header-glass'                         Light themed Header with transparency by default
                                                (absolute position, perfect for light images underneath - solid light background on scroll if the Header is also set as fixed)
    'page-header-glass page-header-dark'         Dark themed Header with transparency by default
                                                (absolute position, perfect for dark images underneath - solid dark background on scroll if the Header is also set as fixed)

  MAIN CONTENT LAYOUT

    ''                                          Full width Main Content if no class is added
    'main-content-boxed'                        Full width Main Content with a specific maximum width (screen width > 1200px)
    'main-content-narrow'                       Full width Main Content with a percentage width (screen width > 1200px)

  DARK MODE

    'sidebar-dark page-header-dark dark-mode'   Enable dark mode (light sidebar/header is not supported with dark mode)
-->
<div id="page-container">
@include('sweetalert::alert')

<!-- Main Container -->
    <main id="main-container">
    @include('sweetalert::alert')

    <!-- Page Content -->
        <div class="bg-image" style="background-image: url('assets/media/photos/photo16@2x.jpg');">
            <div class="row g-0 justify-content-center bg-black-75">
                <div class="hero-static col-sm-8 col-md-6 col-xl-4 d-flex align-items-center p-2 px-sm-0">
                    <!-- Sign In Block -->
                    <div class="block block-transparent block-rounded w-100 mb-0 overflow-hidden ">
                        <a class="centerLogo" href="index.html">
                            <img src="assets/images/logo-1.png" alt="">
                            {{-- <span class="text-dark">Dash</span><span class="text-primary">mix</span> --}}
                        </a>
                        <div
                            class="block-content block-content-full px-lg-5 px-xl-6 py-4 py-md-5 py-lg-6 bg-body-extra-light bg-dark-green other-page-border">
                            <!-- Header -->
                            <div class="mb-2 text-center">

                                {{-- <p class="text-uppercase fw-bold fs-sm text-muted">Sign In</p> --}}
                            </div>
                            <!-- END Header -->

                            <!-- Sign In Form -->
                            <!-- jQuery Validation (.js-validation-signin class is initialized in js/pages/op_auth_signin.min.js which was auto compiled from _js/pages/op_auth_signin.js) -->
                            <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                            <form class="js-validation-signin" action="{{route('resetpassbtn')}}" method="POST">
                                @csrf
                                @if (Session::get('success'))
                                    <div class="alert alert-success">
                                        {{Session::get('success')}}
                                    </div>
                                @endif
                                <input type="hidden" name="id" value="{{ $vData[0]['vendor_id'] }}">
                                <div class="mb-4">
                                    <div class="input-group input-group-lg">
                                        <input type="text" class="form-control" id="login-username"
                                               value="{{ $vData[0]['email'] }}" disabled>
                                        <span class="input-group-text">
                          <i class="fa fa-user-circle"></i>
                        </span>

                                    </div>

                                </div>
                                <div class="mb-4">
                                    <div class="input-group input-group-lg">
                                        <input type="password" class="form-control" id="pass" name="password"
                                               placeholder="New Password">
                                        <span class="input-group-text">
                          <i class="fa fa-asterisk"></i>
                        </span>
                                    </div>

                                    <span class="errors">
                              @error('password')
                                        {{$message}}
                                        @enderror
                          </span>
                                </div>

                                <div class="mb-4">
                                    <div class="input-group input-group-lg">
                                        <input type="password" class="form-control" id="cpass"
                                               name="password_confirmation" placeholder="Confirm Password">
                                        <span class="input-group-text">
                            <i class="fa fa-asterisk"></i>
                          </span>
                                    </div>
                                    <span class="errors">
                                @error('password_confirmation')
                                        {{$message}}
                                        @enderror
                            </span>
                                </div>
                                <div class="mb-2 text-white">
                                    <input type="checkbox" onclick="passwordDisplay()"> Show Password
                                </div>
                                <div class="text-center mb-4">
                                    <button type="submit"
                                            class="btn btn-hero btn-primary btnGreen w-100">
                                        <i class="fa fa-fw fa-sign-in-alt opacity-50 me-1"></i> Reset Password
                                    </button>
                                </div>
                                <div
                                    class="d-sm-flex justify-content-sm-between align-items-sm-center text-center text-sm-start mb-0">
                                    {{-- <div class="form-check">
                                      <input type="checkbox" class="form-check-input" id="login-remember-me" name="login-remember-me" checked>
                                      <label class="form-check-label" for="login-remember-me">Remember Me</label>
                                    </div> --}}
                                    <div class="fw-semibold fs-sm py-1">
                                        <a href="signin" class="text-white">Login</a>
                                    </div>

                                </div>
                            </form>
                            <!-- END Sign In Form -->
                        </div>
                        <div class="block-content bg-body d-none">
                            <div class="d-flex justify-content-center text-center push">
                                <a class="item item-circle item-tiny me-1 bg-default" data-toggle="theme"
                                   data-theme="default" href="#"></a>
                                <a class="item item-circle item-tiny me-1 bg-xwork" data-toggle="theme"
                                   data-theme="assets/css/themes/xwork.min.css" href="#"></a>
                                <a class="item item-circle item-tiny me-1 bg-xmodern" data-toggle="theme"
                                   data-theme="assets/css/themes/xmodern.min.css" href="#"></a>
                                <a class="item item-circle item-tiny me-1 bg-xeco" data-toggle="theme"
                                   data-theme="assets/css/themes/xeco.min.css" href="#"></a>
                                <a class="item item-circle item-tiny me-1 bg-xsmooth" data-toggle="theme"
                                   data-theme="assets/css/themes/xsmooth.min.css" href="#"></a>
                                <a class="item item-circle item-tiny me-1 bg-xinspire" data-toggle="theme"
                                   data-theme="assets/css/themes/xinspire.min.css" href="#"></a>
                                <a class="item item-circle item-tiny me-1 bg-xdream" data-toggle="theme"
                                   data-theme="assets/css/themes/xdream.min.css" href="#"></a>
                                <a class="item item-circle item-tiny me-1 bg-xpro" data-toggle="theme"
                                   data-theme="assets/css/themes/xpro.min.css" href="#"></a>
                                <a class="item item-circle item-tiny bg-xplay" data-toggle="theme"
                                   data-theme="assets/css/themes/xplay.min.css" href="#"></a>
                            </div>
                        </div>
                    </div>
                    <!-- END Sign In Block -->
                </div>
            </div>
        </div>
        <!-- END Page Content -->
    </main>
    <!-- END Main Container -->
</div>
<!-- END Page Container -->

<!--
  Dashmix JS

  Core libraries and functionality
  webpack is putting everything together at assets/_js/main/app.js
-->
<script src="js/dashmix.app.js"></script>

<!-- jQuery (required for jQuery Validation plugin) -->
<script src="js/lib/jquery.js"></script>

<!-- Page JS Plugins -->
<script src="js/plugins/jquery-validation/jquery.validate.js"></script>

<!-- Page JS Code -->
<script src="js/pages/op_auth_signin.js"></script>
<!-- Custom JS Code -->
<script src="js/CustomJavaScript.js"></script>
</body>
</html>
