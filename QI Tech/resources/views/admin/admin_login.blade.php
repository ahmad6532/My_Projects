<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('fonts/icomoon/style.css')}}">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/alertify.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/loader.css')}}?v=1">
    <link rel="stylesheet" href="{{asset('/css/colors.css')}}?v=1">
    <link rel="stylesheet" href="{{asset('/css/custom.css')}}?v=1">
    <link href="{{asset('admin_assets/css/style.css')}}" rel="stylesheet">

    <title class="ng-binding">Admin :: {{ env('APP_NAME') }}</title>
</head>

<body>

<div class="loader-container" style="display: none;">
    <div class="loader"></div>
</div>

<div class="app-container">
    <div class="app-content">
           

            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 left-pane">
                        <img src="{{ asset('images/svg/QI Tech white.svg') }}" class="logo">
                        <h2 class="left-heading">Admin Account</h2>

                        <div class="container tiles-container">
                            <div class="row">

                                <!-- Tile -->
                                <div class="col-lg-3"></div>
                                <div class="col-lg-6">
                                    <div class="card tile-bg">
                                        <div class="img-tile-section user-img1">

                                        </div>
                                        <h2 class="tile-heading">Login to Admin Account</h2>
                                        
                                        
@if ($errors->any())

<div class="red">
{{ $errors->first() }}
</div>

@endif
                                        
                                        <form action="{{ route('admin.postlogin') }}" method="post">
                                                @csrf
                                               
                                                <div class="form-group ">
                                                    <label for="email" class="text-dark">Email</label>
                                                    <input type="email" id="email" name="email" placeholder="Email" class="form-control" value="{{old('email')}}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password" class="text-dark">Password</label>
                                                    <input type="password" id="password" name="password" placeholder="Password" class="form-control" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Login</button>

                                        </form>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
</div>



<script src="{{asset('/js/jquery-3.3.1.min.js')}}"></script>
<script src="{{asset('/js/bootstrap.min.js')}}"></script>
<script src="{{asset('/js/alertify.min.js')}}"></script>
<script src="{{asset('/js/angular.min.js')}}"></script>
<script src="{{asset('/js/angular-route.min.js')}}"></script>


</body></html>
