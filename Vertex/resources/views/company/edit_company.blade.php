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
                        <h1 class="text-heading_vt pb-2">Edit Company Settings</h1>
                    </div>
                    <form id="myform" action="{{ url('update-company/' . $companySettings->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group position-relative caret-holder">
                                    <label for="title">Company<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <select id="company_id" onchange="getBranch()" class="form-control m-b"
                                        name="company_id" style="appearance: none;">
                                        <option value="" disabled selected>Select Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ $company->id == $companySettings->company_id ? 'selected' : '' }}>
                                                {{ $company->company_name }}</option>
                                        @endforeach

                                    </select>
                                    <i class="awesom-icon icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group position-relative caret-holder">
                                    <label for="title">Office Location<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <select id="branch_id" class="form-control m-b" name="branch_id"
                                        style="appearance: none;">
                                        <option selected disabled>Select Location</option>
                                    </select>
                                    <i class="awesom-icon icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group">
                                    <label for="title">Working Hours (Start Time)<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <input type="text" id="office_start_time" name="start_time" value="{{ $companySettings->start_time }}" class="form-control" placeholder="Select Time">
                                    <i class="fontello icon-clock-1"></i>
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group">
                                    <label for="title">Working Hours (End Time)<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <input type="text" id="office_end_time" name="end_time" value="{{ $companySettings->end_time }}" class="form-control" placeholder="Select Time">
                                    <i class="fontello icon-clock-1"></i>
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group">
                                    <label for="title">Lunch (Start Time)<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <input type="text" id="basic-timepicker" name="lunch_start_time" value="{{ $companySettings->lunch_start_time }}" class="form-control" placeholder="Select Time">
                                    <i class="fontello icon-clock-1"></i>
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group">
                                    <label for="title">Lunch (End Time)<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <input type="text" id="lunch_end_time" name="lunch_end_time" value="{{ $companySettings->lunch_end_time }}" class="form-control" placeholder="Select Time">
                                    <i class="fontello icon-clock-1"></i>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group position-relative caret-holder">
                                    <label for="title">Flexible Time<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <select id="" class="form-control m-b" name="status"
                                        style="appearance: none;">
                                        <option selected disabled>Select Flexible</option>
                                        <option value="1"
                                            {{ $companySettings->flexible_time == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0"
                                            {{ $companySettings->flexible_time == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    <i class="awesom-icon icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group">
                                    <label for="title">Late Time<span class="red"
                                        style="font-size:22px;">*</span></label>
                                    <input type="text" id="basic-timepicker2" value="{{ $companySettings->late_time }}" name="late_time" class="form-control" placeholder="Select Time">
                                    <i class="fontello icon-clock-1"></i>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group position-relative caret-holder">
                                    <label for="title">Half Day<span class="red"
                                        style="font-size:22px;">*</span> <span class="text-primary" style="font-size:10px;">(<em>Please enter the number of hours only</em>)</span></label>
                                <input type="number" placeholder="Number Of Hours" value="{{ $companySettings->half_day }}" name="half_day"
                                    class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-12 mb-2">
                                <div class="form-group mb-0">
                                    <label for="title" class="mb-0">Working Days</label>
                                </div>
                                <div class="days-holder">
                                    <div class="d-flex align-items-center p-1">
                                        <input id="monday" {{ strpos($companySettings->days, 'Monday') !== false ? 'checked' : '' }} type="checkbox" name="selectedDays[]" value="Monday">
                                        <label for="monday" class="checkbox-text_vt">Monday</label>
                                    </div>
                                    <div class="d-flex align-items-center p-1">
                                        <input id="tuesday" {{ strpos($companySettings->days, 'Tuesday') !== false ? 'checked' : '' }} type="checkbox" name="selectedDays[]" value="Tuesday">
                                        <label for="tuesday" class="checkbox-text_vt">Tuesday</label>
                                    </div>
                                    <div class="d-flex align-items-center p-1">
                                        <input id="wednesday" type="checkbox" {{ strpos($companySettings->days, 'Wednesday') !== false ? 'checked' : '' }}  name="selectedDays[]" value="Wednesday">
                                        <label for="wednesday" class="checkbox-text_vt">Wednesday</label>
                                    </div>
                                    <div class="d-flex align-items-center p-1">
                                        <input id="thursday" type="checkbox" {{ strpos($companySettings->days, 'Thursday') !== false ? 'checked' : '' }} name="selectedDays[]" value="Thursday">
                                        <label for="thursday" class="checkbox-text_vt">Thursday</label>
                                    </div>
                                    <div class="d-flex align-items-center p-1">
                                        <input id="friday" type="checkbox" {{ strpos($companySettings->days, 'Friday') !== false ? 'checked' : '' }} name="selectedDays[]" value="Friday">
                                        <label for="friday" class="checkbox-text_vt">Friday</label>
                                    </div>
                                    <div class="d-flex align-items-center p-1">
                                        <input id="saturday" type="checkbox" name="selectedDays[]" {{ strpos($companySettings->days, 'Saturday') !== false ? 'checked' : '' }} value="Saturday">
                                        <label for="saturday" class="checkbox-text_vt">Saturday</label>
                                    </div>
                                    <div class="d-flex align-items-center p-1">
                                        <input id="sunday" type="checkbox" {{ strpos($companySettings->days, 'Sunday') !== false ? 'checked' : '' }} name="selectedDays[]" value="Sunday">
                                        <label for="sunday" class="checkbox-text_vt">Sunday</label>
                                    </div>
                                </div>
                            </div>
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
    $(document).ready(function() {
        getBranch();
    });
    
    function getBranch() {
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
                var branch_id = '{{$companySettings->branch_id}}';
                var html = '<option selected disabled>Select Location</option>';
                for (var i = 0; i < data.length; ++i) {
                    var selected = (data[i].id == branch_id ? 'selected':'');
                    html += `<option value="${data[i].id}" ${selected}>${data[i].branch_name}</option>`;
                }
                $('#branch_id').html(html);
            }
        });
    }

    setTimeout(function() {
        $('#alertID').hide('slow')
    }, 3000);
</script>
@endsection
