
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title> {{env('APP_NAME')}}</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('admin_assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700&display=swap" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('admin_assets/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <link href="{{asset('admin_assets/css/style.css')}}" rel="stylesheet">

    <style>
        .customFormSelect {
            display: block;
            width: 100%;
            height: calc(1.5em + 0.75rem + 2px);
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            -webkit-transition: border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
            transition: border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
            -o-transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
    
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
            font-size: .875rem;
        }
    </style>

</head>

<body id="page-top" class="custom-background-color " style="display:grid; place-items:center; min-height:100vh;background:#F8F9FC;" >



<!-- Page Wrapper -->
<div id="wrapper" >

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column" >

        <!-- Main Content -->
        <div id="content" >

            <!-- Begin Page Content -->
            <div class="mod">
                <div>
                    <h5 class="modal-title" id="change_password_ModalLabel" style="line-height:1.7rem;font-size: x-large; color:#222;font-weight: bolder;max-width:500px;">Ensuring Email
                        Access for Both Company and Location Accounts!</h5>
                    <h5 class="modal-title" id="change_password_ModalLabel" style="font-size: small;color: #888;">Please
                        specify which account you want to login?</h5>
                </div>
                
            </div>
            <form id="login-form2" action="/postlogin" method="post">
                <div class="modal-body">
                    @csrf

                    @isset($token)
                    <input type="text" name="both_token" value="{{$token}}" hidden>
                    @endisset
                    <input type="text" name="from_both" value="false" hidden>
                    <div class="">
                        <label for="email" style="background: transparent;">Username/Email</label>
                        <input type="text" id="email2" name="email" placeholder="Email" class="form-control" required>
                        @isset($msg)
                    <p style="color: rgb(170, 57, 57);font-size:small;">{{$msg}}</p>
                    @endisset
                    </div>
                    <div class="mt-3">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Password"
                            class="form-control" required>
                    </div>
                    <div class="mt-3">
                        <label for="select">Select Account:</label>
                        <select name="type" id="ltype" id="select" class="customFormSelect "
                            aria-label="Default select example">
                            <option value="0" selected>Location</option>
                            <option value="2">Company</option>
                        </select>
                    </div>
            
                </div>
                <div class="modal-footer">
                    <a href="app.html#!/login" class="btn btn-secondary"  >Cancel</a>
                    <button  type="submit" class="btn btn-info">Login</button>
                </div>
            </form>
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

