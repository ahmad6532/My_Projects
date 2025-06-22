@extends('layouts.admin.master')
@section('content')
    <style>
        #table1_filter {
            display: none;
        }

    </style>

    <div class="Datatable-content-area mt-2 dataTable-section">
        @if (session('success'))
            <div class="alert alert_vt" id="alertID">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div class="alert alert-success small " style="max-width:100%;">{{ session('success') }}</div>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert_vt" id="alertID">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div class="alert alert-danger small " style="max-width:100%;">{{ session('error') }}</div>
            </div>
        @endif
        <div class='row'>
            <div class='col-lg-3 p-3'>
                <h1 class='subtitle_vt'>Employee Attendance Sheet</h1>
                <p class='head-para_vt'>{{ date('d F Y', strtotime($current_date)) }}</p>
            </div>
            <div class='col-lg-9 p-3'>
                <div class="row justify-content-end">
                    <div class="col-lg-2 col-md-2 px-1 mb-2" style="padding-right:2px !important;">
                        <div class="d-flex">
                            <div class="w-100">
                                @if ($user->haspermission(['daily-all','daily-write']))
                                <button name="submit" type="submit" class="page-btn" data-toggle="modal"
                                    data-target="#myModal1" style="min-width:100%;">Add Manually</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 px-1 mb-2">
                        <form action="{{ route('daily.attend.sheet') }}" id="dateSubmit" method="get">
                            <input type="hidden" name="branch_id" value="{{$selected}}">
                            <div class=" mb-1 position-relative month-field_vt">
                                <input type="text" name="searchDate" id="dateInput" value="{{$current_date}}" class="form-control"
                                    placeholder="Select Date">
                                <i class="fontello icon-calander1"></i>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-3 col-md-3 mb-2" style="padding-left:2px !important;padding-right:2px !important;">
                        <form method="GET" id="myForm" action="{{ route('daily.attend.sheet') }}">
                            <input type="hidden" name="searchDate" value="{{$current_date}}">
                            <div class="form-group position-relative caret-holder px-1">
                                <select class="form-control m-b" id="selectBranch" name="branch_id" required=""
                                    style="appearance: none;">
                                    <option value="" disabled="">Select Location</option>
                                    <option value="all" {{ $selected == 'all' ? 'selected' : '' }}>All</option>
                                    @forelse ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ $selected == $branch->id ? 'selected' : '' }}>
                                            {{ ucwords($branch->branch_name) }}</option>
                                    @empty
                                        <option>No Record Found</option>
                                    @endforelse
                                </select>
                                <i class="awesom-icon icon-down-dir icon-color"></i>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-3 col-md-3 px-1 mb-2"
                        style="padding-left:2px !important;padding-right:2px !important;">
                        <div class="search-icon p-0">
                            <input type="text" class="form-control" onkeyup="searchData()" name="search_input"
                                        id="searchID" value="" placeholder="Search Name or ID">
                                    <a> <i class="fontello icon-search"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table id="table1" class="table table-bordered table-striped table-nowrap table-hover table-centered m-0 table-atten-sheet">
                <thead>
                    <tr>
                        <th>Sr</th>
                        <th>Emp ID</th>
                        <th>Name</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>WH</th>
                        <th>Leave Type</th>
                        <th></th>
                        <th>GeoTrack</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $iteration = 1; ?>
                    @forelse ($employees as $key => $employee)
                        @if((isset($employee->approval->joining_date) && Date("Y-m-d",strtotime($employee->approval->joining_date)) <= Date("Y-m-d", strtotime($current_date)) ))
                            @if( isset($employee->resignations) && isset($employee->resignations->is_approved) == '1' ? Date("Y-m-d",strtotime($employee->resignations->resignation_date)) >= Date("Y-m-d", strtotime($current_date)) : true)
                                @if( isset($employee->terminations) && isset($employee->terminations->is_approved) == '1' ? Date("Y-m-d",strtotime($employee->terminations->resignation_date)) >= Date("Y-m-d", strtotime($current_date)) : true)
                                    <tr>
                                        <td>{{ $iteration }}</td>
                                        <td>
                                            @php
                                                if ($employee->emp_image) {
                                                    $imagePath = public_path($employee->emp_image);

                                                    if (File::exists($imagePath)) {
                                                        $employee->emp_image = asset($employee->emp_image);
                                                    } else {
                                                        if ($employee->emp_gender == 'F') {
                                                            $employee->emp_image = asset('assets/images/female.png');
                                                        } else {
                                                            $employee->emp_image = asset('assets/images/male.png');
                                                        }
                                                    }
                                                } else {
                                                    // If emp_image is empty, set a default image based on gender
                                                    if ($employee->emp_gender == 'F') {
                                                        $employee->emp_image = asset('assets/images/female.png');
                                                    } else {
                                                        $employee->emp_image = asset('assets/images/male.png');
                                                    }
                                                }
                                            @endphp
                                            <table>
                                                <tr style="background:transparent;">
                                                    <td class="border-0 p-0" style="border:0 !important;"><img class='table-img_vt'
                                                            src="{{ asset($employee->emp_image) }}"></td>
                                                    <td class="border-0 p-0" style="border:0 !important;">{{ $employee->emp_id }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td><a class=""
                                            href="{{ url('/employee/directory/employee-profile/' . base64_encode($employee->id)) }}">{{ mb_convert_case($employee->emp_name, MB_CASE_TITLE, 'UTF-8') }}
                                        </a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-between">
                                            @php
                                                if (count($employee['user_attendance']) > 0) {
                                                    $checkIn = $employee['user_attendance'][0]['check_in'];
                                                    echo date('h:i A', strtotime($checkIn));
                                                } else {
                                                    echo '';
                                                }
                                            @endphp
                                             @php
                                             if (count($employee['user_attendance']) > 0) {
                                                 $status = $employee['user_attendance'][0]['check_in_status'];
                                                 if($status != null){
                                                    if($status == 'Pin'){
                                                        echo '<i class="fa-solid fa-keyboard" title="Pin" style="color: var(--btn-bg);font-size: 15px;margin-bottom:0px;"></i>';
                                                    }elseif($status == 'Face'){
                                                        echo '<img src="/assets/images/face_scan.png" title="Face" width="20px"/>';
                                                    }elseif($status == 'FP'){
                                                        echo '<i class="fa-solid fa-fingerprint" title="FingerPrint" style="color: var(--btn-bg);font-size: 15px;margin-bottom:0px;"></i>';
                                                    }elseif($status == 'M'){
                                                        echo '<i class="fa-solid fa-laptop" title="Manual" style="color: var(--btn-bg);"></i>';
                                                    }elseif($status == 'M/Pin'){
                                                        echo '<i class="fa-solid fa-mobile-screen-button" title="Mobile" style="color: var(--btn-bg);font-size: 15px;margin-bottom:0px;"></i><i class="fa-solid fa-keyboard" title="Pin" style="color: var(--btn-bg);font-size: 15px;margin-bottom:0px;"></i>';
                                                    }elseif($status == 'M/Face'){
                                                        echo '<i class="fa-solid fa-mobile-screen-button" title="Mobile" style="color: var(--btn-bg);font-size: 15px;margin-bottom:0px;"></i><img src="/assets/images/face_scan.png" title="Face" width="20px"/>';
                                                    }
                                                }
                                             } else {
                                                 echo '';
                                             }
                                             @endphp
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-between">
                                                @php
                                                    if (count($employee['user_attendance']) > 0) {
                                                        $checkout = $employee['user_attendance'][0]['check_out'];
                                                        if ($checkout == null) {
                                                            echo '';
                                                        } else {
                                                            echo date('h:i A', strtotime($checkout));
                                                        }
                                                    } else {
                                                        echo '';
                                                    }
                                                @endphp
                                                @php
                                                if (count($employee['user_attendance']) > 0) {
                                                    $status = $employee['user_attendance'][0]['check_out_status'];
                                                    if($status != null){
                                                        if($status == 'Pin'){
                                                            echo '<i class="fa-solid fa-keyboard" title="Pin" style="color: var(--btn-bg);font-size: 15px;margin-bottom:0px;"></i>';
                                                        }elseif($status == 'Face'){
                                                            echo '<img src="/assets/images/face_scan.png" title="Face" width="20px"/>';
                                                        }elseif($status == 'FP'){
                                                            echo '<i class="fa-solid fa-fingerprint" title="FingerPrint" style="color: var(--btn-bg);font-size: 15px;margin-bottom:0px;"></i>';
                                                        }elseif($status == 'M'){
                                                            echo '<i class="fa-solid fa-laptop" title="Manual" style="color: var(--btn-bg);"></i>';
                                                        }elseif($status == 'M/Pin'){
                                                        echo '<i class="fa-solid fa-mobile-screen-button" title="Mobile" style="color: var(--btn-bg);font-size: 15px;margin-bottom:0px;"></i><i class="fa-solid fa-keyboard" title="Pin" style="color: var(--btn-bg);font-size: 15px;margin-bottom:0px;"></i>';
                                                        }elseif($status == 'M/Face'){
                                                            echo '<i class="fa-solid fa-mobile-screen-button" title="Mobile" style="color: var(--btn-bg);font-size: 15px;margin-bottom:0px;"></i><img src="/assets/images/face_scan.png" title="Face" width="20px"/>';
                                                        }
                                                    }
                                                } else {
                                                    echo '';
                                                }
                                                @endphp
                                            </div>
                                        </td>
                                        <td>
                                            @foreach ($attendanceData as $attendance)
                                                @if ($attendance['employee_id'] == $employee->id)
                                                {{ ($attendance['workingHours']?$attendance['workingHours']:'') }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            -
                                        </td>
                                        <td>
                                            @foreach ($attendanceData as $attendance)
                                                @if ($attendance['employee_id'] == $employee->id)
                                                    @if ($attendance['attendance'] == 'Full Day')
                                                        <span class="green">Present</span>
                                                    @elseif ($attendance['attendance'] == 'Present')
                                                        <span class="green">Present</span>
                                                    @elseif($attendance['attendance'] == 'Late')
                                                        <span class="green">Present</span>
                                                    @elseif($attendance['attendance'] == 'Half Leave')
                                                        <span class="yellow">Half Leave</span>
                                                    @elseif($attendance['attendance'] == 'Absent')
                                                        <span class="red">Absent</span>
                                                    @elseif($attendance['attendance'] == 'Leave')
                                                        <span class="yellow">Leave</span>
                                                    @elseif($attendance['attendance'] == 'Holiday')
                                                        <span class="yellow">Holiday</span>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            <table>
                                                <tr style="background:transparent;">
                                                    <td class="p-0 border-0" style="border:0 !important;">
                                                        <div class="btn-group dropdown-btn-group dropleft show">
                                                            <button type="button" class="dropdown-toggle track-btn"
                                                            data-toggle="dropdown" aria-expanded="true"><i
                                                                class="fontello icon-location"></i></button>
                                                            <ul class="dropdown-menu" style="padding:15px;">
                                                                <li class="checkbox-row  ">
                                                                    <h1 class="track-title">Check In Address :</h1>
                                                                    @foreach ($attendanceData as $attendance)
                                                                        @if ($attendance['employee_id'] == $employee->id)
                                                                        <p class="track-subtitle">{{ ($attendance['check_in_address']?$attendance['check_in_address']:'') }}</p>
                                                                        @endif
                                                                    @endforeach
                                                                </li>
                                                                <li class="checkbox-row ">
                                                                    <h1 class="track-title">Check Out Address :</h1>
                                                                    @foreach ($attendanceData as $attendance)
                                                                        @if ($attendance['employee_id'] == $employee->id)
                                                                        <p class="track-subtitle">{{ ($attendance['check_out_address']?$attendance['check_out_address']:'') }}</p>
                                                                        @endif
                                                                    @endforeach
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <td class="p-0 border-0" style="border:0 !important;">
                                                        <div class="btn-group dropdown-btn-group dropleft show px-1">
                                                            <button type="button" class="dropdown-toggle track-btn"
                                                                data-toggle="dropdown" aria-expanded="true"><img
                                                                    src="{{ asset('assets/images/ip-img.png') }}"></button>
                                                            <ul class="dropdown-menu" style="padding:15px;">
                                                                <li class="checkbox-row  ">
                                                                    <h1 class="track-title">Check In IP Address :</h1>
                                                                    @foreach ($attendanceData as $attendance)
                                                                        @if ($attendance['employee_id'] == $employee->id)
                                                                        <p class="track-subtitle">{{ ($attendance['check_in_ip_address']?$attendance['check_in_ip_address']:'') }}</p>
                                                                        @endif
                                                                    @endforeach
                                                                </li>
                                                                <li class="checkbox-row  ">
                                                                    <h1 class="track-title">Check Out IP Address :</h1>
                                                                    @foreach ($attendanceData as $attendance)
                                                                        @if ($attendance['employee_id'] == $employee->id)
                                                                        <p class="track-subtitle">{{ ($attendance['check_out_ip_address']?$attendance['check_out_ip_address']:'') }}</p>
                                                                        @endif
                                                                    @endforeach
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <?php $iteration++ ?>
                                @endif
                            @endif
                        @endif
                    @empty
                        <tr class="text-center">
                            <td colspan="9">No Record Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">
            {{ $employees->appends(['searchDate'=>$current_date,'branch_id'=>$selected])->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="exampleModalLabel">Add Employee Attendance</span>
                    <button type="button" class="btn-close close" data-dismiss="modal" aria-label="Close">
                </div>
                <div class="modal-body">
                    <form action="{{ route('addManuallyAttendance') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="nameInput" class="form-label">Office Location<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <select title="Branch" id="branchSelect" name="branch_id" onchange="getemployees()"
                                        required class="form-control" style="appearance: none;">
                                        <option value="" disabled selected>Select Location</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ ucwords($branch->branch_name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i class="fontello icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="nameInput" class="form-label">Employee<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <select title="Branch" name="employee_id" id="employee_id" required
                                        class="form-control" style="appearance: none;" onchange="empId(this)">
                                        <option disable selected value="">Select Employee</option>
                                        <option selected disabled>Select Employee</option>
                                    </select>
                                    <i class="fontello icon-down-dir icon-color"></i>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label class="form-label">Select Date <span class="red"
                                        style="font-size:22px;">*</span></label>
                                    <div class=" mb-1 position-relative month-field_vt">
                                        <input type="text" name="created_date" onchange="getattendance()" id="dateInput1" value="{{date('d-m-Y')}}" required class="form-control" placeholder="Select Date">
                                        <i class="fontello icon-calander1"></i>
                                    </div>
                            </div>
                            <div class="col-lg-6 mb-3 time-icon-holder">
                                <label class="form-label">Check In <span class="red"
                                        style="font-size:22px;">*</span></label>
                                <input type="text" id="basic-timepicker" name="check_in" class="form-control" placeholder="Select Time">
                                <i class="fontello icon-clock-1"></i>
                            </div>
                            <div class="col-lg-6 mb-3 time-icon-holder">
                                <label class="form-label">Check Out</label>
                                <input type="text" id="basic-timepicker2" name="check_out" class="form-control" placeholder="Select Time">
                                <i class="fontello icon-clock-1"></i>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="button" class="page-btn page-btn-outline hover-btn sm-page-btn"
                                    value="Cancel" data-dismiss="modal">
                                <button class="page-btn sm-page-btn">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // $(document).ready(function () {
        //     var table = $('#table1').DataTable({
        //         orderCellsTop: true,
        //         fixedHeader: true,
        //         initComplete: function () {
        //             var api = this.api();
        //             var cell = $('#table1 thead th:eq(7)');
        //             var title = $(cell).text();
        //             var select = $('<select style="border:none;color:var(--heading-color);font-family: var(--font-GothamRegular);font-weight: 700;appearance: none;"><option value="">Status ' + title + '</option></select>')
        //                 .appendTo(cell)
        //                 .on('click', function (e) {
        //                     e.stopPropagation();
        //                 });
        //             select.append('<option value="Present">Present</option>');
        //             select.append('<option value="Absent">Absent</option>');
        //             select.append('<option value="Leave">Leave</option>');
        //             select.append('<option value="Holiday">Holiday</option>');
        //             select.append('<option value="Half Leave">Half Leave</option>');
        //             select.on('change', function () {
        //                 var value = $.fn.dataTable.util.escapeRegex(
        //                     $(this).val()
        //                 );
        //                 api.column(7).search(value === "" ? "" : "^" + value + "$", true, false).draw();
        //             });
        //         },
        //     });
        // });



        var emp_id;

        function getemployees() {
            var branch_id = $('#branchSelect').val();
            $.ajax({
                method: 'get',
                dataType: 'json',
                url: '{{ route('getEmployees') }}',
                data: {
                    branch_id: branch_id
                },
                success: function(response) {
                    var data = response.data;
                    $('#employee_id').html('');
                    var html = '<option selected disabled>Select Employee</option>';
                    for (var i = 0; i < data.length; ++i) {
                        html += `<option value="${data[i].id}">${data[i].emp_name}</option>`;
                    }
                    $('#employee_id').html(html);
                }
            });
        }

        function empId(id){
            emp_id = id.value;
            getattendance()
            // getattendance(id.value)
        }

        function getattendance() {
            var dateInput1 = $('#dateInput1').val();
            $.ajax({
                method: 'get',
                dataType: 'json',
                url: '{{ route('getattendance') }}',
                data: {
                    dateInput1: dateInput1,
                    emp_id: emp_id
                },
                success: function(response) {
                    var data = response.data;
                    document.getElementById('basic-timepicker').value = data.check_in;
                    // document.getElementById('basic-timepicker2').value = data.check_out;
                }
            });
        }

        setTimeout(function() {
            $('#alertID').hide('slow')
        }, 3000);

        $(document).ready(function() {
            $('#selectBranch').change(function() {
                $('#myForm').submit();
            });

            $('#dateInput').change(function() {
                $('#dateSubmit').submit();
            });
            // Get the current month and year
            var currentDate = new Date();

            // Set the maximum date for the datepicker
            $('#dateInput').datepicker({
                format: "dd-mm-yyyy",
                endDate: currentDate,
                defaultDate: currentDate,
                maxDate: new Date()
            });

            // Set the maximum date for the datepicker
            $('#dateInput1').datepicker({
                format: "dd-mm-yyyy",
                endDate: currentDate,
                defaultDate: currentDate,
                maxDate: new Date()
            });
        });

        function searchData() {
            var input = document.getElementById('searchID').value;
            var selectBranch = '{{ $selected }}';
            var searchDate = '{{ $current_date }}';
            let type = '';
            $.ajax({
                url: '{{ route('search.employee.attendence') }}',
                type: 'get',
                data: {
                    'input': input,
                    'branch_id': selectBranch,
                    'searchDate': searchDate,
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response);
                    if (response['success'] == true) {
                        $("#table1 tbody").empty();
                        $("#table1 tbody").html();
                        var employees = response.data.employees.data;
                        var attendances = response.data.attendanceData;
                        var i = 1;
                        $.each(employees, function(index, item) {
                            $.each(attendances, function(index, attendance) {
                                if(attendance.employee_id == item.id){
                                    var emp_image = item.emp_image;
                                    var input = '<tr>';
                                    input += '<td>' + (i++) + '</td>';
                                    input += '<td>' +
                                        '<div class="d-flex align-items-center" style="justify-content:space-evenly;">' +
                                        '<img class="table-img_vt" src="' + emp_image + '">' + item.emp_id +
                                        '</div>' +
                                        '</td>';
                                    input += '<td>' + item.emp_name + '</td>';

                                    var check_in_status = attendance.check_in_status;
                                    var check_in_icon = '';
                                    if(attendance.check_in != ''){
                                        if(check_in_status == 'Pin'){
                                          check_in_icon = '<i class="fa-solid fa-keyboard" title="Pin" style="color: var(--btn-bg);font-size: 15px;margin-bottom:0px;"></i>';
                                        }else if(check_in_status == 'Face'){
                                            check_in_icon = '<img src="/assets/images/face_scan.png" title="Face" width="20px"/>';
                                        }else if(check_in_status == 'FP'){
                                            check_in_icon = '<i class="fa-solid fa-fingerprint" title="FingerPrint" style="color: var(--btn-bg);font-size: 15px;margin-bottom:0px;"></i>';
                                        }else if(check_in_status == 'M'){
                                            check_in_icon = '<i class="fa-solid fa-laptop" title="Manual" style="color: var(--btn-bg);"></i>';
                                        }
                                    }
                                    input += '<td>'+
                                        '<div class="d-flex align-items-center justify-content-between">' + attendance.check_in + check_in_icon +
                                            '</div></td>';

                                    var check_out_status = attendance.check_out_status;
                                    var check_out_icon = '';
                                    if(attendance.check_out != ''){
                                        if(check_out_status == 'Pin'){
                                            check_out_icon = '<i class="fa-solid fa-keyboard" title="Pin" style="color: var(--btn-bg);font-size: 15px;margin-bottom:0px;"></i>';
                                        }else if(check_out_status == 'Face'){
                                            check_out_icon = '<img src="/assets/images/face_scan.png" title="Face" width="20px"/>';
                                        }else if(check_out_status == 'FP'){
                                            check_out_icon = '<i class="fa-solid fa-fingerprint" title="FingerPrint" style="color: var(--btn-bg);font-size: 15px;margin-bottom:0px;"></i>';
                                        }else if(check_out_status == 'M'){
                                            check_out_icon = '<i class="fa-solid fa-laptop" title="Manual" style="color: var(--btn-bg);"></i>';
                                        }
                                    }
                                    input += '<td>'+
                                        '<div class="d-flex align-items-center justify-content-between">' + attendance.check_out + check_out_icon +
                                            '</div></td>';
                                    input += '<td>' + attendance.workingHours + '</td>';
                                    input += '<td>-</td>';
                                    input += '<td>';
                                        if (attendance.attendance == 'Full Day'){
                                            input +='<span class="green">Present</span>';
                                        }else if (attendance.attendance == 'Present'){
                                            input +='<span class="green">Present</span>';
                                        }else if(attendance.attendance == 'Late'){
                                            input +='<span class="green">Present</span>';
                                        }else if(attendance.attendance == 'Half Leave'){
                                            input +='<span class="yellow">Half Leave</span>';
                                        }else if(attendance.attendance == 'Absent'){
                                            input +='<span class="red">Absent</span>';
                                        }else if(attendance.attendance == 'Leave'){
                                            input +='<span class="yellow">Leave</span>';
                                        }else if(attendance.attendance == 'Holiday'){
                                            input +='<span class="yellow">Holiday</span>';
                                        }
                                    '</td>';
                                    input += '<td>'+
                                                '<div class="btn-group dropdown-btn-group dropleft show">'+
                                                    '<button type="button" class="dropdown-toggle track-btn"'+
                                                    'data-toggle="dropdown" aria-expanded="true"><i '+
                                                        'class="fontello icon-location"></i></button>'+
                                                    '<ul class="dropdown-menu" style="padding:15px;">'+
                                                        '<li class="checkbox-row  ">'+
                                                            '<h1 class="track-title">Check In Address :</h1>'+
                                                                '<p class="track-subtitle">'+attendance.check_in_address+ '</p>'+
                                                        '</li>'+
                                                        '<li class="checkbox-row ">'+
                                                            '<h1 class="track-title">Check Out Address :</h1>'+
                                                                '<p class="track-subtitle">'+attendance.check_out_address+'</p>'+
                                                        '</li>'+
                                                    '</ul>'+
                                                '</div>'+
                                                '<div class="btn-group dropdown-btn-group dropleft show px-1">'+
                                                    '<button type="button" class="dropdown-toggle track-btn"'+
                                                        'data-toggle="dropdown" aria-expanded="true"><img '+
                                                            'src="/assets/images/ip-img.png"></button>'+
                                                    '<ul class="dropdown-menu" style="padding:15px;">'+
                                                        ' <li class="checkbox-row  ">'+
                                                            '<h1 class="track-title">Check In IP Address :</h1>'+
                                                                '<p class="track-subtitle">'+ attendance.check_in_ip_address+'</p>'+
                                                        ' </li>'+
                                                        ' <li class="checkbox-row  ">'+
                                                            '<h1 class="track-title">Check Out IP Address :</h1>'+
                                                                '<p class="track-subtitle">'+ attendance.check_out_ip_address+'</p>'+
                                                        ' </li>'+
                                                    '</ul>'+
                                                '</div>'+
                                        '</td>';
                                    input += '</tr>';
                                    $('#table1 tbody').append(input);
                                }
                            });
                        });

                    } else {
                        $('#table1 tbody').empty();
                        let input = '<tr class="text-center">';
                        input += '<td colspan="9">No Record Found</td>';
                        input += '</tr>';
                        $('#table1 tbody').append(input);
                    }
                }
            });
        }

    </script>
@endsection