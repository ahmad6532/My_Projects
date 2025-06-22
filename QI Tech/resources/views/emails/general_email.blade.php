@if (isset($user))
<h6>Hello {{ $user }}</h6>
@else
<h2>{{ $heading }}</h2>
@endif
<p>{{ $msg }}</p>
<br />
@isset($user)
    <p>We look forward to speaking with you soon!</p>
    <br>
    <p>Regards</p>
    <br>
@endisset

<br>
<div style="display: flex; justify-content: center;">
    <a href=" " style="padding: 10px 20px; background-color: green; color: white; text-decoration: none; border-radius: 5px; border: none;">Create account</a>
</div>

@isset($logo)
    <img src=" {{ $head_office->logo }} " alt="">
@endisset
<p>
    This is an automated email. Please don't reply.
</p>
<p>
    Copyright &copy; {{ \Carbon\Carbon::now()->year }} {{ env('APP_NAME') }} 
</p>