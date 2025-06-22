@php
    $currenturl =Request::segment(1);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>{{ $setting[17]['value'] }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="VT" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/theme/' . $setting[15]['value']) }}">

    {{-- custom css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    <!-- fontello icon -->
    <link rel="stylesheet" href="{{ asset('assets/font/fontello/css/fontello.css') }}">

    <!-- Plugins css -->
    <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- select2 Plugins css -->
    <link href="{{ asset('assets/libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/multiselect/css/multi-select.css') }}" rel="stylesheet" type="text/css" />
    {{-- <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" /> --}}
    <link href="{{ asset('assets/libs/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- third party css -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- datepicker Plugins css -->
    <link href="{{ asset('assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- clockpicker Plugins css -->
    <link href="{{ asset('assets/libs/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/clockpicker/bootstrap-clockpicker.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- Summernote css -->
    <link href="{{ asset('assets/libs/summernote/summernote-bs4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"
        id="bs-default-stylesheet" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />
    <link href="{{ asset('assets/css/bootstrap-dark.min.css') }}" rel="stylesheet" type="text/css"
        id="bs-dark-stylesheet" />
    <link href="{{ asset('assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css"
        id="app-dark-stylesheet" />

    <!-- icons -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/font/fontawesome-free-6.4.0/css/all.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js')}}"></script>
    <style>
        #headertitle{
            font-family: var(--font-GothamRegular);
            color: var(--heading-color);
        }
    </style>
</head>

<body class="loading"
    data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "sidebar": { "color": "light", "size": "default", "showuser": false}, "topbar": {"color": "dark"}, "showRightSidebarOnPageLoad": true}'>

    <!-- Begin page -->
    <div id="wrapper">
        <!-- Topbar Start -->
        <div class="navbar-custom">
            <div class="container-fluid">
                <div class="header-wrappe">
                    <div class="d-flex header-left-content">
                        <a href="{{url('/home')}}" class="logo logo-light text-center logo-wrape">
                            <span class="logo-lg-vt">
                                <img src="{{ asset('assets/images/theme/' . $setting[16]['value']) }}" alt=""
                                    height="50" width="100%">
                            </span>
                        </a>
                        <button class="button-menu-mobile waves-effect waves-light">
                            <i class="fontello icon-menu2" style="font-size: 1.6rem; color:#809FB8;"></i>
                        </button>
                        <h4 style="margin: 14px 0;">
                            <strong id="headertitle">
                                @if ($currenturl == 'Admin' || $currenturl == 'home')
                                    Dashboard
                                @elseif (request()->is('employee/directory'))
                                   Directory
                                @elseif (request()->is('employee/directory/*'))
                                   Directory
                                @elseif (request()->is('employee/designation'))
                                   Designation
                                @elseif (request()->is('employee/department'))
                                   Department
                                @elseif (request()->is('employee/resignation'))
                                   Resignation
                                @elseif (request()->is('add-resignation'))
                                  Resignation
                                @elseif (request()->is('edit-resignation/*'))
                                  Resignation
                                @elseif (request()->is('employee/termination'))
                                   Termination
                                @elseif (request()->is('employee/add-termination'))
                                  Termination
                                @elseif (request()->is('edit-termination/*'))
                                  Termination
                                @elseif (request()->is('employee/promotion'))
                                   Promotion
                                @elseif (request()->is('employee/add-promotion'))
                                   Promotion
                                @elseif (request()->is('edit-promotion/*'))
                                   Promotion
                                @elseif (request()->is('employee/shift-management'))
                                  Shift And Schedule
                                @elseif (request()->is('employee/shift-management/add-shift'))
                                  Shift And Schedule
                                @elseif (request()->is('employee/shift-management/edit-shift/*'))
                                  Shift And Schedule
                                @elseif (request()->is('daily-attendance-sheet'))
                                  Daily Time Sheet
                                @elseif (request()->is('monthly-attendance-sheet'))
                                  Monthly Time Sheet
                                @elseif (request()->is('yearly-attendance-sheet'))
                                  Yearly Time Sheet
                                @elseif (request()->is('yearly-attendancebranch-sheet*'))
                                  Yearly Time Sheet
                                @elseif (request()->is('leave-request'))
                                  Leave Request
                                @elseif (request()->is('leave-request/add-leave'))
                                   Leave Request
                                @elseif (request()->is('leave-request/edit-leave/*'))
                                  Leave Request
                                @elseif (request()->is('leave-settings'))
                                  Leave Settings
                                @elseif (request()->is('leave-settings/add-leave-setup'))
                                  Leave Setup
                                @elseif (request()->is('leave-settings/edit-leave-setup/*'))
                                   Leave Setup
                                @elseif (request()->is('holidays'))
                                  Holidays
                                @elseif (request()->is('user-management'))
                                  User Management
                                @elseif (request()->is('roles-permissions'))
                                User Management
                                @elseif (request()->is('add-roles-permissions'))
                                User Management
                                @elseif (request()->is('edit-roles-permissions/*'))
                                User Management
                                @elseif (request()->is('edit-user/*'))
                                User Management
                                @elseif (request()->is('add-user'))
                                User Management
                                @elseif (request()->is('company-management'))
                                  Company Management
                                @elseif (request()->is('company-management/add-company'))
                                Company Management
                                @elseif (request()->is('company-management/edit/*'))
                                Company Management
                                @elseif (request()->is('branch-management'))
                                  Office Location
                                @elseif (request()->is('branch-management/edit/*'))
                                Office Location
                                @elseif (request()->is('branch-management/add-branch'))
                                Office Location
                                @elseif (request()->is('all-notification'))
                                  Notification Management
                                @elseif (request()->is('communication/*'))
                                  Communication System
                                @elseif (request()->is('communication'))
                                  Communication System
                                @elseif (request()->is('device-management'))
                                  Device Management
                                @elseif (request()->is('device-management/add-device'))
                                Device Management 
                                @elseif (request()->is('device-management/edit-device/*'))
                                Device Management 
                                @elseif (request()->is('company-setting'))
                                 Company Settings
                                @elseif (request()->is('company-setting/add-company'))
                                Company Settings
                                @elseif (request()->is('company-setting/edit-company/*'))
                                Company Settings
                                @elseif (request()->is('SMTP'))
                                 SMTP Gateway
                                @elseif (request()->is('Appearance-settings'))
                                 Interface Appearance
                                @elseif (request()->is('new-appearance-setting'))
                                Interface Appearance
                                @elseif (request()->is('update-appearance/*'))
                                Interface Appearance
                                @elseif (request()->is('Theme-settings'))
                                 Theme Settings
                                @elseif (request()->is('version-history'))
                                 Version History
                                @elseif (request()->is('payroll/employee/salary'))
                                 Employee salary
                                @elseif (request()->is('payyroll/add-salary'))
                                Employee salary
                                @elseif (request()->is('payyroll/edit-salary/*'))
                                Employee salary
                                @elseif (request()->is('monthly/payroll/employee-salary'))
                                Monthly Payroll
                                @elseif (request()->is('monthly/payroll/employee-salary/*'))
                                Monthly Payroll
                                @elseif (request()->is('monthly/payroll/*'))
                                Monthly Payroll
                                @elseif (request()->is('monthly/payroll'))
                                Monthly Payroll
                                @else
                                    Unity HR
                                @endif
                            </strong>
                        </h4>
                    </div>
                    <div class="header-right-content">
                        <a class="">
                            <img src="{{ asset('assets/images/users/notification.svg') }}" style="width:18px" />
                        </a>
                        <a class="nav-link nav-user mr-0 waves-effect waves-light" href="#">
                            @php
                                if (Auth::user()->image) {
                                    $imagePath = public_path(Auth::user()->image);
                                    if (File::exists($imagePath)) {
                                        $user_image = asset(Auth::user()->image);
                                    } else {
                                        // If the image file doesn't exist, set a default image based on gender
                                        if (Auth::user()->gender == 'F') {
                                            $user_image = asset('assets/images/female.png');
                                        } else {
                                            $user_image = asset('assets/images/male.png');
                                        }
                                    }
                                } else {
                                    // If emp_image is empty, set a default image based on gender
                                    if (Auth::user()->gender == 'F') {
                                        $user_image = asset('assets/images/female.png');
                                    } else {
                                        $user_image = asset('assets/images/male.png');
                                    }
                                }
                            @endphp
                            <img src="{{ asset($user_image) }}" alt="user-image" class="rounded-circle">
                        </a>
                        <div class="dropdown notification-list topbar-dropdown">
                            <a class="nav-link nav-user mr-0 waves-effect waves-light" data-toggle="dropdown"
                                href="#" role="button" aria-haspopup="false" aria-expanded="false">

                                <span class="pro-user-name ml-1">


                                    <i class="fontello icon-down-open"></i>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    My Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    style="color: #FF0000!important;"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style type="text/css">
            :root {
                --heading-color: {{ $themeColor[0]['value'] }};
                --text-color: {{ $themeColor[1]['value'] }};
                --sidebar-color: {{ $themeColor[2]['value'] }};
                --sidebar-bg-color: {{ $themeColor[3]['value'] }};
                --body-color: {{ $themeColor[4]['value'] }};
                --sidebar-menu-bg-color: #DDEDFF;
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
        </style>
