@extends('layouts.admin.master')
@section('content')
<style>
    div.dataTables_wrapper div.dataTables_filter input {
    margin-left: 0.5em;
    display: inline-block;
    }
    div.dataTables_wrapper div.dataTables_length label,
    div.dataTables_wrapper div.dataTables_filter label{
        font-size:10px;
    }
    tbody{
        font-size:11px;
        line-height: 13px;
    }
    .table thead th {
        font-size: 0.68rem;
    }
    .table td,
    .table th{
        padding: 0.45rem;
    }
</style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 p-1">
                <div class="dashboard-card">
                    <a href="javascript:void(0)" data-toggle="modal"
                    data-target="#totalAttendanceModal">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="circle">
                            <i class="fontello icon-total-atten"></i>
                        </div>
                    </div>
                    <div class="emp-dedails">
                        <h1>Total Employees</h1>
                        <h2>{{ $totalEmployees }}</h2>
                    </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3 p-1">
                <div class="dashboard-card">
                    <a href="javascript:void(0)" data-toggle="modal"
                    data-target="#totalPresentModal">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="circle">
                                <i class="fontello icon-Attendess"></i>
                            </div>
                            <div class="pill-shape">
                                @php
                                    $result_1 = 0;
                                    if($totalEmployees){
                                        $result_1 = ($totalPresentEmpCount/$totalEmployees)*100;
                                    }
                                @endphp
                                <i class="fontello icon-right"></i><small>{{round($result_1)}}%</small>
                            </div>
                        </div>
                        <div class="emp-dedails">
                            <h1>Present</h1>
                            <h2>{{ $totalPresentEmpCount }}</h2>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3 p-1">
                <div class="dashboard-card">
                    <a href="javascript:void(0)" data-toggle="modal"
                    data-target="#totalAbsentModal">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="circle">
                                <i class="fontello icon-half-close"></i>
                            </div>
                            <div class="pill-shape pill-bg-red pill-clr-red">
                                @php
                                    $result_2 = 0;
                                    if($totalEmployees){
                                        $result_2 = ($totalAbsentEmpCount/$totalEmployees)*100;
                                    }
                                @endphp
                                <i class="fontello icon-right"></i><small>{{round($result_2)}}%</small>
                            </div>
                        </div>
                        <div class="emp-dedails">
                            <h1>Absent</h1>
                            <h2>{{ $totalAbsentEmpCount }}</h2>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3 p-1">
                <div class="dashboard-card">
                    <a href="javascript:void(0)" data-toggle="modal"
                    data-target="#totalLateModal">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="circle">
                                <i class="fontello icon-late-come"></i>
                            </div>
                            <div class="pill-shape pill-bg-yellow pill-clr-yellow">
                                @php
                                    $result_3 = 0;
                                    if($totalEmployees){
                                        $result_3 = ($lateEmpCount/$totalEmployees)*100;
                                    }
                                @endphp
                                <i class="fontello icon-right"></i><small>{{round($result_3)}}%</small>
                            </div>
                        </div>
                        <div class="emp-dedails">
                            <h1>Late Coming</h1>
                            <h2>{{ $lateEmpCount }}</h2>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 p-1">
                <div class="dashboard-card" style="height:465px;">
                    <div class="d-flex justify-content-between pb-15 mbl-card-d-board">
                        <div class="card-title">
                            <h1
                                style="color:#222 !important;font-family: var(--font-GothamRegular) !important;
                        font-weight: 900;">
                                Attendance Overview</h1>
                        </div>
                        <div class="d-flex justify-content-center mbl-view-btn-card btn-tabs-holder">
                            <a href="#" class="dashboard-card-btn_vt dashboard-card-active">Month</a>
                            <a href="#" class="dashboard-card-btn_vt">Year</a>
                        </div>
                    </div>
                    <div id="bar-chart-container">
                    </div>
                </div>
            </div>
            <div class="col-lg-4 p-1">
                <div class="dashboard-activities-card"style="overflow:hidden;height:465px;">
                    <div class="activities-header">
                        <div class="card-title mb-0 d-flex align-items-center">
                            <i class="fontello-icon icon-activity icon_vt" style="font-size:20px;color:#222;"></i>
                            <h1
                                style="color:#222 !important;font-family: var(--font-GothamRegular) !important;font-weight: 900;">
                                Recent Activities
                            </h1>
                        </div>
                        <div class="dashboard-link_vt">
                            <a href="{{ route('recently.activity') }}"
                                style="font-family: var(--font-GothamRegular) !important; font-weight: 900;">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="activities-body">
                        @forelse ($logs as $log)
                            <div class="activities-widget">
                                {{--  <div class="circle">
                                    <i
                                        class="fontello-icon {{ app\models\Log::$log_type[$log->type]['icon'] }}"></i>
                                </div>  --}}
                                <div class="d-flex justify-content-between user-detail-area px-1">
                                    <div class="user-detail">
                                        <h2>{!! app\models\Log::$log_type[$log->type]['msg'] . ' ' . $log->msg !!}</h2>
                                        <p>{{ $log->user ? $log->user->email : '' }}</p>
                                    </div>
                                    <div class="user-detail">
                                        <p>{{ $log->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="activities-widget justify-content-center">
                                <div class="user-detail">
                                    <p>No any activity found</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-lg-12 p-1">
                <div class="pi-dashboard-card">
                    <div class="card-title mb-0">
                        <h1
                            style="border-bottom: 0px solid #D3D3D3;color:#222 !important;font-family: var(--font-GothamRegular) !important;
                    font-weight: 900;">
                            Location Wise</h1>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 p-1">
                <div class="pi-dashboard-card">
                    <div class="">
                        <div class="card-title">
                            <h1>Present</h1>
                        </div>

                    </div>
                    <div class="card-body-vt">
                        <div class="d-flex justify-content-center pb-15 mbl-card-d-board">
                            <div class="d-flex justify-content-center mbl-view-btn-card btn-tabs-holder">
                                <a onclick="getPresentEmployees('month')" id="first_month" class="dashboard-card-btn_vt dashboard-card-active">Month</a>
                                <a onclick="getPresentEmployees('year')" id="first_year" class="dashboard-card-btn_vt">Year</a>
                            </div>
                        </div>
                        <div id="first-pi-chart">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 p-1">
                <div class="pi-dashboard-card">
                    <div class="">
                        <div class="card-title">
                            <h1>Late</h1>
                        </div>
                    </div>
                    <div class="card-body-vt">
                        <div class="d-flex justify-content-center pb-15 mbl-card-d-board">
                            <div class="d-flex justify-content-center mbl-view-btn-card btn-tabs-holder">
                                <a onclick="getLateEmployees('month')" id="late_month" class="dashboard-card-btn_vt dashboard-card-active">Month</a>
                                <a onclick="getLateEmployees('year')" id="late_year" class="dashboard-card-btn_vt">Year</a>
                            </div>
                        </div>
                        <div id="second-pi-chart">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 p-1">
                <div class="pi-dashboard-card">
                    <div class="">
                        <div class="card-title">
                            <h1>Absent</h1>
                        </div>
                    </div>
                    <div class="card-body-vt">
                        <div class="d-flex justify-content-center pb-15 mbl-card-d-board">
                            <div class="d-flex justify-content-center mbl-view-btn-card btn-tabs-holder">
                                <a onclick="getAbsentEmployees('month')" id="absent_month" class="dashboard-card-btn_vt dashboard-card-active">Month</a>
                                <a onclick="getAbsentEmployees('year')" id="absent_year" class="dashboard-card-btn_vt">Year</a>
                            </div>
                        </div>
                        <div id="third-pi-chart">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal  --}}
    <div class="modal fade" id="branchdetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" id="edit_holiday" name="id">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="nameInput" class="form-label">Name<span class="red"
                                        style="font-size:14px;">*</span></label>
                                <input type="text" class="form-control" name="event_name" id="holiday_event_name"
                                    placeholder="Enter event name">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="emailInput" class="form-label">From<span class="red"
                                        style="font-size:14px;">*</span></label>
                                <div class=" mb-1 position-relative month-field_vt">
                                    <input type="text" name="holiday_start_date" id="holiday_start_date" required
                                        class="form-control" placeholder="Select Date">
                                    <i class="fontello icon-calander1"></i>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="emailInput" class="form-label">To</label>
                                <div class=" mb-1 position-relative month-field_vt">
                                    <input type="text" name="holiday_end_date" id="holiday_end_date" required
                                        class="form-control" placeholder="Select Date">
                                    <i class="fontello icon-calander1"></i>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Attendees Modal -->
    <div class="modal fade" id="totalAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="exampleModalLabel">Total Attendance</span>
                    <button type="button" class="btn-close close" data-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="modal-body px-2">
                    <table id="totalAttendeeTable" class="table table-striped">
                        <thead>
                            <th>Sr</th>
                            <th>Emp ID</th>
                            <th>Emp Name</th>
                            <th>Location</th>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $employee->emp_id }}</td>
                                    <td><a class=""
                                        href="{{ url('/employee/directory/employee-profile/' . base64_encode($employee->id)) }}">{{ $employee->emp_name }}</a></td>
                                    <td>{{ ($employee->branch?$employee->branch->branch_name:'')}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Present Attendees Modal -->
    <div class="modal fade" id="totalPresentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:60% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="exampleModalLabel">Total Present Employees</span>
                    <button type="button" class="btn-close close" data-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="modal-body px-2">
                    <table id="totalPresentEmployeesTable" class="table table-striped">
                        <thead>
                            <th>Sr</th>
                            <th>Emp ID</th>
                            <th>Emp Name</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Location</th>
                            <th>Updated At</th>
                        </thead>
                        <tbody>
                            @foreach($totalPresentEmp as $employee)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $employee->emp_id }}</td>
                                    <td><a class=""
                                        href="{{ url('/employee/directory/employee-profile/' . base64_encode($employee->id)) }}">{{ $employee->emp_name }}</a></td>
                                    <td>{{ $employee->check_in?date('h:i A',strtotime($employee->check_in)):'-' }}</td>
                                    <td>{{ $employee->check_out ? date('h:i A',strtotime($employee->check_out)):'-' }}</td>
                                    <td>{{ ($employee->branch_name ? $employee->branch_name : '-' )}}</td>
                                    <td>{{ date('d-m-Y h:i A',strtotime($employee->updated_at)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Absent Attendees Modal -->
    <div class="modal fade" id="totalAbsentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:60% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="exampleModalLabel">Total Absent Employees</span>
                    <button type="button" class="btn-close close" data-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="modal-body px-2">
                    <table id="totalAbsentEmployeesTable" class="table table-striped">
                        <thead>
                            <th>Sr</th>
                            <th>Emp ID</th>
                            <th>Emp Name</th>
                            <th>Location</th>
                        </thead>
                        <tbody>
                            @foreach($totalAbsentEmp as $employee)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $employee->emp_id }}</td>
                                    <td><a class=""
                                        href="{{ url('/employee/directory/employee-profile/' . base64_encode($employee->id)) }}">{{ $employee->emp_name }}</a></td>
                                    <td>{{ ($employee->branch?$employee->branch->branch_name:'')}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Late Attendees Modal -->
    <div class="modal fade" id="totalLateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:60% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="exampleModalLabel">Total Late Employees</span>
                    <button type="button" class="btn-close close" data-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="modal-body px-2">
                    <table id="totalLateEmployeesTable" class="table table-striped">
                        <thead>
                            <th>Sr</th>
                            <th>Emp ID</th>
                            <th>Emp Name</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Location</th>
                            <th>Updated At
                        </thead>
                        <tbody>
                            @foreach($lateEmployees as $employee)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $employee->emp_id }}</td>
                                    <td><a class=""
                                        href="{{ url('/employee/directory/employee-profile/' . base64_encode($employee->id)) }}">{{ $employee->emp_name }}</a></td>
                                    <td>{{ $employee->check_in?date('h:i A',strtotime($employee->check_in)):'-' }}</td>
                                    <td>{{ $employee->check_out ? date('h:i A',strtotime($employee->check_out)):'-' }}</td>
                                    <td>{{ ($employee->branch_name?$employee->branch_name:'-')}}</td>
                                    <td>{{ date('d-m-Y h:i A',strtotime($employee->updated_at)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<script src="{{asset('assets/js/echarts.min.js')}}"></script>
<script type="text/javascript">
 $(document).ready(function() {
        $("#totalPresentEmployeesTable").DataTable({
            language: {
                paginate: {
                    previous: "<i class='fas fa-chevron-left' style='font-size:10px;'>",
                    next: "<i class='fas fa-chevron-right' style='font-size:10px;'>"
                        }
                    },
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            });
            var a = $("#datatable-buttons").DataTable({
                lengthChange: !1, buttons: [
                    {
                        extend: "copy", className: "btn-light"
                     },
                {
                     extend: "print",
                     className: "btn-light"
                    },
                     {
                        extend: "pdf",
                        className: "btn-light"
                    }
                ],
                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                            next: "<i class='mdi mdi-chevron-right'>"
                        }
                    },
                    drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
        });

        $("#totalAbsentEmployeesTable").DataTable({ language: { paginate: { previous: "<i class='fas fa-chevron-left' style='font-size:10px;'>", next: "<i class='fas fa-chevron-right' style='font-size:10px;'>" } }, drawCallback: function () { $(".dataTables_paginate > .pagination").addClass("pagination-rounded") } }); var a = $("#datatable-buttons").DataTable({ lengthChange: !1, buttons: [{ extend: "copy", className: "btn-light" }, { extend: "print", className: "btn-light" }, { extend: "pdf", className: "btn-light" }], language: { paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" } }, drawCallback: function () { $(".dataTables_paginate > .pagination").addClass("pagination-rounded") } });
        $("#totalLateEmployeesTable").DataTable({ language: { paginate: { previous: "<i class='fas fa-chevron-left' style='font-size:10px;'>", next: "<i class='fas fa-chevron-right' style='font-size:10px;'>" } }, drawCallback: function () { $(".dataTables_paginate > .pagination").addClass("pagination-rounded") } }); var a = $("#datatable-buttons").DataTable({ lengthChange: !1, buttons: [{ extend: "copy", className: "btn-light" }, { extend: "print", className: "btn-light" }, { extend: "pdf", className: "btn-light" }], language: { paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" } }, drawCallback: function () { $(".dataTables_paginate > .pagination").addClass("pagination-rounded") } });

    });
</script>
<!-- second-graph-start -->
<script type="text/javascript">
    var number_of_days = {{ $number_of_days }};
    var days = [];
    var weekends = [];
    var holiday = [];
    var presentCounts = [];
    var absentCounts = [];
    var lateCounts = [];
    var dataDate = [];
    function mainGraphData(data,dates){

        for (const key in data) {
            if (data.hasOwnProperty(key)) {
                dataDate.push(JSON.stringify(key));
                const attendance = data[key];
                for (const keys in attendance) {
                    if (attendance.hasOwnProperty(keys)) {
                        const value = attendance[keys];

                        if (keys === 'weekendCount') {
                            weekends.push(JSON.stringify(value));
                        }
                        if (keys === 'presentCount') {
                            presentCounts.push(JSON.stringify(value));
                        }
                        if (keys === 'lateCount') {
                            lateCounts.push(JSON.stringify(value));
                        }
                        if (keys === 'absentCount') {
                            absentCounts.push(JSON.stringify(value));
                        }
                    }
                }
            }
        }
        // console.log(weekends);
        // var currentDate = new Date();
        // var currentDay = currentDate.getDate();
        for (var i = 1; i <= dates; i++) {
            days.push(i);
        }
        var dom = document.getElementById('bar-chart-container');
        var myChart = echarts.init(dom, null, {
            renderer: 'canvas',
            useDirtyRect: false
        });
        var app = {};
        var option;
        option = {
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                },
            },
            legend: {
                bottom: '1%',
                left: 'center'
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '10%',
                containLabel: true
            },
            xAxis: [{
                type: 'category',
                data: days
            }],
            yAxis: [{
                type: 'value'
            }],
            series: [
                {
                    name: 'Present',
                    type: 'bar',
                    stack: 'Ad',
                    itemStyle: {
                        color: '#91CC75'
                    },
                    emphasis: {
                        focus: 'series'
                    },
                    data: presentCounts
                },
                {
                    name: 'Late Coming',
                    type: 'bar',
                    stack: 'Ad',
                    itemStyle: {
                        color: '#FAC858'
                    },
                    emphasis: {
                        focus: 'series'
                    },
                    data: lateCounts
                },
                {
                    name: 'Absent',
                    type: 'bar',
                    stack: 'Ad',
                    itemStyle: {
                        color: '#EE6666'
                    },
                    emphasis: {
                        focus: 'series'
                    },
                    data: absentCounts
                }
            ]
        };
        myChart.setOption(option);
        if (option && typeof option === 'object') {
            myChart.setOption(option);
        }
        window.addEventListener('resize', myChart.resize);
    }
</script>
<!-- second-graph-start -->
<script>

    $(document).ready(function() {
        var type = "month";
        getPresentEmployees(type);
        getLateEmployees(type);
        getAbsentEmployees(type);
        getMainGraphData(type);
    });

    function getMainGraphData(type){
        $.ajax({
            url: "{{ route('dashboard.main.graph') }}",
            method: "POST",
            data: {
                'type': type,
                '_token': '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(response) {
                mainGraphData(response.data,response.dates)
            },
            error: function(response) {
                console.log(response);
                // window.location.reload();
            }
        });
    }

    function getPresentEmployees(type) {
        var addYearClass = document.getElementById("first_year");
        var RemoveMonthClass = document.getElementById("first_month");
        if(type == 'year'){
            addYearClass.classList.add("dashboard-card-active");
            RemoveMonthClass.classList.remove("dashboard-card-active");
        }else{
            addYearClass.classList.remove("dashboard-card-active");
            RemoveMonthClass.classList.add("dashboard-card-active");
        }
        $.ajax({
            url: "{{ route('home.present.chart') }}",
            method: "POST",
            data: {
                'type': type,
                '_token': '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(response) {
                firstGraphData(response.data)
            },
            error: function(response) {
                console.log(response);
                // window.location.reload();
            }
        });
    }
    function getLateEmployees(type) {
        var addYearClass = document.getElementById("late_year");
        var RemoveMonthClass = document.getElementById("late_month");
        if(type == 'year'){
            addYearClass.classList.add("dashboard-card-active");
            RemoveMonthClass.classList.remove("dashboard-card-active");
        }else{
            addYearClass.classList.remove("dashboard-card-active");
            RemoveMonthClass.classList.add("dashboard-card-active");
        }
        $.ajax({
            url: "{{ route('home.present.chart') }}",
            method: "POST",
            data: {
                'type': type,
                '_token': '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(response) {
                secondGraphData(response.data)
            },
            error: function(response) {
                console.log(response);
                // window.location.reload();
            }
        });
    }
    function firstGraphData(data) {
        var dom = document.getElementById('first-pi-chart');
        var myChart = echarts.init(dom, null, {
            renderer: 'canvas',
            useDirtyRect: false
        });

        var colors = ['#91CC75', '#FAC858', '#F48484', '#62D3EC', '#E6358E'];
        var pieData = [];

        for (var i = 0; i < data.length; i++) {
            var newColor = colors[i];
            var presentCount = data[i].present || 0;
            pieData.push({
                value: presentCount,
                name: data[i].branch_name,
                itemStyle: {
                    normal: {
                        color: newColor
                    }
                }
            });
        }

        var option = {
            tooltip: {
                trigger: 'item'
            },
            series: [{
                name: 'Access From',
                type: 'pie',
                radius: '50%',
                data: pieData, // Use the generated pieData here
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }]
        };

        if (option && typeof option === 'object') {
            myChart.setOption(option);
        }

        window.addEventListener('resize', function() {
            myChart.resize();
        });
    }

    function secondGraphData(data){
    var dom = document.getElementById('second-pi-chart');
    var myChart = echarts.init(dom, null, {
            renderer: 'canvas',
            useDirtyRect: false
        });

        var colors = ['#91CC75', '#FAC858', '#F48484', '#62D3EC', '#E6358E'];
        var pieData = [];

        for (var i = 0; i < data.length; i++) {
            var newColor = colors[i];
            var lateCount = data[i].late || 0;
            pieData.push({
                value: lateCount,
                name: data[i].branch_name,
                itemStyle: {
                    normal: {
                        color: newColor
                    }
                }
            });
        }

        var option = {
            tooltip: {
                trigger: 'item'
            },
            series: [{
                name: 'Access From',
                type: 'pie',
                radius: '50%',
                data: pieData, // Use the generated pieData here
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }]
        };

        if (option && typeof option === 'object') {
            myChart.setOption(option);
        }

        window.addEventListener('resize', function() {
            myChart.resize();
        });
    }
    function getAbsentEmployees(type) {
        var addYearClass = document.getElementById("absent_year");
        var RemoveMonthClass = document.getElementById("absent_month");
        if(type == 'year'){
            addYearClass.classList.add("dashboard-card-active");
            RemoveMonthClass.classList.remove("dashboard-card-active");
        }else{
            addYearClass.classList.remove("dashboard-card-active");
            RemoveMonthClass.classList.add("dashboard-card-active");
        }
        $.ajax({
            url: "{{ route('home.present.chart') }}",
            method: "POST",
            data: {
                'type': type,
                '_token': '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(response) {
                thirdGraphData(response.data)
            },
            error: function(response) {
                console.log(response);
                // window.location.reload();
            }
        });
    }
    function thirdGraphData(data){
    var dom = document.getElementById('third-pi-chart');
    var myChart = echarts.init(dom, null, {
            renderer: 'canvas',
            useDirtyRect: false
        });

        var colors = ['#91CC75', '#FAC858', '#F48484', '#62D3EC', '#E6358E'];
        var pieData = [];

        for (var i = 0; i < data.length; i++) {
            var newColor = colors[i];
            var absentCount = data[i].absent || 0;
            pieData.push({
                value: absentCount,
                name: data[i].branch_name,
                itemStyle: {
                    normal: {
                        color: newColor
                    }
                }
            });
        }

        var option = {
            tooltip: {
                trigger: 'item'
            },
            series: [{
                name: 'Access From',
                type: 'pie',
                radius: '50%',
                data: pieData, // Use the generated pieData here
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }]
        };

        if (option && typeof option === 'object') {
            myChart.setOption(option);
        }

        window.addEventListener('resize', function() {
            myChart.resize();
        });
    }
</script>
<!-- fourth-graph-start -->
<script type="text/javascript">
    var dom = document.getElementById('third-pi-chart');
    var myChart = echarts.init(dom, null, {
        renderer: 'canvas',
        useDirtyRect: false
    });
    var app = {};

    var option;

    option = {
        tooltip: {
            trigger: 'item'
        },
        series: [{
            name: 'Access From',
            type: 'pie',
            radius: '50%',
            data: [
                @php
                    // Define an array of colors for branches
                    $colors = ['#91CC75', '#FAC858', '#F48484', '#62D3EC', '#E6358E'];
                @endphp

                @foreach ($branches as $index => $branch)
                    @php
                        // Get the color for the current branch using the array of colors
                        $color = $colors[$index % count($colors)];
                        // Get the absent_count for the current branch from the $branchAttendanceData array
                        $absentCount = isset($branchAttendanceData[$branch->branch_name]['absent_count']) ? $branchAttendanceData[$branch->branch_name]['absent_count'] : 0;
                    @endphp {
                        value: {{ $absentCount }},
                        name: @json($branch->branch_name),
                        itemStyle: {
                            normal: {
                                color: '{{ $color }}'
                            }
                        }
                    },
                @endforeach
            ],
            emphasis: {
                itemStyle: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            },
            onclick: function(params) {
                var branchName = params.name;
                // Assuming you have a function to open the modal with the branch details
                openBranchModal(branchName);
            }
        }]
    };
    if (option && typeof option === 'object') {
        myChart.setOption(option);
    }
    window.addEventListener('resize', myChart.resize);

    // Function to open the modal with the branch details
    function openBranchModal(branchName) {
        // Assuming you have a function to open the modal using the branch name
        // You can use JavaScript/jQuery to show the modal
        // Example:
        // $('#branchdetails').modal('show');
        // You can populate the modal with branch details using the branchName variable
    }
</script>
@endsection
