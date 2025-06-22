@extends('layouts.admin.blocks.header')
@section("content")
<div class="container-flud">
    <div class="content">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div>
                    <img class="img-fluid" src="{{asset('/img/login-left.png')}}" alt="">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="login-content-wrape">
                    <div class="login-content border rounded-3">
                        <div class="content-logo text-center">
                            <img class="mb-4" src="{{asset('/img/logo.png')}}" alt="">
                            <h1>Welcome</h1>
                            <p>Please enter the email address and password</p>
                        </div>
                        <div class="login-content-group mt-4">
                            <form action="{{ route('login') }}" method="POST" >
                                @csrf
                                <div class="mb-3">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>   
                                <div class="mb-3 position-relative eye-holder">
                                    <input id="pass_log_id" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                                    <i toggle="#password-field" class="fontello icon-eye-off toggle-password"></i>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                                <div class="mb-3 checkbox-component-group">
                                    <div class="d-flex checkbox-content">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                    <div class="forgot-link">
                                    @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                                    </div>
                                </div>
                                <div class="mt-4 text-center">
                                    <button  type="submit" class="btn btn-primary login-btn">{{ __('Login') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection()
@extends('layouts.admin.blocks.footer')
 
