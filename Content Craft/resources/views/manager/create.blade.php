@extends('layout.master')
@section('content')
    <form action="{{ route('manager.store') }}" id="createManagerForm" method="POST" class="w-100 mt-3"
        enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="uuid" value="0">
        <div class="form-outline mb-5">
            <span class="fw-bold">Choose Your Image</span><br>
            <input type="file" required name="avatar">
            @error('avatar')
                <span class="alert alert-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="w-100 d-flex ">
            <div class="w-50">
                <div class="form-outline mb-3 signup-input-div">
                    <label class="form-label">First Name</label>
                    <input required type="text" name="firstName" class="form-control auth-form " placeholder="john" />
                    <i class="fa-regular fa-user"></i>
                     @error('firstName')
                <span class="alert alert-danger">{{$message}}</span>
            @enderror
                </div>
                <div class="form-outline mb-3 signup-input-div">
                    <label class="form-label">Email</label>
                    <input required type="email" name="email" class="form-control auth-form "
                        placeholder="johndoe@gmail.com" />
                    <i class="fa-regular fa-envelope"></i>
                     @error('email')
                <span class="alert alert-danger">{{$message}}</span>
            @enderror
                </div>
            </div>
            <div class="w-50">
                <div class="form-outline mb-3 signup-input-div">
                    <label class="form-label">Last Name</label>
                    <input required type="text" name="lastName" class="form-control auth-form " placeholder="Doe" />
                    <i class="fa-regular fa-user"></i>
                     @error('lastName')
                <span class="alert alert-danger">{{$message}}</span>
            @enderror
                </div>

                <div class="form-outline mb-3 signup-input-div">
                    <label class="form-label">Gender</label>
                    <div class="d-flex justify-content-between  w-100">
                        <span class="form-control auth-form gender ">
                            <input required type="radio" value="Male" name="gender" class=" p-5 " />
                            <label class="form-label">Male</label>
                        </span>
                        <span class="form-control auth-form gender ">
                            <input required type="radio" value="Female" name="gender" class=" p-5 " />
                            <label class="form-label">Female</label>
                        </span>
                         @error('gender')
                <span class="alert alert-danger">{{$message}}</span>
            @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="w-100">
            <div class="form-outline mb-3 signup-input-div single-input-div">
                <label class="form-label">Address</label>
                <input required type="text" name="address" class="form-control auth-form single-input"
                    placeholder="7 Nicols, St Brlklyn NY1122" />
                     @error('address')
                <span class="alert alert-danger">{{$message}}</span>
            @enderror
            </div>
        </div>
        <div class="w-100 d-flex ">
            <div class="w-50">
                <div class="form-outline mb-3 signup-input-div">
                    <label class="form-label">Phone Number</label>
                    <input required type="text" name="phone" class="form-control auth-form single-input"
                        placeholder="Enter Phone Number" />
 @error('phone')
                <span class="alert alert-danger">{{$message}}</span>
            @enderror
                </div>
                <div class="form-outline mb-3 signup-input-div">
                    <label class="form-label">Country</label>
                    <select name="country" required class="form-control auth-form single-input">
                        <option value="">Select Country</option>
                        <option value="USA">USA</option>
                        <option value="UAE">UAE</option>
                        <option value="Pakistan">Pakistan</option>
                    </select>
                     @error('country')
                <span class="alert alert-danger">{{$message}}</span>
            @enderror
                </div>

            </div>
            <div class="w-50">
                <div class="form-outline mb-3 signup-input-div">
                    <label class="form-label">Password</label>
                    <input required type="password" name="password" class="form-control auth-form single-input"
                        placeholder="Enter Password" />
 @error('password')
                <span class="alert alert-danger">{{$message}}</span>
            @enderror
                </div>
                <div class="form-outline mb-3 signup-input-div">
                    <label class="form-label">Password</label>
                    <input required type="password" name="password_confirmation" class="form-control auth-form single-input"
                        placeholder="Confirm Password" />
                        
                </div>
            </div>
        </div>

        <div class="w-100 d-flex ">
            <div class="w-50">
                <div class="form-outline mb-3 signup-input-div">
                    <label class="form-label">Postal Code</label>
                    <input required type="number" name="postalCode" class="form-control auth-form single-input"
                        placeholder="Enter Postal Code" />
                         @error('postalCode')
                <span class="alert alert-danger">{{$message}}</span>
            @enderror
                </div>
            </div>

        </div>

        <div class="d-flex justify-content-end w-100 mb-5">
            <button type="submit" class="btn btn-success w-50  auth-submit-btn btn-block" id="createManagerBtn">Create
                Manager</button>
        </div>
    </form>
@endsection
