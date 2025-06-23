<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" />
    <title>Login</title>
</head>

<body class="auth-body">
    <div class=" d-flex w-100 justify-content-evenly">
        <div class=" w-50 d-flex justify-content-center align-items-center ">
            <!-- <img src="/SS.PNG" alt="logo" class="bg-logo"> -->
        </div>

        <div class="w-50 d-flex justify-content-center align-items-center ">
            <div class="col-10 d-flex auth-inner-div align-items-center justify-content-center flex-column  min-vh-100">
                <div class="col-10">
                    <span class="auth-heading">Sign In</span><br>
                    <span class="auth-detail">Welcome to Content Craft</span><br>
                </div>

                <form action="{{ route('login') }}" method="POST" id="loginForm" class="col-10 mt-4">

                    <div class="form-outline mb-3">
                        <label class="form-label" for="form2Example1">Email</label>
                        <input type="email" id="form2Example1" required name="email" class="form-control auth-form" placeholder="johndoe@server.com" />
                        <i class="fa-regular fa-envelope"></i>

                    </div>

                    <div class="form-outline mb-3">
                        <label class="form-label" for="form2Example2">Password</label>
                        <input type="text" name="password" required id="form2Example2" class="form-control auth-form" placeholder="********" />
                        <i class="fa-solid fa-lock"></i>

                    </div>
                    <button type="submit" class="btn btn-success auth-submit-btn btn-block" id="loginBtn">LOGIN</button>
                </form>
                <div class="col-10">
                    <a href="{{route('register')}}" class=" d-flex justify-content-end text-decoration-none text-white  mt-2">Register</a>
                </div>
            </div>
        </div>

    </div>



</body>

</html>