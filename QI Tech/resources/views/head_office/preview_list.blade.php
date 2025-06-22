
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

        <style>
        

{{--        @if($location->branding->has_image)--}}
{{--        html{--}}
{{--            background-image: url("{{$location->branding->bg}}");--}}
{{--            background-repeat: no-repeat;--}}
{{--            background-size: cover;--}}
{{--            background-position: top;--}}
{{--        }--}}

{{--       @endif--}}
    </style>



</head>

<body id="page-top" class="custom-background-color">

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow" style="text-align: right; margin-right:20px;">
            <a class="nav-link text-info font-weight-bold" href="{{route("user.logout",['_token' => csrf_token()])}}" >
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
            <div class="container-fluid custom-background ">
                <div class="row m-5">
                    <div class="col-md-12 text-center">

                        <h3 class="h5 text-info font-weight-bold mb-3">Please Select Head Office from below</h3>

{{--                        <div class="input-group mb-3">--}}
{{--                            <div class="input-group-prepend">--}}
{{--                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>--}}
{{--                            </div>--}}
{{--                            <input type="text" class="form-control" placeholder="Search" aria-label="search" aria-describedby="basic-addon1">--}}
{{--                        </div>--}}
@include('layouts.error')
                        <div class="row text-dark no-link mt-3">

                            @foreach ($hos as $ho)
                                <div class="col-md-2">
                                <a href="{{route('head_office.select_head_office',['head_office_id' => $ho->head_office_id,'_token' => csrf_token()])}}">
                                    <div class="card card-hover text-info">
                                        <div class="card-body">
                                            <h1 class="font-weight-bold">HO</h1>
                                            <p class="user-card-name">{{ $ho->head_office->company_name }}</p>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            @endforeach
                            
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

<!-- Bootstrap core JavaScript-->
<script src="{{asset('admin_assets/vendor/jquery/jquery.min.js')}}"></script>
<script src="{{asset('admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

<!-- Core plugin JavaScript-->
<script src="{{asset('admin_assets/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

<!-- Custom scripts for all pages-->
<script src="{{asset('admin_assets/js/sb-admin-2.min.js')}}"></script>

</body>

</html>