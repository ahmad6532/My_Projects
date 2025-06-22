@extends('layouts.admin.master')
@section('content')
<div class="container-fluid">
@if(session("error"))
        <div class="alert alert_vt" id="alertID">
<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
    <div class="alert alert-danger small " style="max-width:100%;">{{ session('error')}}</div>
</div>
@endif
    <div class="row justify-content-center mt-2">
        <div class="col-lg-12">
            <div class="user-content-detail">
                <div class="text-center">
                    <h1 class="text-heading_vt pb-4">Update User</h1>
                </div>
                <form  action="{{url('Update-user-Details/'.$Userdetails->id)}}" method="POST"  enctype="multipart/form-data">
                                    @csrf
                                  @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="billing-last-name">User Name<span class="red" style="font-size:22px;">*</span></label>
                                        <input name="user_name" class="form-control  @error('name') is-invalid @enderror" value="{{ $Userdetails->fullname}}" type="text" placeholder="Enter User Name" required  autocomplete="name" autofocus>
                                        @error('user_name')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="billing-last-name">Email Address<span class="red" style="font-size:22px;">*</span></label>
                                        <input class="form-control" required name="email"  value="{{ $Userdetails->email}}"  type="email" placeholder="Enter User Email Address" >
                                        @error('email')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="billing-last-name">Expiry Date<span class="red" style="font-size:22px;">*</span></label>
                                        <div class=" mb-1 position-relative month-field_vt">
                                            @php
                                                $expiryDate = $Userdetails->expiry_date ? date('d-m-Y', strtotime($Userdetails->expiry_date)) : '';
                                            @endphp
                                            <input type="text" name="expiry_date" id="dateInput" value="{{ $expiryDate }}" required class="form-control" autocomplete="off" placeholder="Select Date">
                                            <i class="fontello icon-calander1"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group position-relative arrow_vt">
                                        <label for="billing-first-name">User Role<span class="red" style="font-size:22px;">*</span></label>
                                        <select name="user_role_id"  required class="form-control" style="appearance: none;">
                                            <option disable selected>Select User Role</option>
                                            @foreach($roles as $item)
                                            @if($Userdetails->role_id==$item->id)
                                            <option  value="{{$item->id}}" selected>{{$item->role_name}}</option>
                                            @else
                                            <option  value="{{$item->id}}">{{$item->role_name}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        <i class="fontello icon-down-dir icon-color"></i>
                                    </div>
                                </div>
                        </div> <!-- end row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="billing-first-name">Assign Company<span class="red" style="font-size:22px;">*</span></label>
                                    <select name="company_id[]" id="company_id" multiple onchange="getMultipleBranches()" required class="form-control selectpicker" style="appearance: none;">
                                        <option  disabled>Select Company</option>
                                        @forelse ($companies as $company)
                                            @php
                                                $selected = in_array($company->id, explode(',', $Userdetails->company_id));
                                            @endphp
                                            <option value="{{$company->id}}" {{$selected ? 'selected' : ''}}>{{ucwords($company->company_name)}}</option>
                                        @empty
                                            <option>No Record Found</option>
                                        @endforelse
                                    </select>
                                    {{-- <i class="fontello icon-down-dir icon-color"></i> --}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="billing-first-name">Office Location<span class="red" style="font-size:22px;">*</span></label>
                                    <select name="branch_id[]" id="branch_id" multiple required class="form-control selectpicker" style="appearance: none;">
                                        <option disabled>Select Branch</option>
                                    </select>
                                    {{-- <i class="fontello icon-down-dir icon-color"></i> --}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Password<span class="red" style="font-size:22px;">*</span></label>
                                    <input id="password-field" name="new_password" class="form-control" value="{{ old('new-password') }}" type="password"  placeholder="Enter New Password"   />
                                    <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password small"></span>
                                    @error('new_password')    <small class="text-danger">{{$message}}</small> @enderror
                                </div>
                            </div>
                           
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="billing-email-address">User Status</label>
                                </div>
                                <div class=" pb-2">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value="1" id="status_id1" name="user_status" class="custom-control-input" {{ $Userdetails->is_active=='1' ? 'checked' : ''}} >
                                        <label class="custom-control-label" for="status_id1">Active</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio"  value="0" id="status_id2" name="user_status" class="custom-control-input" {{ $Userdetails->is_active=='0' ? 'checked' : ''}} >
                                        <label class="custom-control-label" for="status_id2">Inactive</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="billing-email-address">Is Pin Enable ?</label>
                                </div>
                                <div class=" pb-2">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value="1" id="status_id3" name="user_pin_status" class="custom-control-input" {{ $Userdetails->is_pin_enable=='1' ? 'checked' : ''}} >
                                        <label class="custom-control-label" for="status_id3">Yes</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio"  value="0" id="status_id4" name="user_pin_status" class="custom-control-input" {{ $Userdetails->is_pin_enable=='0' ? 'checked' : ''}} >
                                        <label class="custom-control-label" for="status_id4">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="billing-email-address">Can Face Update ?</label>
                                </div>
                                <div class="pb-2">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value="1" id="status_id5" name="user_face_status" class="custom-control-input" {{ $Userdetails->can_update_face=='1' ? 'checked' : ''}} >
                                        <label class="custom-control-label" for="status_id5">Yes</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio"  value="0" id="status_id6" name="user_face_status" class="custom-control-input" {{ $Userdetails->can_update_face=='0' ? 'checked' : ''}} >
                                        <label class="custom-control-label" for="status_id6">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="billing-email-address">Is App Attendance Allowed ?</label>
                                </div>
                                <div class=" pb-2">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value="1" id="status_id7" name="is_app_attendance_allowed" class="custom-control-input" {{ $Userdetails->is_attendance_allowed=='1' ? 'checked' : ''}} >
                                        <label class="custom-control-label" for="status_id7">Yes</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio"  value="0" id="status_id8" name="is_app_attendance_allowed" class="custom-control-input" {{ $Userdetails->is_attendance_allowed=='0' ? 'checked' : ''}} >
                                        <label class="custom-control-label" for="status_id8">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <Button type="submit" name="submit" class="page-btn">Update</Button>
                            </div>
                        </div>
                 </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script type="text/javascript">
    setTimeout(function(){
    $('#alertID').hide('slow')
    }, 3000);

    $(document).ready(function() {
        // Get the current month and year
        var currentDate = new Date();
        $('#dateInput').datepicker({
            format: "dd-mm-yyyy",
            defaultDate: currentDate,
            orientation: "bottom auto"
        });

        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
                if (input.attr("type") == "password" ) {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                    a.attr("type", "password");
                }
        });
        getMultipleBranches();

    });

    function getMultipleBranches() {
        var company_ids = [];

        // Iterate through selected options and extract their values
        $('#company_id option:selected').each(function () {
            company_ids.push($(this).val());
        });

        $.ajax({
            method: 'get',
            dataType: 'json',
            url: '{{ route('get.multiple.branches') }}',
            data: {
                company_id: company_ids
            },
            success: function (response) {
                var data = response.data;

                // Check if data is not empty before updating the branch_id select
                if (data.length > 0) {
                    $('#branch_id').html('');
                    var html = '<option disabled>Select Location</option>';
                    var branch_id = "{{$Userdetails->branch_id}}";
                    for (var i = 0; i < data.length; ++i) {
                        var selected = branch_id.split(',').includes(String(data[i].id)) ? 'selected' : '';
                        html += `<option value="${data[i].id}" ${selected}>${data[i].branch_name}</option>`;
                    }
                    $('#branch_id').html(html);
                    $('#branch_id').selectpicker('refresh');
                } else {
                    // Handle the case when there is no data to display
                    $('#branch_id').html('<option value="" disabled>No Location Found</option>');
                    $('#branch_id').selectpicker('refresh');
                }
            }
        });
    }
</script>
@endsection
