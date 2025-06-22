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
                        <h1 class="text-heading_vt pb-2">Add Device</h1>
                    </div>
                    <form id="myform" action="{{ route('save.device') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group position-relative caret-holder">
                                    <label for="company_id">Company<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <select id="companySelect" onchange="getbranch()" class="form-control m-b"
                                        name="company_id" style="appearance: none;">
                                        <option value="" disabled selected>Select Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}" {{old('company_id') == $company->id ?'selected':''}}>{{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                    <i class="awesom-icon icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group position-relative caret-holder">
                                    <label for="branch_id">Office Location<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <select id="branchSelect" class="form-control m-b" name="branch_id"
                                        style="appearance: none;">
                                        <option selected disabled>Select Location</option>
                                    </select>
                                    <i class="awesom-icon icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group ">
                                    <label for="device_name">Device Name<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <input type="text" id="device_name" value="{{old('device_name')}}" name="device_name" class="form-control" placeholder="Enter Device Name">
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group ">
                                    <label for="device_model">Device Model<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <input type="text" id="device_model" value="{{old('device_model')}}" name="device_model" class="form-control" placeholder="Enter Device Model">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group position-relative caret-holder">
                                    <label for="device_type">Device Type<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <select id="device_type" class="form-control m-b" name="device_type"
                                        style="appearance: none;">
                                        <option selected disabled>Select Type</option>
                                        @foreach($deviceTypes as $type)
                                            <option value="{{$type->id}}" {{(old('device_type')==$type->id?'selected':'')}}>{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                    <i class="awesom-icon icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="device_ip">Device IP<span class="red" style="font-size:22px;">*</span></label>
                                    <input type="text" id="device_ip" name="device_ip" value="{{old('device_ip')}}" class="form-control" placeholder="Enter Device IP Address">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="serial_number">Serial Number<span class="red" style="font-size:22px;">*</span></label>
                                    <input type="text" id="serial_number" name="serial_number" value="{{old('serial_number')}}" class="form-control" placeholder="Enter Serial Number">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="heartbeat">Request Heartbeat<span class="red"
                                        style="font-size:22px;">*</span></label>
                                    <input type="text" id="heartbeat" name="heartbeat" value="{{old('heartbeat')}}" class="form-control" placeholder="Enter Heartbeat Seconds">
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group">
                                    <label for="heartbeat">Warranty Expiry<span class="red"
                                        style="font-size:22px;">*</span></label>
                                    <input type="text" id="expiryDate" name="expiryDate" value="{{old('expiryDate',date('d-m-Y',strtotime(now())))}}" class="form-control" placeholder="Select Expiry Date">
                                    <i class="fontello icon-calander1" style="top:35px;"></i>
                                </div>
                            </div>
                            <div class="col-md-12 my-2">
                                <Button type="submit" class="page-btn">Save</Button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            var currentDate = new Date();

            // Set the maximum date for the datepicker
            $('#expiryDate').datepicker({
                format: "dd-mm-yyyy",
                defaultDate: currentDate
            });
        });
        function getbranch() {
            var company_id = $('#companySelect').val();
            $.ajax({
                method: 'get',
                dataType: 'json',
                url: '{{ route('get-branch') }}',
                data: {
                    company_id: company_id
                },
                success: function(response) {
                    var data = response.data;
                    $('#branchSelect').html('');
                    var html = '<option selected disabled>Select Location</option>';
                    for (var i = 0; i < data.length; ++i) {
                        html += `<option value="${data[i].id}">${data[i].branch_name}</option>`;
                    }
                    $('#branchSelect').html(html);
                }
            });
        }
        setTimeout(function() {
            $('#alertID').hide('slow')
        }, 3000);
    </script>
@endsection
