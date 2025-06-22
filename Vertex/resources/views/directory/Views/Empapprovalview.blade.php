@extends('layouts.admin.master')
@section('content')
    @php
        $employeeId = 0;
        if (!empty(Session::get('employee_id'))) {
            $employeeId = Session::get('employee_id');
        }
    @endphp
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="nav nav-pills navtab-bg nav-pills-tab text-center justify-content-center"
                                    id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link  mt-2 py-2" id="custom-v-pills-billing-tab"
                                        href="{{ url('view/' . $employeeId, ['edit' => 'null']) }}" role="tab"
                                        aria-controls="custom-v-pills-billing" aria-selected="true">Personal Details
                                    </a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-shipping-tab"
                                        href="{{ url('view-education/' . $employeeId, ['edit' => 'null']) }}" role="tab"
                                        aria-controls="custom-v-pills-shipping" aria-selected="false">Education</a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-payment-tab"
                                        href="{{ url('view-experiences/' . $employeeId, ['edit' => 'null']) }}"
                                        role="tab" aria-controls="custom-v-pills-payment"
                                        aria-selected="false">Employment</a>
                                    <a class="nav-link  mt-2 py-2" id="custom-v-pills-payment1-tab"
                                        href="{{ url('view-refrences/' . $employeeId, ['edit' => 'null']) }}" role="tab"
                                        aria-controls="custom-v-pills-payment2" aria-selected="false">References</a>
                                    <a class="nav-link active show mt-2 py-2" id="custom-v-pills-payment2-tab"
                                        href="#" role="tab" aria-controls="custom-v-pills-payment2"
                                        aria-selected="false">Approvals</a>
                                </div>
                            </div> <!-- end col-->
                            <div class="col-lg-12">
                                <div class="tab-content main-tabs-content">
                                    <div class="tab-pane fade active show" id="custom-v-pills-billing" role="tabpanel"
                                        aria-labelledby="custom-v-pills-billing-tab">
                                        <div class="tab-pane fade active show" id="custom-v-pills-payment2" role="tabpanel"
                                            aria-labelledby="custom-v-pills-payment2-tab">
                                            <div class="border p-2 mt-4">
                                                <h1 class="text-heading_vt text-overlap_vt" style="width:120px;">For
                                                    Office Use</h1>
                                                <form class="p-2"
                                                    action="{{ url('update-employee-approval/' . base64_encode($employee_id)) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="row">
                                                        <div class="col-md-6 mt-2">
                                                            <div class="form-group">
                                                                <label for="billing-last-name">Position <span class="red"
                                                                        style="font-size:12px;">*</span></label>
                                                                <select name="designation_id"
                                                                    class="form-control" required>
                                                                    <option value="" selected disabled>
                                                                        Select Designation</option>
                                                                    @foreach ($designations as $designation)
                                                                        <option value='{{ $designation->id }}' {{ old('designation_id', $EmpApproval->designation_id == $designation->id) ? 'selected' : '' }}>
                                                                            {{ $designation->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('designation_id')
                                                                    <div class="error">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mt-2">
                                                            <div class="form-group position-relative month-field_vt mb-1">
                                                                <label for="billing-last-name">Date of Joining<span class="red"
                                                                        style="font-size:12px;">*</span></label>
                                                                <input name="joining_date" class="form-control"
                                                                        required type="text" id="dateInput2"
                                                                        placeholder="Select Joining Date"
                                                                        value="{{ old('joining_date',date('d-m-Y', strtotime($EmpApproval->joining_date))) }}" />
                                                                        <i class="fontello icon-calander1" style="top:35px;"></i>
                                                                @error('joining_date')
                                                                    <div class="error">{{ $message }}</div>
                                                                @enderror

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mt-2">
                                                            <div class="form-group">
                                                                <label for="billing-last-name">Cell # Issued</label>
                                                                <input name="phone_issued" class="form-control"
                                                                                    type="number" placeholder="Enter Issued Cell"
                                                                                    value="{{ old('phone_issued',$EmpApproval->phone_issued) }}" />
                                                                @error('phone_issued')
                                                                    <div class="error">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mt-2">
                                                            <div class="form-group">
                                                                <label for="billing-last-name">Starting Salary<span class="red"
                                                                        style="font-size:12px;">*</span></label>
                                                                <input name="starting_sal" class="form-control"
                                                                        required type="number"
                                                                        placeholder="Enter Salary"
                                                                        value="{{ old('starting_sal',$EmpApproval->starting_sal) }}" />
                                                                @error('starting_sal')
                                                                    <div class="error">{{ $message }}</div>
                                                                @enderror

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mt-2">
                                                            <div class="form-group">
                                                                <label for="billing-last-name">Company Email</label>
                                                                <input name="emp_email" class="form-control"
                                                                                type="email"
                                                                                placeholder="Enter Company Email"
                                                                                value="{{ old('emp_email',$empDetail->emp_email) }}" />
                                                                @error('emp_email')
                                                                    <div class="error">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mt-2">
                                                            <div class="form-group">
                                                                <label for="billing-last-name">Other Allowances</label>
                                                                <input name="allownces" class="form-control"
                                                                                    type="text" placeholder="Enter Other Allowance"
                                                                                    value="{{ old('allownces') }}" />
                                                                @error('allownces')
                                                                    <div class="error">{{ $message }}</div>
                                                                @enderror

                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <!-- </form> -->
                                            </div>
                                        </div>
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
@endsection
