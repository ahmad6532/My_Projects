
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Head Office :: {{env('APP_NAME')}}</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('admin_assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700&display=swap" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('admin_assets/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <link href="{{asset('admin_assets/css/style.css')}}" rel="stylesheet">

</head>

<body id="page-top" class="custom-background-color">

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow" style="text-align: right; margin-right:20px;">
            <a class="nav-link text-info font-weight-bold" href="{{route("head_office.logout")}}" >
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Sign Out
            </a>

        </li>
    </ul>

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Begin Page Content -->
            <div class="container-fluid bg-white custom-background ">

                <div class="text-center pt-5">
                    <div class="pb-2 text-gray-800">
                        <img src="{{ asset('images/tl.png') }}" class="img-fluid img-thumbnail" width="150">
                    </div>
                    <p class="font-weight-bold">{{ $ho->company_name }}, {{ $ho->address}}, 
                        </p>
                </div>

                <div class="row m-5">
                    <div class="col-md-8 box-icon-collection-border text-center">

                      
                        @include('layouts.error')

                    

                        <button data-toggle="modal" data-target="#loginModal" class="btn btn-info font-weight-bold">Login with Admin Email Address</button>
                       
                    </div>
                    <div class="col-md-4 text-center">

                        <h3 class="h5 text-info font-weight-bold">Quick Record</h3>
                        <button class="d-inline-block btn btn-outline-info">-</button>
                        <button class="d-inline-block btn btn-outline-info">-</button>
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


<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal"
     aria-hidden="true">


     <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="change_password_ModalLabel">Login to admin account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form action="{{route('head_office.post_admin_login')}}" method="post">
            <div class="modal-body">
                    @csrf
                    <input type="hidden" name="type" value="1">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Password" class="form-control" required>
                    </div>
            

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-info">Login</button>
            </div>
                </form>
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

</body>

</html>