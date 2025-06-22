@extends('layouts.admin.master')
@section('content')
    <div class="profile-page-detail">
        <div class='profile-area mb-2'>
            <div class='row'>
                <div class='col-lg-6 profile-left-section my-2'>
                    <div class='d-flex'>
                        <div class='profile-img-holder'>
                            <img class='img-fluid' src="" />
                        </div>
                        <div class='right-section-detail'>
                            <h1>Muhammad Babar</h1>
                                <p>Designer</p>
                                <p>viion</p>

                            <p class='mb-0'>
                                <bold>Branch ID :</bold>01
                            </p>
                            <p class='mb-0'>
                                <bold>Employee ID :</bold> 02
                            </p>
                            <p>
                                <bold>Date of Join :</bold>
                               2-5-2021
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
                        <p>21654856</p>
                    </div>
                    <div class='person-detail-widget mb-1'>
                        <h2>Email :</h2>
                        <p>shabbirsuleman68@gmail.com</p>
                    </div>
                    <div class='person-detail-widget mb-1'>
                        <h2>Birthday :</h2>
                        <p>2-may</p>
                    </div>
                    <div class='person-detail-widget mb-1'>
                        <h2>Address :</h2>
                        <p>kjdhfiudsh</p>
                    </div>
                    <div class='person-detail-widget mb-1'>
                        <h2>Gender :</h2>
                        <p>male</p>
                    </div>
                    <div class='person-detail-widget mb-1'>
                        <h2>NIC :</h2>
                        <p>35252-321965564-5</p>
                    </div>
                </div>

            </div>
        </div>
        <div class="">
            <div class="">
                <div class="card">
                    <div class="card-body p-0">
                        <form>
                            <div id="basicwizard">
                                <!-- <ul class="nav nav-pills bg-light mb-4 navtab-bg">
                                    <li class="nav-item">
                                        <a href="#basictab1" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2 active show"> 
                                            Profile
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#basictab2" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                            Attendance
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#basictab3" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                            Current
                                        </a>
                                    </li>
                                </ul> -->
                                    <div class="nav nav-pills navtab-bg nav-pills-tab text-center mb-2" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                        <a class="nav-link mt-2 py-2 active show profile-tabs"  data-toggle="tab" aria-selected="true" role="tab" href="#basictab1">Profile
                                        </a>
                                        <a class="nav-link mt-2 py-2 profile-tabs" data-toggle="tab" href="#basictab2" role="tab" aria-controls="custom-v-pills-shipping"
                                            aria-selected="false">Attendance</a>
                                        <a class="nav-link mt-2 py-2 profile-tabs" data-toggle="tab" href="#basictab3" role="tab" aria-controls="custom-v-pills-shipping"
                                            aria-selected="false">Current</a>
                                    </div>

                                <div class="tab-content b-0 mb-0 pt-0">
                                    <div class="tab-pane fade show active" id="basictab1">
                                        <div class="row">
                                            <div class="col-lg-4 mb-2">
                                                <div class="personal-info">
                                                    <h1 class="mb-2">Personal Information</h1>
                                                    <div class="person-detail-widget mb-1">
                                                        <h2>Father’s Name :</h2>
                                                        <p>Muhammad Haroon</p>
                                                    </div>
                                                    <div class="person-detail-widget mb-1">
                                                        <h2>Mother’s Name :</h2>
                                                        <p>Saniya Khalid</p>
                                                    </div>
                                                    <div class="person-detail-widget mb-1">
                                                        <h2>Nationality :</h2>
                                                        <p>Pakistani</p>
                                                    </div>
                                                    <div class="person-detail-widget mb-1">
                                                        <h2>Place of Birth :</h2>
                                                        <p>Lahore</p>
                                                    </div>
                                                    <div class="person-detail-widget mb-1">
                                                        <h2>Religion :</h2>
                                                        <p>Islam</p>
                                                    </div>
                                                    <div class="person-detail-widget mb-1">
                                                        <h2>Blood Group :</h2>
                                                        <p>B+</p>
                                                    </div>
                                                    <div class="person-detail-widget mb-1">
                                                        <h2>Marital Status :</h2>
                                                        <p>Single</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-8 mb-2">
                                                <div class="Emergency-info">
                                                    <h1 class="mb-2">Emergency Contact</h1>
                                                    <h3>Primary</h3>
                                                    <div class="person-detail-widget mb-1">
                                                        <h2>Name :</h2>
                                                        <p>Muhammad Haroon</p>
                                                    </div>
                                                    <div class="person-detail-widget mb-1">
                                                        <h2>Relationship :</h2>
                                                        <p>Father</p>
                                                    </div>
                                                    <div class="person-detail-widget mb-1">
                                                        <h2>Phone :</h2>
                                                        <p>03334466778</p>
                                                    </div>
                                                    <div class="border-top">
                                                        <h3>Secondary</h3>
                                                    </div>
                                                    <div class="person-detail-widget mb-1">
                                                        <h2>Name :</h2>
                                                        <p>Muhammad Haroon</p>
                                                    </div>
                                                    <div class="person-detail-widget mb-1">
                                                        <h2>Relationship :</h2>
                                                        <p>Father</p>
                                                    </div>
                                                    <div class="person-detail-widget mb-1">
                                                        <h2>Phone :</h2>
                                                        <p>03334466778</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- end row -->
                                        <div class="row">
                                            <div class="col-lg-4 mb-2">
                                                <div class="bank-info">
                                                    <h1 class="mb-2">Bank Information</h1>
                                                    <div class="person-detail-widget mb-1">
                                                        <h2>Bank Name :</h2>
                                                        <p>ICI Bank</p>
                                                    </div>
                                                    <div class="person-detail-widget mb-1">
                                                        <h2>Bank Account No :</h2>
                                                        <p>1236785490</p>
                                                    </div>
                                                    <div class="person-detail-widget mb-1">
                                                        <h2>IFSC Code :</h2>
                                                        <p>IC124504</p>
                                                    </div>
                                                    <div class="person-detail-widget mb-1">
                                                        <h2>PAN No :</h2>
                                                        <p>TC005YC</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-8 mb-2">
                                                <div class="family-info">
                                                    <h1 class="mb-2">Family Information</h1>
                                                    <div class="table-responsive">
                                                        <table id="table1" class="table table-striped table-bordered table-nowrap table-hover table-centered m-0">
                                                            <thead class="table-head border-top border-bottom">
                                                                <tr>
                                                                    <th>Name</th>
                                                                    <th>Relationship</th>
                                                                    <th>Date of Birth</th>
                                                                    <th>Phone</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="Listing_vt">
                                                                <tr>
                                                                    <td>Muhammad Haroon</td>
                                                                    <td>Father</td>
                                                                    <td>4th June 1999</td>
                                                                    <td>03337788996</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Muhammad Haroon</td>
                                                                    <td>Father</td>
                                                                    <td>4th June 1999</td>
                                                                    <td>03337788996</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- end row -->
                                        <div class="row">
                                            <div class="col-lg-4 mb-2">
                                                <div class="education-info">
                                                    <h1 class="mb-2">Education</h1>
                                                    <div class="education-detail">
                                                        <div class="detail-line"></div>
                                                        <h2>Institute of Management Sciences</h2>
                                                        <h6>BS in Computer Science</h6>
                                                        <p>2020-2024</p>
                                                    </div>
                                                    <div class="education-detail">
                                                        <div class="detail-line"></div>
                                                        <h2>Concordia College</h2>
                                                        <h6>Intermediate in Computer Science</h6>
                                                        <p>2019-2020</p>
                                                    </div>
                                                    <div class="education-detail">
                                                        <div class="detail-line"></div>
                                                        <h2>Lahore Grammar School</h2>
                                                        <h6>Matric in Computer Science</h6>
                                                        <p>2018-2019</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-8 mb-2">
                                                <div class="family-info">
                                                    <h1 class="mb-2">Family Information</h1>
                                                    <div class="education-detail">
                                                        <div class="detail-line"></div>
                                                        <h2>Web Designer at Zen Technolgies</h2>
                                                        <h6>January 2020 - Present (3 years 4 months)</h6>  
                                                    </div>
                                                    <div class="education-detail">
                                                        <div class="detail-line"></div>
                                                        <h2>Graphic Designer at Ron-Tech</h2>
                                                        <h6>February 2019 - Present (2 years 3 months) </h6>
                                                    </div>
                                                    <div class="education-detail">
                                                        <div class="detail-line"></div>
                                                        <h2>Web and Graphics Designer at Delta Technology</h2>
                                                        <h6>January 2019 - Present (3 years 4 months)</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- end row -->
                                    </div>

                                    <div class="tab-pane" id="basictab2">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="check-section">
                                                    <div class="row">
                                                        <div class="col-lg-4 mb-2">
                                                            <div class="Punch-area">
                                                                <h1>Time Utilization</h1>
                                                                <p>2454</p>
                                                                <div class="punch-widget mb-2">
                                                                    <i class="fontello icon-thumb"></i>
                                                                    <div>
                                                                        <h3>Time In</h3>
                                                                        <p>
                                                                            54-5456
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="punch-widget mb-2">
                                                                    <i class="fontello icon-thumb text-danger"></i>
                                                                    <div>
                                                                        <h3>Time out</h3>
                                                                        <p>
                                                                        566-245
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8 mb-2">
                                                            <div class='stats-area'>
                                                                <h1>Statistics</h1>
                                                                <div class='stats-widget'>
                                                                    <div class='d-flex justify-content-between align-items-center'>
                                                                        <h2>Today Duration</h2>
                                                                        <p>1<bold>01</bold>
                                                                        </p>
                                                                    </div>
                                                                    <div class="progress">
                                                                        <div class="progress-bar first-progress" role="progressbar"
                                                                            style="" aria-valuenow="25" aria-valuemin="0"
                                                                            aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                                <div class='stats-widget'>
                                                                    <div class='d-flex justify-content-between align-items-center'>
                                                                        <h2>This Week</h2>
                                                                        <p>22<bold>10</bold>
                                                                        </p>
                                                                    </div>
                                                                    <div class="progress">
                                                                        <div class="progress-bar second-progress" role="progressbar"
                                                                            style="" aria-valuenow="25"
                                                                            aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                                <div class='stats-widget'>
                                                                    <div class='d-flex justify-content-between align-items-center'>
                                                                        <h2>This Month</h2>
                                                                        <p>20<bold>20
                                                                            </bold>
                                                                        </p>
                                                                    </div>
                                                                    <div class="progress">
                                                                        <div class="progress-bar third-progress" role="progressbar"
                                                                            style="" aria-valuenow="25"
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
                                                            <form method="get" id="myForm" action="">
                                                                <div class="form-group position-relative caret-holder px-1 my-1">
                                                                    <input type="text" name="date" id="dateInput" value=""
                                                                        name="day" class="form-control" placeholder="Select Date">
                                                                    <i class="fontello icon-calander1"></i>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="col-lg-4" style="padding-right:3px !important;padding-left:3px !important;">
                                                            <form method="get" id="searchByDate" action="">
                                                                <div class="">
                                                                    <div class="form-group px-1 my-1 position-relative month-field_vt">
                                                                        <input type="text" placeholder="Select Month" value=""
                                                                            id="datepicker" name="month" class="form-control">
                                                                        <i class="fontello icon-calander1"></i>
                                                                    </div>
                                                                </div>
                                                            </form>
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

                                                                    <tr>
                                                                        <td>01</td>
                                                                        <td>12-2-2020</td>
                                                                        <td> <i class='fontello icon-star-12 yellow'></i> </td>
                                                                        <td style="background-color:lightyellow;"> <i class='fontello icon-star-12 yellow'></i> </td>
                                                                        <td>25</td>
                                                                        <td>25</td>
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
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div> <!-- end col -->
                                        </div> <!-- end row -->
                                    </div>

                                    <div class="tab-pane" id="basictab3">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="text-center">
                                                    <h2 class="mt-0"><i class="mdi mdi-check-all"></i></h2>
                                                    <h3 class="mt-0">Thank you !</h3>

                                                    <p class="w-75 mb-2 mx-auto">Quisque nec turpis at urna dictum luctus. Suspendisse convallis dignissim eros at volutpat. In egestas mattis dui. Aliquam
                                                        mattis dictum aliquet.</p>

                                                    <div class="mb-3">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                            <label class="custom-control-label" for="customCheck1">I agree with the Terms and Conditions</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- end col -->
                                        </div> <!-- end row -->
                                    </div>

                                </div> <!-- tab-content -->
                            </div> <!-- end #basicwizard-->
                        </form>
                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div>
        </div>
        <!-- <div class="check-section">
            <div class="row">
                <div class="col-lg-4 mb-2">
                    <div class="Punch-area">
                        <h1>Time Utilization</h1>
                        <p>2454</p>
                        <div class="punch-widget mb-2">
                            <i class="fontello icon-thumb"></i>
                            <div>
                                <h3>Time In</h3>
                                <p>
                                    54-5456
                                </p>
                            </div>
                        </div>
                        <div class="punch-widget mb-2">
                            <i class="fontello icon-thumb text-danger"></i>
                            <div>
                                <h3>Time out</h3>
                                <p>
                                   566-245
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 mb-2">
                    <div class='stats-area'>
                        <h1>Statistics</h1>
                        <div class='stats-widget'>
                            <div class='d-flex justify-content-between align-items-center'>
                                <h2>Today Duration</h2>
                                <p>1<bold>01</bold>
                                </p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar first-progress" role="progressbar"
                                    style="" aria-valuenow="25" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class='stats-widget'>
                            <div class='d-flex justify-content-between align-items-center'>
                                <h2>This Week</h2>
                                <p>22<bold>10</bold>
                                </p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar second-progress" role="progressbar"
                                    style="" aria-valuenow="25"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class='stats-widget'>
                            <div class='d-flex justify-content-between align-items-center'>
                                <h2>This Month</h2>
                                <p>20<bold>20
                                    </bold>
                                </p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar third-progress" role="progressbar"
                                    style="" aria-valuenow="25"
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
                    <form method="get" id="myForm" action="">
                        <div class="form-group position-relative caret-holder px-1 my-1">
                            <input type="text" name="date" id="dateInput" value=""
                                name="day" class="form-control" placeholder="Select Date">
                            <i class="fontello icon-calander1"></i>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4" style="padding-right:3px !important;padding-left:3px !important;">
                    <form method="get" id="searchByDate" action="">
                        <div class="">
                            <div class="form-group px-1 my-1 position-relative month-field_vt">
                                <input type="text" placeholder="Select Month" value=""
                                    id="datepicker" name="month" class="form-control">
                                <i class="fontello icon-calander1"></i>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> -->
    </div>
    
    <!-- <div class="">
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

                        <tr>
                            <td>01</td>
                            <td>12-2-2020</td>
                            <td> <i class='fontello icon-star-12 yellow'></i> </td>
                            <td style="background-color:lightyellow;"> <i class='fontello icon-star-12 yellow'></i> </td>
                            <td>25</td>
                            <td>25</td>
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
                </tbody>
            </table>
        </div>
    </div> -->

    <script>

    </script>
@endsection
