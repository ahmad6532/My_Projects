<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Near Miss</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            color: #9b9ec5;
            text-align: center; 
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="w-100 d-flex justify-content-center align-items-center" style="height: 100vh">
            <div class="p-4 rounded-1 gap-2 d-flex justify-content-center align-items-center flex-column"
                style="box-shadow: rgba(14, 30, 37, 0.12) 0px 2px 4px 0px, rgba(14, 30, 37, 0.32) 0px 2px 16px 0px;width:400px">
                <svg width="56" height="56" style="background:rgba(84,162,148,0.1);border-radius:50%; padding:10px" class="my-2 " viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 6L9 17L4 12" stroke="#54a294" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <p class="fw-semibold fs-5 m-0 p-0" style="color: #4a4e6d;">Near Miss submitted successfully!</p>
                <a href="{{route('location.user_login_view')}}"  class="link-dark fw-semibold">Go back</a>
                <a href="{{route('location.user_login_view')}}" class="link-primary fw-semibold">{{substr(route('location.user_login_view'),0,35)}}...</a>
            </div>



    </div>

</body>

</html>
