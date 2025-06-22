<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" />
    <title>LogIn</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row bg-dark d-flex justify-content-center align-items-center  min-vh-100 ">
            <div class="col-6 bg-white d-flex align-items-center justify-content-center flex-column  min-vh-100  ">
                <h1 class="mb-5">Login</h1>
                @if (session()->has('message'))
                    <span class="text-danger w-50 text-center">{{ session('message') }}</span>
                    {{-- @elseif (session('status'))
                    <span class="text-success">Your Password has been Changed</span> --}}
                @endif
                <form action="{{ route('login') }}" method="POST" class="col-8">
                    @csrf
                    <!-- Email input -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Email</label>
                        <input type="email" id="form2Example1" name="email" class="form-control" />
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password input -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example2">Password</label>
                        <input type="text" name="password" id="form2Example2" class="form-control" />
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block mt-5 mb-4 ">Sign in</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
