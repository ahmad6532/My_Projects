<h6>Hi {{$user}}!</h6>
<br>
<p>Your company account for {{$company}} has been successfully set up and you're ready to go!</p>

{{-- @if(isset($password))
<p>Your password is <b> {{$password}} </b> </p>
@endif --}}
<p>Please use the User credentials to login!</p>
<br>
<style>
    .btn{
        background: #2BAFA4;
    color: white;
    padding: 10px 20px;
    border-radius: 4px;
    transition: 0.3s ease-in-out;
    }
    .btn:hover{
        background: #46ddd1;
        transition: 0.3s ease-in-out;
    }
</style>
<a href="{{ route('login') }}" class="btn">Login</a>
<br>
<br>
<p>Remember, we're always here to help, whether it's a technical issue, if you need some pointers on getting started, or how to optimise your workflow.</p>
<br>
<br>
<p>The QI-Tech team</p>