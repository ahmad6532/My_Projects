<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('fonts/icomoon/style.css')}}">
    <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/loader.css?v=1')}}">
    <link rel="stylesheet" href="{{asset('css/colors.css?v=1')}}">
    <link rel="stylesheet" href="{{asset('css/custom.css?v=1')}}">
    <link rel="stylesheet" href="{{asset('css/custom.css?v=1')}}">
    <link rel="stylesheet" href="{{asset('Slim-Password-Strength-Meter-Plugin-For-jQuery/src/password.css')}}">

    <title>Reset your Password</title>

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
        border-radius: 0.5rem;
        -webkit-transition: border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
        transition: border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
        -o-transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;

        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
        font-size: .875rem;
    }
        .pass-wrapper{
            margin-top: 0.3rem;
        }
        .pass-graybar,.pass-colorbar{
            height: 4px;
            border-radius: 10px;
        }
        .emojione{
            width: 15px;
            font-size: 15px;
        }
        .pass-text{
            font-size: 12px;
    color: #999;
    font-weight: 500;
        }
        .clouds-wrapper{
        background: url('{{asset("images/login-cloud2.png")}}') no-repeat;
        background-size: 20%;
        background-position: bottom left;
        position: relative;
    }
    .clouds-wrapper::before{
        position: absolute;
        content: '';
        width: 100%;
        height: 100%;
        background: url('{{asset("images/login-cloud1.png")}}') no-repeat;
        background-size: 45%;
        background-position: bottom right;
    }
    </style>
</head>

<body>


<div class="app-container">

    <div class="app-content">
        <div ng-include="'views/back.html'"></div>

        <div class="login-wrapper container-fluid">
            <div class="row shadow-box-wrapper clouds-wrapper">
                <div class="col-lg-12 col-sm-12">
                <div class="d-flex align-items-center justify-content-between w-100 login-nav" style="padding-right: 0.6rem;">
                    <a href="/"><img  src='{{$logo}}' class="logo-login ml-4"></a>
                </div>
                <div class="">
                    <div class="mx-auto">
                        <div class="center">
                            <h2 class="right-login-heading primary mt-0" style="color: #6ebed5;">Reset password</h2>
                        </div>
                        @include('layouts.error')
                        <form  action="{{route('reset_password.update',[$type,$token])}}" class="close-form ml-auto" style="margin-top: 4rem;" method="post">
                            @csrf
                            <input type="hidden" name="type" value="{{$type}}">
                            <input type="hidden" name="token" value="{{$token}}">
                            <div class="form-field">
                                <label style="color: #999; font-size:14px;font-weight:500;">Password</label>
                                <input class="form-control customFormSelect" id="main-pass" type="password" placeholder="Enter Password" name="password" required>
                            </div>
                            <div class="form-field">
                                <label style="color: #999; font-size:14px;font-weight:500;">Confirm Password</label>
                                <input id="c_pass" class="form-control customFormSelect" type="password" placeholder="Confirm Password" name="c_password" required>
                            </div>
                            <div class="form-field">
                                <input type="submit" value="submit" id="submit" class="btn btn-info btn-block" style="{{isset($ho->sign_button_color) ? 'background: ' . $ho->sign_button_color : ''}};{{isset($ho->sign_btn_text_color) ? 'color: ' . $ho->sign_btn_text_color : ''}}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>



<script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
{{-- <script src="js/bootstrap.min.js"></script>
<script src="js/alertify.min.js"></script>
<script src="js/angular.min.js"></script>
<script src="js/angular-route.min.js"></script> --}}
<script src="{{asset('Slim-Password-Strength-Meter-Plugin-For-jQuery/src/password.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/svg4everybody/2.1.9/svg4everybody.min.js" integrity="sha512-7XdqOa4bqU/hqbUuS8epr0ECSj9laLWnualyqow2TQ3RJ+TUoac0yKNjEtgg3gHGEbM+Jbwgo6K/+Y/0mJHOfA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/emojione/2.2.7/lib/js/emojione.min.js"></script>
<script src="{{ asset('/js/alertify.min.js') }}"></script>
<script>
    $(document).ready(function(){
        emojione.imageType = 'svg';
        emojione.sprints = true;
        emojione.imagePathSVGSprites = 'https://github.com/Ranks/emojione/raw/master/assets/sprites/emojione.sprites.svg';
        $('#main-pass').password({
            steps: {
                    // Easily change the steps' expected score here
                    0: '<span class="emojione">😣</span> Weak. Really Inscure',
                    33: '<span class="emojione">😑</span> So-so. Try combining letters & numbers',
                    67: '<span class="emojione">😉</span> Almost. Must contain 2 special symbols',
                    94: '<span class="emojione">😎</span> Awesome! You have a secure password',
                },
            minimumLength: 8,
            enterPass: emojione.unicodeToImage('Type your password'),
            shortPass: emojione.unicodeToImage('😣 Weak. Must contain at least 8 characters'),
            badPass: emojione.unicodeToImage('Still needs improvement! 😷'),
            goodPass: emojione.unicodeToImage('Yeah! That\'s better! 👍'),
            strongPass: emojione.unicodeToImage('Yup, you made it 🙃'),
        }).on('password.score', function (e, score) {
            console.log('Current score is %d', score)
                if (score > 75) {
                  $('#submit').removeAttr('disabled');
                } else {
                  $('#submit').attr('disabled', true);
                }
              });
        
    })

    $('.close-form').on('submit',function(event){
        const validPattern = /^[A-Za-z0-9!@#$%^&*()_+[\]{};:,.<>?\\|`~\-=/]*$/;
        if(validPattern.test($('#main-pass').val()) == false){
            event.preventDefault();
            alertify.alert('Only Letters | Numbers | Special Symbols are allowed').set({
                title: "Invalid Password!"
            });
        }
        else if($('#main-pass').val() !== $('#c_pass').val()){
            console.log($('#main-pass').val(),$('#c_pass').val())
            alertify.alert("Passwords don't match !").set({
                title: "Password Error!"
            });
            event.preventDefault();
        }
    })
</script>

<!-- App Scripts -->
{{-- <script src="js/angular_app.js?v=1"></script>
<script src="js/route.js?v=1"></script>

<!-- Services -->
<script src="js/services/AppService.js?v=1"></script>
<script src="js/services/UIService.js?v=1"></script>
<script src="js/services/ApiService.js?v=1"></script> --}}

<!-- Controllers -->
{{-- <script src="js/controllers/AppController.js?v=1"></script>
<script src="js/controllers/Auth/LoginController.js?v=1"></script>
<script src="js/controllers/Auth/MainSignupController.js?v=1"></script>
<script src="js/controllers/Auth/LocationSignupController.js?v=1"></script>
<script src="js/controllers/Auth/HeadOfficeSignupController.js?v=1"></script>
<script src="js/controllers/Auth/UserSignupController.js?v=1"></script> --}}


</body>

</html>
