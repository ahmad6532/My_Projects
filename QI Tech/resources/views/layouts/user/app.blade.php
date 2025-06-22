<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('fonts/LitteraText/stylesheet.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" href="{{asset('css/my-profile.css')}}">
    <link rel="stylesheet" href="{{asset('css/colors.css')}}">
    <title>QI Tech 2.0</title>

    @yield('styles')
</head>

<body>

    <div class="sub-header">
    </div>

    @yield('content')

    <img class="cloud-img1" src="{{asset('images/cloud1.jpg')}}" />
    <img class="cloud-img2" src="{{asset('images/cloud2.jpg')}}" />

    <!-- Font awesome -->
    <script src="https://kit.fontawesome.com/350d033a5a.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
    @yield('scripts')
</body>

</html>