@extends('layouts.admin.master')
@section('content')

<div class="container-fluid">
    @if($errors->has("from_date"))
        <div class="alert alert_vt" id="alertID">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <div class="alert alert-danger small " style="max-width:100%;">{{ $errors->first('from_date') }}</div>
        </div>
        @endif
        <div class="row justify-content-center mt-3">
            <div class="col-lg-10 user-form rounded">
                <div class="white-bg border" style="padding:15px;">
                    <div class="text-center">
                        <h1 class="text-heading_vt pb-2">Add Leave Request</h1>
                    </div>
                    <form  method="POST" action="{{ route('save.leave.request') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="nameInput" class="form-label">Company<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <select name="company_id" onchange="getBranches()" id="company_id"
                                        required class="form-control" style="appearance: none;">
                                        <option disable selected>Select Company</option>
                                        @forelse ($companies as $company)
                                            <option value="{{ $company->id }}">{{ ucwords($company->company_name) }}
                                            </option>
                                        @empty
                                            <option>No Record Found</option>
                                        @endforelse
                                    </select>
                                    <i class="fontello icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="nameInput" class="form-label">office Location<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <select name="branch_id" onchange="getEmployees()" id="branch_id"
                                        required class="form-control" style="appearance: none;">
                                        <option disable selected>Select Location</option>
                                    </select>
                                    <i class="fontello icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="nameInput" class="form-label">Employee<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <select title="Employee" id="emp_id" name="emp_id" required onchange="getTotalRemainingLeaves()"
                                        class="form-control" style="appearance: none;" aria-placeholder="Select Employee">
                                        <option disable selected value="">Select Employee</option>
                                    </select>
                                    <i class="fontello icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="nameInput" class="form-label">Leave Type<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <select id="leave_type" name="leave_type" required onchange="getRemainingLeaves()" 
                                        class="form-control" style="appearance: none;">
                                        <option disable selected value="">Select Leave Type</option>
                                        @forelse ($Leave_types as $type)
                                            <option value="{{$type->id}}">{{ucwords($type->types)}}</option>
                                        @empty
                                            <option>No data found</option>
                                        @endforelse
                                    </select>
                                    <i class="fontello icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nameInput" class="form-label"><span id="leaveTitle">Remaining Leaves</span></label>
                                    <input type="text" class="form-control" value="" name="remaining_leaves" id="remaining_leaves" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nameInput" class="form-label">Total Remaining Leaves</label>
                                    <input type="text" class="form-control" value="" id="total_remaining_leaves" disabled>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">From<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <div class=" mb-1 position-relative month-field_vt">
                                        <input type="text" name="from_date" id="dateInput1" onchange="changeDates()" value="{{date('d-m-Y')}}" class="form-control" placeholder="Select Date">
                                        <i class="fontello icon-calander1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">To<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <div class=" mb-1 position-relative month-field_vt">
                                        <input type="text" name="to_date" id="dateInput2" onchange="changeDates()" required value="{{date('d-m-Y')}}" class="form-control" placeholder="Select Date">
                                        <i class="fontello icon-calander1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nameInput" class="form-label">Approved Days<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <input type="number" class="form-control" name="approved_days" id="approved_days" readonly required placeholder="Enter Days">
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="nameInput" class="form-label">Approved By<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <select name="approved_by"  id="approved_by"
                                        required class="form-control" style="appearance: none;">
                                            <option disable selected>Select Approval</option>
                                            <option value="CEO">CEO</option>
                                            <option value="Admin">Admin</option>
                                            <option value="PM">Project Manager</option>
                                    </select>
                                    <i class="fontello icon-down-dir icon-color"></i>
                                </div>
                            </div> --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="textarea1" class="form-label">Remarks<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <textarea class="form-control" name="remarks" id="textarea1" rows="3" required placeholder="I want to apply leave for specific reason."></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <Button type="submit" class="page-btn">Save</Button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script>
    function changeDates() {
        var fromDateInput = document.getElementById('dateInput1');
        var toDateInput = document.getElementById('dateInput2');
        var approvedDaysInput = document.getElementById('approved_days');

        var fromDateValue = fromDateInput.value;
        var toDateValue = toDateInput.value;

        var fromDateComponents = fromDateValue.split('-');
        var toDateComponents = toDateValue.split('-');

        var fromDay = parseInt(fromDateComponents[0], 10);
        var fromMonth = parseInt(fromDateComponents[1], 10) - 1;
        var fromYear = parseInt(fromDateComponents[2], 10);

        var toDay = parseInt(toDateComponents[0], 10);
        var toMonth = parseInt(toDateComponents[1], 10) - 1;
        var toYear = parseInt(toDateComponents[2], 10);

        var fromDate = new Date(fromYear, fromMonth, fromDay);
        var toDate = new Date(toYear, toMonth, toDay);

        if (!isNaN(fromDate) && !isNaN(toDate)) {
            var timeDiff = toDate - fromDate;
            var dayDiff = timeDiff / (1000 * 3600 * 24)+1;
            approvedDaysInput.value = dayDiff;
        } else {
            approvedDaysInput.value = ''; // Clear the field if dates are invalid
        }
    }
    $(document).ready(function() {
        changeDates()
        $('#dateInput').change(function() {
            $('#dateSubmit').submit();
        });

        // Get the current month and year
        var currentDate = new Date();

        // Set the maximum date for the datepicker
        $('#dateInput2').datepicker({
            format: "dd-mm-yyyy",
            maxDate: new Date()
        });

        // Set the maximum date for the datepicker
        $('#dateInput1').datepicker({
            format: "dd-mm-yyyy",
            maxDate: new Date()
        });
    });

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

    function getEmployees() {
        var branch_id = $('#branch_id').val();
        $.ajax({
            method: 'get',
            dataType: 'json',
            url: '{{ route('get.branch.employees') }}',
            data: {
                branch_id: branch_id
            },
            success: function(response) {
                var data = response.data;
                $('#emp_id').html('');
                var html = '<option selected disabled>Select Employee</option>';
                for (var i = 0; i < data.length; ++i) {
                    html += `<option value="${data[i].id}">${data[i].emp_name}</option>`;
                }
                $('#emp_id').html(html);
            }
        });
    }
    function getTotalRemainingLeaves() {
        var emp_id = $('#emp_id').val();
        var company_id = $('#company_id').val();
        $.ajax({
            method: 'get',
            dataType: 'json',
            url: '{{ route('getTotalRemainingLeaves') }}',
            data: {
                emp_id: emp_id,
                company_id: company_id
            },
            success: function(response) {
                $('#total_remaining_leaves').val(response.totalremainingLeaves);
            }
        });
    }

    function getRemainingLeaves() {
        var emp_id = $('#emp_id').val();
        var leave_type = $('#leave_type').val();
        var company_id = $('#company_id').val();
        $.ajax({
            method: 'get',
            dataType: 'json',
            url: '{{ route('getRemainingLeaves') }}',
            data: {
                emp_id: emp_id,
                leave_type: leave_type,
                company_id: company_id
            },
            success: function(response) {
                // if(response.remainingLeaves == 0){
                //     $('#approved_days').prop('disabled', true).prop('title', "You can't get more leaves");
                // }else{
                //     $('#approved_days').prop('disabled', false);
                // }
                $('#leaveTitle').html(response.leaveTitle + ' Leaves');
                $('#remaining_leaves').val(response.remainingLeaves);
            }
        });
    }
</script>

@endsection
