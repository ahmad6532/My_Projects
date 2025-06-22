<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Literra&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Literra', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            font-size: 24px;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
        }
        .logo {
            width: 100px;
            height: auto;
            max-width: 100%;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <div style="width: 100%; height: auto;">
        <img class="logo" src="{{ isset($head_office_user->headOffice->logo) ? $head_office_user->headOffice->logo : asset('/images/svg/logo_blue.png') }}" alt="Company Logo">
    </div>
    <p class="header">Hello {{ isset($user) ? $user->first_name : $head_office_user->user->first_name }}</p>
    <p>We are thrilled to welcome you as a {{ isset($request->head_office_position) ? $request->head_office_position : $head_office_user->position }} to {{ $head_office_user->headOffice->company_name }}.<br>Let's get started!</p>
        
    <a href="{{ isset($signin_link) ? $signin_link : url('/login') }}" style="display: inline-block; padding: 12px 24px; background-color: #2bafa5; color: #ffffff; text-decoration: none; font-weight: bold; border-radius: 5px; margin-top: 20px;">
        Sign In
    </a>
    
</body>
</html>