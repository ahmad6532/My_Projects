<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .email-container {
            background-color: #ffffff;
            padding: 20px;
            max-width: 600px;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .email-logo img {
            max-width: 150px;
        }
        .email-content {
            margin-top: 20px;
        }
        .email-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #72C4BA;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 16px;
        }
        .email-footer {
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Logo -->
        <div class="email-logo">
            <img src="{{ $logo }}" alt="Logo">
        </div>

        @if ($user_present == true)
            Hi {{ $user->first_name . ' ' . $user->surname }}
        @endif
        <!-- Email Content -->
        <div class="email-content">
            <p>A case has been shared with you. {{$user_present == false ? 'Please complete your registration to access it.' : 'Please login to view the case from your user account.'}}</p>
            
            <!-- Button with a link to the registration route -->
            @if ($user_present == false)
                <p style="margin-top:1rem;">Please click on the link to get started:</p>
            @endif

            @if ($user_present == true)
                <a href="{{route('login',['error' => 0, 'email' => $user->email])}}" class="email-button" style="background:#dbdbdb;color:black;">
                    Go to my User Account
                </a>
            @else
                <a href="{{ route('headOffice.shared_case_signup', ['email' => $user->email]) }}" class="email-button">
                    Complete Registration
                </a>
            @endif
        </div>

    </div>
</body>
</html>
