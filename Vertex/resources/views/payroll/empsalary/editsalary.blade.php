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
                        <h1 class="text-heading_vt pb-2">Edit Staff Salary</h1>
                    </div>
                    <form  method="POST" action="{{ route('Update.emp_salary', ['id' => $emp_salary->id]) }}">
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
                                            <option value="{{ $company->id }}" {{old('company_id',$emp_salary->company_id == $company->id)?'selected':''}}>{{ ucwords($company->company_name) }}
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
                                    <label for="nameInput" class="form-label">Office Location<span class="red"
                                            style="font-size:14px;">*</span></label>
                                        <select title="Branch" name="branch_id" id="branch_id" required class="form-control"
                                            style="appearance: none;" onchange="getEmployees()">
                                            <option disable selected>Select Location</option>
                                        </select>
                                    <i class="fontello icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="nameInput" class="form-label">Employee<span class="red"
                                            style="font-size:14px;">*</span></label>
                                        <select title="Branch" id="emp_id" name="emp_id" required class="form-control"
                                            style="appearance: none;" aria-placeholder="Select Employee">
                                            <option disable selected value="">Select Employee</option>
                                        </select>
                                    <i class="fontello icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="nameInput" class="form-label">Net Salary<span class="red"
                                            style="font-size:14px;">*</span></label>
                                            <input type="number" class="form-control" value="{{old('net_salary',$emp_salary->net_salary)}}" required name="net_salary" id="net_salary" placeholder="Enter Net-Salary">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nameInput" class="form-label" style="color: red"><strong>Earnings</strong></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nameInput" class="form-label" style="color: red"><strong>Deductions</strong></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nameInput" class="form-label">Basic<span class="red"
                                        style="font-size:14px;">*</span></label>
                                    <input type="number" class="form-control" value="{{old('basic',$emp_salary->basic_salary)}}" name="basic" id="basic" required placeholder="Enter Basic Salary">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nameInput" class="form-label">Tex<span class="red"
                                        style="font-size:14px;">*</span></label> 
                                    <input type="number" class="form-control" value="{{old('tax',$emp_salary->salary_tax)}}" name="tax" id="tax" required placeholder="Enter Tax">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Mobile Allowance<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <div class=" mb-1 position-relative month-field_vt">
                                        <input type="number" value="{{old('mobile_allowance',$emp_salary->mobile_allowance)}}" name="mobile_allowance" id="mobile_allowance" required class="form-control" placeholder="Enter Allowance">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Leave<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <div class=" mb-1 position-relative month-field_vt">
                                        <input type="number" value="{{old('leave_charges',$emp_salary->leave_charges)}}" name="leave_charges" id="leave_charges" required class="form-control" placeholder="Enter Leave Charges">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nameInput" class="form-label">Fuel Allownce<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <input type="number" class="form-control" value="{{old('fuel_allownce',$emp_salary->fuel_allowance)}}" name="fuel_allownce" id="fuel_allownce" required placeholder="Enter Fuel Allownce">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="nameInput" class="form-label">Prof.Tax<span class="red"
                                            style="font-size:14px;">*</span></label>
                                            <input type="number" class="form-control" value="{{old('prof_tax',$emp_salary->prof_tax)}}" name="prof_tax" id="prof_tax" required placeholder="Enter Prof.Tax">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="textarea1" class="form-label">Car Allownce<span class="red"
                                            style="font-size:14px;">*</span></label>
                                            <input type="number" class="form-control" value="{{old('car_allownce',$emp_salary->car_allowance)}}" name="car_allownce" id="car_allownce" required placeholder="Enter Car Allownce">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="textarea1" class="form-label">Other<span class="red"
                                            style="font-size:14px;">*</span></label>
                                            <input type="number" class="form-control" value="{{old('other_charges',$emp_salary->other)}}" name="other_charges" id="other_charges" required placeholder="Enter Other Charges">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="textarea1" class="form-label">Medical Allownce<span class="red"
                                            style="font-size:14px;">*</span></label>
                                            <input type="number" class="form-control" value="{{old('medical_allownce',$emp_salary->medical_allowance)}}" name="medical_allownce" id="medical_allownce" required placeholder="Enter Medical Allownce">
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
                    var storedId = '{{ $emp_salary->branch_id }}'.trim();
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
                    console.log(data)
                    $('#emp_id').html('');
                    var html = '<option selected disabled>Select Employee</option>';
                    var employee = '{{ $emp_salary->emp_id }}'.trim();
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
