@if (isset($passwordSuccessMessage))
    <div class="alert alert-success">
        {{ $passwordSuccessMessage }}
    </div>
@endif


<style>
    .tooltip {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 220px;
        background-color: black;
        color: #fff;
        text-align: center;
        border-radius: 5px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        margin-left: -110px;
        opacity: 0;
        transition: opacity 0.3s;
    }
    .tooltip:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }
    .fa-circle-question {
        font-size: 20px;
    }
</style>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Location :: {{ env('APP_NAME') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="{{ asset('css/alertify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/loader.css') }}?v=1">

    <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <link href="{{ asset('v2/fonts/LitteraText/stylesheet.css') }}" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('admin_assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <link href="{{ asset('admin_assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ route('location.color_css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('v2/css/main.css') }}">
    @if ($location->branding->has_image)
        <style>
            html {
                background-image: url("{{ $location->branding->bg }}");
                background-repeat: no-repeat;
                background-size: cover;
                background-position: top;
            }
        </style>
    @endif
    <style>
        .pincode-input-container>.form-control {
            display: inline-block;
            font-size: 20px;
        }

        body {
            font-family: "Littera Text", sans-serif !important;
        }

        .logout-img {
            width: 17px;
            height: 17px;
        }

        .user-card-btn {
            display: none;
        }


        .user-card:hover .user-card-btn {
            display: inline-block;
        }
    </style>



</head>

<body id="page-top" class="custom-background-color">



    <!-- Page Wrapper -->
    <div id="wrapper">
        <div class="loader-bg" id="loader" style="display: none;">
            <div class="bar-loader"></div>
        </div>


        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column mx-5">

            <!-- Main Content -->
            <div id="content" class="custom-scroll" style="max-height:80vh;overflow-y:scroll;margin-top:2rem;">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow"
                        style="text-align: right; margin-right:40px;margin-top:15px;">
                        <a style="" class="nav-link font-weight-bold" href="{{ route('location.logout') }}">
                            <i class="fa-solid fa-arrow-left"></i> Back</a>
                        </a>

                    </li>
                </ul>

                <!-- Begin Page Content -->
                <div class="container-fluid custom-background ">
                    @php
                        $default_logo = '/images/svg/logo_blue.png';
                    @endphp

                    <div class="row mt-2 ">



                        <div class="col-md-8 box-icon-collection-border text-center ">
                            <div class=" d-flex align-items-center">
                                <a href="/"><img
                                    src="{{ isset($location->organization_setting_assignment->organization_setting) && $location->organization_setting_assignment->organization_setting->setting_logo() ? $location->organization_setting_assignment->organization_setting->setting_logo() : asset('images/svg/logo_blue.png') }}" class="img-fluid " width="130"></a>

                            </div>

                            @include('layouts.error')
                            <div class="w-100 text-center " style="margin-top: 50px;margin-bottom: 50px;">
                                <p class="font-weight-normal text-black h4 mb-0 pl-4" style="color: #48494e">Welcome to
                                    {{ isset($location->location_code) ? $location->location_code : $location->trading_name }}
                                </p>
                            </div>

                            <div class="w-100 d-flex justify-content-between align-items-center gap-3 ">

                                <div class="input-group rounded ">
                                    <span class="input-group-text border-0 bg-transparent search-addon"
                                        id="search-addon">
                                        <i class="fas fa-search" style="color: #969697;z-index: 2"
                                            aria-hidden="true"></i>
                                    </span>
                                    <input type="search" class="form-control rounded shadow-none search-input"
                                        placeholder="Type to search" aria-label="Search" aria-describedby="search-addon"
                                        id="searchInput">
                                </div>
                                <button data-toggle="modal" data-target="#loginModal"
                                    class="primary-btn font-weight-bold user-custom-btn "
                                    style="padding: 8px 30px; white-space: nowrap;">Sign in with my email
                                    address
                                </button>
                            </div>

                            @if (count($location->quick_logins) < 1)
                                <div class="section-hold">
                                    <a href="#" class="user-placeholder" data-toggle="modal"
                                        data-target="#loginModal">
                                        <i class="fa-solid fa-user-plus"></i>
                                        <p>Add me to quick login</p>
                                    </a>
                                </div>
                                <br />
                            @endif


                            <div id="main-wrapper" class="row text-dark no-link mt-5 user-section"
                                style="row-gap: 1.4rem;">

                                @foreach ($location->quick_logins->sortByDesc('isPinned') as $index => $ql)
                                    <div class="col-md-3 user-card-wrapper">

                                        <div class="user-card {{ $ql->isPinned ? 'right-pin' : '' }}"
                                            onclick="makeActive(this)">
                                            <button onclick="userBtnHandler(event,this)" class="user-card-btn">
                                                <span></span>
                                            </button>
                                            <div class="user-card-btn-modal">
                                                <a onclick="event.stopPropagation()"
                                                    href="{{ route('location.remove_pin', ['id' => $ql->id,'_token'=>csrf_token()]) }}"><i
                                                        class="fa-solid fa-trash-can"></i>Remove User</a>
                                                <a onclick="event.stopPropagation()"
                                                    href="{{ route('location.pinned_user', ['id' => $ql->id]) }}"><i
                                                        class="fa-solid fa-thumbtack"></i>{{ $ql->isPinned ? 'Remove Pin' : 'Pin User' }}</a>
                                            </div>
                                            <div class="user-card-body">
                                                <img class="placeholder-img rounded-circle"
                                                    src="{{ $ql->user->logo ?? asset('images/pin_placeholder.png') }}"
                                                    alt="placeholder_img">
                                                <h3 class="placeholder-username">{{ $ql->user->name }}</h3>
                                                <input hidden value="{{ $ql->user_id }}" class="user_id"
                                                    type="text" name="userId" id="userId{{ $ql->id }}">
                                                <div class="pin-wrapper">
                                                    <p id="enterPin">Enter PIN</p>
                                                    <div class="user-inputs-wrapper">
                                                        <input id='input1{{ $ql->id }}' type='text'
                                                            maxLength="1">
                                                        <input id='input2{{ $ql->id }}' type='text'
                                                            maxLength="1">
                                                        <input id='input3{{ $ql->id }}' type='text'
                                                            maxLength="1">
                                                        <input id='input4{{ $ql->id }}' type='text'
                                                            maxLength="1">
                                                    </div>
                                                </div>
                                                <div class="user-role">
                                                    {{ $ql->user->getRoleName() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="col-md-3 user-holder">
                                    <a href="#" class="user-placeholder1" data-toggle="modal"
                                        data-target="#loginModal">
                                        <i class="fa-solid fa-user-plus"></i>
                                        <p>Add me to quick login</p>
                                    </a>
                                </div>

                                <!--
                            <div class="col-md-3">
                                <div class="card card-hover bg-info text-white">
                                    <div class="card-body">
                                        <h1 class="font-weight-bold"><i class="fa fa-plus"></i></h1>
                                        <p class="user-card-name">Add Me</p>
                                    </div>
                                </div>
                            </div>
                        -->

                            </div>

                            @if (count($location->quick_logins) > 4)
                                <div class="text-center text-dark h1 mt-2"><i class="fa fa-chevron-down"></i></div>
                            @endif
                            <!--
                            <button data-toggle="modal" data-target="#loginModal"
                                class="btn btn-info font-weight-bold user-custom-btn mt-5">Sign in with my email
                                address</button> -->
                            <p class="mt-3 small font-weight-bold">
                            <div class="link-wrapper">
                                <p>Don't have a User Account?</p>
                                <a class="link-unstyled" href="/app.html#!/signup/user" target="_blank">Sign up</a>
                            </div>
                            <button id="need-btn" class="btn btn-outline-secondary btn-outline-custom">
                                Need support?
                            </button>
                            <div id="msg" hidden>
                                @if ($head_office->is_viewable_to_user == 0)
                                    <span id="help-desk-text">QI tech help desk details</span>
                                @else($head_office->is_viewable_to_user == 'on')
                                    <p id="share-msg" style="text-align: left;font-weight: 500;">
                                        {{ $head_office->help_description }}</p>
                                    <span id="phone-details-email"
                                        style="display:{{ $head_office->is_email_viewable == 1 ? 'flex' : 'none' }};align-items:center;gap:0.5rem;">
                                        <p class="mb-0" style="color: rgb(30, 30, 30)"><i
                                                class="fa-solid fa-envelope"></i></p>
                                        {{ $head_office->technical_email }}
                                    </span>
                                    <span id="phone-details-phone"
                                        style="display:{{ $head_office->is_phone_viewable == 1 ? 'flex' : 'none' }};align-items:center;gap:0.5rem;">
                                        <p class="mb-0" style="color: rgb(30, 30, 30)"><i
                                                class="fa-solid fa-phone"></i>
                                        </p> {{ $head_office->technical_phone }}
                                    </span>
                                    <p style="font-weight: 600;text-align:left;display:{{ $head_office->is_viewable_hours == 1 ? 'flex' : 'none' }};"
                                        class="mt-4 mb-0">Hours Available</p>
                                    <table class="table"
                                        style="display:{{ $head_office->is_viewable_hours == 1 ? 'block' : 'none' }};">
                                        <thead>
                                            <tr class="text-left" style="text-align: left;">
                                                {{-- <th style="display: none;"></th> --}}
                                                <th>Day</th>
                                                <th>From</th>
                                                <th>To</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $days = [
                                                    'monday',
                                                    'tuesday',
                                                    'wednesday',
                                                    'thursday',
                                                    'friday',
                                                    'saturday',
                                                    'sunday',
                                                ];
                                            @endphp

                                            @csrf

                                            {{-- @foreach ($days as $day)
                                                @php
                                                    $var = 'is_open_' . $day;
                                                    $start = $day . '_start_time';
                                                    $end = $day . '_end_time';
                                                @endphp
                                                <tr class=" text-left" style="text-align: left;">
                                                    
                                                    <td style="font-weight: 500;">{{ ucfirst($day) }}</td>
                                                    <td>{{ strtolower($head_office_timing->convert_time($head_office_timing->$start)) }}
                                                    </td>
                                                    <td>{{ strtolower($head_office_timing->convert_time($head_office_timing->$end)) }}
                                                    </td>
                                                </tr>
                                            @endforeach --}}


                                        </tbody>
                                    </table>
                                @endif
                            </div>


                            </p>
                        </div>
                        <div class="col-md-4 text-start pl-4" style="place-items: center;">

                            <div class="ml-5 mt-5">
                                <div class="report-head ">
                                    <h3 class="h4 " style="color: #48494e">Quick Report</h3>
                                    <span class="tooltip">
                                        <i class="fa-regular fa-circle-question"></i>
                                        <span class="tooltiptext">Quick report forms can be completed without signing in as a user</span>
                                    </span>
                                </div>
                        
                                @php
                                    $hasReports = false;
                                @endphp
                        
                                @if (isset($head_office->near_miss) && $head_office->near_miss->is_quick_report == 1 && isset($head_office->near_miss->category) && $head_office->near_miss->isActive == true)
                                    <p class="m-0 p-0 pb-1 mt-4" style="color:#5A75E6; font-size:14px; font-weight: 400; line-height: 0.7">{{$head_office->near_miss->category->name}}</p>
                                    <div class="d-flex gap-2 qr-wrapper">
                                        <a href="{{ route('near_miss.standalone') }}?location_id={{ $location->id }}" style="color: #48494e;"
                                           class="m-0 p-0" style="font-size:15px; cursor:pointer; padding-top:2px;">Near Miss</a>
                                        @if (isset($head_office->near_miss) && $head_office->near_miss->is_qr_code == 1)
                                            <a href="{{ route('location.near_miss.qr_code') }}" style="color: #48494e;"
                                               class="m-0 p-0 qr" style="font-size:15px; cursor:pointer; padding-top:2px;"
                                               title="QR Code" target="_blank">
                                               <i class="fa fa-qrcode"></i>
                                            </a>
                                        @endif
                                    </div>
                                    @php $hasReports = true; @endphp
                                @endif
                        
                                @php
                                    $user = Auth::guard('location')->user();
                                    $forms = $user->group_forms();
                                @endphp
                        
                                @if (count($forms))
                                    @foreach ($forms->groupBy('category.name') as $category => $forms2)
                                        @php
                                            $filteredForms = $forms2->filter(function ($form) {
                                                return $form->is_quick_report && $form->is_active; // Check if form is a quick report and active
                                            });
                                        @endphp
                        
                                        @if ($filteredForms->isNotEmpty())
                                            <div class="w-100 pt-3">
                                                <p class="m-0 p-0 pb-1" style="color:#5A75E6; font-size:14px; font-weight: 400; line-height: 0.7">
                                                    {{ $category }}
                                                </p>
                                                <div class="d-flex flex-column gap-1">
                                                    @foreach ($filteredForms as $form)
                                                        <div class="d-flex gap-2 qr-wrapper">
                                                            <a style="color: #48494e;"
                                                               href="/bespoke_form_v3/#!/submit/{{ $form->id }}?location_id={{ $location->id }}"
                                                               class="m-0 p-0" style="font-size:15px; cursor:pointer; padding-top:2px;">
                                                               {{ $form->name }}
                                                            </a>
                        
                                                            @if (isset($form) && $form->is_qr_code == 1)
                                                                <a href="{{ route('location.near_miss.qr_code') }}?form_id={{ $form->id }}"
                                                                   style="color: #48494e;"
                                                                   class="m-0 p-0 qr"
                                                                   style="font-size:15px; cursor:pointer; padding-top:2px;"
                                                                   title="QR Code" target="_blank">
                                                                   <i class="fa fa-qrcode"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @php $hasReports = true; @endphp
                                        @endif
                                    @endforeach
                                @endif
                        
                                @if (!$hasReports)
                                    <p>No quick report forms available. Login to see all forms.</p>
                                @endif
                        
                            </div>
                        </div>
                        

                        {{-- <a href="{{route('location.dispensing_incidents')}}"
                            class="d-inline-block btn btn-outline-info">Dispensing incident</a> --}}
                    </div>

                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->











    @if (isset($pinCheck) && $pinCheck == 1)
        <div class="modal fade show" id="pinModal" tabindex="-1" role="dialog" aria-labelledby="pinModal"
            aria-hidden="true" style="display: block; background-color: rgba(0, 0, 0, 0.5);" id="pinModal">


            <div class="modal-dialog position-relative" role="document">
                <div class="modal-content p-4">

                    <a href="{{ route('location.dashboard') }}" type="button" class="close"
                        style="position:absolute; top:4px; right:4px;" data-dismiss="pinModal" aria-label="Close"
                        id="pinModalCloseBtn">
                        <span aria-hidden="true">&times;</span>
                    </a>

                    <form action="{{ route('location.update_pin') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <h5 class="modal-title"
                                style="color:black;font-weight: bold; text-align:center; margin-bottom:1rem"
                                id="change_password_ModalLabel">Setup Quick Sign In</h5>
                            <div class="d-none">
                                <input type="text" name="new_pin" id="new_pin_field">
                            </div>

                            <div class="d-flex justify-content-center align-items-center flex-column mx-auto gap-4"
                                style="width:42%">
                                <div class="pin-wrapper2" style="opacity: 1">
                                    <p class="" style="font-size: 10px; margin:0;" id="create-your-pin">Create
                                        your PIN</p>
                                    <div class="user-inputs-wrapper2">
                                        <input id='input1' type='text' required maxLength="1">
                                        <input id='input2' type='text' required maxLength="1">
                                        <input id='input3' type='text' required maxLength="1">
                                        <input id='input4' type='text' required maxLength="1">
                                    </div>
                                </div>
                                <button type="submit"
                                    class="btn primary-btn font-weight-bold user-custom-btn w-100 d-flex justify-content-center"
                                    style="padding: 8px 30px;">Create</button>

                                <a href={{ route('location.dashboard') }} class="fw-bold text-black">Skip</a>
                            </div>

                        </div>

                    </form>
                </div>
            </div>
    @endif
    </div>














    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal"
        aria-hidden="true">


        <div class="modal-dialog position-relative" role="document">
            <div class="modal-content p-4">
                <!-- <div class="modal-header"> -->

                <button type="button" class="close" style="position:absolute; top:4px; right:4px;"
                    data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <!-- </div> -->
                <form action="{{ route('postlogin') }}" method="post">
                    <div class="modal-body">
                        <h5 class="modal-title" style="color:black;font-weight: bold; text-align:center;"
                            id="change_password_ModalLabel">Sign in as a User</h5>
                        @csrf
                        <input type="hidden" name="type" value="1">
                        <div class="">
                            <label style="margin: 0;font-size: 12px;" for="email">Email address</label>
                            <input type="email" id="email" name="email" placeholder="Email"
                                class="form-control" style="height:50px" required>

                        </div>
                        <div class="" style="margin-top:1rem;">
                            <div class="d-flex w-100 justify-content-between">
                                <p style="margin: 0;font-size: 12px;">Password</p>
                                <a style="margin: 0;font-size: 12px;text-decoration:underline;" class="text-black"
                                    href="{{ route('forgot_password') }}">Forgotten password</a>
                            </div>
                            <input type="password" id="password" name="password" placeholder="Password"
                                class="form-control" style="height:50px" required>
                        </div>
                        <div class="form-group">
                            <style>
                                .custom-control-input:checked ~ .custom-control-label::before {
  color: #fff;
  border-color: #2bafa5;
  background-color: #2bafa5;
}
                                .custom-control-label::before{
                                    top: 0;
                                }
                                .custom-control-label::after{
                                    top: 0;
                                }
                            </style>
                            <div class="custom-control custom-checkbox small">
                                <input type="checkbox" value="1" class="custom-control-input" id="pin_check"
                                    name="pin_check"/>
                                <label class="custom-control-label text-black" for="pin_check">Add me to Quick Sign In
                                    <i class="fa fa-info-circle"></i>
                                </label>
                            </div>
                        </div>

                        <button type="submit"
                            class="btn primary-btn font-weight-bold user-custom-btn w-100 d-flex justify-content-center"
                            style="padding: 8px 30px;">Sign In</button>
                        <div class="mt-4 w-100">
                            <p class="text-black w-100 text-center">Don't have an account? <a
                                    class="text-black text-decoration-underline" href="{{ route('signup') }}">Sign
                                    up</a></p>
                            <p class="text-black w-100 text-center">
                            <p class="text-black text-decoration-underline text-center"  id="need-btn2">Need Help?
                            </p>
                            </p>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>


    <!-- Pin Login -->
    <div class="modal fade" id="pinloginModal" tabindex="-1" role="dialog" aria-labelledby="pinloginModal"
        aria-hidden="true">


        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="change_password_ModalLabel">Login with your pin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="pinform" action="{{ route('location.pinlogin') }}" method="post">
                    <input type="hidden" id="uid" name="uid">
                    <input type="hidden" id="pin2" name="pin2">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">

                            <div style="width:200px;margin:auto">
                                <input tabindex="0" type="text" id="pincode-input1" name="pin"
                                    style="display:inline-block">
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://kit.fontawesome.com/350d033a5a.js" crossorigin="anonymous"></script>
    <script src="{{ asset('admin_assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('admin_assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>


    <!-- Custom scripts for all pages-->
    <script src="{{ asset('admin_assets/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('js/alertify.min.js') }}"></script>


    <script>
        const msg = $('#msg').html();

        $('#need-btn, #need-btn2').on('click', function(e) {
            console.log("hello");
            alertify.alert('Need Help!', msg);
        });
    </script>


    <script>
        var uid = -1;

        function set_focus() {

            $('#pinloginModal').focus();
            // $('#pin').focus();
            // $('#pin').removeAttr('disabled');
        }
    </script>


    <script type="text/javascript" src="{{ asset('admin_assets/js/bootstrap-pincode-input.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#searchInput').focus()
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.user-card').length) {
                $('.user-card').removeClass('active');
                $('.user-card-btn-modal').css('display', 'none');
            }
        });

        $('.user-card').on('click', function() {
            $('.user-card-btn-modal').css('display', 'none');
            $('.user-card').removeClass('active');
            $(this).addClass('active');
            $(this).find('.user-inputs-wrapper input:first').focus()
        });



        var inputs = [];
        var apiCallInProgress = false;

        function makeActive(element) {
            $(element).addClass('active');
            $(element).children().eq(1).css('display', 'none');
            inputs = $(element).find('.user-inputs-wrapper input').map(function() {
                return this.id;
            }).get();
            inputs.map((id) => {
                const input = document.getElementById(id);
                addListener(input);
            });

        }


        function userBtnHandler(event, element) {
            event.stopPropagation();
            $('.user-card-btn-modal').css('display', 'none');
            const parentElement = $(element).parent();
            if (parentElement.hasClass('active')) {
                parentElement.removeClass('active')
            } else {
                $(element).siblings(':first').css('display', 'flex');
            }
        }




        function addListener(input) {
            input.addEventListener("keyup", (event) => {
                event.stopPropagation();
                const code = parseInt(input.value);
                if (code >= 0 && code <= 9) {
                    const n = input.nextElementSibling;
                    if (n) n.focus();
                } else {
                    input.value = "";
                }

                const key = event.key; // const {key} = event; ES6+
                if (key === "Backspace" || key === "Delete") {
                    const prev = input.previousElementSibling;
                    if (prev) prev.focus();
                }
                checkFulfillment();
            });

        }

        function checkFulfillment() {
            fulfilledInputs = 0;

            inputs.forEach((id) => {
                const input = document.getElementById(id);
                if (input.value !== "") {
                    fulfilledInputs++;
                }
            });

            if (fulfilledInputs === inputs.length && !apiCallInProgress) {
                apiCallInProgress = true;
                $('#' + inputs[0]).siblings().attr('readonly', true);
                pinSubmit()
            } else {}
        }

        async function pinSubmit() {
            const input1 = $('#' + inputs[0]).val();
            const input2 = $('#' + inputs[1]).val();
            const input3 = $('#' + inputs[2]).val();
            const input4 = $('#' + inputs[3]).val();
            try {
                $('#loader').fadeIn();
                const response = await fetch('/location/pin_login2', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        uid: $('#' + inputs[0]).closest('.user-card-body').find('.user_id').val(),
                        pin2: `${input1}${input2}${input3}${input4}`
                    }),
                })

                if (response.status == 422) {
                    apiCallInProgress = false;
                    $('#loader').fadeOut();

                    $('#' + inputs[0]).parent().addClass('wrong-otp');
                    $('#' + inputs[0]).parents().find('#enterPin').text('Try Again!').css('color', 'red')
                    setTimeout(function() {
                        $(input1).parent().remove('wrong-otp');
                        $('#' + inputs[0]).val('')
                        $('#' + inputs[1]).val('')
                        $('#' + inputs[2]).val('')
                        $('#' + inputs[3]).val('')
                        $('#' + inputs[0]).siblings().removeAttr('readonly')
                        $('#' + inputs[0]).parent().removeClass('wrong-otp')
                        $('#' + inputs[0]).focus()
                    }, 500);
                } else if (response.status == 200) {
                    apiCallInProgress = false;
                    // $('#loader').fadeOut();
                    $('#' + inputs[0]).parents().find('#enterPin').text('Success!').css('color', 'green')
                    $('#' + inputs[0]).css('outline', '1px solid green');
                    $('#' + inputs[0]).siblings().css('outline', '1px solid green');
                    setTimeout(function() {
                        window.location.href = '/location/dashboard'
                    }, 1000);
                } else if (response.status == 429) {
                    apiCallInProgress = false;
                    $('#loader').fadeOut();
                    $('#' + inputs[0]).parents().find('#enterPin').text('Too many attempts!').css('color',
                        'red')
                    $(input1).parent().addClass('wrong-otp');
                    setTimeout(function() {
                        $(input1).parent().remove('wrong-otp');
                        $('#' + inputs[0]).val('')
                        $('#' + inputs[1]).val('')
                        $('#' + inputs[2]).val('')
                        $('#' + inputs[3]).val('')
                        $('#' + inputs[0]).siblings().removeAttr('readonly')
                        $('#' + inputs[0]).parent().removeClass('wrong-otp')
                        $('#' + inputs[0]).focus()
                    }, 500);
                } else {
                    apiCallInProgress = false;
                    $('#loader').fadeOut();
                    alertify.alert('Unkown Error Occured!').title('Error')
                }
            } catch (error) {
                console.log(error, 'error ocuerd')
            }
        }



        $(document).ready(function() {
            $('#pincode-input1').pincodeInput({
                hidedigits: true,
                complete: function(value, e, errorElement) {
                    $('#uid').val(uid);
                    $('#pin2').val(value);
                    //$(elem).attr('disabled', true);
                    $('#pinform').submit();
                }
            });
            const inputs = $('.user-inputs-wrapper2 input');

            inputs.on('input', function() {
                const currentInput = $(this);
                const nextInput = currentInput.next('input');

                if (currentInput.val().length === 1) {
                    nextInput.focus();
                }

                checkAllInputs();
            });

            inputs.on('keydown', function(e) {
                const currentInput = $(this);
                const prevInput = currentInput.prev('input');

                if (e.key === 'Backspace' && currentInput.val().length === 0) {
                    prevInput.focus();
                }
            });

            function checkAllInputs() {
                let allFilled = true;
                let values = [];

                inputs.each(function() {
                    if ($(this).val().length === 0) {
                        allFilled = false;
                    }
                    values.push($(this).val());
                });
                $("#new_pin_field").val(values.join(''));
                if (allFilled) {
                    console.log("All inputs filled:", values.join(''));
                    $('#create-your-pin').text('Confirm your PIN');
                } else {
                    console.log("Some inputs are missing.");
                    $('#create-your-pin').text('Create your PIN');
                }
            }

        });
    </script>

    @if (Session::has('success'))
        <script>
            alertify.success("{{ Session::get('success') }}");
        </script>
    @elseif(Session::has('error'))
        <script>
            alertify.error("{{ Session::get('error') }}");
        </script>
    @endif




</body>

</html>
