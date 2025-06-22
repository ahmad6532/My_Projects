@extends('layouts.admin.master')
@section('content')
    <style>
        .filter-option::after {
            display: none !important;
        }
    </style>

    <div class="container-fluid">
        @if (session('error'))
            <div class="alert alert_vt" id="alertID">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div class="alert alert-danger small " style="max-width:100%;">{{ session('error') }}</div>
            </div>
        @endif
        <div class="row justify-content-center mt-2">
            <div class="col-lg-10">
                <div class="user-content-detail">
                    <div class="text-center">
                        <h1 class="text-heading_vt pb-4">Add New User</h1>
                    </div>
                    <form action="{{ route('insert.new.user') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="billing-last-name">User Name<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <input name="user_name" class="form-control  @error('name') is-invalid @enderror"
                                        value="{{ old('user_name') }}" type="text" placeholder="Enter User Name" required
                                        autocomplete="none" autofocus>
                                    @error('user_name')
                                        <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="billing-last-name">Email Address<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <input class="form-control" name="email" value="{{ old('email') }}" type="email"
                                        placeholder="Enter User Email Address" required autocomplete="none">
                                    @error('email')
                                        <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="billing-last-name">Expiry Date<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <div class=" mb-1 position-relative month-field_vt">
                                        <input type="text" name="expiry_date" min="{{ date('Y-m-d') }}" id="dateInput"
                                            value="{{ date('d-m-Y') }}" required autocomplete="off" class="form-control"
                                            placeholder="Select Date">
                                        <i class="fontello icon-calander1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="billing-first-name">User Role<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <select name="user_role_id" required class="form-control">
                                        <option value="" disable selected>Select User Role</option>
                                        @foreach ($roles as $item)
                                            <option value="{{ $item->id }}">{{ $item->role_name }}</option>
                                        @endforeach
                                    </select>
                                    {{-- <i class="fontello icon-down-dir icon-color"></i> --}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="billing-first-name">Assign Company<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <select name="company_id[]" id="company_id" required onchange="getMultipleBranches()"
                                        class="form-control selectpicker" multiple style="appearance: none;">
                                        <option value="all" selected>All</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}">{{ ucwords($company->company_name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- <i class="fontello icon-down-dir icon-color"></i> --}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="billing-first-name">Office Location<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <select name="branch_id[]" id="branch_id" multiple required
                                        class="form-control selectpicker" style="appearance: none;">
                                        <option value="all" selected>All</option>
                                    </select>
                                    {{-- <i class="fontello icon-down-dir icon-color"></i> --}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="billing-last-name">Password<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <input id="password-field" name="user_password" class="form-control" type="password"
                                        placeholder="Enter Password" id="billing-last-name" required
                                        autocomplete="new-password">
                                    <span toggle="#password-field"
                                        class="fa fa-fw fa-eye-slash field-icon toggle-password small"></span>
                                    @error('user_password')
                                        <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="billing-email-address">User Status</label>
                                </div>
                                <div class="pb-2">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value="1" id="status_id1" name="user_status"
                                            class="custom-control-input" checked="">
                                        <label class="custom-control-label" for="status_id1">Active</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value="0" id="status_id2" name="user_status"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="status_id2">Inactive</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="billing-email-address">Is Pin Enable ?</label>
                                </div>
                                <div class="pb-2">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value="1" id="status_id3" name="user_pin_status"
                                            class="custom-control-input" checked="">
                                        <label class="custom-control-label" for="status_id3">Yes</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value="0" id="status_id4" name="user_pin_status"
                                            class="custom-control-input">
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
                                        <input type="radio" value="1" id="status_id5" name="user_face_status"
                                            class="custom-control-input" checked="">
                                        <label class="custom-control-label" for="status_id5">Yes</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value="0" id="status_id6" name="user_face_status"
                                            class="custom-control-input">
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
                                        <input type="radio" value="1" id="status_id7" name="is_app_attendance_allowed" class="custom-control-input" checked >
                                        <label class="custom-control-label" for="status_id7">Yes</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio"  value="0" id="status_id8" name="is_app_attendance_allowed" class="custom-control-input">
                                        <label class="custom-control-label" for="status_id8">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <Button type="submit" class="page-btn">Save</Button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#company_id').on('change', function() {
                if ($(this).val() !== null && $(this).val().length > 0) {
                    if ($(this).val().includes('all')) {
                        // If 'All' is part of the selection along with others, remove others immediately.
                        $(this).val(['all']);
                    }
                }
            });
            // Get the current month and year
            var currentDate = new Date();

            // Set the maximum date for the datepicker
            $('#dateInput').datepicker({
                format: "dd-mm-yyyy",
                defaultDate: currentDate,
                startDate: currentDate,
                orientation: "bottom auto"
            });
        });

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

        function getMultipleBranches() {
            var company_ids = [];
            // Iterate through selected options and extract their values
            $('#company_id option:selected').each(function() {
                company_ids.push($(this).val());
            });

            $.ajax({
                method: 'get',
                dataType: 'json',
                url: '{{ route('get.multiple.branches') }}',
                data: {
                    company_id: company_ids
                },
                success: function(response) {
                    var data = response.data;

                    // Check if data is not empty before updating the branch_id select
                    if (data.length > 0) {
                        $('#branch_id').html('');
                        var html = '<option disabled>Select Location</option><option value="all">All</option>';
                        for (var i = 0; i < data.length; ++i) {
                            html += `<option value="${data[i].id}">${data[i].branch_name}</option>`;
                        }
                        $('#branch_id').html(html);
                        $('#branch_id').selectpicker('refresh');
                    } else {
                        // Handle the case when there is no data to display
                        $('#branch_id').html('<option value="all">All</option>');
                        $('#branch_id').selectpicker('refresh');
                    }
                }
            });
        }
    </script>
@endsection
