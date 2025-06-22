
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background-color: #f6f6f6; padding: 20px; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .logo img { width: 150px; }
        h1 { font-size: 28px; color: #333333; }
        p { font-size: 16px; color: #666666; }
        .btn { display: inline-block; background-color: #06b388; color: #ffffff; padding: 12px 24px; border-radius: 5px; text-decoration: none; font-size: 16px; margin-top: 20px; }
        .footer { font-size: 12px; color: #888888; margin-top: 20px; line-height: 1.5; }
    </style>
    <div class="email-container">
        <div class="logo">
            <img src="{{ $logo }}" alt="Head Office Logo">
        </div>

        <h1 style="padding-top: 15px">Hello!</h1>
        <p>{{ $head_office_name }} has added you as a {{ $request->head_office_position }}.</p>
        <p>To access the account, you'll need to create a user account first.</p>

        <a href="{{ route('create_head_office_user', $token) }}" class="btn">Create Account</a>

        <p class="footer">
            {{-- This is an automated email. This inbox is not monitored.<br> --}}
            Copyright &copy; 2024 {{ env("APP_NAME") }}, All rights reserved.
        </p>
    </div>