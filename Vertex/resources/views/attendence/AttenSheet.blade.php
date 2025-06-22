@extends('layouts.admin.master')
@section('content')
    <style>
        .custom-tooltip {
            position: relative;
            display: inline-block;
        }

        .scroll-overflow-x {
            overflow-x: scroll;
        }

        .tooltip-text {
            visibility: hidden;
            width: 120px;
            background-color: rgb(49, 56, 49);
            color: #fff;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            font-size: 10px;
            z-index: 99999;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .custom-tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        #table-btn {
            font-size: 12px !important;
        }

        .Datatable-content-area {
            border: 1px solid #dee2e6;
            padding: 10px;
            border-radius: 5px 5px 0 0;
            margin-top: 30px;
        }

        .table-img_vt {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 5px;
        }

        .search-icon {
            position: relative;
            padding: 0 5px;
        }

        .search-icon input {
            padding-left: 30px;
        }

        .search-icon i {
            position: absolute;
            top: 5px;
            left: 13px;
            color: #949494;
        }
    </style>

    <div class="attendance-sheet-head">
        <div class="row align-items-center p-2" style="padding-top: 13px !important;">
            <div class="col-lg-3 mb-1 mt-2">
                <h1 class="subtitle_vt" style="font-size:16px;">Attendance Sheet</h1>
                @if (isset($branch_id))
                    <p class="head-para_vt">{{ $branch_id ? 'Branch ID : ' . $branch_id : '' }}</p>
                @endif
            </div>
            <div class="col-lg-9 mb-0 mt-1">
                <div class="row input-holder-section">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-2 col-md-2 px-1 mb-2" style="padding-right:2px !important;">
                        <div class="d-flex">
                            <div class="w-100">
                                @if ($user->haspermission(['monthly-all', 'monthly-write']))
                                    <button name="submit" type="submit" class="page-btn page-btn-outline mn-width-auto"
                                        style="width:100%;" data-toggle="modal" data-target="#uploadCSV">Upload CSV</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-1 mb-1" style="padding-right:3px !important;padding-left:3px !important;">
                        <form method="GET" id="myForm" action="{{ route('monthly.attend.sheet') }}">
                            <input type="hidden" name="year_month"
                                value="{{ date('Y-m', strtotime($current_month_year)) }}" class="form-control">
                            <div class="form-group position-relative caret-holder px-1 mb-1">
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
                                <i class="awesom-icon icon-down-dir purple_vt ca"></i>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-4 mb-1 mb-0" style="padding-right:3px !important;padding-left:3px !important;">
                        <form method="get" id="searchByDate" action="{{ route('monthly.attend.sheet') }}">
                            <div class="">
                                <input type="hidden" name="branch_id" value="{{ $selected }}">
                                <div class=" mb-1 position-relative month-field_vt">
                                    <input type="text" name="year_month" id="datepicker"
                                        value="{{ $current_month_year }}" class="form-control" placeholder="Select Month">
                                    <i class="fontello icon-calander1"></i>
                                </div>
                                <!-- <div class="col-lg-4">
                                                        <button type="submit" class="searchBtn page-btn mn-width-auto w-100">Search</button>
                                                    </div> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="Datatable-content-area mt-2 dataTable-section">
        <div class='row align-items-center'>
            <div class='col-lg-4 p-3 detail-sheet-emp d-flex'>
                <h1 class='subtitle_vt'>Employee Attendance Sheet</h1>
                <p class='head-para_vt px-1'>{{ $current_month_year }}</p>
            </div>
            <div class='col-lg-8 p-3'>
                <div class="dataTable-right-area">
                    <div class="icon-holder-vt">
                        <div class="icon-widget_vt">
                            <i class="fontello icon-ok-circled2 green"></i>&nbsp;
                            <p class='head-para_vt'>Presents</p>
                        </div>
                        <div class="icon-widget_vt">
                            <i class="fontello icon-cancel-circled-outline red"></i>&nbsp;
                            <p class='head-para_vt'>Absent</p>
                        </div>
                        <div class="icon-widget_vt">
                            <i class="fontello icon-star-12 yellow"></i>&nbsp;
                            <p class='head-para_vt'>Holidays</p>
                        </div>
                        <div class="icon-widget_vt">
                            <i class="fontello icon-day-night yellow"></i>&nbsp;
                            <p class='head-para_vt'>Half Leaves</p>
                        </div>
                        <div class="icon-widget_vt">
                            <i class='fontello icon-late-come yellow' style="font-size: medium;"></i>&nbsp;
                            <p class='head-para_vt'>Late Coming</p>
                        </div>
                    </div>

                    <div>
                        @if ($user->haspermission(['monthly-all', 'monthly-write']))
                            <a href="{{ url('download-AttendanceSheet/' . $selected . '/' . $current_month_year) }}">
                                <button id="downloadBtn" class="page-btn mn-width-auto">Download CSV</button>
                            </a>
                        @endif
                    </div>
                    {{-- <div class="search-icon">
                        <input type="text" class="form-control" style="font-size:0.8rem !important;"
                            onkeyup="searchData()" name="search_input" id="searchID" value=""
                            placeholder="Search by Name or ID">
                        <a> <i class="fontello icon-search" style="font-size:0.8rem !important;"></i></a>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table id="table_container"
                class="table table-bordered table-striped table-nowrap table-hover table-centered m-0 table-atten-sheet">
                <thead>
                    <tr>
                        <th>Sr</th>
                        <th>Emp ID</th>
                        <th>Name</th>

                            @for ($num = 1; $num <= $number_of_days; $num++)
                                @php
                                    $date = Carbon\Carbon::create($year, $month, $num);
                                    $dayName = $date->format('D'); // 'D' format gives the abbreviated day name
                                @endphp
                                <th class="text-center">
                                    {{ $num }}<br>{{ $dayName[0] }}
                                </th>
                            @endfor
                        <th>Absent</th>
                        <th>Late</th>
                        <th>HL</th>
                        <th>Leave</th>
                        <th>WH</th>
                        <th>Actual Hours</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $itration = 1; ?>
                    @forelse ($employees as $key => $employee)
                        @if((isset($employee->approval->joining_date) && Date("Y-m",strtotime($employee->approval->joining_date)) <= Date("Y-m", strtotime($current_month_year)) ))
                            @if( isset($employee->resignations) && isset($employee->resignations->is_approved) == '1' ? Date("Y-m",strtotime($employee->resignations->resignation_date)) >= Date("Y-m", strtotime($current_month_year)) : true)
                                @if(isset($employee->terminations) && isset($employee->terminations->is_approved) == '1' ? Date("Y-m",strtotime($employee->terminations->termination_date)) >= Date("Y-m", strtotime($current_month_year)) : true)
                                    <tr>
                                        <td>{{ $itration }}</td>
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
                                                    <td class="border-0 p-0" style="border:0 !important;">
                                                        {{ $employee->emp_id }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td><a class=""
                                                href="{{ url('/employee/directory/employee-profile/' . base64_encode($employee->id)) }}">{{ mb_convert_case($employee->emp_name, MB_CASE_TITLE, 'UTF-8') }}
                                            </a>
                                        </td>
                                        @forelse ($attendanceArray as $dailyAttendance)
                                            @if($dailyAttendance['employee_id'] == $employee->id)
                                                @if ($dailyAttendance['attendance_status'] == 'weekend')
                                                    <td title="Weekend">
                                                        <i class='fontello icon-star-12 yellow' title="Weekend"></i>
                                                    </td>
                                                @elseif($dailyAttendance['attendance_status'] == 'present on weekend')
                                                <td title="Present On Weekend" style="background-color:#b3ee677a;">
                                                    <span class="custom-tooltip">
                                                        <i class='fontello icon-ok-circled2 green' title="Present On Weekend"></i>
                                                    </span>
                                                </td>
                                                @elseif($dailyAttendance['attendance_status'] == 'present on holiday')
                                                <td title="Present On Holiday" style="background-color:#eced9a;">
                                                    <span class="custom-tooltip">
                                                        <i class='fontello icon-ok-circled2 green' title="Present On Holiday"></i>
                                                    </span>
                                                </td>
                                                @elseif($dailyAttendance['attendance_status'] == 'holiday')
                                                    <td style="background-color:#eced9a;" title="Holiday">
                                                        <i class='fontello icon-star-12 yellow' title="Holiday"></i>
                                                    </td>
                                                @elseif($dailyAttendance['attendance_status'] == 'present on leave')
                                                    <td title="Present On Leave" style="background-color:#8f8f89a2;">
                                                        <span class="custom-tooltip">
                                                            <i class='fontello icon-ok-circled2 green' title="Present On Leave"></i>
                                                        </span>
                                                    </td>
                                                @elseif($dailyAttendance['attendance_status'] == 'leave')
                                                    <td title="Leave">
                                                        <i class='fontello icon-star-12 blue' title="Leave"></i>
                                                    </td>
                                                @elseif($dailyAttendance['attendance_status'] == 'absent')
                                                    <td title="Absent">
                                                        <i class='fontello icon-cancel-circled-outline red' title="Absent"></i>
                                                    </td>
                                                @elseif($dailyAttendance['attendance_status'] == 'half_leave')
                                                    <td title="Half Leave">
                                                        <span class="custom-tooltip">
                                                            <i class="fontello icon-day-night yellow" title="Half Leave"></i>
                                                        </span>
                                                    </td>
                                                @elseif ($dailyAttendance['attendance_status'] == 'late_coming')
                                                    <td title="Late Coming">
                                                        <i class='fontello icon-late-come yellow' style="font-size: medium;" title="Late Coming"></i>
                                                    </td>
                                                @elseif ($dailyAttendance['attendance_status'] == 'present')
                                                    <td title="Present">
                                                        <span class="custom-tooltip">
                                                            <i class='fontello icon-ok-circled2 green' title="Present"></i>
                                                        </span>
                                                    </td>
                                                @elseif($dailyAttendance['attendance_status'] == 'new_joining')
                                                    <td class="text-center">
                                                        x
                                                    </td>
                                                @elseif ($dailyAttendance['attendance_status'] == 'resigned')
                                                    <td class="text-center">
                                                        R
                                                    </td>
                                                @elseif ($dailyAttendance['attendance_status'] == 'terminated')
                                                    <td class="text-center">
                                                        T
                                                    </td>
                                                @elseif ($dailyAttendance['attendance_status'] == 'free')
                                                    <td class="text-center">
                                                        -
                                                    </td>
                                                @endif
                                            @endif
                                        @empty
                                            @for ($num = 1; $num <= $number_of_days; $num++)
                                                <td class="text-center">
                                                    -
                                                </td>
                                            @endfor
                                        @endforelse
                                        @php $monthly_summary = $employee['get_user_monthly_attendance']; @endphp
                                        <td>
                                            {{ $monthly_summary ? $monthly_summary['absents'] :'-' }}
                                        </td>
                                        <td>
                                            {{ $monthly_summary ? $monthly_summary['late_comings'] :'-' }}
                                        </td>
                                        <td>
                                            {{ $monthly_summary ? $monthly_summary['half_leaves'] :'-' }}
                                        </td>
                                        <td>
                                            {{ $monthly_summary ? $monthly_summary['leaves'] :'-' }}
                                        </td>
                                        <td>
                                            {{ $monthly_summary ? $monthly_summary['working_hours'] :'-' }}
                                        </td>
                                        <td>
                                            {{ $monthly_summary ? $monthly_summary['actual_working_hours'] :'-' }}
                                        </td>
                                    </tr>
                                    <?php $itration++; ?>
                                @endif
                            @endif
                        @endif
                    @empty
                        <tr class="text-center">
                            <td colspan="40">No Record Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- <div class="pagination">
            {{ $employees->appends(['branch_id' => $selected, 'year_month' => $current_month_year])->links() }}
        </div> --}}
    </div>
    <div class="modal fade" id="uploadCSV" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="exampleModalLabel">Upload CSV for Employee Attendance</span>
                    <button type="button" class="btn-close close" data-dismiss="modal" aria-label="Close">
                </div>
                <div class="modal-body">
                    <form action="{{ url('add-employee-attendence') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="row">
                        </div>
                        <div class=" mb-2">
                            <label class="form-label">CSV File</label>
                            <input type="file" name="attendance_list" accept=".txt" class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="button" class="page-btn page-btn-outline hover-btn sm-page-btn"
                                    value="Cancel" data-dismiss="modal">
                                <button class="page-btn sm-page-btn">Upload</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var rows = document.querySelectorAll('tr');
            rows.forEach(function(row) {
                var count = row.querySelectorAll('.absent').length;
                var absentCountElement = row.querySelector('.absent-count');
                if (absentCountElement) {
                    absentCountElement.textContent = count.toString();
                }
            });
        });

        // function searchData() {
        //     var input = document.getElementById('searchID').value;
        //     var selectBranch = '0';
        //     let type = '';
        //     $.ajax({
        //         url: '{{ route('monthly.attend.sheet') }}',
        //         type: 'get',
        //         data: {
        //             'emp_name': input,
        //             'branch_id': '{{ $selected }}',
        //             'year_month': '{{ $current_month_year }}',
        //         },
        // dataType: 'json',
        // success: function(response) {
        //     console.log(response.data);
        //     if (response['success'] == true) {
        //         $("#table_container tbody").empty();
        //         var employees = response.data.employees.data;
        //         var i = 1;
        //         $.each(employees, function(index, item) {
        //             var row = "<tr>";
        //             row += "<td>" + (i++) + "</td>";
        //             row += "<td>" + item.emp_id + "</td>";
        //             row += "<td>" + item.emp_name + "</td>";
        //             // for (var num1 = 1; num1 <= response.number_of_days; num1++) {
        //             //     var current_date = moment(response.date).add(num1 - 1, 'days').format('YYYY-MM-DD');
        //             //     var td_id = moment(current_date).format('YYYY-MM-DD');
        //             //     var attendance_found = false;

        //             //     $.each(attendances, function(index, attendance) {
        //             //         var created_at = moment(attendance.created_at).format('YYYY-MM-DD');
        //             //         if (created_at === td_id) {
        //             //             attendance_found = true;
        //             //             var startTime = moment(attendance.check_in);
        //             //             var endTime = moment(attendance.check_out);
        //             //             var duration = endTime.diff(startTime, 'seconds');
        //             //             var productinTime = moment.duration(duration).asHours();
        //             //             if (productinTime < 4) {
        //             //                 attendance_found = 'half';
        //             //             }
        //             //             return false; // Exit the loop once attendance is found
        //             //         }
        //             //     });

        //             //     var day_name = moment(td_id).format('dddd');

        //             //     if (attendance_found === true) {
        //             //         row += "<td id='" + td_id + "' class='present'><i class='fontello icon-ok-circled2 green'></i></td>";
        //             //     } else {
        //             //         if (attendance_found === 'half') {
        //             //             row += "<td id='" + td_id + "' class='half'><i class='fontello icon-day-night yellow'></i></td>";
        //             //         } else if (day_name === 'Saturday' || day_name === 'Sunday') {
        //             //             row += "<td id='" + td_id + "' class='holiday'><i class='fontello icon-star-12 yellow'></i></td>";
        //             //         } else if (moment(td_id).isSameOrBefore(moment(response.now_date))) {
        //             //             row += "<td id='" + td_id + "' class='absent'><i class='fontello icon-cancel-circled-outline red'></i></td>";
        //             //         } else {
        //             //             row += "<td id='" + td_id + "'>-</td>";
        //             //         }
        //             //     }
        //             // }
        //             row += "</tr>";
        //             $("#table_container tbody").append(row);
        //         });
        //     } else {
        //         $('#table_container tbody').empty();
        //         let message = response.data;
        //         let input = '<tr class="text-center">';
        //         input += '<td colspan="3">' + message + '</td>';
        //         input += '</tr>';
        //         $('#table_container tbody').append(input);
        //     }

        // }
        //     });
        // }

        function handleFileSelect(event) {
            const file = event.target.files[0];
            const fileName = document.getElementById("file-name");
            fileName.textContent = file.name;
        }

            $(document).ready(function() {
                  $('#table_container').DataTable({
                    "dom": "<'row'<'col-sm-6 col-md-6 pl-0' l><'col-sm-6 col-md-6' f>>" +
                        "<'row'<'col-sm-12 scroll-overflow-x mb-2' tr>>" +
                        "<'row'<'col-sm-12 col-md-9'i><'col-sm-12 col-md-3'p>>",
                    "columnDefs": [
                        { "orderable": true, "targets": [0, 1, 2] },  // Columns 0, 1, and 2 are sortable
                        { "orderable": false, "targets": '_all' }     // All other columns are not sortable
                    ]
                });
            // });


            $('#selectBranch').change(function() {
                $('#myForm').submit();
            });

            $('#datepicker').change(function() {
                $('#searchByDate').submit();
            });

            var currentDate = new Date();
            var currentMonth = currentDate.getMonth() + 1;
            var currentYear = currentDate.getFullYear();

            // Set the maximum date for the datepicker
            $('#datepicker').datepicker({
                format: "MM yyyy",
                minViewMode: "months",
                endDate: currentMonth + "-" + currentYear
            });
        });
    </script>
@endsection
