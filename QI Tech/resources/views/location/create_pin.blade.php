{{-- style for tooltip --}}
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
        font-size: 24px;
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

    <title>Location :: {{env('APP_NAME')}}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Custom fonts for this template-->
    <link href="{{asset('admin_assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700&display=swap" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('admin_assets/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <link href="{{asset('admin_assets/css/style.css')}}" rel="stylesheet">

</head>

<body id="page-top">


    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow" style="text-align: right; margin-right:40px;margin-top:15px;">
            <a style="color: #2cafa4;font-size:18px;" class="nav-link " href="{{route('location.user_login_view')}}">
                <img class="logout-img" src="{{ asset('images/arrow-narrow-left.svg') }}" alt="logout_icon">
                Back
            </a>

        </li>
    </ul>
<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column mx-5">

        <!-- Main Content -->
        <div id="content">

            <!-- Begin Page Content -->
            <div class="container-fluid custom-background " style="min-height: 80vh;">

                <div class=" d-flex align-items-center">
                    <a href="/"><img src="{{ $location->branding->logo }}" class="img-fluid " width="130"></a>
                    <p class="font-weight-bold text-black h4 mb-0 mt-3 pl-4">{{ $location->trading_name }}</p>
                </div>

                <div class="row mt-2">
                    <div class="col-md-8 box-icon-collection-border text-center">

                            <div class=" w-50 p-3 border rounded shadow mx-auto" style="margin-block: 4rem;">
                                <h3 class="h5 text-info font-weight-bold">Create a Pin</h3>
                                @include('layouts.error')

                                <form action="{{ route('location.update_pin') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="pin">New four digit Pin</label>
                                        <input type="text" id="pin" name="pin" placeholder="New Pin" class="form-control" minlength="4" maxlength="4" onkeypress='return (event.charCode >= 48 && event.charCode <= 57)' required>
                                    </div>
                                    <div class="form-group">
                                        <label for="c_pin">Confirm Pin</label>
                                        <input type="text" id="c_pin" name="new_pin" placeholder="New Pin" class="form-control" minlength="4" maxlength="4" onkeypress='return (event.charCode >= 48 && event.charCode <= 57)' required>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-info" name="submit" type="submit">Update Pin</button>
                                    </div>

                                </form>
                            </div>

                    </div>
                    <div class="col-md-4 text-center">

                        <div class="report-head">
                            <h3 class="h5  font-weight-bold">Quick Report</h3>

                            {{-- tooltip for question mark --}}
                            <span class="tooltip">
                                <i class="fa-regular fa-circle-question"></i>
                                <span class="tooltiptext">Quick report forms can be completed without signing in as a user</span>
                            </span>
                        </div>
                        @php
                        $user = Auth::guard('location')->user();
                        $forms = $user->group_forms();
                        @endphp
                        @if(count($forms))
                        @foreach($forms->groupBy('category.name') as $category => $forms2)
                            <div class="category-Wrapper mt-3">
                                <h4>{{$category}}</h4>
                                    @foreach($forms2 as $form)
                                        <a class="category-btn"  href="{{route('be_spoke_forms.be_spoke_form.preview',$form->id)}}">
                                            {{$form->name}}
                                        </a>
                                    @endforeach
                            </div>
                        @endforeach
                        @endif
                        
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


<!-- Logout Modal-->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal"
     aria-hidden="true">


     <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="change_password_ModalLabel">Login to your user account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form action="{{route('postlogin')}}" method="post">
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