<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>{{config('app.name')}}</title>

    <meta name="description"
          content="Portal - Touchwon &amp; Developed by Fun Plus Studios">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('media/favicons/favicon.ico') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/favicon.ico') }}">

    <!-- Fonts and Styles -->
    @yield('css_before')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" id="css-main" href="{{ mix('css/dashmix.css') }}">
    <link rel="stylesheet" id="css-main" href="assets/css/custom.css">
    <link rel="stylesheet" id="css-main" href="js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">


    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="js/plugins/jquery-ui/jquery-ui.css">



    <!-- You can include a specific file from public/css/themes/ folder to alter the default color theme of the template. eg: -->
<!-- <link rel="stylesheet" id="css-theme" href="{{ mix('css/themes/xwork.css') }}"> -->
@yield('css_after')

<!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};
    </script>
</head>

<body>
<!-- Page Container -->
<div id="page-container"
     class="sidebar-o enable-page-overlay sidebar-dark side-scroll page-header-fixed main-content-narrow">


    <!-- Sidebar -->
    <!--Sidebar Mini Mode - Display Helper classes
      Adding 'smini-hide' class to an element will make it invisible (opacity: 0) when the sidebar is in mini mode
      Adding 'smini-show' class to an element will make it visible (opacity: 1) when the sidebar is in mini mode
      If you would like to disable the transition animation, make sure to also add the 'no-transition' class to your element

      Adding 'smini-hidden' to an element will hide it when the sidebar is in mini mode
      Adding 'smini-visible' to an element will show it (display: inline-block) only when the sidebar is in mini mode
      Adding 'smini-visible-block' to an element will show it (display: block) only when the sidebar is in mini mode
    -->
    <nav id="sidebar" aria-label="Main Navigation">
        <div class="bg-header-dark">
            <div class="content-header bg-white-5">
                <!-- Logo -->
                <div class="fw-semibold text-white tracking-wide">
                  <span class="smini-hidden">
                      <img class="w-100 h-60 mt-4" src="assets/images/logo.jpg">
                  </span>
                </div>
                <!-- END Logo -->

                <!-- Options -->
                <div>
                    <button type="button" class="btn btn-sm btn-alt-secondary d-lg-none" data-toggle="layout"
                            data-action="sidebar_close">
                        <i class="fa fa-times-circle"></i>
                    </button>
                    <!-- END Close Sidebar -->
                </div>
                <!-- END Options -->
            </div>
        </div>

        <!-- Sidebar Scrolling -->
        <div class="js-sidebar-scroll bg-black">
            <!-- Side Navigation -->
            <div class="content-side content-side-full">
                <ul class="nav-main">
                    <div class="h-50 w-100 mt-3">
                        <div class="align-items-center justify-content-between side-box p-2">
                            <div class="ms-3 ">
                                <p class="text-white mb-0">
                                    {{ __('Vendor Credits Balance') }}
                                </p>

                                <p class="text-white fs-lg fw-semibold ">
                                    @if(session('vbalance'))
                                        {{session('vbalance')}}
                                    @else
                                        0
                                    @endif
                                </p>

                            </div>
                            <hr class="hr">
                            <div class="ms-3 ">
                                <p class="text-white mb-0">
                                    {{ __('Your Promo Code') }}
                                </p>

                                <p class="text-white fs-lg fw-semibold ">
                                    @if(session('vdata'))
                                        {{session('vdata')}}
                                    @endif
                                </p></div>

                        </div>
                    </div>

                    <br><br>

                    <li class="nav-main-item">
                        <a type="button" id="bDrawer1" href="{{ route('vdraw') }}"
                           class="btn btn-alt-secondary header-btn w-100 sidebar-height">
                            <span>{{ strtoupper(__('Drawer')) }}</span>
                        </a>
                    </li>
                    <br>
                    <li class="nav-main-item">
                        <a type="button" id="bShifts1"
                           class="btn btn-alt-secondary header-btn w-100 sidebar-height"
                           href="{{route('shiftbtn')}}">
                            <span>{{ strtoupper(__('Shifts')) }}</span>
                        </a>
                    </li>
                    <br>
                    <li class="nav-main-item">
                        <a type="button" id="bHelp"
                           class="btn btn-alt-secondary header-btn w-100 sidebar-height"
                           href="{{route('helpbtn')}}">
                            <i class="fa-solid fa-circle-question"></i>
                            <span>{{ strtoupper(__('Help')) }}</span>
                        </a>
                    </li>

                </ul>

            </div>
            <!-- END Side Navigation -->
        </div>
        <!-- END Sidebar Scrolling -->
    </nav>
    <!-- END Sidebar -->


    <header id="page-header" class="bg-black ">
        <!-- Header Content -->
        <div class="content-header p-5 w-100">
            <!-- Top Section -->
            <div class="space-x-3 block-header alignproperly block-header-default bg-black h-100">
                <!-- Toggle Sidebar -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
                <button type="button" class="btn btn-alt-secondary signout" data-toggle="layout"
                        data-action="sidebar_toggle">
                    <i class="fa fa-fw fa-bars"></i>
                </button>
                <!-- END Toggle Sidebar -->

                <a href="{{route('player_view')}}" type="button" id="bPlayers1"
                   class="btn btn-alt-secondary header-btn">
                    <i class="fas fa-user-friends opacity-100"></i> <span
                        class="ms-1 d-none d-sm-inline-block">{{ strtoupper(__('Players')) }}</span>
                </a>

                <a type="button" id="bAddPlayer1" class="btn btn-alt-secondary header-btn "
                   href="{{route('addplayer')}}">
                    <i class="fas fa-user-plus opacity-100"></i> <span
                        class="ms-1 d-none d-sm-inline-block">{{ strtoupper(__('Add Player')) }}</span>
                </a>

                <a type="button" id="bbulkcredit1" class="btn btn-alt-secondary header-btn"
                   href="{{route('bulkcredits')}}">
                    <i class="fas fa-shopping-cart opacity-100"></i> <span
                        class="ms-1 d-none d-sm-inline-block">{{ strtoupper(__('Bulk Credits')) }}</span>
                </a>
            </div>
            <!-- END Top Section -->

            <!-- Right Section -->
            <div class="logOut">
                <!-- Shortcuts Dropdown -->
                <div class="dropdown d-inline-block">
                    <a href="{{ route('logout') }}" class="btn btn-alt-secondary signout"
                       id="page-header-shortcuts-dropdown"
                       data-bs-toggle="tooltip" data-bs-placement="top" title
                       data-bs-original-title="{{ __('Logout') }}"
                       aria-describedby="tooltip431600">
                        <i class="fas fa-power-off "></i>
                    </a>
                </div>
                <!-- END Shortcuts Dropdown -->
            </div>
            <!-- END Left Section -->
        </div>
        <!-- END Header Content -->
    </header>


    <!-- Main Container -->
    <main id="main-container">
        <!-- Page Content -->
        <div class="row dashBoardContentMargin no-gutters flex-md-10-auto bg-black">
            @yield('content')
        </div>
        <!-- End Page Content -->
    </main>
    <!-- END Main Container -->

</div>
<!-- END Page Container -->

<script>

    var language = '{!!  app()->getLocale() !!}';

    //alert(language);
</script>

<!-- Jquery JS -->
<script src="js/lib/jquery.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.maskedinput/jquery.maskedinput.min.js"></script>

<!-- Page JS Plugins -->
<script src="js/plugins/jquery-ui/jquery-ui.js"></script>
<script src="js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="js/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="js/plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
<script src="js/plugins/datatables-buttons/dataTables.buttons.min.js"></script>
<script src="js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
<script src="js/plugins/datatables-buttons-jszip/jszip.min.js"></script>
<script src="js/plugins/datatables-buttons-pdfmake/pdfmake.min.js"></script>
<script src="js/plugins/datatables-buttons-pdfmake/vfs_fonts.js"></script>
<script src="js/plugins/datatables-buttons/buttons.print.min.js"></script>
<script src="js/plugins/datatables-buttons/buttons.html5.min.js"></script>
<script src="js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="js/plugins/flatpickr/flatpickr.min.js"></script>
<script src="js/plugins/sweetalert2/sweetalert2.min.js"></script>

<!--<script src="js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js"></script>-->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<!-- Dashmix Core JS -->
<script src="{{ mix('js/dashmix.app.js') }}"></script>

<script>Dashmix.helpersOnLoad(['js-flatpickr', 'jq-datepicker', 'jq-colorpicker', 'jq-maxlength', 'jq-select2', 'jq-rangeslider', 'jq-masked-inputs', 'jq-pw-strength']);</script>
<!-- Laravel Original JS -->
<!-- <script src="{{ mix('/js/laravel.app.js') }}"></script> -->

<script type="text/javascript" src="js/plugins/other/moment.min.js" ></script>
<script type="text/javascript" src="js/plugins/other/datetime-moment.js" ></script>

<!-- Custom javaScript-->
<script src="js/CustomJavaScript.js"></script>
<script src="js/pages/be_tables_datatables.js"></script>
@yield('js_after')
</body>

</html>
