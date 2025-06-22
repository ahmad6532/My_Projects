@extends('layouts.admin.master')
@section('content')
    <style>
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
                {{-- <p class="head-para_vt">256</p> --}}
            </div>
            <div class="col-lg-9 mb-0 mt-1">
                <div class="row input-holder-section">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-2 col-md-2 px-1 mb-2" style="padding-right:2px !important;">
                        {{-- <div class="d-flex">
                            <div class="w-100">
                                @if ($user->haspermission(['yearly-all','yearly-write']))
                                <button name="submit" type="submit" class="page-btn page-btn-outline mn-width-auto"
                                    style="width:100%;" data-toggle="modal" data-target="#uploadCSV">Upload CSV</button>
                                    @endif
                            </div>
                        </div> --}}
                    </div>
                    <div class="col-lg-4 mb-1 mb-1" style="padding-right:3px !important;padding-left:3px !important;">
                        <form action="{{ route('yearly.search.branch') }}" method="get" id="myForm">
                            <div class="form-group position-relative caret-holder px-1">
                                <select title="Branch" name="selectBranch" id="selectBranch" required class="form-control"
                                    style="appearance: none;">
                                    <option disabled selected>Select Location</option>
                                    <option value="all" {{ $selectBranch == 'all' ? 'selected' : '' }}>All</option>
                                    @forelse ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ $selectBranch == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->branch_name }}</option>
                                    @empty
                                        <option>No Record Found</option>
                                    @endforelse
                                </select>
                                <i class="awesom-icon icon-down-dir icon-color"></i>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-4 mb-1 mb-0" style="padding-right:3px !important;padding-left:3px !important;">
                        <form method="get" id="searchByDate">
                            <div class="">
                                <input type="hidden" name="branch_id" value="">
                                <div class=" mb-1 position-relative month-field_vt">
                                    <input type="text" name="year_month" id="datepicker" value=""
                                        class="form-control" placeholder="Select Year">
                                    <i class="fontello icon-calander1"></i>
                                </div>
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
                <p class='head-para_vt px-1'>{{ $current_year }}</p>
            </div>
            <div class='col-lg-8 p-3'>
                <div class="dataTable-right-area">
                    <div class="icon-holder-vt">
                        <div class="icon-widget_vt">
                            <i class="fontello icon-ok-circled2 green"></i>
                            <p class='head-para_vt'>Presents</p>
                        </div>
                        <div class="icon-widget_vt">
                            <i class="fontello icon-cancel-circled-outline red"></i>
                            <p class='head-para_vt'>Absent</p>
                        </div>
                        {{-- <div class="icon-widget_vt">
                            <i class="fontello icon-star-12 gray"></i>
                            <p class='head-para_vt'>Holidays</p>
                        </div>
                        <div class="icon-widget_vt">
                            <i class="fontello icon-day-night gray"></i>
                            <p class='head-para_vt'>Half Leaves</p>
                        </div> --}}
                    </div>

                    <div>
                        @if ($user->haspermission(['yearly-all','yearly-write']))
                        <a href="">
                            <button id="downloadBtn" class="page-btn mn-width-auto">Download CSV</button>
                        </a>
                        @endif
                    </div>
                    <div class="search-icon">
                        <input type="text" class="form-control" style="font-size:0.8rem !important;"
                            onkeyup="searchData()" name="search_input" id="searchID" value=""
                            placeholder="Search by Name or ID">
                        <a> <i class="fontello icon-search" style="font-size:0.8rem !important;"></i></a>
                    </div>
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
                        <th>January</th>
                        <th>February</th>
                        <th>March</th>
                        <th>April</th>
                        <th>May</th>
                        <th>June</th>
                        <th>July</th>
                        <th>August</th>
                        <th>September</th>
                        <th>October</th>
                        <th>November</th>
                        <th>December</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $key => $employee)
                        <tr>
                            <td>{{ $employees->firstItem() + $key }}</td>
                            <td>@php
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
                            </table></td>
                            <td>{{ $employee->emp_name }}</td>
                            @foreach ($allMonthsData as $item)
                                @if ($item['employee_id'] == $employee->id)
                                    @foreach ($item['attendanceData'] as $attendance)
                                        <td>
                                            <table class="w-100 ">
                                                <tr style="background:transparent">
                                                    <td class="border-0 text-center" style="border:0 !important;">
                                                        <i class="fontello icon-ok-circled2 green"></i>
                                                        <p class="m-0">{{ $attendance['Present'] }}</p>
                                                    </td>
                                                    <td class="border-0 text-center" style="border:0 !important;">
                                                        <i class="fontello icon-cancel-circled-outline red"></i>
                                                        <p class="m-0">{{ $attendance['absent_count'] }}</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    @endforeach
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
            </table>
        </div>
        <div class="pagination">
            {{ $employees->links() }}
        </div>
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
        $(document).ready(function() {
            // Get the current month and year
            var currentDate = new Date();
            var currentMonth = currentDate.getMonth() + 1; // Add 1 since months are zero-based
            var currentYear = currentDate.getFullYear();

            // Set the maximum date for the datepicker
            $('#datepicker').datepicker({
                format: "MM yyyy",
                minViewMode: "months",
                endDate: currentMonth + "-" + currentYear
            });
        });
        $(document).ready(function() {
            $('#selectBranch').change(function() {
                $('#myForm').submit();
            });
        });
    </script>
@endsection
