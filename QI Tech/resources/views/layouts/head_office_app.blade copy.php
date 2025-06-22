<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="QI-Tech System">
    <meta name="author" content="Khuram Nawaz Khayam,  Ahtsham Farooq">

    <title>@yield('title') :: {{ env('APP_NAME') }}</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('admin_assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">

    <!-- <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700&display=swap" rel="stylesheet"> -->

    <!-- Custom styles for this template-->
    <link href="{{asset('admin_assets/css/sb-admin-2.min.css')}}" rel="stylesheet">    
    <link href="{{asset('bootstrap-datepicker/css/bootstrap-datepicker3.standalone.css')}}" rel="stylesheet">

    <link href="{{asset('admin_assets/css/style.css')}}" rel="stylesheet">
    {{--
    <link href="{{asset('admin_assets/css/color_style.css')}}" rel="stylesheet">--}}
    <link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
    @yield('styles')
    <link href="{{route('head_office.color_css')}}" rel="stylesheet">
    
    <link href="{{asset('admin_assets/css/select2.min.css')}}" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Topbar -->
    <nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow">

        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop"
            class="btn btn-link @if(!isset($hide_sidebar)) d-md-none @endif rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>


        <div class="heading-logo @if(isset($case_manager)) case_manager_page_logo @endif">
            <a href="{{route('head_office.dashboard')}}">
                <img src="{{asset('images/svg/black.svg')}}" class="" alt="Qi Tech Logo">
            </a>
            @if(isset($case_manager))<span class="h6 case_manager_title">Case Manager</span>@endif
        </div>
        @if(request()->route()->getName() == 'head_office.dashboard')
        <?php $selected_ho_for_address = Auth::guard('web')->user()->selected_head_office; ?>
        <p class="address font-weight-bold ">{{$selected_ho_for_address->address}}</p>
        @endif
        @yield('case_manager_tabs')
        @hasSection('top_bar_search')
        @yield('top_bar_search')
        @endif
        <nav class="nearmiss-navbar topheader-nav" @if(isset($hide_top_header_bar)) style="display:none" @endif>
            <ul>
                <li><i class="fa fa-bars open-nav"></i></li>
                @hasSection('topbar_nav_items')
                @yield('topbar_nav_items')
                @endif
                @if(in_array(request()->route()->getName(),['head_office.my_organisation','head_office.organisation_structure',
                'head_office.organisation_tags','head_office.tag_category_tags','head_office.organisation.assign_tags',
                'head_office.organisation.assign_groups', 'organisation_settings.organisation_setting.index', 
                'organisation_settings.organisation_setting.create', 'organisation_settings.organisation_setting.edit']))
                <li> <a class="@if(request()->route()->getName() == 'head_office.my_organisation') active @endif"
                        href="{{route('head_office.my_organisation')}}"><span>My Locations</span></a> </li>
                <li> <a class="@if(request()->route()->getName() == 'head_office.organisation_structure') active @endif"
                        href="{{route('head_office.organisation_structure')}}"><span>Organisation Structure</span></a>
                </li>
                <li> <a class="@if(request()->route()->getName() == 'head_office.organisation_tags') active @endif"
                        href="{{route('head_office.organisation_tags')}}"><span>Tags</span></a></li>
                <li> <a class="@if(request()->route()->getName() == 'organisation_settings.organisation_setting.index') active @endif"
                    href="{{route('organisation_settings.organisation_setting.index')}}"><span>Organisation Settings</span></a></li>
                {{-- <li> <a class="@if(request()->route()->getName() == 'head_office.organisation_settings') active @endif"
                        href="{{route('head_office.organisation_settings')}}"><span>Settings</span></a></li> --}}
                @endif

                @if(Route::is(['head_office.be_spoke_form*','be_spoke_form_categories.be_spoke_form_category*','head_office.gdpr*']))
                <li> <a class="@if(Route::is('head_office.be_spoke_form*')) active @endif"
                    href="{{route('head_office.be_spoke_form.index')}}"><span>Bespoke Forms</span></a> </li>
                <li> <a class="@if(Route::is('be_spoke_form_categories.be_spoke_form_category*')) active @endif"
                    href="{{route('be_spoke_form_categories.be_spoke_form_category.index')}}"><span>Bespoke Form Categories</span></a> </li>
                <li> <a class="@if(Route::is('head_office.gdpr.index')) active @endif"
                    href="{{route('head_office.gdpr.index')}}"><span>Gdpr Tags</span></a> </li>
                    
                @endif








                @if(in_array(request()->route()->getName(),['head_office.psa','head_office.psa.holding_area','head_office.psa.view']))
                <?php 
            $headOffice = Auth::guard('web')->user()->selected_head_office;
            $unApproved_count_holding_area = App\Models\Headoffices\ReceivedNationalAlert::where('status','unapproved')->where('head_office_id',$headOffice->id)->count(); 
            ?>
                <li> <a class="@if(request()->route()->getName() == 'head_office.psa') active @endif"
                        href="{{route('head_office.psa')}}"><span>Patient Safety Alerts</span></a> </li>
                <li> <a class="@if(request()->route()->getName() == 'head_office.psa.holding_area') active @endif"
                        href="{{route('head_office.psa.holding_area')}}"><span>Holding Area</span>
                        @if(isset($unApproved_count_holding_area) && $unApproved_count_holding_area)
                        <i class="badge badge-danger badge-counter">{{$unApproved_count_holding_area}}</i>
                        @endif</a> </li>
                @endif
                @hasSection('top-nav-title')
                <li> <a class="active" href="#"><span>@yield('top-nav-title')</span></a></li>
                @endif
                <li class="navbar-open"></li>
            </ul>
        </nav>

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">


            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none" style="display: none">
                <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-search fa-fw"></i>
                </a>
                <!-- Dropdown - Messages -->
                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                    aria-labelledby="searchDropdown">
                    <form class="form-inline mr-auto w-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
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
        $current_headoffice = Illuminate\Support\Facades\Auth::guard('web')->user()->selected_head_office;
        $topbar_notifications = \App\Models\Headoffices\HeadofficeUserNotification::where('head_office_id',$current_headoffice->id)->orderBy('status','desc')->orderBy('created_at','desc')->take(5)->get();
        $topbar_notification_unread_count =\App\Models\Headoffices\HeadofficeUserNotification::where('head_office_id',$current_headoffice->id)->where('status','unread')->count(); 
       ?>
            <!-- Nav Item - Alerts -->
            @if(isset($case_manager)) <li class="nav-item mx-1"> <a href="{{route('head_office.dashboard')}}"
                    class="text-info nav-link"><i class="fa fa-arrow-left"></i>&nbsp;Back</a></li>@endif
            <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                    <!-- Counter - Alerts -->
                    @if($topbar_notification_unread_count > 0) <span
                        class="badge badge-danger badge-counter">{{$topbar_notification_unread_count}}</span> @endif
                </a>
                <!-- Dropdown - Alerts -->
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                    aria-labelledby="alertsDropdown">
                    <h6 class="dropdown-header dropdown-header-gradient-primary">
                        Alerts Center
                        <a href="#" class="text-white mark_all_notifications_as_read float-right">Mark all as read</a>
                    </h6>

                    @foreach($topbar_notifications as $alert)
                    <a class="dropdown-item d-flex align-items-center"
                        href="{{!empty($alert->url)?route('head_office.process_notifcation_url',$alert->id):'#'}}">
                        <div class="mr-3">
                            <div
                                class="icon-circle {{\App\Models\Headoffices\HeadofficeUserNotification::alertColoring($alert->type)['background_class']}}">
                                <i
                                    class="{{\App\Models\Headoffices\HeadofficeUserNotification::alertColoring($alert->type)['icon']}} text-white"></i>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">{{$alert->created_at->format('d F, Y h:i a')}}</div>
                            <span
                                class="@if($alert->status == \App\Models\Headoffices\HeadofficeUserNotification::$statusUnread)font-weight-bold @endif">{{$alert->title}}</span>
                        </div>
                    </a>
                    @endforeach
                    @if(count($topbar_notifications) == 0) <p class="font-italic center pt-1">No notifications.</p>
                    @endif
                    <a class="dropdown-item text-center small text-gray-500"
                        href="{{route('headoffice.view_notifications')}}">Show All</a>
                </div>
            </li>


            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <!-- <img class="img-profile rounded-circle" src="{{asset('admin_assets/img/undraw_profile.svg')}}"> -->
                    @php $current_user = Illuminate\Support\Facades\Auth::guard('web')->user(); @endphp
                    {!! $current_user->selected_head_office->head_office_logo() !!}
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    @if(Auth::guard('web')->user()->has_multiple_head_offices)
                    <a class="dropdown-item" href="{{route('head_office.preview_list')}}">
                        <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i>
                        Change Head Office
                    </a>
                    @endif
                    <a class="dropdown-item" href="{{route('user.view_profile')}}">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Edit My Details
                    </a>
                    <a class="dropdown-item" href="{{route('head_office.view_profile')}}">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        My Headoffice Profile
                    </a>
                    {{-- <div class="dropdown-divider"></div>--}}

                    <a class="dropdown-item" href="{{route("user.logout")}}">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Head Office Sign Out
                    </a>
                </div>
            </li>



        </ul>

    </nav>
    <!-- End of Topbar -->
    <!-- Page Wrapper -->
    <div id="wrapper">



        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion @if(!isset($hide_sidebar)) toggled @endif sidebar-menu-ul  @if(isset($hide_sidebar)) sidebar-hidden @endif"
            id="accordionSidebar">

            <li class="nav-item">
                <!-- Sidebar - Brand -->
                <a class="sidebar-brand d-flex align-items-center justify-content-center" title="Head Office Main Menu"
                    data-toggle="collapse" data-target="#all_users" aria-expanded="true"
                    aria-controls="collapseReportNow">
                    <div class="sidebar-brand-icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="sidebar-brand-text mx-3"></div>
                </a>
                <div id="all_users" class="collapse" aria-labelledby="headingAllUsers" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header text-info ">Head Office Main Menu</h6>
                        <a class="collapse-item" href="{{route('head_office.head_office_user_profiles')}}">Users
                            Profiles</a>
                        <a class="collapse-item" href="{{route('head_office.head_office_users')}}">Head Office Users
                        </a>
                        <a class="collapse-item text-danger" href="#">Blocked Users </a>
                    </div>
                </div>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item @if(request()->route()->getName() =='head_office.dashboard' ) active @endif">
                <a class="nav-link" href="{{route('head_office.dashboard')}}" title="Dashboard">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                </a>
            </li>

            <hr class="sidebar-divider">
            <li class="nav-item @if(request()->route()->getName() =='head_office.my_organisation' ) active @endif">
                <a class="nav-link @if(request()->route()->getName() == 'head_office.my_organisation') active @endif "
                    href="{{route('head_office.my_organisation')}}" title="My Organisation">
                    <i class="fa fa-sitemap"></i>
                </a>
            </li>
            <hr class="sidebar-divider">
            <li class="nav-item @if(request()->route()->getName() =='head_office.psa' ) active @endif">
                <a class="nav-link @if(request()->route()->getName() == 'head_office.psa') active @endif "
                    href="{{route('head_office.psa')}}" title="Patient Safety Alerts">
                    <i class="fa fa-file-archive"></i>
                </a>
            </li>
            <hr class="sidebar-divider">
            <li class="nav-item @if(request()->route()->getName() =='case_manager.index' ) active @endif">
                <a class="nav-link @if(request()->route()->getName() == 'case_manager.index') active @endif "
                    href="{{route('case_manager.index')}}" title="Case Manager">
                    <i class="fa fa-tasks"></i>
                </a>
            </li>
            <li class="nav-item @if(request()->route()->getName() =='head_office.contact' ) active @endif">
                <a class="nav-link @if(request()->route()->getName() == 'head_office.contact') active @endif "
                    href="{{route('head_office.contact')}}" title="Contacts">
                    <i class="fas fa-address-card"></i>
                </a>
            </li>
            <!-- <hr class="sidebar-divider">
         <li class="nav-item">
            <a class="nav-link" href="{{route('location.view_dispensing_incidents')}}" title="Dispensing Incidents">
                <i class="fas fa-fw fa-eraser"></i>
            </a>
        </li> -->

            <!-- <hr class="sidebar-divider">
        <li class="nav-item">
            <a class="nav-link" href="{{route('location.view_patient_safety_alerts')}}" title="Patient Safety Alerts">
                <i class="fas fa-exclamation-triangle"></i>
            </a>
        </li> -->


            <div class="sidebar-menu-bottom">

                <li class="nav-item dropdown-submenu">
                    <a class="nav-link" href="#" title="Settings" data-toggle="collapse" data-target="#settings"
                        aria-expanded="true" aria-controls="collapseSettings">
                        <i class="fas fa-cogs"></i>
                    </a>
                    <div id="settings" class="collapse">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header text-info ">Settings</h6>
                            <a class="collapse-item" onclick="loadActiveTab('#head_office_details');"
                                href="{{route('head_office.settings')}}#head_office_details">Head Office Details</a>
                            <a class="collapse-item " onclick="loadActiveTab('#password');"
                                href="{{route('head_office.settings')}}#password">
                                Password & Security
                            </a>
                            <a class="collapse-item " onclick="loadActiveTab('#patient_safety_alert_settings');"
                                href="{{route('head_office.settings')}}#patient_safety_alert_settings">
                                Patient Safety Alerts
                            </a>
                            <a class="collapse-item" onclick="loadActiveTab('#subscription');"
                                href="{{route('head_office.settings')}}#subscription">Subscription & Invoices</a>
                            <a class="collapse-item " onclick="loadActiveTab('#personalize_my_account');"
                                href="{{route('head_office.settings')}}#personalize_my_account">
                                Personalise My Account
                            </a>
                            <a class="collapse-item " onclick="loadActiveTab('#finance_department_detail');"
                                href="{{route('head_office.settings')}}#finance_department_detail">
                                Finance Department Detail
                            </a>
                            <a class="collapse-item " onclick="loadActiveTab('#root_cause_analysis');"
                                href="{{route('head_office.settings')}}#root_cause_analysis">
                                Root Cause Analysis
                            </a>
                            <a class="collapse-item " onclick="loadActiveTab('#finance_department_detail');"
                                href="{{route('head_office.be_spoke_form.index')}}">
                                Be Spoke Forms
                            </a>
                        </div>
                    </div>
                </li>


                <li class="nav-item">
                    <a class="nav-link" href="#" title="Help">
                        <i class="fas fa-question"></i>
                    </a>
                </li>
            </div>

            {{--
            <!-- Divider -->--}}
            {{--
            <hr class="sidebar-divider">--}}



            {{--
            <!-- Sidebar Toggler (Sidebar) -->--}}
            {{-- <div class="text-center d-none d-md-inline" style="display: none;">--}}
                {{-- <button class="rounded-circle border-0" id="sidebarToggle"></button>--}}
                {{-- </div>--}}

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content" class="mt-1">


                <!-- Begin Page Content -->

                @yield('content')

                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <!-- <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; {{ env('APP_NAME') . " " . \Carbon\Carbon::now()->year }} </span>
                </div>
            </div> -->
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

    <!-- Service Message Modal-->
    <div class="modal fade" id="ServiceMessageModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Service Message</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
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
    <script src="{{asset('bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('admin_assets/js/sb-admin-2.min.js')}}"></script>
    <script src="{{asset('js/alertify.min.js')}}"></script>
    
    
    <script src="{{asset('admin_assets/js/select2.min.js')}}"></script>
    @yield('scripts')
    <script src="{{asset('admin_assets/head-office-script.js')}}"></script>
    
    <script src="{{asset('admin_assets/js/view_case.js')}}"></script>
    <script>
        $('.select_group').select2();
    </script>

</body>

</html>