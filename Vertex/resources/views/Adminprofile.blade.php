@extends('layouts.admin.master')
@section('content')
                                    <div class="border p-2" >
                                            <form id="myform" action="{{route('')}}" method="POST"  enctype="multipart/form-data">
                                                 @csrf
                                                <div class="row">
                                                        <div class="col-md-12 text-center position-relative">
                                                        <img src="{{ asset('assets/images/users/user.png') }}" alt="user-image" class="rounded-circle form-img_vt">
                                                        <div class="camera_vt">
                                                            <i class="fontello icon-camera1 color-white camera-icon_vt"><input type="file" name="image" ></i>

                                                        </div>
                                                        </div>
                                                    </div> <!-- end row -->
                                                    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="billing-name">Full Name</label>
                                                                <input name="name" class="form-control" type="text" value="{{ old('name') }}" required placeholder="Enter Full Name" id="billing-name" />
                                                               
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="billing-phone">E-mail</label>
                                                                <input name="emp_email" class="form-control" type="email"  value="{{ old('emp_email') }}"  required placeholder="Enter Email" id="billing-phone" />
                                                               
                                                            </div>
                                                        </div>
                                                    </div> <!-- end row -->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                        <div class="form-group position-relative arrow_vt">
                                                                <label for="billing-first-name">Gender</label>
                                                                <!-- <input class="form-control" type="text" required placeholder="Enter your first name" id="billing-first-name" /> -->
                                                                <select title="gender" name="gender"  required class="form-control" style="appearance: none;">
                                                                    <option  value="" disable selected>Select Gender</option>
                                                                    <option  value="M">Male</option>
                                                                    <option value="F">Female</option>                                   
                                                                </select>
                                                                <i class="fontello icon-down-dir icon-color"></i>
                                                                
                                                            </div>
                                                        </div>
                                                       
                                                    </div> <!-- end row -->
                                                   
                                                   
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="billing-phone">Phone Number</label>
                                                                <input name="emp_phone" class="form-control" type="number"  value="{{ old('emp_phone') }}" required placeholder="Enter Phone Number" id="billing-phone" />
                                                            </div>
                                                        </div>
                                                        
                                                    </div> <!-- end row -->
                                                    <div class="row">
                                                       
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>N.I.C</label>
                                                                <input name="cnic" class="form-control" value="{{ old('cnic') }}" type="number" required placeholder="Enter N.I.C" id="billing-cnic"  />
                                                            </div>
                                                        </div>
                                                    </div> <!-- end row -->
                                                   
                                                   
                                                </form>
                                            </div>
                                            
                                               
                                        </div>
@endsection