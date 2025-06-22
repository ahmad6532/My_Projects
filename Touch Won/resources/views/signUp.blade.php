<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title>TouchWon-Portal</title>

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
    <link rel="shortcut icon" href="media/favicons/favicon.ico">
    <link rel="icon" type="image/png" sizes="192x192" href="media/favicons/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="media/favicons/favicon.ico">
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



    <!-- Page Content -->
        <div class="bg-image" style="background-image: url('assets/media/photos/photo16@2x.jpg');">
            <div class="row g-0 justify-content-center bg-black-75">
                <div class="hero-static col-sm-8 col-md-6 d-flex align-items-center p-2 px-sm-0">

                    <!-- Sign In Block -->
                    <div class="block block-transparent block-rounded w-100 mb-0 overflow-hidden ">
                        <a class="centerLogo">
                            <img src="assets/images/r.png" alt="">
                            {{-- <span class="text-dark">Dash</span><span class="text-primary">mix</span> --}}
                        </a>

                        <div class="block-content block-content-full px-lg-5 px-xl-6 py-4 py-md-5 py-lg-6 bg-body-extra-light bg-dark-green other-page-border content-Btn">
                            <a href="signin" class="ribbon">
                                <img src="assets/images/x-button.png" width="40" height="40">
                            </a>

                            <div class="mb-2 text-center">

                                {{-- <p class="text-uppercase fw-bold fs-sm text-muted">Password Reminder</p> --}}
                            </div>
                            <!-- END Header -->

                            <!-- Reminder Form -->
                            <!-- jQuery Validation (.js-validation-reminder class is initialized in js/pages/op_auth_reminder.min.js which was auto compiled from _js/pages/op_auth_reminder.js) -->
                            <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->

                            <form class="js-validation-signup" action="{{ route('vendor_registration') }}"
                                  method="POST">
                                @csrf
                                <div class="row py-3">
                                    <div class="mb-4 col-6">
                                        <input type="text"
                                               class="form-control form-control-lg form-control-alt register-fields"
                                               value="{{old('first_name')}}" id="signup-f_name" name="first_name"
                                               placeholder="First Name">
                                        <span class="errors">
                                                @error('first_name')
                                            {{$message}}
                                            @enderror
                                            </span>
                                        <span id="fname" class="errors"></span>
                                    </div>
                                    <div class="mb-4 col-6">
                                        <input type="text"
                                               class="form-control form-control-lg form-control-alt register-fields"
                                               value="{{old('last_name')}}" id="signup-l_name" name="last_name"
                                               placeholder="Last Name">
                                        <span class="errors">
                                                @error('last_name')
                                            {{$message}}
                                            @enderror
                                            </span>
                                        <span id="lname" class="errors"></span>
                                    </div>

                                    <div class="mb-4 col-6">
                                        <input type="email"
                                               class="form-control form-control-lg form-control-alt register-fields"
                                               value="{{old('email')}}" id="signup-email" name="email"
                                               placeholder="Email">
                                        <span class="errors">
                                                @error('email')
                                            {{$message}}
                                            @enderror
                                            </span>
                                    </div>
                                    <div class="mb-4 col-6">
                                        <input type="text"
                                               class="form-control form-control-lg form-control-alt register-fields"
                                               value="{{old('phone_number')}}" id="phone_number" name="phone_number"
                                               placeholder="Phone number">
                                        <span class="errors">
                                                @error('phone_number')
                                            {{$message}}
                                            @enderror
                                            </span>
                                    </div>
                                    <div class="mb-4 col-6">
                                        <input type="password" class="form-control form-control-lg form-control-alt register-fields" id="password" name="password" placeholder="Password">
                                        <span class="errors">
                                                @error('password')
                                            {{$message}}
                                            @enderror
                                            </span>

                                    </div>
                                    <div class="mb-4 col-6">
                                        <input type="password" class="form-control form-control-lg form-control-alt register-fields" id="password_confirm" name="password_confirm" placeholder="Password Confirm">
                                        <span class="errors">
                                                @error('password_confirm')
                                            {{$message}}
                                            @enderror
                                            </span>

                                    </div>
                                    <div class="mb-4 col-6">
                                        <input type="text" maxlength="10"
                                               class="form-control form-control-lg form-control-alt register-fields"
                                               value="{{old('vendor_promocode')}}" id="promocode"
                                               name="vendor_promocode"
                                               placeholder="Create promo code">
                                        <span class="errors">
                                                @error('vendor_promocode')
                                            {{$message}}
                                            @enderror
                                            </span>
                                    </div>
                                    <div class="mb-4 col-6">
                                        <input type="text"
                                               class="form-control form-control-lg form-control-alt register-fields"
                                               id="captcha_code" name="captcha-code"
                                               placeholder="Enter captcha code">
                                        <span class="errors">
                                                @error('captcha-code')
                                            {{$message}}
                                            @enderror
                                            </span>
                                        <span id="error" class="errors"></span>
<div id="error"></div>
                                    </div>


                                    <div class="mb-4 col-6">
                                        <div class="form-check text-white">
                                            <input type="checkbox" class="form-check-input" id="signup-terms"
                                                   name="signup-terms">
                                            <label class="form-check-label" for="signup-terms">I agree to
                                                <a class="point" href="{{route('t&c')}}">   Terms &amp; Conditions</a></label>
                                            <div class="errors">
                                                    @error('signup-terms')
                                                {{$message}}
                                                @enderror
                                                </div></div>
                                    </div>
                                    <div class="mb-4 col-6 captchadiv">
                                        <input type="text" readonly="readonly"
                                               class="form-control form-control-lg form-control-alt register-fields inputcolor"
                                              id="capt">
                                        <img id="ref" src="assets/images/ref.png" title="Refresh Captcha" class="button1 image-fluid"  onclick="cap()">
                                    </div>


                                </div>

                                <div class="mb-4 text-center">
                                    <button type="submit" class="btn w-75 btnGreen btn-lg btn-hero btn-primary" onclick="return validcap()">
                                        <i class="fa fa-fw fa-plus opacity-50 me-1"></i> Register
                                    </button>
                                    {{-- <p class="mt-3 mb-0 d-lg-flex justify-content-lg-between">
                                      <a class="btn btn-sm btn-alt-secondary d-block d-lg-inline-block mb-1" href="signin">
                                        <i class="fa fa-sign-in-alt opacity-50 me-1"></i> Sign In
                                      </a>
                                      <a class="btn btn-sm btn-alt-secondary d-block d-lg-inline-block mb-1" href="#" data-bs-toggle="modal" data-bs-target="#modal-terms">
                                        <i class="fa fa-book opacity-50 me-1"></i> Read Terms
                                      </a>
                                    </p> --}}
                                </div>

                            </form>
                            <!-- END Reminder Form -->
                        </div>
                    </div>
                    <!-- END Reminder Block -->
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
<script src="js/lib/jquery.min.js"></script>

<!-- Page JS Plugins -->
<script src="js/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="js/CustomJavaScript.js"></script>
<!-- Page JS Code -->
<!--

<script src="js/pages/op_auth_signup.js"></script>

-->
</body>
</html>
