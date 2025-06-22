<div style="font-family: 'Poppins', Arial, sans-serif; padding: 20px; background-color: #f4f4f9; border: 1px solid #d1d1d1; border-radius: 10px; width: 80%; margin: 0 auto;">
    <img src="{{ asset('images/svg/logo_blue.png') }} " alt="QI-TECH LOGO" style="display: block; margin: 0 auto; padding-bottom: 15px; max-width: 100px;">

    <p style="text-align: left; font-size: 16px; color: #333; line-height: 1.6;">
        This mail is to inform you that your <br> <strong>{{ $field }}</strong> has been changed.
    </p>

    <p style="text-align: left; font-size: 16px; color: #555; line-height: 1.6;">
        Your {{ strtolower($field) }} has changed from <span>{{ $oldValue }} </span>
            to <span style="font-weight: bold; color: rgb(80, 170, 156);">{{ $newValue }}</span>
    </p>

      {{-- <p style="text-align: left; font-size: 16px; color: #555; line-height: 1.6;">
        <strong>New {{ ucfirst($field) }}:</strong> <span style="font-weight: bold; color: rgb(80, 170, 156);">{{ $newValue }}</span>
    </p> --}}

    <p style="text-align: left; font-size: 16px; color: #777; line-height: 1.6;">
        Your password has not changed.<br>
        @if ($field == 'username')
        Please use your new username for future logins.
        @endif
    </p>

    <hr style="margin: 20px 0; border: none; border-top: 1px solid #ccc;">

    <p style="text-align: center; font-size: 14px; color: #777;">
        &copy; {{ \Carbon\Carbon::now()->year }} {{ env('APP_NAME') }}. All rights reserved.
    </p>
</div>
