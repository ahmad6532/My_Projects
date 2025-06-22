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
        @if (session('error'))
            <div class="alert alert_vt" id="alertID">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div class="alert alert-danger small " style="max-width:100%;">{{ session('error') }}</div>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert_vt" id="alertID">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div class="alert alert-success small " style="max-width:100%;">{{ session('success') }}</div>
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
                                    <a class="nav-link  mt-2 py-2" id="custom-v-pills-billing-tab"
                                        href="{{ url('employee/directory/edit-employee/' . base64_encode($employeeId)) }}"
                                        role="tab" aria-controls="custom-v-pills-billing" aria-selected="true">Personal
                                        Details
                                    </a>
                                    <a class="nav-link show mt-2 py-2" id="custom-v-pills-shipping-tab"
                                        href="{{ url('employee/directory/edit-education/' . base64_encode($employeeId)) }}"
                                        role="tab" aria-controls="custom-v-pills-shipping"
                                        aria-selected="false">Education</a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-payment-tab"
                                        href="{{ url('employee/directory/edit-experiences/' . base64_encode($employeeId)) }}"
                                        role="tab" aria-controls="custom-v-pills-payment"
                                        aria-selected="false">Employment</a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-payment1-tab"
                                        href="{{ url('employee/directory/edit-refrences/' . base64_encode($employeeId)) }}"
                                        role="tab" aria-controls="custom-v-pills-payment2"
                                        aria-selected="false">References</a>
                                    <a class="nav-link active mt-2 py-2" id="custom-v-pills-payment1-tab" href="#"
                                        role="tab" aria-controls="custom-v-pills-payment2"
                                        aria-selected="false">Account</a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-payment2-tab"
                                        href="{{ url('employee/directory/edit-approval/' . base64_encode($employeeId)) }}"
                                        role="tab" aria-controls="custom-v-pills-payment2"
                                        aria-selected="false">Approvals</a>
                                </div>
                            </div> <!-- end col-->

                            <div class="col-lg-12">
                                <div class="tab-content main-tabs-content">
                                    <div class="tab-pane fade active show" id="custom-v-pills-billing" role="tabpanel"
                                        aria-labelledby="custom-v-pills-billing-tab">
                                        <div class="border p-2">
                                            <h1 class="text-heading_vt text-overlap_vt" style="width:90px;">Account</h1>
                                            <form id="myform"  class="p-2 mt-2"
                                                action="{{ url('update-account-detail/' . base64_encode($employeeId)) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="edit" value="preview">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="billing-last-name">Bank Name : <span class="red"
                                                                    style="font-size:22px;">*</span></label>
                                                            <input name="bank_name" class="form-control" type="text"
                                                                required
                                                                value="{{ $accountDetails ? $accountDetails->bank_name : '' }}"
                                                                placeholder="Enter Your Bank Name" id="billing-last-name" />
                                                            @error('bank_name')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="billing-name">Account No : <span class="red"
                                                                    style="font-size:22px;">*</span></label>
                                                            <input name="account_number" class="form-control" required
                                                                type="number"
                                                                value="{{ $accountDetails ? $accountDetails->account_no : '' }}"
                                                                placeholder="Enter Your Account No" />
                                                            @error('account_number')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                </div> <!-- end row -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="billing-phone">IFSC Code : <span class="red"
                                                                    style="font-size:22px;">*</span></label>
                                                            <input name="ifsc_code" class="form-control" type="text"
                                                                required
                                                                value="{{ $accountDetails ? $accountDetails->ifsc_code : '' }}"
                                                                placeholder="Enter IFSC Code" />
                                                            @error('ifsc_code')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="billing-phone">PAN No : <span class="red"
                                                                    style="font-size:22px;">*</span></label>
                                                            <input name="pan" class="form-control" type="text"
                                                                required
                                                                value="{{ $accountDetails ? $accountDetails->pan_no : '' }}"
                                                                placeholder="Enter PAN No" />
                                                            @error('pan')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="pt-4 pb-2">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <a href="{{ url('/employee/directory/add-references/' . base64_encode($employeeId) . '/preview') }}"
                                                                        class="page-btn page-btn-outline hover-btn">Previous</a>
                                                                    <Button type="submit" name="submit"
                                                                        class="page-btn submit_btn ">Save &
                                                                        Continue</Button>
                                                                </div>
                                                            </div> <!-- end row -->
                                                        </div>
                                                    </div>
                                            </form>
                                        </div> <!-- end col-->
                                        <a href="{{ route('add.approval') }}">
                                            <Button class="page-btn page-btn-outline hover-btn">Skip</Button>
                                        </a>
                                    </div> <!-- end row-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
            </div>
            <script type="text/javascript">
                setTimeout(function() {
                    $('#alertID').hide('slow')
                }, 3000);
            </script>
        @endsection
