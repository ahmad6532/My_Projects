@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid">
        @if (session('error'))
            <div class="alert alert_vt" id="alertID">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div class="alert alert-danger small " style="max-width:100%;">{{ session('error') }}</div>
            </div>
        @endif
        <div class="row justify-content-center mt-3">
            <div class="col-lg-10 user-form rounded">
                <div class="white-bg border" style="padding:15px;">
                    <div class="text-center">
                        <h1 class="text-heading_vt pb-2">Edit Leave Setup</h1>
                    </div>
                    <form id="myform" action="{{ route('update.setup.setting',['id' => $edit_leave->id]) }}" method="POST">
                        @csrf
                        @method("PUT")
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group position-relative caret-holder">
                                    <label for="title">Company<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <select id="company_id" class="form-control m-b" name="company_id"
                                        style="appearance: none;">
                                        <option value="" disabled selected>Select Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ old('company_id', $company->id == $edit_leave->company_id) ? 'selected' : '' }}>
                                                {{ ucwords($company->company_name) }}</option>
                                        @endforeach
                                    </select>
                                    <i class="awesom-icon icon-down-dir icon-color"></i>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="text-heading_vt pb-1">Annual Leaves</h4>
                                <div class="form-group ">
                                    <label for="title">Days<span class="red" style="font-size:22px;">*</span></label>
                                    <input type="number" id="annualDays" name="annualDays" value="{{ $edit_leave->annual_days }}" class="form-control"
                                        placeholder="Enter Number of Days">
                                </div>
                                <h1 class="text-heading_vt pb-1 pt-2">Carry Forward</h1>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" value="1"
                                        {{ $edit_leave->annual_carry_forward == 1 ? 'checked' : '' }}
                                        id="annual_carry_forward" onclick="annualyesCheck();" name="annual_carry_forward" class="custom-control-input">
                                    <label class="custom-control-label" for="annual_carry_forward">Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" value="0"
                                        {{ $edit_leave->annual_carry_forward == 0 ? 'checked' : '' }}
                                        id="no_annual_carry_forward" onclick="annualyesCheck();" name="annual_carry_forward"
                                        class="custom-control-input">
                                    <label class="custom-control-label" for="no_annual_carry_forward">No</label>
                                </div>
                                <div id="annual_carry_forward_div" class="pt-2" style="display:none">
                                    <div class="form-group ">
                                        <input type="number" id="annual_forwardDays"
                                        value="{{ isset($edit_leave->annual_forward_days) ? $edit_leave->annual_forward_days : '' }}"
                                        name="annual_forwardDays"
                                            class="form-control" placeholder="Enter Number of Forwarded Days">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h4 class="text-heading_vt pb-1">Casual Leaves</h4>
                                <div class="form-group ">
                                    <label for="title">Days<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <input type="number" id="casual_numberOfDays" value = "{{ $edit_leave->casual_days }}"
                                    name="casual_numberOfDays" class="form-control"
                                    placeholder="Enter Number of Days">
                                </div>
                                <h1 class="text-heading_vt pb-1 pt-2">Carry Forward</h1>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" value="1"
                                        {{ $edit_leave->casual_carry_forward == 1 ? 'checked' : '' }}
                                        id="casual_carry_forward" onclick="casualyesCheck();" name="casual_carry_forward"
                                        class="custom-control-input" >
                                    <label class="custom-control-label" for="casual_carry_forward">Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" value="0"
                                        {{ $edit_leave->casual_carry_forward == 0 ? 'checked' : '' }}
                                        id="no_casual_carry_forward" onclick="casualyesCheck();" name="casual_carry_forward"
                                         class="custom-control-input">
                                    <label class="custom-control-label" for="no_casual_carry_forward">No</label>
                                </div>
                                <div id="casual_carry_forward_div" class="pt-2" style="display:none">
                                    <div class="form-group ">
                                        <input type="number" id="casual_forwardDays"
                                        value="{{ isset($edit_leave->casual_forward_days) ? $edit_leave->casual_forward_days : '' }}"
                                        name="casual_forwardDays"
                                            class="form-control" placeholder="Enter Number of Forwarded Days">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="text-heading_vt pb-1">Sick Leaves</h4>
                                <div class="form-group ">
                                    <label for="title">Days<span class="red"
                                            style="font-size:22px;">*</span></label>
                                        <input type="number" id="sick_leave_numberOfDays"
                                            value="{{ $edit_leave->sick_days }}" name="sick_leave_numberOfDays"
                                            class="form-control" placeholder="Enter Number of Days">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h4 class="text-heading_vt pb-1">Maternity Leaves <small
                                        style="color:gray;font-size:9px;">(Applicable For Female)</small></h4>

                                <div class="form-group ">
                                    <label for="title">Days<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <input type="number" id="maternity_numberOfDays"
                                    value="{{ $edit_leave->maternity_days }}" name="maternity_numberOfDays"
                                    class="form-control" placeholder="Enter Number of Days">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 my-2">
                                <Button type="submit" class="page-btn">Update</Button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
             // Get references to the input fields
             const annualDaysInput = document.getElementById('annualDays');
            const casualNumberOfDaysInput = document.getElementById('casual_numberOfDays');
            const sickLeaveNumberOfDaysInput = document.getElementById('sick_leave_numberOfDays');
            annualDaysInput.addEventListener('input', function() {
                const annualDaysValue = parseFloat(annualDaysInput.value);

                // Get the current value of casual leave input
                const casualDaysValue = parseFloat(casualNumberOfDaysInput.value);

                // Get the current value of sick leave input
                const sickLeaveDaysValue = parseFloat(sickLeaveNumberOfDaysInput.value);

                // Calculate the total leave days
                const totalLeaveDays = casualDaysValue + sickLeaveDaysValue;

                // Check if the total leave days exceed the annual leave
                if (totalLeaveDays > annualDaysValue) {
                // Throw an error and set both casual and sick leave to the maximum allowed
                alert('Total leave days cannot exceed annual leave.');
                casualNumberOfDaysInput.value = annualDaysValue - sickLeaveDaysValue;
                }
            });

            // Add an event listener to the casual leave input
            casualNumberOfDaysInput.addEventListener('input', function() {
                // Get the value entered in the casual leave input
                const casualDaysValue = parseFloat(casualNumberOfDaysInput.value);

                // Get the current value of sick leave input
                const sickLeaveDaysValue = parseFloat(sickLeaveNumberOfDaysInput.value);

                // Calculate the total leave days
                const totalLeaveDays = casualDaysValue + sickLeaveDaysValue;

                // Check if the total leave days exceed the annual leave
                if (totalLeaveDays > annualDaysInput.value) {
                // Throw an error and set casual leave to the maximum allowed
                alert('Total leave days cannot exceed annual leave.');
                casualNumberOfDaysInput.value = annualDaysInput.value - sickLeaveDaysValue;
                }
            });

            // Add an event listener to the sick leave input
            sickLeaveNumberOfDaysInput.addEventListener('input', function() {
                // Get the value entered in the sick leave input
                const sickLeaveDaysValue = parseFloat(sickLeaveNumberOfDaysInput.value);

                // Get the current value of casual leave input
                const casualDaysValue = parseFloat(casualNumberOfDaysInput.value);

                // Calculate the total leave days
                const totalLeaveDays = casualDaysValue + sickLeaveDaysValue;

                // Check if the total leave days exceed the annual leave
                if (totalLeaveDays > annualDaysInput.value) {
                // Throw an error and set sick leave to the maximum allowed
                alert('Total leave days cannot exceed annual leave.');
                sickLeaveNumberOfDaysInput.value = annualDaysInput.value - casualDaysValue;
                }
            });
        document.addEventListener('DOMContentLoaded', function() {
            var annualCarryForwardRadio = document.getElementById('annual_carry_forward');
            annualyesCheck();
        });
        function annualyesCheck() {
            if (document.getElementById('annual_carry_forward').checked) {
                document.getElementById('annual_carry_forward_div').style.display = 'block';
            } else if (document.getElementById('no_annual_carry_forward').checked) {
                document.getElementById('annual_carry_forward_div').style.display = 'none';
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            var annualCarryForwardRadio = document.getElementById('casual_carry_forward');
            casualyesCheck();
        });
        function casualyesCheck() {
            if (document.getElementById('casual_carry_forward').checked) {
                document.getElementById('casual_carry_forward_div').style.display = 'block';
            } else if (document.getElementById('no_casual_carry_forward').checked) {
                document.getElementById('casual_carry_forward_div').style.display = 'none';
            }
        }
        setTimeout(function() {
            $('#alertID').hide('slow')
        }, 3000);
    </script>
@endsection
