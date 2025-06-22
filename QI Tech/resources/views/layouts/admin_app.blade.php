<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="QI-Tech System">
    <meta name="author" content="Khuram Nawaz Khayam, Ahtsham Farooq">

    <title>@yield('title') :: {{ env('APP_NAME') }}</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('admin_assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700&display=swap" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('admin_assets/css/sb-admin-2.min.css')}}" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="{{asset('admin_assets/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin_assets/css/style.css')}}" rel="stylesheet">

    <link href="{{asset('admin_assets/css/color_style.css')}}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @yield('styles')


</head>

<body id="page-top">

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>


    <div class="heading-logo">
        <a href="{{route('admin.dashboard')}}">
            <img src="{{asset('images/svg/black.svg')}}" alt="Qi Tech">
        </a>
    </div>
        <p class="address font-weight-bold ">Admin: {{ Auth::guard('admin')->user()->name }}</p>
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

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter">3+</span>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header dropdown-header-gradient-primary">
                    Alerts Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">December 12, 2019</div>
                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
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
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
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

                <a class="dropdown-item" href="{{route('admin.edit_my_details')}}">
                    <i class="fas fa-user-edit fa-sm fa-fw mr-2 text-gray-400"></i>
                    Edit My Details
                </a>

                <a class="dropdown-item" href="{{route('admin.update_password')}}">
                    <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i>
                    Change Password
                </a>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="{{route("admin.logout")}}" >
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Sign Out
                </a>

            </div>
        </li>



    </ul>

</nav>
<!-- End of Topbar -->
<!-- Page Wrapper -->
<div id="wrapper">



    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion sidebar-menu-ul" id="accordionSidebar">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="{{route('admin.dashboard')}}" title="Dashboard">
                <i class="fas fa-fw fa-tachometer-alt"></i> Dashboard
            </a>
        </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <li class="nav-item">
        <a class="nav-link" href="{{route('locations.location.index')}}" title="Location Accounts">
            <i class="fas fa-fw fa-th-list"></i> Location Accounts
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">
    <li class="nav-item">
            <a class="nav-link" href="{{route('users.user.index')}}" title="User Accounts">
                <i class="fas fa-fw fa-user-alt"></i> User Accounts
            </a>
        </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <li class="nav-item">
            <a class="nav-link" href="{{route('head_offices.head_office.index')}}" title="Head Offices">
                <i class="fas fa-fw fa-th-list"></i> Head Office list
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">
    <li class="nav-item">
            <a class="nav-link" href="{{route('national_alerts.national_alert.index')}}" title="National Alerts">
                <i class="fas fa-fw fa-th-list"></i> National Alerts
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <li class="nav-item">
            <a class="nav-link" href="{{route('database.index')}}" title="Databases">
                <i class="fas fa-fw fa-database"></i> Databases
            </a>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider">

        <li class="nav-item">
            <a class="nav-link" href="{{route('service_messages.service_message.index')}}" title="Service Messages">
                <i class="fas fa-fw fa-list"></i> Service Messages
            </a>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider">

{{--    <li class="nav-item">--}}
{{--            <a class="nav-link" href="{{route('be_spoke_forms.be_spoke_form.index')}}" title="Databases">--}}
{{--                <i class="fas fa-fw fa-list-alt"></i> Be Spoke Forms--}}
{{--            </a>--}}
{{--        </li>--}}

        <!-- Divider -->
{{--        <hr class="sidebar-divider">--}}


    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content" class="mt-5">


            <!-- Begin Page Content -->

        @yield('content')

        <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; {{ env('APP_NAME') . " " . \Carbon\Carbon::now()->year }} </span>
                </div>
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

 <!-- Page level plugins -->
 <script src="{{asset('admin_assets/vendor/datatables/jquery.dataTables.min.js')}}"></script>
 <script src="{{asset('admin_assets/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
 <script src="{{asset('admin_assets/vendor/datatables/dataTables.buttons.min.js')}}"></script>
 <script src="{{asset('admin_assets/vendor/datatables/buttons.html5.min.js')}}"></script>
 <script src="{{asset('admin_assets/js/demo/datatables-demo.js')}}"></script>
 <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.bootstrap4.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@yield('scripts')
<script src="{{asset('admin_assets/js/admin-scripts.js')}}"></script>
</body>

</html>