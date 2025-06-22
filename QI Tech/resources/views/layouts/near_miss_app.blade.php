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

    <link href="{{ asset('v2/fonts/LitteraText/stylesheet.css') }}" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('admin_assets/css/sb-admin-2.min.css')}}" rel="stylesheet">
    <link href="{{asset('bootstrap-datepicker/css/bootstrap-datepicker3.standalone.css')}}" rel="stylesheet">
    <link href="{{asset('admin_assets/css/style.css')}}" rel="stylesheet">
    
    @yield('styles')
    {{-- <link href="{{route('location.color_css')}}" rel="stylesheet"> --}}

</head>

<body id="page-top">
<nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow">
    <div class="heading-logo">
        {{-- <a href="{{route('location.dashboard')}}">
            <img src="{{$location->branding->logo}}" alt="Company Logo">
        </a> --}}
    </div>
</nav>
 <div id="content-wrapper" class="d-flex flex-column">
    <div id="content" class="">
    @yield('content')
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