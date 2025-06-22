@php
    $location = Auth::guard('location')->user() ?? $location;
    $forms = $location->group_forms();
    $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
    $userMain = Auth::guard('web')->user() ?? Auth::guard('user')->user();
    $name = '';
    if (isset($user)) {
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $name =
            isset($user->name) && $user->name
                ? implode(
                    '',
                    array_map(function ($word) {
                        return strtoupper($word[0]);
                    }, explode(' ', $user->name)),
                )
                : '';
    }
@endphp
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        function checkPasswordMatch() {
            var password = document.querySelector('input[name="new_password"]').value;
            var confirmPassword = document.querySelector('input[name="new_password_confirmation"]').value;
            var message = document.getElementById('passwordMatchMessage');
            
            if (password === confirmPassword) {
                message.textContent = 'Passwords match!';
                message.className = 'alert alert-success';
            } else {
                message.textContent = 'Passwords do not match!';
                message.className = 'alert alert-danger';
            }
        }

        function passwordStrength() {
            var password = document.querySelector('input[name="new_password"]').value;
            var strength = document.getElementById('passwordStrength');
            
            if (password.length < 6) {
                strength.textContent = 'Weak';
                strength.style.color = 'red';
            } else if (password.length < 10) {
                strength.textContent = 'Medium';
                strength.style.color = 'orange';
            } else {
                strength.textContent = 'Strong';
                strength.style.color = 'green';
            }
        }

        function togglePasswordVisibility(inputId) {
            var input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .container {
            width: 60%;
            background-color: white; 
            border: 2px solid #ccc; 
            border-radius: 15px; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
            padding: 30px; 
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 20px;
        }

        .logo img {
            height: 80px;
            width: auto;
        }

        .heading-line {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        .form-container h3 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .form-container h4 {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }

        .form-control {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #73c3bc;
            box-shadow: 0 0 8px rgba(115, 195, 188, 0.3);
        }

        .text-danger {
            margin-top: 5px;
            font-size: 0.9em;
        }

        .btn-primary {
            background-color: #73c3bc;
            border: none;
            color: black;
            font-weight: bold;
            border-radius: 20px;
            padding: 10px 20px;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #5ba6a0;
        }

        .btn-primary:focus {
            outline: none;
            box-shadow: none;
        }

        .input-group-text {
            cursor: pointer;
            background-color: #f9f9f9;
            border-radius: 0 8px 8px 0;
        }

        .input-group {
            overflow: hidden;
            border-radius: 8px;
        }

        #passwordStrength, #passwordMatchMessage {
            font-size: 0.9em;
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <a href="{{ route('location.dashboard') }}"><img
                    src="{{ isset($location->organization_setting_assignment->organization_setting) && $location->organization_setting_assignment->organization_setting->setting_logo() ? $location->organization_setting_assignment->organization_setting->setting_logo() : asset('images/svg/logo_blue.png') }}"></a>
            </div>
            <div class="heading-line">Welcome, {{ $location->trading_name }}</div>
        </div>

        <h3>You logged in with your one-time password</h3>
        <h4>This is your first time logging into your account. Please change your password to continue.</h4>
        
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('locations.changePassword') }}" class="form-container">
            @csrf
            <div class="form-group">
                <label for="new_password">New Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" name="new_password" required oninput="passwordStrength()" id="new_password">
                    <div class="input-group-append">
                        <span class="input-group-text" onclick="togglePasswordVisibility('new_password')">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>
                <div id="passwordStrength" style="margin-top: 5px;"></div>
                @error('new_password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="form-group">
                <label for="new_password_confirmation">Confirm New Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" name="new_password_confirmation" required oninput="checkPasswordMatch()" id="new_password_confirmation">
                    <div class="input-group-append">
                        <span class="input-group-text" onclick="togglePasswordVisibility('new_password_confirmation')">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>
                <div id="passwordMatchMessage" style="margin-top: 5px;"></div>
                @error('new_password_confirmation')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Change Password</button>
        </form>
    </div>
</body>
</html>
