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
                        <h1 class="text-heading_vt pb-2">Edit Shift</h1>
                    </div>
                    <form action="{{ route('update.shift') }}" method="POST" id="myForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{$shift_detail->id}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="nameInput" class="form-label">Shift Name<span class="red"
                                            style="font-size:14px;">*</span></label>
                                            <input type="text" name="shift_name" value="{{ old('shift_name',$shift_detail->shift_name)}}"  id="shift_name"
                                            autocomplete="off" class="form-control" 
                                            placeholder="Enter Shift Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Start Time <span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <div class=" mb-1 position-relative month-field_vt">
                                        <input type="text" id="dateInput1" value="{{ old('start_time',$shift_detail->start_time)}}" name="start_time" class="form-control" placeholder="Select Time">
                                        <i class="fontello icon-clock-1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">End Time <span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <div class=" mb-1 position-relative month-field_vt">
                                        <input type="text" id="dateInput2" value="{{ old('end_time',$shift_detail->end_time)}}" name="end_time" class="form-control" placeholder="Select Time">
                                        <i class="fontello icon-clock-1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Break (Start Time) <span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <div class=" mb-1 position-relative month-field_vt">
                                        <input type="text" id="dateInput3" value="{{ old('break_start_time',$shift_detail->break_start_time)}}" name="break_start_time" class="form-control" placeholder="Select Time">
                                        <i class="fontello icon-clock-1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Break (End Time) <span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <div class=" mb-1 position-relative month-field_vt">
                                        <input type="text" id="dateInput4" value="{{ old('break_end_time',$shift_detail->break_end_time)}}" name="break_end_time" class="form-control" placeholder="Select Time">
                                        <i class="fontello icon-clock-1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="title">Late Time<span class="red"
                                        style="font-size:22px;">*</span></label>
                                        <div class=" mb-1 position-relative month-field_vt">
                                            <input type="text" id="basic-timepicker5" name="late_time" value="{{old('late_time',$shift_detail->late_time)}}" class="form-control" placeholder="Select Time">
                                            <i class="fontello icon-clock-1"></i>
                                        </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group position-relative caret-holder">
                                    <label for="title">Half Day<span class="red"
                                        style="font-size:22px;">*</span> <span class="text-primary" style="font-size:10px;">(<em>Please enter the number of hours only</em>)</span></label>
                                <input type="number" placeholder="Number Of Hours" name="half_day"  value="{{old('half_day',$shift_detail->half_day)}}"
                                    class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group ">
                                    <label for="title">Working Hours</label>
                                    <input type="readonly" id="working_hours" disabled class="form-control" placeholder="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="nameInput" class="form-label">Recurring</label>
                                    <select name="is_recurring" id="is_recurring" required
                                        class="form-control" style="appearance: none;">
                                        <option disabled>Select Recurring</option>
                                        <option value="1" {{ ($shift_detail->break_end_time == 1 ? 'selected':'') }}>Yes</option>
                                        <option value="0" {{ ($shift_detail->break_end_time == 0 ? 'selected':'') }}>No</option>
                                    </select>
                                    <i class="fontello icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="textarea1" class="form-label">Add Note<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <textarea class="form-control" id="textarea1" name="note" rows="3" placeholder="Enter Note">{{ $shift_detail->note }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-2">
                                <div class="form-group mb-0">
                                    <label for="title" class="mb-0">Working Days<span class="red"
                                        style="font-size:14px;">*</span></label>
                                </div>
                                <div class="days-holder">
                                    <div class="d-flex align-items-center p-1">
                                        <input id="monday" {{ strpos($shift_detail->working_days, 'Monday') !== false ? 'checked' : '' }} type="checkbox" name="selectedDays[]" value="Monday">
                                        <label for="monday" class="checkbox-text_vt">Monday</label>
                                    </div>
                                    <div class="d-flex align-items-center p-1">
                                        <input id="tuesday" {{ strpos($shift_detail->working_days, 'Tuesday') !== false ? 'checked' : '' }} type="checkbox" name="selectedDays[]" value="Tuesday">
                                        <label for="tuesday" class="checkbox-text_vt">Tuesday</label>
                                    </div>
                                    <div class="d-flex align-items-center p-1">
                                        <input id="wednesday" type="checkbox" {{ strpos($shift_detail->working_days, 'Wednesday') !== false ? 'checked' : '' }}  name="selectedDays[]" value="Wednesday">
                                        <label for="wednesday" class="checkbox-text_vt">Wednesday</label>
                                    </div>
                                    <div class="d-flex align-items-center p-1">
                                        <input id="thursday" type="checkbox" {{ strpos($shift_detail->working_days, 'Thursday') !== false ? 'checked' : '' }} name="selectedDays[]" value="Thursday">
                                        <label for="thursday" class="checkbox-text_vt">Thursday</label>
                                    </div>
                                    <div class="d-flex align-items-center p-1">
                                        <input id="friday" type="checkbox" {{ strpos($shift_detail->working_days, 'Friday') !== false ? 'checked' : '' }} name="selectedDays[]" value="Friday">
                                        <label for="friday" class="checkbox-text_vt">Friday</label>
                                    </div>
                                    <div class="d-flex align-items-center p-1">
                                        <input id="saturday" type="checkbox" name="selectedDays[]" {{ strpos($shift_detail->working_days, 'Saturday') !== false ? 'checked' : '' }} value="Saturday">
                                        <label for="saturday" class="checkbox-text_vt">Saturday</label>
                                    </div>
                                    <div class="d-flex align-items-center p-1">
                                        <input id="sunday" type="checkbox" {{ strpos($shift_detail->working_days, 'Sunday') !== false ? 'checked' : '' }} name="selectedDays[]" value="Sunday">
                                        <label for="sunday" class="checkbox-text_vt">Sunday</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
                                <a href="{{route('shift.index')}}" style="padding: 11px 35px;" class="page-btn page-btn-outline hover-btn">Back</a>
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
            $('#dateInput').change(function() {
                $('#dateSubmit').submit();
            });

            // Get the current month and year
            var currentDate = new Date();

            // Set the maximum date for the datepicker
            $('#dateInput2').datepicker({
                format: "dd-mm-yyyy",
                orientation: "bottom auto",
                defaultDate: currentDate,
            });

            // Set the maximum date for the datepicker
            $('#dateInput1').datepicker({
                format: "dd-mm-yyyy",
                orientation: "bottom auto",
                defaultDate: currentDate,
            });
        });

        
    </script>
@endsection
