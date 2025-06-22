<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="QI-Tech System">
    <meta name="author" content="Khuram Nawaz Khayam">

    <title>@yield('title') :: {{ env('APP_NAME') }}</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('admin_assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700&display=swap" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('admin_assets/css/sb-admin-2.min.css')}}" rel="stylesheet">
    <link href="{{asset('bootstrap-datepicker/css/bootstrap-datepicker3.standalone.css')}}" rel="stylesheet">
    <link href="{{asset('admin_assets/css/style.css')}}" rel="stylesheet">
    
    @yield('styles')
    <link href="{{route('location.color_css')}}" rel="stylesheet">

</head>

<body id="page-top">

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>


    <div class="heading-logo">
        <a href="{{route('location.dashboard')}}">
            <img src="{{Auth::guard('location')->user()->branding->logo}}" alt="Company Logo">
        </a>
    </div>
        <p class="address font-weight-bold " @if(request()->route()->getName() != 'location.dashboard') style="visibility: hidden;" @endif>{{Auth::guard('location')->user()->name()}}</p>
        <nav class="nearmiss-navbar topheader-nav">
            <ul>
            @if(in_array(request()->route()->getName(),['location.view_near_miss','location.nearmiss.analysis']))
                <li> <a class="@if(request()->route()->getName() == 'location.view_near_miss') active @endif" href="{{route('location.view_near_miss')}}"><span>Reported Near Misses</span></a></li>
                <li> <a class="@if(request()->route()->getName() == 'location.nearmiss.analysis') active @endif" href="{{route('location.nearmiss.analysis')}}"><span>Analysis</span></a></li>
            @endif
            @hasSection('top-nav-title')
                <li> <a class="active" href="#"><span>@yield('top-nav-title')</span></a></li>
            @endif

                @if(Route::is(['be_spoke_forms.be_spoke_form*','location.be_spoke_form_category*'])  && Auth::guard('location')->user()->userCanUpdateSettings() )
                <li> 
                    <a class="@if(Route::is('be_spoke_forms.be_spoke_form*')) active @endif" href="{{route('be_spoke_forms.be_spoke_form.index')}}">
                        <span>Bespoke Forms</span>
                    </a>
                </li>
                <li>
                    <a class="@if(Route::is('location.be_spoke_form_category*')) active @endif" href="{{route('location.be_spoke_form_category.index')}}">
                        <span>Bespoke Form Categories</span>
                    </a>
                </li>
                @endif
            </ul>
        </nav> 
    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none" style="display: none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                 aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small"
                               placeholder="Search for..." aria-label="Search"
                               aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>
        <?php 
        $current_location = Illuminate\Support\Facades\Auth::guard('location')->user();
        $current_user = Illuminate\Support\Facades\Auth::guard('web')->user();  
        $topbar_notifications = \App\Models\LocationUserNotification::where('location_id',$current_location->id)->where('user_id', $current_user->id)->orderBy('status','desc')->orderBy('created_at','desc')->take(5)->get();
        $topbar_notification_unread_count =\App\Models\LocationUserNotification::where('location_id',$current_location->id)->where('user_id', $current_user->id)->where('status','unread')->count(); 
       ?>
        <!-- Nav Item - Alerts -->
        <li class="nav-item  mx-1">
            <a class="nav-link " href="{{route('location.root_cause_analysis.requests')}}">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
               @if(count($current_location->root_cause_analysis_requests) > 0) <span class="badge badge-danger badge-counter">{{count($current_location->root_cause_analysis_requests)}}</span> @endif
            </a>
        </li>
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
               @if($topbar_notification_unread_count > 0) <span class="badge badge-danger badge-counter">{{$topbar_notification_unread_count}}</span> @endif
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header dropdown-header-gradient-primary">
                    Alerts Center
                    <a href="#" class="text-white mark_all_notifications_as_read float-right">Mark all as read</a>
                </h6>
                @foreach($topbar_notifications as $alert)
                <a class="dropdown-item d-flex align-items-center" href="{{!empty($alert->url)?route('location.process_notifcation_url',$alert->id):'#'}}">
                    <div class="mr-3">
                        <div class="icon-circle {{\App\Models\LocationUserNotification::alertColoring($alert->type)['background_class']}}">
                            <i class="{{\App\Models\LocationUserNotification::alertColoring($alert->type)['icon']}} text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">{{$alert->created_at->format('d F, Y h:i a')}}</div>
                        <span class="@if($alert->status == \App\Models\LocationUserNotification::$statusUnread)font-weight-bold @endif">{{$alert->title}}</span>
                    </div>
                </a>
                @endforeach
                @if(count($topbar_notifications) == 0) <p class="font-italic center pt-1">No notifications.</p> @endif
                <!-- <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-success">
                            <i class="fas fa-donate text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">December 7, 2019</div>
                        $290.29 has been deposited into your account!
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-warning">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">December 2, 2019</div>
                        Spending Alert: We've noticed unusually high spending for your account.
                    </div>
                </a> -->
                <a class="dropdown-item text-center small text-gray-500" href="{{route('location.view_notifications')}}">Show All</a>
            </div>
        </li>


        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="img-profile rounded-circle"
                     src="{{asset('admin_assets/img/undraw_profile.svg')}}">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{route("user.logout")}}" >
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Sign me Out
                </a>
                <a class="dropdown-item" href="{{route('user.view_profile')}}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Edit My Details
                </a>
{{--                <div class="dropdown-divider"></div>--}}

                <a class="dropdown-item" href="{{route("location.logout")}}" >
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Location Sign Out
                </a>
            </div>
        </li>



    </ul>

</nav>
<!-- End of Topbar -->
<!-- Page Wrapper -->
<div id="wrapper">



    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled sidebar-menu-ul print-display-none" id="accordionSidebar">

        <li class="nav-item">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" title="Report Now" href="#"
                data-toggle="collapse" data-target="#report_now" aria-expanded="true" aria-controls="collapseReportNow">
                <div class="sidebar-brand-icon">
                    <i class="fa fa-plus-circle"></i>
                </div>
                <div class="sidebar-brand-text mx-3"></div>
            </a>
            <div id="report_now" class="collapse" aria-labelledby="headingReportNow" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header text-info ">Report Now Menu</h6>
        
                    <a class="collapse-item" href="{{route('location.near_miss')}}">Near Miss</a>
                    <a class="collapse-item" href="{{route('location.dispensing_incidents')}}">Dispensing Incident</a>
                    <!-- Ul wehgaira -->
        
                    @php
                    $user = Auth::guard('location')->user();
                    $forms = [];
                    if($user->head_office()){
                        if($user->organization_setting_assignment)
                        {
                            foreach($user->assigned_bespoke_forms as $h_form)
                            {
                                if($h_form->form->is_active)
                                    $forms[] = $h_form->form;
                            }
                        $forms = collect($forms);
                        //$forms = $user->assigned_bespoke_forms;
                        }
                        
                    }
                    else 
                    {
                        $forms = $user->be_spoke_forms->where('is_active',1)->get();
                        
                    }
        
                    @endphp
                    
                    <div class="dropright">
                        @foreach($forms->groupBy('category.name') as $category => $forms2)
                            <a class="collapse-item dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{$category}}
                            </a>
                            <div class="dropdown-menu">
                                <h6 class="collapse-header text-info ">{{$category}}</h6>
                                @foreach($forms2 as $form)
                                <a class="collapse-item" href="{{route('be_spoke_forms.be_spoke_form.preview', $form->id)}}">{{$form->name}}</a>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </li>

        <style>
            #wrap {
              width: 100%;
              height: 50px;
              margin: 0;
              z-index: 99;
              position: relative;
              background-color: #ffffff;
            }
            .navbar1 {
              height: 50px;
              padding: 0;
              margin: 0;
              position: absolute;
            }
            .navbar1 li {
              height: auto;
              width: 135px;
              float: left;
              text-align: center;
              list-style: none;
              padding: 0;
              margin: 0;
              background-color: #ffffff;
            }
            .navbar1 a {
              padding: 18px 0;
              border-left: 1px solid #ffffff;
              text-decoration: none;
              color: #3a3b45;
              display: block;
            }
            .navbar1 li:hover,
            .navbar1 a:hover {
              background-color: #ffffff;
            }
            .navbar1 li ul {
              display: none;
              height: auto;
              margin: 0;
              padding: 0;
            }
            .navbar1 li:hover ul {
              display: block;
            }
            .navbar1 li ul li {
              background-color: #ffffff;
            }
            .navbar1 li ul li a {
              
            }
            .navbar1 li ul li a:hover {
              background-color: #ffffff;
            }
          </style>



        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="{{route('location.dashboard')}}" title="Dashboard">
                <i class="fas fa-fw fa-tachometer-alt"></i>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">


        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="{{route('location.view_near_miss')}}" title="Near Miss">
                <i class="fas fa-fw fa-star"></i>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">
        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="{{route('location.view_dispensing_incidents')}}" title="Dispensing Incidents">
                <i class="fas fa-fw fa-eraser"></i>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">
        <li class="nav-item">
            <a class="nav-link" href="{{route('location.view_patient_safety_alerts')}}" title="Patient Safety Alerts">
                <i class="fas fa-exclamation-triangle"></i>
            </a>
        </li>

        <li class="nav-item">
                <a class="nav-link"  href="#" title=" Be Spoke Forms "  data-toggle="collapse" data-target="#settings_forms" aria-expanded="true" aria-controls="collapseSettings">
                    <i class="fas fa-fw fa-list-alt"></i>
                </a>
                <div id="settings_forms" class="collapse" aria-labelledby="headingSettings" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header text-info ">Be Spoke Forms</h6>

                        @php 
                        $user = Auth::guard('location')->user();
                        $forms = [];
                            if($user->head_office()){
                                if($user->organization_setting_assignment)
                                    $forms = $user->organization_setting_assignment->organization_setting->organisationSettingBespokeForms;
                            }
                            else {
                                $forms = $user->be_spoke_forms;
                            }
                        @endphp
                        @foreach($forms as $form)
                            @php
                                if($user->head_office() && count($forms) > 0)
                                    $form = $form->form;
                            @endphp
                            <a class="collapse-item" href="{{route('be_spoke_forms.be_spoke_form.records', $form->id)}}">{{$form->name}}</a>
                        @endforeach
                        
                    </div>
                </div>
        </li>

    <!-- Divider -->
                <hr class="sidebar-divider">

        <div class="sidebar-menu-bottom">


        <li class="nav-item dropdown-submenu">
            <a class="nav-link" href="#" title="Settings" data-toggle="collapse" data-target="#settings"
               aria-expanded="true" aria-controls="collapseSettings">
                <i class="fas fa-cogs"></i>
            </a>

            <div id="settings" class="collapse" aria-labelledby="headingSettings"
                 data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header text-info ">Settings</h6>
                    <a class="collapse-item" href="{{route('location.edit_location_details')}}">Location Details</a>
                    <a class="collapse-item" href="{{route('be_spoke_forms.be_spoke_form.index')}}">Be Spoke Forms</a>
                    <!-- <a class="collapse-item" href="{{route('location.settings.nearmisses')}}">Near Misses</a> -->
                    <div class="dropright">
                        <a class="collapse-item dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Password & Security
                        </a>
                        <div class="dropdown-menu">
                            <h6 class="collapse-header text-info ">Password & Security</h6>
                            <a class="collapse-item" href="{{route('location.update_password_view')}}">Change Password</a>
                            <a class="collapse-item" href="#">Assign Manager</a>
                            <a class="collapse-item" href="{{route('location.verified_devices')}}">Verified Devices</a>
                        </div>
                    </div>
                    <a class="collapse-item" href="{{route('location.subscription')}}">Subscription & Invoices</a>
                    <div class="dropright">
                        <a class="collapse-item dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Personalise My Account
                        </a>
                        <div class="dropdown-menu">
                            <h6 class="collapse-header text-info ">Personalize</h6>
                            <a class="collapse-item" href="{{route('location.color_branding')}}">Colour & Branding</a>
                            <a class="collapse-item" href="{{route('location.reporting')}}">Reporting</a>
                        </div>
                    </div>
                    <a class="collapse-item" href="{{route('location.blocked_users')}}">Blocked Users</a>
                    <a class="collapse-item" href="{{route('location.export_incidents')}}">Export Incidents</a>
                </div>
            </div>
        </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <li class="nav-item">
            <a class="nav-link" href="#" title="Help">
                <i class="fas fa-question"></i>
            </a>
        </li>
            <!-- Divider -->
            <hr class="sidebar-divider">
        @if(Auth::guard('location')->user()->branding->has_logo)
            <li class="nav-item text-center">
             <img src="{{asset('images/svg/QI Tech white.svg')}}" alt="default Logo">
                <small class="text-white-50">Powered by</small>

        </li>
     @endif
        </div>

        {{--        <!-- Divider -->--}}
        {{--        <hr class="sidebar-divider">--}}



        {{--        <!-- Sidebar Toggler (Sidebar) -->--}}
        {{--        <div class="text-center d-none d-md-inline" style="display: none;">--}}
        {{--            <button class="rounded-circle border-0" id="sidebarToggle"></button>--}}
        {{--        </div>--}}

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content" class="">


            <!-- Begin Page Content -->

        @yield('content')

        <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <!-- <div class="copyright text-center my-auto">
                    <span>Copyright &copy; {{ env('APP_NAME') . " " . \Carbon\Carbon::now()->year }} </span>
                </div> -->
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="login.html">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="{{asset('admin_assets/vendor/jquery/jquery.min.js')}}"></script>
<script src="{{asset('admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

<!-- Core plugin JavaScript-->
<script src="{{asset('admin_assets/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

<!-- Custom scripts for all pages-->
<script src="{{asset('admin_assets/js/sb-admin-2.min.js')}}"></script>
<script src="{{asset('bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
@yield('scripts')
<script src="{{asset('admin_assets/location-script.js')}}"></script>
</body>

</html>