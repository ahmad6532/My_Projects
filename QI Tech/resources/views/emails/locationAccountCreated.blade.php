<!DOCTYPE html>
<html>
<head>

<style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            justify-content: center;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 150px;
        }
        .button {
            display: inline-block;
            padding: 12px 20px;
            background-color: #2bafa5;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #249b91;
        }
    </style>
</head>
<body>
    <div class="email-container">
       
        <p>Hello,</p>
        <p>Your account is set up and ready to go!</p>
        <p><strong>Username:</strong> {{ $username }}</p>
        <p><strong>Password:</strong> {{ $password }}</p>
        <p>Please click the button below to log in to your account and change your password.</p>

        <a href="{{ route('login') }}" class="button">Login now</a>
        <p>Thank you,<br>{{ config('app.name') }} Team</p>
    </div>
</body>
</html>
