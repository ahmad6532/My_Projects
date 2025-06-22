@extends('layouts.admin.master')
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert_vt" id="alertID">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div class="alert alert-success small " style="max-width:100%;">{{ session('success') }}</div>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert_vt" id="alertID">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div class="alert alert-danger small " style="max-width:100%;">{{ session('error') }}</div>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="nav nav-pills navtab-bg nav-pills-tab text-center justify-content-center"
                                    id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active show mt-2 py-2" id="custom-v-pills-billing-tab" href="#"
                                        role="tab" aria-controls="custom-v-pills-billing" aria-selected="true">Personal
                                        Details
                                    </a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-shipping-tab"
                                        href="{{ url('employee/directory/edit-education/' . base64_encode($employee_id)) }}"
                                        role="tab" aria-controls="custom-v-pills-shipping"
                                        aria-selected="false">Education</a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-payment-tab"
                                        href="{{ url('employee/directory/edit-experiences/' . base64_encode($employee_id)) }}"
                                        role="tab" aria-controls="custom-v-pills-payment"
                                        aria-selected="false">Employment</a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-payment1-tab"
                                        href="{{ url('employee/directory/edit-refrences/' . base64_encode($employee_id)) }}"
                                        role="tab" aria-controls="custom-v-pills-payment2"
                                        aria-selected="false">References</a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-payment1-tab"
                                        href="{{ url('employee/directory/edit-account/' . base64_encode($employee_id)) }}"
                                        role="tab" aria-controls="custom-v-pills-payment2"
                                        aria-selected="false">Account</a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-payment2-tab"
                                        href="{{ url('employee/directory/edit-approval/' . base64_encode($employee_id)) }}"
                                        role="tab" aria-controls="custom-v-pills-payment2"
                                        aria-selected="false">Approvals</a>
                                </div>
                            </div> <!-- end col-->
                            <br>

                            <div class="col-lg-12">
                                <div class="tab-content main-tabs-content">
                                    <form action="{{ url('update-employee/' . base64_encode($employee_id)) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="edit" value="preview">
                                        <div class="tab-pane fade active show" id="custom-v-pills-billing" role="tabpanel"
                                            aria-labelledby="custom-v-pills-billing-tab">
                                            <div class="border p-2">
                                                <div class="row">
                                                    <div class="col-md-12" style="padding: 25px 0;">
                                                        <!-- <img id="output"/> -->
                                                        <div class="d-flex justify-content-center">
                                                            <div class="camera-content-holder position-relative">
                                                                @php
                                                                    if ($EmpDetails->emp_image) {
                                                                        $imagePath = public_path($EmpDetails->emp_image);
                                                                        if (File::exists($imagePath)) {
                                                                            $EmpDetails->emp_image = asset($EmpDetails->emp_image);
                                                                        } else {
                                                                            // If the image file doesn't exist, set a default image based on gender
                                                                        if ($EmpDetails->emp_gender == 'F') {
                                                                            $EmpDetails->emp_image = asset('assets/images/female.png');
                                                                        } else {
                                                                            $EmpDetails->emp_image = asset('assets/images/male.png');
                                                                        }
                                                                    }
                                                                } else {
                                                                    // If emp_image is empty, set a default image based on gender
                                                                    if ($EmpDetails->emp_gender == 'F') {
                                                                        $EmpDetails->emp_image = asset('assets/images/female.png');
                                                                    } else {
                                                                        $EmpDetails->emp_image = asset('assets/images/male.png');
                                                                        }
                                                                    }
                                                                @endphp
                                                                <img id="output" src="{{ $EmpDetails->emp_image }}"
                                                                    alt="user-image" class="rounded-circle form-img_vt">
                                                                <div class="camera-holder">
                                                                    <label for="test1" class="camera_vt">
                                                                        <i
                                                                            class="fontello icon-camera1 color-white camera-icon_vt">
                                                                            <input id="test1" type="file"
                                                                                name="image" onchange="loadFile(event)"
                                                                                style="display:none;"></i>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group position-relative arrow_vt">
                                                            <label for="billing-first-name">Company <span class="red"
                                                                    style="font-size:22px;">*</span></label>
                                                            <select name="company_id" id="company_id"
                                                                onchange="getBranches()" required class="form-control"
                                                                style="appearance:none;">
                                                                <option value="" disable selected>Select Company
                                                                </option>
                                                                @forelse($companies as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        {{ old('company_id', $EmpDetails->company->id == $item->id) ? 'selected' : '' }}>
                                                                        {{ $item->company_name }}</option>
                                                                @empty
                                                                    <option>No Record Found</option>
                                                                @endforelse
                                                            </select>
                                                            <i class="fontello icon-down-dir icon-color"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group position-relative arrow_vt">
                                                            <label for="billing-first-name">Location <span class="red"
                                                                    style="font-size:22px;">*</span></label>
                                                            <select name="branch_id" id="branch_id" required
                                                                class="form-control" style="appearance: none;">
                                                                <option disable selected>Select Location</option>
                                                                @forelse ($branches as $branch)
                                                                    <option value="{{ $branch->id }}"
                                                                        {{ old('branch_id', $EmpDetails->branch_id == $branch->id) ? 'selected' : '' }}>
                                                                        {{ ucwords($branch->branch_name) }}</option>
                                                                @empty
                                                                    <option>No Record Found</option>
                                                                @endforelse
                                                            </select>
                                                            <i class="fontello icon-down-dir icon-color"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="billing-last-name">Employee ID <span
                                                                    class="red"
                                                                    style="font-size:22px;">*</span></label>
                                                            <input name="emp_id" class="form-control" type="number"
                                                                required value="{{ old('emp_id', $EmpDetails->emp_id) }}"
                                                                placeholder="Enter Employee ID" id="billing-last-name" />
                                                            @error('emp_id')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="billing-name">Full Name <span class="red"
                                                                    style="font-size:22px;">*</span></label>
                                                            <input name="name" class="form-control" required
                                                                type="text"
                                                                value="{{ old('name', $EmpDetails->emp_name) }}"
                                                                placeholder="Enter Full Name" id="billing-name" />
                                                            @error('name')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="billing-email-address">Father's Name </label>
                                                            <input name="fathername" class="form-control" type="text"
                                                                value="{{ old('fathername', $EmpDetails->father_name) }}"
                                                                placeholder="Enter Father Name" id="billing-fathername" />
                                                            @error('fathername')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="billing-phone">Mother's Name </label>
                                                            <input name="mothername" class="form-control" type="text"
                                                                value="{{ old('mothername', $EmpDetails->mother_name) }}"
                                                                placeholder="Enter Mother Name" id="billing-mothername" />
                                                            @error('mothername')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="billing-phone">Personal Email <span class="red"
                                                                    style="font-size:22px;">*</span></label>
                                                            <input name="personal_email" class="form-control" type="email"
                                                                required
                                                                value="{{ old('personal_email', $EmpDetails->personal_email) }}"
                                                                placeholder="Enter Personal Email" />
                                                            @error('personal_email')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group position-relative arrow_vt">
                                                            <label for="billing-first-name">Gender <span class="red"
                                                                    style="font-size:22px;">*</span></label>
                                                            <select title="gender" name="gender" required
                                                                class="form-control" style="appearance:none;">
                                                                <option value="" disable selected>Select Gender
                                                                </option>
                                                                <option value="M"
                                                                    {{ old('gender', $EmpDetails->emp_gender) == 'M' ? 'selected' : '' }}>
                                                                    Male</option>
                                                                <option value="F"
                                                                    {{ old('gender', $EmpDetails->emp_gender) == 'F' ? 'selected' : '' }}>
                                                                    Female</option>
                                                            </select>
                                                            <i class="fontello icon-down-dir icon-color"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group position-relative month-field_vt mb-1 ">
                                                            <label for="billing-date-of-birth">Date of Birth </label>
                                                            <input name="dateofbirth" class="form-control" type="text"
                                                                value="{{ old('dateofbirth', date('d-m-Y', strtotime($EmpDetails->dob))) }}"
                                                                id="dateofbirth" placeholder="Select Date" />
                                                            <i class="fontello icon-calander1" style="top:35px;"></i>
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="billing-phone">Phone <span class="red"
                                                                    style="font-size:22px;">*</span></label>
                                                            <input id="phoneid" name="emp_phone" class="form-control"
                                                                required type="number" onkeyup="Checkinput()"
                                                                value="{{ old('emp_phone', $EmpDetails->emp_phone) }}"
                                                                placeholder="Enter Phone" />
                                                            <p id="demo" class="alert-danger small"> </p>
                                                            @error('emp_phone')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>N.I.C <span class="red"
                                                                    style="font-size:22px;">*</span></label>
                                                            <input id="cnic_id" name="cnic" class="form-control"
                                                                required value="{{ old('cnic', $EmpDetails->cnic) }}"
                                                                type="number" onkeyup="Checkinputnic()"
                                                                placeholder="Enter N.I.C" />
                                                            <p id="nicmessage" class="alert-danger small"> </p>
                                                            @error('cnic')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="religion">Religion <span class="red"
                                                                    style="font-size:22px;">*</span></label>
                                                            <input name="religion" class="form-control" required
                                                                type="text"
                                                                value="{{ ucwords(old('religion', $EmpDetails->religion)) }}"
                                                                placeholder="Enter Religion" id="religion">
                                                            @error('religion')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group position-relative arrow_vt">
                                                            <label for="billing-state">Nationality <span class="red"
                                                                    style="font-size:22px;">*</span></label>
                                                            <select id="country_id" onchange="getCities()" required
                                                                name="country_id" class="form-control"
                                                                style="appearance:none;">
                                                                <option selected disable>Select Nationality</option>
                                                                @foreach ($countries as $item)
                                                                    <option value="{{ $item->country_id }}"
                                                                        {{ old('nationality', $EmpDetails->nationality == $item->country_id) ? 'selected' : '' }}>
                                                                        {{ $item->country_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="fontello icon-down-dir icon-color"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group position-relative arrow_vt">
                                                            <label for="billing-state">Place of Birth</label>
                                                            <select id="city_id" name="city_id" class="form-control"
                                                                style="appearance: none;">
                                                                <option value="" disabled>Select City</option>
                                                            </select>
                                                            <i class="fontello icon-down-dir icon-color"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="billing-blood-group">Blood Group </label>
                                                            <input name="bloodgroup" class="form-control"
                                                                value="{{ old('bloodgroup', $EmpDetails->blood_group) }}"
                                                                type="text" placeholder="Enter Blood Group"
                                                                id="billing-phone" />
                                                            @error('bloodgroup')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="billing-address">Address</label>
                                                            <input name="address" class="form-control"
                                                                value="{{ old('address', $EmpDetails->emp_address) }}"
                                                                type="text" placeholder="Enter Address"
                                                                id="billing-address">
                                                            @error('address')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group position-relative arrow_vt">
                                                            <label for="billing-state">Marital Status</label>
                                                            <select id="statusId" name="marital_status"
                                                                onchange="yesmarried();" class="form-control"
                                                                style="appearance: none;">
                                                                <option value=""selected disabled>Select Marital
                                                                    Status</option>
                                                                <option value="0"
                                                                    {{ old('marital_status', $EmpDetails->marital_status) == '0' ? 'selected' : '' }}>
                                                                    Single</option>
                                                                <option value="1"
                                                                    {{ old('marital_status', $EmpDetails->marital_status) == '1' ? 'selected' : '' }}>
                                                                    Married</option>
                                                            </select>
                                                            <i class="fontello icon-down-dir icon-color"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div id="spouseId" style="display:none" class="form-group">
                                                            <label>Name of Spouse</label>
                                                            <input name="spouse" id="spouse" class="form-control"
                                                                value="{{ old('spouse', $EmpDetails->spouse_name) }}"
                                                                type="text" placeholder="Enter Spouse Name"
                                                                id="billing-town-city" />
                                                            {{-- @error('emp_email')
                                                                    <div class="error">{{ $message }}</div>
                                                                @enderror --}}
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->
                                                <!-- </form> -->
                                            </div>
                                            <div class="row px-2 justify-content-between">
                                                <div class="col-md-6 col-lg-6 col-ms-12 border p-2 mt-4">
                                                    <h1 class="text-heading_vt text-overlap_vt"
                                                        style="width: 57px !important;">Living</h1>
                                                    <h1 class="text-heading_vt pt-2">Accommodation</h1>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" value="0" id="customRadio11"
                                                            name="accomodation" class="custom-control-input"
                                                            {{ $EmpDetails->is_independant == 0 ? 'checked="true"' : '' }}>
                                                        <label class="custom-control-label" for="customRadio11">Living
                                                            with Parents/Relatives</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadio12" value="1"
                                                            name="accomodation" class="custom-control-input"
                                                            {{ $EmpDetails->is_independant == 1 ? 'checked="true"' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="customRadio12">Independent</label>
                                                    </div>
                                                    <hr>
                                                    <div class="form-group mb-0">
                                                        <h1 class="text-heading_vt">Specify</h1>
                                                    </div>
                                                    <div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" value="1" id="customRadio13"
                                                                name="accomodation_specify" class="custom-control-input"
                                                                {{ $EmpDetails->has_home == 1 ? 'checked="true"' : '' }}>
                                                            <label class="custom-control-label"
                                                                for="customRadio13">Owned</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" value="0" id="customRadio14"
                                                                name="accomodation_specify" class="custom-control-input"
                                                                {{ $EmpDetails->has_home == 0 ? 'checked="true"' : '' }}>
                                                            <label class="custom-control-label"
                                                                for="customRadio14">Rented</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-6 col-ms-12 border p-2 mt-4">
                                                    <h1 class="text-heading_vt text-overlap_vt"
                                                        style="width: 85px !important;">Transport</h1>
                                                    <div class="row transport_vt">
                                                        <div class="col-md-6">
                                                            <h1 class="text-heading_vt pt-2">Do you have transportation?
                                                            </h1>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" value="1" id="hasTransport"
                                                                    name="has_transport" onclick="yesCheck();"
                                                                    class="custom-control-input"
                                                                    {{ $EmpDetails->has_transport == 1 ? 'checked="true"' : '' }}>
                                                                <label class="custom-control-label"
                                                                    for="hasTransport">Yes</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" value="0" id="noTransport"
                                                                    name="has_transport" onclick="yesCheck();"
                                                                    class="custom-control-input"
                                                                    {{ $EmpDetails->has_transport == 0 ? 'checked="true"' : '' }}>
                                                                <label class="custom-control-label"
                                                                    for="noTransport">No</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div id="specifyID" style="display:none"
                                                                class="form-group mb-0">
                                                                <h1 class="text-heading_vt pt-2">Specify</h1>
                                                            </div>
                                                            <div id="typeoftransportId" style="display:none">
                                                                <div
                                                                    class="custom-control custom-radio custom-control-inline">
                                                                    <input type="radio" value="car"
                                                                        id="customRadio17" name="transport_type"
                                                                        class="custom-control-input"
                                                                        {{ $EmpDetails->transport_type == 'car' ? 'checked="true"' : '' }}>
                                                                    <label class="custom-control-label"
                                                                        for="customRadio17">Car</label>
                                                                </div>
                                                                <div
                                                                    class="custom-control custom-radio custom-control-inline">
                                                                    <input type="radio" value="motorcycle"
                                                                        id="customRadio18" name="transport_type"
                                                                        class="custom-control-input"
                                                                        {{ $EmpDetails->transport_type == 'motorcycle' ? 'checked="true"' : '' }}>
                                                                    <label class="custom-control-label"
                                                                        for="customRadio18">Motorcycle</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="registerNo" class="col-md-6" style="display:none">
                                                            <div class="form-group mb-0">
                                                                <h1 class="text-heading_vt pt-2">Registration Number</h1>
                                                            </div>
                                                            <div class="form-group mb-0">
                                                                <input class="form-control" name="register_no"
                                                                    value="{{ old('register_no', $EmpDetails->registration_no) }}"
                                                                    type="text"
                                                                    placeholder="Enter Registration Number" />
                                                                @error('register_no')
                                                                    <div class="error">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div id="haslicense" class="row transport_vt">
                                                        <div class="col-md-6 mb-2">
                                                            <h1 class="text-heading_vt mt-0">Do you have a driving license?
                                                            </h1>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" value="1" id="customRadio451"
                                                                    name="has_license" onchange="yeslicensed();"
                                                                    class="custom-control-input"
                                                                    {{ $EmpDetails->driving_license == 1 ? 'checked="true"' : '' }}>
                                                                <label class="custom-control-label"
                                                                    for="customRadio451">Yes</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" value="0" id="customRadio143"
                                                                    name="has_license" onchange="yeslicensed();"
                                                                    class="custom-control-input"
                                                                    {{ $EmpDetails->driving_license == 0 ? 'checked="true"' : '' }}>
                                                                <label class="custom-control-label"
                                                                    for="customRadio143">No</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div id="licenseNo" style="display:none" class="form-group">
                                                                <h1 class="text-heading_vt mt-0">Specify</h1>
                                                                <input name="license_number"
                                                                    value="{{ old('license_number', $EmpDetails->license_no) }}"
                                                                    class="form-control" type="number"
                                                                    placeholder="Enter License Number" />
                                                                @error('license_number')
                                                                    <div class="error">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row px-2 justify-content-between">
                                                <div class="col-md-12 col-lg-12 col-ms-12 border p-2 mt-4">
                                                    <h1 class="text-heading_vt text-overlap_vt">Upload Documents</h1>
                                                    <div class="row justify-content-end px-2 py-2">
                                                        <button type="button"
                                                            class="page-btn page-btn-outline sm-page-btn"
                                                            style="float:right;" id="openModalButton">Upload File</button>
                                                    </div>
                                                    @if ($emp_documents)
                                                        @foreach ($emp_documents as $key => $item)
                                                            @php $iteration = $key + 1; @endphp
                                                            <div class="float_vt ml-0 card_list"
                                                                id="card_list_{{ $iteration }}">
                                                                <div class="davice_devices_vt px-0 width_vt pointer"
                                                                    style="overflow: inherit;">
                                                                    <div class="d-flex justify-content-end pr-1" style="background-color: #f9f9f9 !important;">
                                                                        <span
                                                                            onclick="deleteDoc({{ $item->id }},'card_list_{{ $iteration }}')"><i
                                                                                class="fa-solid fa-xmark"
                                                                                style="font-size:12px;color:#028cc9;"
                                                                                title="Remove"></i></span>
                                                                    </div>
                                                                    <div class="device_hed_vt">
                                                                        <div class="title_name_vt ml-0 font-weight-bolder text-center"
                                                                            id="dev_name_{{ $iteration }}"
                                                                            style="width:100px">
                                                                            <div id="documentappen"
                                                                                class="content_image text-center mb-1">
                                                                                <img src="https://dev.securegenic.com/assets/media/misc/xls_icon.png"
                                                                                    loading="lazy" alt="Content Image">
                                                                            </div>
                                                                            <div id="discription">
                                                                                <span>{{ $item->discription }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                    <span id="card_span"></span>
                                                </div>
                                            </div>
                                            <div class="pt-4 pb-2">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <Button id="submitID1" onclick="javascript:Tabpane();"
                                                            type="submit" name="submit" class="page-btn">Update &
                                                            Continue</Button>
                                                    </div>
                                                </div> <!-- end row -->
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div> <!-- end col-->
                        </div> <!-- end row-->
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
    </div>
    <div class="modal" id="addContentFilesModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="font-size: 24px;">&times;</span>
                    </button>
                    <div class="card-body p-2">
                        <div class="form-group row">
                            <div class="col-lg-12 px-0">
                                <h4 class="heading-user text-center model-title">Upload Document File</h4>
                            </div>
                        </div>
                        <form id="modalForm">
                            <div class="table_main pt-0" id="v_group_table">
                                <div class="form-group row">
                                    <div class="col-lg-12 drop-area" id="dropArea">
                                        <label for="csv-file" class="custom-file-upload"><img
                                                src="{{ asset('assets/images/upload_files.png') }}"
                                                class="img-fluid" /></label>
                                        <input id="csv-file" type="file" name="content_list[]"
                                            accept=".jpg, .jpeg, .png, .doc, .docx, .pdf, .txt"
                                            onchange="handleFileSelect(event)" required>
                                        <span id="file-name"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="nameInput" class="form-label">Document Description</label>
                                        <input type="text" class="form-control" name="document_discription"
                                            id="document_discription" placeholder="Enter Description">
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-3 pl-0">
                                    <button type="reset" id="resetButton"
                                        class="page-btn page-btn-outline hover-btn sm-page-btn">Cancel</button>
                                    <button id="ContentUploadBtn"
                                        onclick="addInputField(document.getElementById('csv-file').files[0], document.getElementById('document_discription').value)"
                                        class="page-btn sm-page-btn">Import</button>
                                </div>
                                <div class="progress mb-1" style="display:none;">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            // Add a click event listener to your button
            $('#openModalButton').click(function() {
                // Reset the modal form (assuming you have a form in your modal with id "modalForm")
                $('#modalForm')[0].reset();

                // if ($('#csv-file').val() == '') {
                //     $('#file-name').addClass('d-none');
                // }
                // Open the modal
                $('#addContentFilesModal').modal('show');
            });

            $('#modalForm').submit(function(event) {
                event.preventDefault();
            });
        });

        function addInputField(file, description) {
            // Create a new card_list element with a unique ID
            var newCardId = Date.now(); // Generate a unique timestamp-based ID
            var id = 'card_list_' + newCardId;
            var newCardList = $('<div>', {
                class: 'row float_vt ml-0 card_list',
                id: id, // Use the ID
                'data-card-id': newCardId,
                style: 'display: block;',
            });

            // Create the inner structure of the card_list div
            var innerContent = `
            <div class="davice_devices_vt px-0 width_vt pointer" style="overflow: inherit;">
                <div class="row justify-content-end pr-3">
                    <span onclick="removeDoc('${id}')"><i class="fa-solid fa-xmark" style="font-size:12px;color:#028cc9;" title="Remove"></i></span>
                </div>
                <div class="device_hed_vt">
                    <div class="title_name_vt ml-0 font-weight-bolder text-center" id="dev_name_${newCardId}" style="width: 100px">
                        <div class="content_image text-center mb-1">
                            <img src="https://dev.securegenic.com/assets/media/misc/xls_icon.png" loading="lazy" alt="Content Image">
                        </div>
                        <div class="discription" style="font-size: 12px;">${description}</div>
                    </div>
                </div>
            </div>
        `;

            // Append the inner content to the new card_list element
            newCardList.html(innerContent);
            $('#card_span').append(newCardList);

            // append card
            var newInputFile = document.createElement("input");
            newInputFile.setAttribute("type", "file");
            newInputFile.setAttribute("name", "content_list[]");
            newInputFile.classList.add("title_name_vt", "d-none", "text-center", "ml-0", "font-weight-bolder");

            var fileList = new DataTransfer();
            fileList.items.add(file);
            newInputFile.files = fileList.files;
            document.getElementById("card_list_" + newCardId).style.display = 'block';

            var getDiv = document.getElementById("card_list_" + newCardId);
            getDiv.appendChild(newInputFile);

            var document_discription = document.createElement("input");
            document_discription.setAttribute("type", "text");
            document_discription.setAttribute("name", "document_discription[]");
            document_discription.classList.add("form-control", "modal-input", "d-none", "mb-0");
            document_discription.value = description;
            document_discription.setAttribute("readonly", "readonly");
            document_discription.style.border = "none";

            var descriptionContainer = document.getElementById("dev_name_" + newCardId);
            descriptionContainer.appendChild(document_discription);

            document.getElementById("close").click();
        }

        function removeDoc(id) {
            var div = document.getElementById(id);
            if (div) {
                div.remove();
            }
        }

        function deleteDoc(id, div) {
            Swal.fire({
                title: 'Delete',
                text: 'Are you sure you want to delete? ',
                iconHtml: '<img src="{{ asset('assets/images/delete-alert.png') }}">',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('delete_document/') }}",
                        type: 'get',
                        data: {
                            'id': id,
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success == true) {
                                Swal.fire({
                                    title: 'Success',
                                    text: response.message,
                                    iconHtml: '<img src="{{ asset('assets/images/success-icon.png') }}">',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    timer: 1000,
                                })
                                if (div) {
                                    document.getElementById(div).remove();
                                }
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: response.message,
                                    iconHtml: '<img src="{{ asset('assets/images/delete-alert.png') }}">',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    timer: 1000,
                                })
                            }
                        }
                    });
                }
            })
        }
        $(document).ready(function() {
            var currentDate = new Date();
            $('#dateofbirth').datepicker({
                format: "dd-mm-yyyy",
                endDate: currentDate,
            });
            yesmarried();
            getCities();
        });

        function yesmarried() {
            var d = document.getElementById('statusId').value;
            if (d == '1') {
                document.getElementById('spouseId').style.display = 'block';

            } else {
                document.getElementById('spouseId').style.display = 'none';
            }
        }

        function getBranches() {
            var company_id = $('#company_id').val();
            $.ajax({
                method: 'get',
                dataType: 'json',
                url: '{{ route('get-branch') }}',
                data: {
                    company_id: company_id
                },
                success: function(response) {
                    var data = response.data;
                    $('#branch_id').html('');
                    var html = '<option selected disabled>Select Location</option>';
                    for (var i = 0; i < data.length; ++i) {
                        html += `<option value="${data[i].id}">${data[i].branch_name}</option>`;
                    }
                    $('#branch_id').html(html);
                }
            });
        }

        function getCities() {
            var country_id = $('#country_id').val();

            $.ajax({
                url: "{{ route('get.cities') }}",
                type: 'GET',
                data: {
                    'country_id': country_id,
                },
                success: function(response) {
                    if (response.success == true) {
                        var result = response.data;
                        var city_id = '{{ $EmpDetails->city_of_birth }}';
                        $('#city_id').html('');
                        var html = '<option selected disabled>Select City</option>';
                        for (var i = 0; i < result.length; i++) {
                            // var selected = result[i].city_id === city_id ? 'selected':'';
                            html += `<option value="${result[i].city_id}">${result[i].city_name}</option>`;
                        }
                        $('#city_id').html(html);
                    }
                }
            });
        }

        // $(function() {
        //     $.ajaxSetup({
        //         headers : {
        //             'CSRFToken' : getCSRFTokenValue()
        //         }
        //     });
        // });

        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };

        function yesCheck() {
            if (document.getElementById('hasTransport').checked) {
                document.getElementById('typeoftransportId').style.display = 'block';
                document.getElementById('specifyID').style.display = 'block';
                document.getElementById('registerNo').style.display = 'block';
                // document.getElementById('haslicense').style.display = 'block';
                // document.getElementById('licenseNo').style.display = 'block';
            } else {
                if (document.getElementById('noTransport').checked) {
                    document.getElementById('typeoftransportId').style.display = 'none';
                    document.getElementById('specifyID').style.display = 'none';
                    document.getElementById('registerNo').style.display = 'none';
                    // document.getElementById('haslicense').style.display = 'block';
                    // document.getElementById('licenseNo').style.display = 'none';
                }
            }
        }
        var hasTransport = "{{ $EmpDetails->has_transport }}";
        if (hasTransport == '1') {
            document.getElementById('typeoftransportId').style.display = 'block';
            document.getElementById('specifyID').style.display = 'block';
            document.getElementById('registerNo').style.display = 'block';
        }

        function yeslicensed() {
            if (document.getElementById('customRadio451').checked) {
                document.getElementById('licenseNo').style.display = 'block';
            } else {
                if (document.getElementById('customRadio143').checked)
                    document.getElementById('licenseNo').style.display = 'none';
            }
        }

        var driving_license = "{{ $EmpDetails->driving_license }}";
        if (driving_license == '1') {
            document.getElementById('licenseNo').style.display = 'block';
        }

        setTimeout(function() {
            $('#alertID').hide('slow')
        }, 200000);
    </script>
@endsection
