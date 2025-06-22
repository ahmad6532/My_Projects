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
                        <h1 class="text-heading_vt pb-2">Add Promotion</h1>
                    </div>
                    <form action="{{ route('save.promotion') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="nameInput" class="form-label">Company<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <select title="Company" id="company_id" name="company_id" required class="form-control"
                                        style="appearance: none;" onchange="getBranches()">
                                        <option disable selected value="">Select Company</option>
                                        @forelse($companies as $item)
                                            <option value="{{ $item->id }}" {{ old('company_id') ? 'selected' : '' }}>
                                                {{ ucwords($item->company_name) }}</option>
                                        @empty
                                            <option>No Record Found</option>
                                        @endforelse
                                    </select>
                                    <i class="fontello icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="nameInput" class="form-label">Branch<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <select title="Branch" name="branch_id" id="branch_id" required class="form-control"
                                        style="appearance: none;" onchange="getEmployees()">
                                        <option disable selected>Select Branch</option>
                                    </select>
                                    <i class="fontello icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="nameInput" class="form-label">Employee<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <select title="Employee" id="emp_id" name="emp_id" required class="form-control"
                                        style="appearance: none;" onchange="getDesination()">
                                        <option disable selected value="">Select Employee</option>
                                    </select>
                                    <i class="fontello icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Promotion Date <span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <div class=" mb-1 position-relative month-field_vt">
                                        <input type="text" name="from_date" id="dateInput1" value=""
                                            class="form-control" placeholder="Select Date" autocomplete="off">
                                        <i class="fontello icon-calander1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nameInput" class="form-label">Promotion From<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <input type="text" class="form-control" name="emp_desig" id="emp_desig"
                                        placeholder="Dsignation" readonly>
                                    <input type="hidden" class="form-control" name="emp_desig_id" id="emp_desig_id">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="nameInput" class="form-label">Promotion To<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <select title="Designation" id="designation_id" name="designation_id" required
                                        class="form-control" style="appearance: none;">
                                        <option disable selected value="">Select Designation</option>
                                        @forelse($designations as $item)
                                            <option value="{{ $item->id }}">
                                                {{ ucwords($item->name) }}</option>
                                        @empty
                                            <option>No Record Found</option>
                                        @endforelse
                                    </select>
                                    <i class="fontello icon-down-dir icon-color"></i>
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
                    var html = '<option selected disabled>Select Branch</option>';
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

        function getDesination() {
            var emp_id = $('#emp_id').val();
            $.ajax({
                method: 'get',
                dataType: 'json',
                url: '{{ route('getDesination') }}',
                data: {
                    emp_id: emp_id
                },
                success: function(response) {
                    var data = response.data;
                    console.log(data);
                    $('#emp_desig').val(data.name);
                    $('#emp_desig_id').val(data.id);
                }
            });
        }

        $(document).ready(function() {
            $('#dateInput').change(function() {
                $('#dateSubmit').submit();
            });
        });

        $(document).ready(function() {
            // Get the current month and year
            var currentDate = new Date();

            // Set the maximum date for the datepicker
            $('#dateInput2').datepicker({
                format: "dd-mm-yyyy",
                maxDate: new Date(),
                orientation: "bottom auto"
            });
        });

        $(document).ready(function() {
            // Get the current month and year
            var currentDate = new Date();

            // Set the maximum date for the datepicker
            $('#dateInput1').datepicker({
                format: "dd-mm-yyyy",
                maxDate: new Date(),
                orientation: "bottom auto"
            });
        });
    </script>
@endsection
