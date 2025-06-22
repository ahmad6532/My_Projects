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
                        <h1 class="text-heading_vt pb-2">Edit Termination</h1>
                    </div>
                    <form action="{{ route('update.termination') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" name="id" value="{{ $termination_data->id }}">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="nameInput" class="form-label">Company<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <select title="Company" id="company_id" name="company_id" required class="form-control"
                                        style="appearance: none;" onchange="getBranches()">
                                        <option disable selected value="">Select Company</option>
                                        @forelse($companies as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('company_id', $item->id == $termination_data->company_id) ? 'selected' : '' }}>
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
                                    <label for="nameInput" class="form-label">Office Location<span class="red"
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
                                        style="appearance: none;" aria-placeholder="Select Employee">
                                        <option disable selected value="">Select Employee</option>
                                    </select>
                                    <i class="fontello icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="nameInput" class="form-label">Termination Type<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <select title="Termination" name="termination" id="branch_id" required
                                        class="form-control" style="appearance: none;">
                                        <option disable selected>Select Type</option>
                                        <option value="Resignation"
                                            {{ 'Resignation' == $termination_data->termination_type ? 'selected' : '' }}>
                                            Resignation</option>
                                        <option value="Involuntary termination"
                                            {{ 'Involuntary termination' == $termination_data->termination_type ? 'selected' : '' }}>
                                            Involuntary termination</option>
                                        <option value="Constructive dismissal"
                                            {{ 'Constructive dismissal' == $termination_data->termination_type ? 'selected' : '' }}>
                                            Constructive dismissal</option>
                                        <option value="Firing"
                                            {{ 'Firing' == $termination_data->termination_type ? 'selected' : '' }}>Firing
                                        </option>
                                        <option value="Retirement"
                                            {{ 'Retirement' == $termination_data->termination_type ? 'selected' : '' }}>
                                            Retirement</option>
                                        <option value="Mutual termination"
                                            {{ 'Mutual termination' == $termination_data->termination_type ? 'selected' : '' }}>
                                            Mutual termination</option>
                                    </select>
                                    <i class="fontello icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Termination Date <span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <div class=" mb-1 position-relative month-field_vt">
                                        <input type="text" name="termination_date" autocomplete="off" id="dateInput2"
                                            value="{{ old('termination_date', date('d-m-Y', strtotime($termination_data->termination_date))) }}"
                                            class="form-control" placeholder="Select Date">
                                        <i class="fontello icon-calander1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Notice Date <span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <div class=" mb-1 position-relative month-field_vt">
                                        <input type="text" name="notice_date" autocomplete="off" id="dateInput1"
                                            value="{{ old('notice_date', date('d-m-Y', strtotime($termination_data->notice_date))) }}"
                                            class="form-control" placeholder="Select Date">
                                        <i class="fontello icon-calander1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="textarea1" class="form-label">Reason<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <textarea class="form-control" id="textarea1" rows="3" name="reason" placeholder="Some Personal Reason.">{{ $termination_data->reason }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <Button type="submit" class="page-btn">Update</Button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Get the current month and year
            var currentDate = new Date();

            // Set the maximum date for the datepicker
            $('#dateInput2').datepicker({
                format: "dd-mm-yyyy",
                orientation: "bottom auto",
            });

            // Set the maximum date for the datepicker
            $('#dateInput1').datepicker({
                format: "dd-mm-yyyy",
                orientation: "bottom auto",
            });
            getBranches();
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
                    var storedId = '{{ $termination_data->branch_id }}'.trim();
                    var html = '<option selected disabled>Select Location</option>';
                    for (var i = 0; i < data.length; ++i) {
                        var selected = data[i].id.toString() == storedId ? 'selected' : '';
                        html += `<option value="${data[i].id}" ${selected}>${data[i].branch_name}</option>`;
                    }
                    $('#branch_id').html(html);
                    getEmployees();
                }
            });
        }
        setTimeout(function() {
            $('#alertID').hide('slow')
        }, 3000);

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
                    var employee = '{{ $termination_data->emp_id }}'.trim();
                    for (var i = 0; i < data.length; ++i) {
                        var selected = data[i].id == employee ? 'selected' : '';
                        html += `<option value="${data[i].id}" ${selected}>${data[i].emp_name}</option>`;
                    }
                    $('#emp_id').html(html);
                }
            });
        }
    </script>
@endsection
