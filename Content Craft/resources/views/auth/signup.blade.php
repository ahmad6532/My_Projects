<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    {{-- Custome CSS  --}}
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" />
    <title>Complete Profile</title>
</head>

<body class="auth-body">
    <div class=" d-flex w-100 justify-content-evenly" style="height: 100vh">
        <div class=" logo-div d-flex justify-content-center align-items-center ">
            <img src="/images/Group 44710@2x.PNG" alt="logo" class="bg-logo">
        </div>

        <div class="signup-form-div d-flex justify-content-center align-items-center "
            style="height: 100%;overflow-y:scroll">
            <div
                class="signup-inner-div d-flex auth-inner-div align-items-center justify-content-center flex-column  min-vh-100 ">
                <div class="signup-sub-div">
                    <div class="signup-heading-div">
                        <span class="auth-heading">Complete Your Profile</span><br>
                        <span class="auth-detail">Welcome to the Tennis Fights Admin Panel. Kindly complete the profile
                            before further proceeding.</span><br>


                    </div>
                    <form action="{{ route('register') }}" id="signUpForm" method="POST" class="w-100 mt-1"
                        enctype="multipart/form-data">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-outline mb-5 signup-input-img-div">
                            <img src="/images/man.png" id="userImage" alt="Upload Icon">
                            <input required type="file" id="inputImage" name="avatar">
                        </div>
                        <div class="w-100 d-flex ">
                            <div class="w-50">
                                <div class="form-outline mb-3 signup-input-div">
                                    <label class="form-label">First Name</label>
                                    <input required type="text" name="firstName" class="form-control auth-form "
                                        placeholder="john" />
                                    <i class="fa-regular fa-user"></i>
                                </div>
                                <div class="form-outline mb-3 signup-input-div">
                                    <label class="form-label">Email</label>
                                    <input required type="email" name="email" class="form-control auth-form "
                                        placeholder="johndoe@gmail.com" />
                                    <i class="fa-regular fa-envelope"></i>
                                </div>
                            </div>
                            <div class="w-50">
                                <div class="form-outline mb-3 signup-input-div">
                                    <label class="form-label">Last Name</label>
                                    <input required type="text" name="lastName" class="form-control auth-form "
                                        placeholder="Doe" />
                                    <i class="fa-regular fa-user"></i>
                                </div>

                                <div class="form-outline mb-3 signup-input-div">
                                    <label class="form-label">Gender</label>
                                    <div class="d-flex justify-content-between  w-100">
                                        <span class="form-control auth-form gender ">
                                            <input required type="radio" value="Male" name="gender"
                                                class=" p-5 " />
                                            <label class="form-label">Male</label>
                                        </span>
                                        <span class="form-control auth-form gender ">
                                            <input required type="radio" value="Female" name="gender"
                                                class=" p-5 " />
                                            <label class="form-label">Female</label>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-100">
                            <div class="form-outline mb-3 signup-input-div single-input-div">
                                <label class="form-label">Address</label>
                                <input required type="text" name="address"
                                    class="form-control auth-form single-input"
                                    placeholder="7 Nicols, St Brlklyn NY1122" />
                            </div>
                        </div>
                        <div class="w-100 d-flex ">
                            <div class="w-50">
                                <div class="form-outline mb-3 signup-input-div">
                                    <label class="form-label">Phone Number</label>
                                    <input required type="text" name="phone"
                                        class="form-control auth-form single-input" placeholder="Enter Phone Number" />

                                </div>
                                <div class="form-outline mb-3 signup-input-div">
                                    <label class="form-label">Country</label>
                                    <select name="country" required class="form-control auth-form single-input">
                                        <option value="">Select Country</option>
                                        <option value="USA">USA</option>
                                        <option value="UAE">UAE</option>
                                        <option value="Pakistan">Pakistan</option>
                                    </select>
                                </div>

                            </div>
                            <div class="w-50">
                                <div class="form-outline mb-3 signup-input-div">
                                    <label class="form-label">Password</label>
                                    <input required type="password" name="password"
                                        class="form-control auth-form single-input" placeholder="Enter Password" />

                                </div>
                                <div class="form-outline mb-3 signup-input-div">
                                    <label class="form-label">Password</label>
                                    <input required type="password" name="password_confirmation"
                                        class="form-control auth-form single-input" placeholder="Confirm Password" />
                                </div>
                            </div>
                        </div>

                        <div class="w-100 d-flex ">
                            <div class="w-50">
                                <div class="form-outline mb-3 signup-input-div">
                                    <label class="form-label">Postal Code</label>
                                    <input required type="number" name="postalCode"
                                    class="form-control auth-form single-input" placeholder="Enter Postal Code" />
                                </div>
                            </div>
                            <div class="w-50">
                                <div class="form-outline mb-3 signup-input-div">
                                    <label class="form-label">Manager</label>
                                    <select name="managerId" required class="form-control auth-form single-input">
                                        @foreach ($managers as $manager)
                                            <option value="{{ $manager->id }}">{{ $manager->firstName }} {{ $manager->lastName }}</option><br>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end w-100 mb-5">
                            <button type="submit" class="btn btn-success auth-submit-btn btn-block"
                                id="completeProfileBtn">Complete Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- JQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {{-- Parsley JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    {{-- Custome JS --}}
    <script src="/js/script.js"></script>

</body>

</html>
