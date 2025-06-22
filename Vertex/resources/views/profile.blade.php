@extends('layouts.admin.master')
@section('content')
    @if (session('success'))
        <div class="alert alert_vt" id="alertID">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <div class="alert alert-success small " style="max-width:100%;">{{ session('success') }}</div>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-center update-content-wrape">
                <div class="card-box border-1 update-profile_vt">

                    <form action="{{ url('Update-user/' . $users->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-center">
                                    <div class="camera-content-holder position-relative">
                                        @if (!empty($users->image))
                                            <img id="output" src="{{ $users->image }}" alt="user-image"
                                                class="rounded-circle form-img_vt">
                                        @else
                                            @if ($users->gender == 'F')
                                                <img id="output" src="{{ asset('images/female.png') }}" alt="user-image"
                                                    class="rounded-circle form-img_vt">
                                            @else
                                                <img id="output" src="{{ asset('images/male.png') }}" alt="user-image"
                                                    class="rounded-circle form-img_vt">
                                            @endif
                                        @endif
                                        <div class="camera-holder">
                                            <label for="test1" class="camera_vt">
                                                <i class="fontello icon-camera1 color-white camera-icon_vt">
                                                    <input id="test1" type="file" name="user_image"
                                                        onchange="loadFile(event)" style="display:none;"></i>

                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row -->
                        <div class="row mb-3 mt-3">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 profile-detail_vt">
                                <h4 class="header-title m-0 font-weight-bold pt-2">Profile Details </h4>
                                <p class="para_vt">Update your profile details and photo here.</p>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pt-xs-15">
                                <div class=" d-flex justify-content-end flex-direction">
                                    <div class="mr-2 pt-xs-15 d-flex">
                                        <button type="submit" name="submit" class="page-btn sm-page-btn">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="billing-name">Full Name</label>
                                    <input name="user_name" class="form-control" type="text"
                                        value="{{ $users->fullname }}" placeholder="Enter Full Name" id="billing-name" />

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="billing-phone">Email</label>
                                    <input name="user_email" class="form-control" type="email" readonly
                                        value="{{ $users->email }}" placeholder="Enter Email"  />

                                </div>
                            </div>
                        </div> <!-- end row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="billing-phone">Phone Number</label>
                                    <input name="user_phone" class="form-control" type="number" value="{{ $users->phone }}"
                                        placeholder="Enter Phone Number"  />
                                    @error('user_phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="billing-phone">Role</label>
                                    <input name="User_role" class="form-control" type="text" readonly
                                        value="{{ $users->role->role_name }}" placeholder="Enter Phone Number"
                                         />
                                </div>
                            </div>
                        </div> <!-- end row -->


                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input id="password-field" name="new_password" class="form-control"
                                        value="{{ old('new-password') }}" type="password" placeholder="Enter New Password"
                                        autocomplete="off" />
                                    <span toggle="#password-field"
                                        class="fa fa-fw fa-eye field-icon toggle-password small"></span>
                                    @error('new_password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input id="password-field1" name="password_confirmation" class="form-control"
                                        value="{{ old('password_confirmation') }}" type="password"
                                        placeholder="Enter Password Again" autocomplete="off" />
                                    <span toggle="#password-field1"
                                        class="fa fa-fw fa-eye field-icon toggle-password small"></span>
                                    @error('password_confirmation')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div> <!-- end row -->


                    </form>
                </div>


            </div>
        </div>
    </div>
    </div>
    <script type="text/javascript">
        setTimeout(function() {
            $('#alertID').hide('slow')
        }, 3000);


        $(document).ready(function() {

            $(".toggle-password").click(function() {

                $(this).toggleClass("fa-eye fa-eye-slash");
                var input = $($(this).attr("toggle"));
                if (input.attr("type") == "password") {
                    input.attr("type", "text");


                } else {
                    input.attr("type", "password");
                    a.attr("type", "password");
                }
            });
        });
    </script>
    <script>
        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
    </script>
@endsection
