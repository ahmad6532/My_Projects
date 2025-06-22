@extends('layouts.admin.master')
@section('content')
    <div class="profile-page-detail">
        <div class='profile-area mb-2'>
            <div class='row'>
                <div class='col-lg-6 profile-left-section my-2'>
                    <div class='d-flex'>
                        <div class='profile-img-holder'>
                            @php
                                if ($getUserDetails->emp_image) {
                                    $imagePath = public_path($getUserDetails->emp_image);
                                    if (File::exists($imagePath)) {
                                        $getUserDetails->emp_image = asset($getUserDetails->emp_image);
                                    } else {
                                        // If the image file doesn't exist, set a default image based on gender
        if ($getUserDetails->emp_gender == 'F') {
            $getUserDetails->emp_image = asset('images/female.png');
        } else {
            $getUserDetails->emp_image = asset('images/male.png');
        }
    }
} else {
    // If emp_image is empty, set a default image based on gender
    if ($getUserDetails->emp_gender == 'F') {
        $getUserDetails->emp_image = asset('images/female.png');
    } else {
        $getUserDetails->emp_image = asset('images/male.png');
                                    }
                                }
                            @endphp
                            <img class='img-fluid' src="{{ asset($getUserDetails->emp_image) }}" />
                        </div>
                        <div class='right-section-detail'>
                            <h1>{{ $getUserDetails->emp_name }}</h1>
                            @if ($getUserDetails->employeeDesignation != null)
                                <p>{{ ucwords($getUserDetails->employeeDesignation) }}</p>
                            @endif
                            @if ($getUserDetails->branch_name != null)
                                <p>{{ ucwords($getUserDetails->branch_name) }}</p>
                            @endif
                            <p class='mb-0'>
                                <bold>Branch ID :</bold> {{ $getUserDetails->branche_id ?? 'N/A' }}
                            </p>
                            <p class='mb-0'>
                                <bold>Employee ID :</bold> {{ $getUserDetails->emp_id }}
                            </p>
                            <p>
                                <bold>Date of Join :</bold>
                                {{ isset($getUserDetails->join_date) ? $getUserDetails->join_date : 'N/A' }}
                            </p>
                            <div class='py-2'>
                                <button class="page-btn">Send Email</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='col-lg-6 profile-right-section my-2'>
                    <!-- <div class="edit-section">
                            <i class="fontello icon-edit-1"></i>
                        </div> -->
                    <div class='person-detail-widget mb-1'>
                        <h2>Phone Number :</h2>
                        <p>{{ $getUserDetails->emp_phone }}</p>
                    </div>
                    <div class='person-detail-widget mb-1'>
                        <h2>Email :</h2>
                        <p>{{ $getUserDetails->emp_email }}</p>
                    </div>
                    <div class='person-detail-widget mb-1'>
                        <h2>Birthday :</h2>
                        <p>{{ date('d M, Y', strtotime($getUserDetails->Dob)) }}</p>
                    </div>
                    <div class='person-detail-widget mb-1'>
                        <h2>Address :</h2>
                        <p>{{ $getUserDetails->emp_address }}</p>
                    </div>
                    <div class='person-detail-widget mb-1'>
                        <h2>Gender :</h2>
                        <p>{{ $getUserDetails->emp_gender == 'M' ? 'Male' : 'Female' }}</p>
                    </div>
                    <div class='person-detail-widget mb-1'>
                        <h2>NIC :</h2>
                        <p>{{ $getUserDetails->cnic }}</p>
                    </div>
                </div>

            </div>
        </div>
        <div class="check-section">
            <div class="row">
                <div class="col-lg-4 mb-2">
                    <div class="Punch-area">
                        <h1>Time Utilization</h1>
                        <p>{{ $getCurrentDate }}</p>
                        <div class="punch-widget mb-2">
                            <i class="fontello icon-thumb"></i>
                            <div>
                                <h3>Time In</h3>
                                <p>
                                    @if ($todaysRecord != null)
                                        @if ($todaysRecord->check_in != '')
                                            {{ date('h:i A', strtotime($todaysRecord->check_in)) }}
                                        @else
                                            N/A
                                        @endif
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="punch-widget mb-2">
                            <i class="fontello icon-thumb text-danger"></i>
                            <div>
                                <h3>Time out</h3>
                                <p>
                                    @if ($todaysRecord != null)
                                        @if ($todaysRecord->check_out != '')
                                            {{ date('h:i A', strtotime($todaysRecord->check_out)) }}
                                        @else
                                            N/A
                                        @endif
                                    @endif
                                </p>
                            </div>
                        </div>
                        <!-- <div class="right-section-detail">
                                <p><bold>Over time :  </bold>   {{ $overbreak }}</p>
                            </div> -->
                    </div>
                </div>
                <div class="col-lg-8 mb-2">
                    <div class='stats-area'>
                        <h1>Statistics</h1>
                        <div class='stats-widget'>
                            <div class='d-flex justify-content-between align-items-center'>
                                <h2>Today Duration</h2>
                                <p>{{ $workingHours != null ? $workingHours : 0 }}/<bold>{{ $getTotalOfficeHours }}</bold>
                                </p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar first-progress" role="progressbar"
                                    style="width: {{ $workPercantage }}%" aria-valuenow="25" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class='stats-widget'>
                            <div class='d-flex justify-content-between align-items-center'>
                                <h2>This Week</h2>
                                <p>{{ $weeklyData['weeklyUsersTime'] }}/<bold>{{ $weeklyData['totalWeeklyHours'] }}</bold>
                                </p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar second-progress" role="progressbar"
                                    style="width: {{ $weeklyData['weekTimePercentage'] }}%" aria-valuenow="25"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class='stats-widget'>
                            <div class='d-flex justify-content-between align-items-center'>
                                <h2>This Month</h2>
                                <p>{{ $monthlyData['monthlyuserTime'] }}/<bold>{{ $monthlyData['totalMonthlyHours'] }}
                                    </bold>
                                </p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar third-progress" role="progressbar"
                                    style="width: {{ $monthlyData['monthTimePercentage'] }}%" aria-valuenow="25"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='profile-area mt-0 mb-2'>
            <div class='row px-2'>
                <div class="col-lg-4"></div>
                <div class="col-lg-4" style="padding-right:3px !important;padding-left:3px !important;">
                    <form method="get" id="myForm" action="{{ route('search.user.data', ['id' => $id]) }}">
                        <div class="form-group position-relative caret-holder px-1 my-1">
                            <input type="text" name="date" id="dateInput" value="{{ $userSearchedDate }}"
                                name="day" class="form-control" placeholder="Select Date">
                            <i class="fontello icon-calander1"></i>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4" style="padding-right:3px !important;padding-left:3px !important;">
                    <form method="get" id="searchByDate" action="{{ route('search.user.data', ['id' => $id]) }}">
                        <div class="">
                            <div class="form-group px-1 my-1 position-relative month-field_vt">
                                <input type="text" placeholder="Select Month" value="{{ $userSearchedMonth }}"
                                    id="datepicker" name="month" class="form-control">
                                <i class="fontello icon-calander1"></i>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="">
        <div class="table-responsive">
            <table id="basic-datatable" class="table table-striped table-hover table-bordered table-nowrap table-centered table-atten-sheet m-0">
                <thead>
                    <tr>
                        <th>Sr</th>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Working Hours</th>
                        <th>Status</th>
                        <th>GeoTrack</th>
                    </tr>
                </thead>
                <tbody class="Listing_vt">
                    @foreach ($getEmployeeAttendance as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $data->newDate }}</td>
                            
                            @if($data->isWeekDay == true)
                            <td> <i class='fontello icon-star-12 yellow'></i> </td>
                            @elseif($data->isHoliday == true)
                            <td style="background-color:lightyellow;"> <i class='fontello icon-star-12 yellow'></i> </td>
                            @else
                            <td> {{$data->check_in ? date('h:i A',strtotime($data->check_in)):''}} </td>
                            @endif
                        
                            @if($data->isWeekDay == true)
                            <td> <i class='fontello icon-star-12 yellow'></i> </td>
                            @elseif($data->isHoliday == true)
                            <td style="background-color:lightyellow;"> <i class='fontello icon-star-12 yellow'></i> </td>
                            @else
                            <td> {{$data->check_out ? date('h:i A',strtotime($data->check_out)):''}} </td>
                            @endif

                            @if($data->isWeekDay == true)
                            <td> <i class='fontello icon-star-12 yellow'></i> </td>
                            @elseif($data->isHoliday == true)
                            <td style="background-color:lightyellow;"> <i class='fontello icon-star-12 yellow'></i> </td>
                            @else
                            <td> {{$data->totalProduction}} </td>
                            @endif

                            @if ($data->isWeekDay == true)
                                <td><i class='fontello icon-star-12 yellow'></i></td>
                            @elseif($data->isHoliday == true)
                                <td style="background-color:lightyellow;"> <i class='fontello icon-star-12 yellow'></i> </td>
                            @elseif($data->Present == 'Absent')
                                <td><i class='fontello icon-cancel-circled-outline red'></i></td>
                            @elseif($data->Present == 'Full Day')
                                <td><i class='fontello icon-ok-circled2 green'></i></td>
                            @elseif($data->Present == "Present")
                                <td><i class='fontello icon-ok-circled2 green'></i></td>
                            @else
                                <td><i class='fontello icon-day-night yellow'></i></td>
                            @endif
                            <td>
                                <table>
                                    <tr style="background:transparent;">
                                        <td class="p-0 border-0" style="padding: 0 !important; border:0 !important;">
                                            <div class="btn-group dropdown-btn-group dropleft show">
                                                <button type="button" class="dropdown-toggle track-btn" data-toggle="dropdown" aria-expanded="true"><i class="fontello icon-location"></i></button>
                                                <ul class="dropdown-menu" style="padding:15px;">
                                                    <li class="checkbox-row  ">
                                                        <h1 class="track-title">Check In Address :</h1>
                                                        <p class="track-subtitle">2972 Westheimer Rd. Santa Ana, Illinois
                                                            85486 </p>
                                                    </li>
                                                    <li class="checkbox-row ">
                                                        <h1 class="track-title">Check Out Address :</h1>
                                                        <p class="track-subtitle">2972 Westheimer Rd. Santa Ana, Illinois
                                                            85486 </p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td class="p-0 border-0" style="padding: 0 !important; border:0 !important;">
                                            <div class="btn-group dropdown-btn-group dropleft show px-1">
                                                <button type="button" class="dropdown-toggle track-btn"
                                                    data-toggle="dropdown" aria-expanded="true"><img
                                                        src="{{ asset('assets/images/ip-img.png') }}"></button>
                                                <ul class="dropdown-menu" style="padding:15px;">
                                                    <li class="checkbox-row  ">
                                                        <h1 class="track-title">IP Address :</h1>
                                                        <p class="track-subtitle">192.587.254.9</p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>

        $(document).ready(function() {
            $('#dateInput').change(function() {
                $('#myForm').submit();
            });
        });

        $(document).ready(function() {
            $('#datepicker').change(function() {
                $('#searchByDate').submit();
            });
        });
        $(document).ready(function() {
            var currentDate = new Date();
            var currentMonth = currentDate.getMonth() + 1;
            var currentYear = currentDate.getFullYear();
            $('#datepicker').datepicker({
                format: "MM yyyy",
                minViewMode: "months",
                endDate: currentMonth + "-" + currentYear
            });
        });
        $(document).ready(function() {
            var currentDate = new Date();
            $('#dateInput').datepicker({
                format: "dd-mm-yyyy",
                endDate: currentDate,
                maxDate: new Date(),
            });
        });
    </script>
@endsection
