@extends('layouts.head_office_app')
@section('title', 'Head office Profile')
@section('content')
@section('styles')
    <link href="https://qitech.kiebit.com/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.css" rel="stylesheet">
    <style>
        /* Popup container - can be anything you want */
        .popup {
            position: relative;
            display: inline-block;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* The actual popup */
        .popup .popuptext {
            visibility: hidden;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px 0;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -80px;
        }

        /* Popup arrow */
        .popup .popuptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent transparent;
        }

        /* Toggle this class - hide and show the popup */
        .popup .show {
            visibility: visible;
            -webkit-animation: fadeIn 1s;
            animation: fadeIn 1s;
        }

        /* Add animation (fade in the popup) */
        @-webkit-keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }


        .profile-pic {
            color: transparent;
            transition: all .3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            transition: all .3s ease;

            input {
                display: none;
            }

            img {
                position: absolute;
                object-fit: cover;
                width: 120px;
                height: 120px;
                box-shadow: 0 0 10px 0 rgba(255, 255, 255, .35);
                border-radius: 100px;
                z-index: 0;
            }

            .-label {
                cursor: pointer;
                height: 120px;
                width: 120px;
            }

            &:hover {
                .-label {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    background-color: rgba(0, 0, 0, .8);
                    z-index: 10000;
                    color: rgb(250, 250, 250);
                    ;
                    transition: background-color .2s ease-in-out;
                    border-radius: 100px;
                    margin-bottom: 0;
                }
            }

            span {
                display: inline-flex;
                padding: .2em;
                height: 2em;
            }
            
            
        }
        #logs-table_wrapper{
            width: 80%;
            margin: 0 auto;
        }
        div.dt-container div.dt-layout-row div.dt-layout-cell{
            width: 80% !important;
            display: flex !important;
            margin: 0 auto;
        }
        #logs-table_wrapper .dt-start, #logs-table_wrapper .dt-end {
            width: fit-content !important;
        }
        #session-dataTable_wrapper .dt-start, #session-dataTable_wrapper .dt-end {
            width: fit-content !important;
        }
        #logs-table_wrapper .dt-search input{
            padding-left: 28px !important;
        }
        #logs-table_wrapper .dt-search input{
            padding-left: 28px !important;
        }
        #session-dataTable_wrapper .dt-search input{
            padding-left: 20px !important;
        }
    </style>
@endsection

<input type="hidden" id="route" value="{{ route('head_office.update_status') }}">
<input type="hidden" id="route_active" value="{{ route('head_office.update_active') }}">
<input type="hidden" id="route_disturb" value="{{ route('head_office.update_disturb') }}">
<input type="hidden" id="_token" value="{{ csrf_token() }}">
<div id="content">

    <!-- Profile page heading -->
    <div class="content-page-heading">
        My Profile
    </div>

    <div class="profile-center-area">

        <nav class="page-menu bordered">
            <ul class="nav nav-tab main_header">
                <li><a href="javascript:void(0)" class="active" data-bs-toggle="tab" data-bs-target="#profile"
                        id="aboutMeProfile" onclick="changeTabUrl('aboutMeProfile')">About Me<span></span></a></li>
                <li><a href="javascript:void(0)" data-bs-toggle="tab" data-bs-target="#working_status"
                        id="workingProfile" onclick="changeTabUrl('workingProfile')">Working Status<span></span></a>
                </li>
                <li><a href="javascript:void(0)" data-bs-toggle="tab" data-bs-target="#session_history"
                        id="sessionProfile" onclick="changeTabUrl('sessionProfile')">Session History<span></span></a>
                </li>
                <li><a href="{{route('head_office.view_profile_logs')}}"  
                    id="aboutMeProfile" onclick="changeTabUrl('aboutMeProfile')">My Activity<span></span></a></li>
            </ul>
        </nav>
        <hr class="hrBeneathMenu">
        <!-- profile page contents -->
        <div class="tab-content" id="myTabContent">
            <div id="working_status" class="working_status tab-pane">

                <div class='profile-page-working-status-contents profile-page-contents hide-placeholder-parent'>
                    <div class="inputSection">Work Pattern
                        <div class="content_right">
                            <span id="editButton" onclick="enableEditing()">
                                <svg width="15" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11 3.99998H6.8C5.11984 3.99998 4.27976 3.99998 3.63803 4.32696C3.07354 4.61458 2.6146 5.07353 2.32698 5.63801C2 6.27975 2 7.11983 2 8.79998V17.2C2 18.8801 2 19.7202 2.32698 20.362C2.6146 20.9264 3.07354 21.3854 3.63803 21.673C4.27976 22 5.11984 22 6.8 22H15.2C16.8802 22 17.7202 22 18.362 21.673C18.9265 21.3854 19.3854 20.9264 19.673 20.362C20 19.7202 20 18.8801 20 17.2V13M7.99997 16H9.67452C10.1637 16 10.4083 16 10.6385 15.9447C10.8425 15.8957 11.0376 15.8149 11.2166 15.7053C11.4184 15.5816 11.5914 15.4086 11.9373 15.0627L21.5 5.49998C22.3284 4.67156 22.3284 3.32841 21.5 2.49998C20.6716 1.67156 19.3284 1.67155 18.5 2.49998L8.93723 12.0627C8.59133 12.4086 8.41838 12.5816 8.29469 12.7834C8.18504 12.9624 8.10423 13.1574 8.05523 13.3615C7.99997 13.5917 7.99997 13.8363 7.99997 14.3255V16Z"
                                        stroke="#888" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                            <span id="cancelButton" onclick="cancelEditing()" style="display: none;">
                                <svg width="15" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18 6L6 18M6 6L18 18" stroke="#888" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span> &nbsp;
                            <span id="updateButton"
                                onclick="event.preventDefault(); document.getElementById('update-head-office-timing').submit();"
                                style="display: none;">
                                <svg width="15" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 6L9 17L4 12" stroke="#888" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <form id="update-head-office-timing" method="post"
                        action="{{ route('head_office.update_ho_timing') }}">
                        <table class="table table-striped" id="scheduleTable">
                            <thead>
                                <tr>
                                    <th style="display: none;"></th>
                                    <th>Day</th>
                                    <th>From</th>
                                    <th>To</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $days = [
                                        'monday',
                                        'tuesday',
                                        'wednesday',
                                        'thursday',
                                        'friday',
                                        'saturday',
                                        'sunday',
                                    ];
                                @endphp

                                @csrf

                                @foreach ($days as $day)
                                    @php
                                        $var = 'is_open_' . $day;
                                        $start = $day . '_start_time';
                                        $end = $day . '_end_time';
                                    @endphp
                                    <tr class="day_{{ $head_office_timing->$var }}">
                                        <td class="checkbox" style="display: none;">
                                            <input type="checkbox" value="1"
                                                {{ $head_office_timing->$var ? 'checked' : '' }}
                                                name="{{ $var }}" />
                                        </td>
                                        <td>{{ ucfirst($day) }}</td>
                                        <td>{{ $head_office_timing->$start? strtolower($head_office_timing->convert_time($head_office_timing->$start)):"Off" }}
                                        </td>
                                        <td>{{$head_office_timing->$end? strtolower($head_office_timing->convert_time($head_office_timing->$end)) :"Off"}}
                                        </td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </form>
                    <div class="inputSection">Leave
                        <span style="float: right;cursor:pointer" data-bs-toggle="modal"
                            data-bs-target="#add_new_holiday">
                            <svg width="15" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 5V19M5 12H19" stroke="#888" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </span>
                    </div>
                    @if (isset($head_office_user_holidays) && count($head_office_user_holidays) > 0)
                    <table class="table table-striped">
                        <tr>
                            <th>Away From</th>
                            <th>Return On</th>
                            <th>Total Days</th>
                            <th>Type</th>
                            <th></th>
                        </tr>
                        @foreach ($head_office_user_holidays as $holiday)
                            <tr>
                                <td>
                                    {{ $holiday->away_from->format(config('app.dateFormat')) }}
                                </td>
                                <td>
                                    {{ $holiday->return_on->format(config('app.dateFormat')) }}
                                </td>
                                <td>
                                    {{ $holiday->total_days }}
                                </td>
                                <td>
                                    {{ $holiday->type }}
                                </td>
                                <td>
                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a href="{{ route('head_office.delete_head_office_user_holiday', $holiday->id) }}"
                                            class="delete_button"
                                            data-msg="Are you sure, you want to end this session?"
                                            title="Delete Holiday">
                                            <svg width="20" style="color: white" height="24"
                                                viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach


                    </table>
                    @else
                    <p class="">You have no leave booked</p>
                    @endif
                    <div class="inputSection">Upcoming Holidays
                        <span class="content_right">
                            <span id="show_all" class="inputSection">
                                <svg width="15" height="16" viewBox="0 0 22 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M1.42012 8.71318C1.28394 8.49754 1.21584 8.38972 1.17772 8.22342C1.14909 8.0985 1.14909 7.9015 1.17772 7.77658C1.21584 7.61028 1.28394 7.50246 1.42012 7.28682C2.54553 5.50484 5.8954 1 11.0004 1C16.1054 1 19.4553 5.50484 20.5807 7.28682C20.7169 7.50246 20.785 7.61028 20.8231 7.77658C20.8517 7.9015 20.8517 8.0985 20.8231 8.22342C20.785 8.38972 20.7169 8.49754 20.5807 8.71318C19.4553 10.4952 16.1054 15 11.0004 15C5.8954 15 2.54553 10.4952 1.42012 8.71318Z"
                                        stroke="#888" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M11.0004 11C12.6573 11 14.0004 9.65685 14.0004 8C14.0004 6.34315 12.6573 5 11.0004 5C9.34355 5 8.0004 6.34315 8.0004 8C8.0004 9.65685 9.34355 11 11.0004 11Z"
                                        stroke="black" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                            <span id="hide_all" style="display: none" class="inputSection">
                                <svg width="15" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18 6L6 18M6 6L18 18" stroke="#888" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </span>
                        </span>
                    </div>
                    <table class="table table-striped" id="holidayTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="holidayTbody">
                            <tr>
                                <td>New Year's Day</td>
                                <td>01-Jan-2024</td>
                                <td>Working? <a href="#">Yes</a> / <a href="#">No</a></td>
                            </tr>
                            @foreach ($bank_holidays as $divion)
                                <tr style="display: {{ $divion->yes || $divion->no ? 'none' : '' }}"
                                    class="{{ $divion->yes || $divion->no ? 'hidden_entries' : '' }}">
                                    <td>
                                        {{ $divion->title }}
                                    </td>
                                    <td>
                                        {{ $divion->date->format(config('app.dateFormat')) }}
                                    </td>
                                    @php $date = $divion->date->format('d-m-Y'); @endphp
                                    <td>Working?
                                        <a class="{{ $divion->yes ? 'disabled' : '' }}"
                                            href="{{ route('head_office.update_head_office_user_bank_holiday_selection', ['title' => $divion->title, 'date' => $date, 'is_working' => 1, 'reference_id' => $divion->reference_id]) }}">Yes</a>
                                        /

                                        <a class="{{ $divion->no ? 'disabled' : '' }}"
                                            href="{{ route('head_office.update_head_office_user_bank_holiday_selection', ['title' => $divion->title, 'date' => $date, 'is_working' => 0, 'reference_id' => $divion->reference_id]) }}">No</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="inputSection">User Status</div>
                        <div class="form-check">
                            <input type="checkbox" @checked($head_office_user->is_active == 1) class="form-check-input"
                                name="user_status" onchange="updateActive(event)" id="user_active" />
                            <label class="form-check-label" for="user_active">Active</label>
                    </div>
                        <div class="form-check">
                            <input type="checkbox" @checked($head_office_user->do_not_disturb == 1) class="form-check-input"
                                name="user_status_disturb" onchange="updateDisturb(event)" id="user_not_active" />
                            <label class="form-check-label" for="user_not_active">Do not disturb</label>
                    </div>
                    <div class="inputSection pt-1">Work Status</div>

                    <div style="display:flex; flex-wrap: wrap;" class="workStatusRadioButtons">
                        <div class="form-check">
                            <input type="radio" @checked($head_office_user->work_status == 0) class="form-check-input"
                                name="c1" value="1" onchange="updateStatus(0)" id="inTheOffice" />
                            <label class="form-check-label" for="inTheOffice">In the office</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" @checked($head_office_user->work_status == 1)
                                name="c1" value="2" onchange="updateStatus(1)" id="workingHome" />
                            <label class="form-check-label" for="workingHome">On a break</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" @checked($head_office_user->work_status == 2)
                                name="c1" value="3" onchange="updateStatus(2)" id="outSick" />
                            <label class="form-check-label" for="outSick">Working from home</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" @checked($head_office_user->work_status == 3)
                                name="c1" value="4" onchange="updateStatus(3)" id="onBreak" />
                            <label class="form-check-label" for="onBreak">Away sick</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" @checked($head_office_user->work_status == 4)
                                name="c1" value="5" onchange="updateStatus(4)" id="outOffice" />
                            <label class="form-check-label" for="outOffice">Working from outside</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" @checked($head_office_user->work_status == 5)
                                name="c1" value="6" onchange="updateStatus(5)" id="outOffice1" />
                            <label class="form-check-label" for="outOffice1">Lone working</label>
                        </div>

                    </div>

                </div>
            </div>
            <div id="profile" class="profile tab-pane show active">
                <div class="text-center mt-3">

                    <div class="profile-pic">
                        <label class="-label" for="file">
                            <span class="glyphicon glyphicon-camera"></span>
                            <span>Change Image</span>
                        </label>
                        <input id="file" type="file" onchange="loadFile(event)" accept=".png , .jpg , .jpeg" />
                        <img src="{{ $user->logo }}" id="output" width="200" />
                    </div>

                    <div class="profile-user-name-heading"><a href="#">{{ $user->name }}</a></div>
                </div>
                <div class="profile-page-contents hide-placeholder-parent">
                    <div class="w-100 d-flex justify-content-between align-items-center">
                        <div class="inputSection">Personal</div>
                        {{-- <span style="float: right;cursor:pointer" onclick="add_area()">
                            <svg width="15" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 5V19M5 12H19" stroke="#888" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </span> --}}
                        <div class="position-relative"><svg id="addExpertiseIcon" width="15" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5V19M5 12H19" stroke="#888" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                        </svg>
                        <p onclick="add_area()" id="addExpertiseBtn" class="position-absolute rounded-3 p-2" style="border:1px solid gray; width:120px; display:none; cursor: pointer;">
                            Add Expertise
                        </p>

                    </div>
                    </div>
                    <script>
                        $(document).ready(function() {
                            $('#addExpertiseIcon').click(function(event) {
                                event.stopPropagation();
                                $('#addExpertiseBtn').toggle();
                            });
                    
                            $(document).click(function(event) {
                                if (!$(event.target).closest('#addExpertiseIcon, #addExpertiseBtn').length) {
                                    $('#addExpertiseBtn').hide();
                                }
                            });
                        });
                    </script>
                    
                    <label class="inputGroup">Job title:
                        <input type="text" onfocusout="updatePosition({{ $user->id }},this)"
                            placeholder="Add a position" value="{{ $user->selected_head_office_position }}">
                    </label>
                    <label class="inputGroup">Location:
                        <input type="text" placeholder="Add location"
                            value="{{ $user->selected_head_office_user->location }}"
                            onfocusout="updateLocation(this)">
                    </label>


                    @if ($user->selected_head_office_user->head_office_user_area->count() > 0)
                    <div class="inputSection">Expertise
                        
                    </div>
                    @endif

                    <div id="area_div">

                    </div>
                    @if ($user->selected_head_office_user->head_office_user_area->count() > 0)
                    <div id="area_values">
                        @foreach ($user->selected_head_office_user->head_office_user_area as $area)
                            <label class="inputGroup" id="{{ $area->id }}">Area:
                                <input style="width:40%" type="text" placeholder="Add area"
                                    value="{{ $area->area }}">
                                <div class="custom_overlay">
                                    <span class="custom_overlay_inner">
                                        <a href="{{ route('head_office.delete_area', $area->id) }}"
                                            class="delete_button"
                                            data-msg="Are you sure you want to delete this area?">
                                            <svg width="20" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5"
                                                    stroke="#888" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                        </a>
                                    </span>
                                </div>
                            </label>
                            <label class="inputGroup" id="{{ $area->id }}">Level:
                                <input style="width:40%" type="text" placeholder="Add Level"
                                    value="{{ $area->level }}">
                                <div class="custom_overlay">
                                    <span class="custom_overlay_inner">
                                        <a href="{{ route('head_office.delete_area', $area->id) }}"
                                            class="delete_button"
                                            data-msg="Are you sure you want to delete this area?">
                                            <svg width="20" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5"
                                                    stroke="#888" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                        </a>
                                    </span>
                                </div>
                            </label>
                        @endforeach

                    </div>
                    @endif    




                    






                    <div class="inputSection">Contact Info
                        <span style="float: right;cursor:pointer" id="dropdownMenuButton_x"
                            data-bs-toggle="dropdown">
                            <svg width="15" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 5V19M5 12H19" stroke="#888" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </span>
                        <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton_x"
                            style="">
                            <a href="javascript:void(0)" class="dropdown-item" onclick="add_phone()">Add Phone</a>
                            <a href="javascript:void(0)" class="dropdown-item" onclick="add_email()">Add Email</a>
                        </div>
                    </div>
                    <div>
                        <label class="inputGroup popup email-pop-wrapper">Email:
                            <input value="{{ $user->active_email }}" readonly type="email" onclick="myFunction()"
                                placeholder="Add an email">
                            <span class="popuptext" id="myPopup">This can be edited from the user account. Go there now
                                <a href='{{ route('user.view_profile') }}' target="_blanks">Yes</a> / No</span>
                                @if ($user->is_email_hidden)
                                <div class="badge bg-info position-absolute"
                                    style="
                                font-size: 10px;
                                top: 4px;
                                right: 0px;
                            ">
                                    hidden</div>
                            @endif
                        </label>
                        <div class="custom_overlay">
                            <span class="custom_overlay_inner" style="transform:translateY(-56%);">
                                @if (!$user->is_email_hidden)
                                    <a href="{{ route('user.hide.email', ['type' => 1, 'is_sub_contact' => false]) }}"
                                        title="hide this contact?"
                                        data-msg="Are you sure you want to hide this?" class="hide_email">
                                        <svg width="20" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.7429 5.09232C11.1494 5.03223 11.5686 5 12.0004 5C17.1054 5 20.4553 9.50484 21.5807 11.2868C21.7169 11.5025 21.785 11.6103 21.8231 11.7767C21.8518 11.9016 21.8517 12.0987 21.8231 12.2236C21.7849 12.3899 21.7164 12.4985 21.5792 12.7156C21.2793 13.1901 20.8222 13.8571 20.2165 14.5805M6.72432 6.71504C4.56225 8.1817 3.09445 10.2194 2.42111 11.2853C2.28428 11.5019 2.21587 11.6102 2.17774 11.7765C2.1491 11.9014 2.14909 12.0984 2.17771 12.2234C2.21583 12.3897 2.28393 12.4975 2.42013 12.7132C3.54554 14.4952 6.89541 19 12.0004 19C14.0588 19 15.8319 18.2676 17.2888 17.2766M3.00042 3L21.0004 21M9.8791 9.87868C9.3362 10.4216 9.00042 11.1716 9.00042 12C9.00042 13.6569 10.3436 15 12.0004 15C12.8288 15 13.5788 14.6642 14.1217 14.1213"
                                                stroke="#888" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                @else
                                    <a href="{{ route('user.hide.email', ['type' => 0, 'is_sub_contact' => false]) }}"
                                        title="show this contact?"
                                        data-msg="Are you sure you want to display this?" class="hide_email">
                                        <svg width="20" height="16" viewBox="0 0 22 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M1.42012 8.71318C1.28394 8.49754 1.21584 8.38972 1.17772 8.22342C1.14909 8.0985 1.14909 7.9015 1.17772 7.77658C1.21584 7.61028 1.28394 7.50246 1.42012 7.28682C2.54553 5.50484 5.8954 1 11.0004 1C16.1054 1 19.4553 5.50484 20.5807 7.28682C20.7169 7.50246 20.785 7.61028 20.8231 7.77658C20.8517 7.9015 20.8517 8.0985 20.8231 8.22342C20.785 8.38972 20.7169 8.49754 20.5807 8.71318C19.4553 10.4952 16.1054 15 11.0004 15C5.8954 15 2.54553 10.4952 1.42012 8.71318Z"
                                                stroke="#888" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M11.0004 11C12.6573 11 14.0004 9.65685 14.0004 8C14.0004 6.34315 12.6573 5 11.0004 5C9.34355 5 8.0004 6.34315 8.0004 8C8.0004 9.65685 9.34355 11 11.0004 11Z"
                                                stroke="#888" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                @endif
                            </span>
                        </div>
                    </div>
                    @foreach ($contacts->where('type', 1) as $k => $contact)
                        <label class="inputGroup popup">Email :
                            <input type="text" placeholder="Add email" id="email_{{ $contact->id }}"
                                value="{{ $contact->contact }}" type="text"
                                onfocusout="updateEmail({{ $contact->id }},this)"
                                style="background: {{ $contact->is_email_hidden ? 'rgb(239 239 239 / 78%)' : '' }}">

                            
                            <div class="custom_overlay">
                                <span class="custom_overlay_inner ">
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('head_office.delete_contact', $contact->id) }}"
                                            class="delete_button"
                                            data-msg="Are you sure you want to delete this contact?">
                                            <svg width="20" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5"
                                                    stroke="#888" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                        </a>
                                        
                                    </div>
                                </span>
                            </div>
                        </label>
                    @endforeach
                    <div id="email_div">

                    </div>
                    <div>
                        <label class="inputGroup popup">Phone:
                            <input type="text" id="telephone" placeholder="Add a phone number" value="{{ $user->active_contact }}"
                                readonly type="text" onclick="byPhone()">
                            <span class="popuptext" id="myPopupPhone">This can be edited from the user account. Go there
                                now
                                <a href='{{ route('user.view_profile') }}' target="_blanks">Yes</a> / No</span>
                            
                                @if ($user->is_phone_hidden)
                                <div class="badge bg-info position-absolute"
                                    style="
                                font-size: 10px;
                                top: 4px;
                                right: 0px;
                            ">
                                    hidden</div>
                            @endif
                        </label>
                        <div class="custom_overlay">
                            <span class="custom_overlay_inner" style="transform:translateY(-56%);">
                                &nbsp;
                                @if (!$user->is_phone_hidden)
                                    <a href="{{ route('user.hide.phone', ['type' => 1, 'is_sub_contact' => false]) }}"
                                        title="hide this contact?"
                                        data-msg="Are you sure you want to hide this?" class="hide_email">
                                        <svg width="20" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.7429 5.09232C11.1494 5.03223 11.5686 5 12.0004 5C17.1054 5 20.4553 9.50484 21.5807 11.2868C21.7169 11.5025 21.785 11.6103 21.8231 11.7767C21.8518 11.9016 21.8517 12.0987 21.8231 12.2236C21.7849 12.3899 21.7164 12.4985 21.5792 12.7156C21.2793 13.1901 20.8222 13.8571 20.2165 14.5805M6.72432 6.71504C4.56225 8.1817 3.09445 10.2194 2.42111 11.2853C2.28428 11.5019 2.21587 11.6102 2.17774 11.7765C2.1491 11.9014 2.14909 12.0984 2.17771 12.2234C2.21583 12.3897 2.28393 12.4975 2.42013 12.7132C3.54554 14.4952 6.89541 19 12.0004 19C14.0588 19 15.8319 18.2676 17.2888 17.2766M3.00042 3L21.0004 21M9.8791 9.87868C9.3362 10.4216 9.00042 11.1716 9.00042 12C9.00042 13.6569 10.3436 15 12.0004 15C12.8288 15 13.5788 14.6642 14.1217 14.1213"
                                                stroke="#888" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                @else
                                    <a href="{{ route('user.hide.phone', ['type' => 0, 'is_sub_contact' => false]) }}"
                                        title="show this contact"
                                        data-msg="Are you sure you want to display this?" class="hide_email">
                                        <svg width="20" height="16" viewBox="0 0 22 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M1.42012 8.71318C1.28394 8.49754 1.21584 8.38972 1.17772 8.22342C1.14909 8.0985 1.14909 7.9015 1.17772 7.77658C1.21584 7.61028 1.28394 7.50246 1.42012 7.28682C2.54553 5.50484 5.8954 1 11.0004 1C16.1054 1 19.4553 5.50484 20.5807 7.28682C20.7169 7.50246 20.785 7.61028 20.8231 7.77658C20.8517 7.9015 20.8517 8.0985 20.8231 8.22342C20.785 8.38972 20.7169 8.49754 20.5807 8.71318C19.4553 10.4952 16.1054 15 11.0004 15C5.8954 15 2.54553 10.4952 1.42012 8.71318Z"
                                                stroke="#888" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M11.0004 11C12.6573 11 14.0004 9.65685 14.0004 8C14.0004 6.34315 12.6573 5 11.0004 5C9.34355 5 8.0004 6.34315 8.0004 8C8.0004 9.65685 9.34355 11 11.0004 11Z"
                                                stroke="#888" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                @endif
                            </span>
                        </div>
                    </div>
                    @foreach ($contacts->where('type', 0) as $key => $contact)
                        <label class="inputGroup popup">Phone :
                            <input type="text" class="phone" placeholder="Add a phone number" value="{{ $contact->contact }}"
                                 onfocusout="updatePhone({{ $contact->id }},this)"
                                style="background: {{ $contact->is_phone_hidden ? 'rgb(239 239 239 / 78%)' : '' }}">
                            
                            <div class="custom_overlay">
                                <span class="custom_overlay_inner">
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('head_office.delete_contact', $contact->id) }}"
                                            class="delete_button"
                                            data-msg="Are you sure you want to delete this contact?">
                                            <svg width="20" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5"
                                                    stroke="#888" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                        </a>
                                        
                                    </div>
                                </span>
                            </div>
                        </label>
                    @endforeach
                    <div id="phone_div">

                    </div>
                   
                    <div class="inputSection">About Me</div>
                    <style>
                        #about_me:focus {
                            box-shadow: none;
                        }
                    </style>
                    <textarea spellcheck="true"  class="form-control fw-semibold" name="about_me" id="about_me" cols="3" rows="1"
                        onfocusout="updateAbout({{ $user->id }},this)">{{ $user->selected_head_office_user->about_me }}</textarea>
                    
                   
                </div>
            </div>
            <style>
                #session-dataTable_filter:after, .dt-search:after {
                left:10px !important;
            }
            </style>
            <div id="session_history" class="working_status tab-pane">
                <div class="profile-page-working-status-contents">
                    <table class="table w-100 new-table" id="session-dataTable" style="width: 80%">
                        <thead>
                            <tr>
                                <th><input type="checkbox" name="select_all" value="1"
                                        id="dataTable-select-all"></th>
                                <th style="text-align: left;">Device</th>
                                <th style="text-align: left;">Location</th>
                                <th style="text-align: left;">Last Usage</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($l_u_s as $session)
                                <tr>
                                    <td data-session-id="{{ $session->id }}"></td>
                                    <td>
                                        <div class="table-icon-wrapper">
                                            <img src="{{ asset('images/monitor-03.svg') }}" alt="icon">
                                            <div>
                                                <p style="margin: 0">
                                                    @if ($session->platform == null)
                                                        Unknown
                                                    @elseif($session->platform == 'OS X')
                                                        Mac
                                                    @else
                                                        {{ $session->platform }}
                                                    @endif
                                                </p>
                                                <p style="margin: 0" class="browser">
                                                    {{ rtrim(preg_replace('/\d/', '', $session->browser), '.') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="line-height: 1.2;">
                                            @if (!empty(trim($session->city)))
                                                {{ $session->city }},
                                            @endif {{ $session->country }}
                                            <p style="margin: 0;color:#999;font-size:14px;">{{ $session->ip }}</p>
                                        </div>
                                    </td>

                                    <td>
                                        <div style="line-height: 1.2;width:170px;">
                                            {{ $session->updated_at->format('d M Y h:i A') }}
                                            <p style="margin:0;color:#999;font-size:14px;">
                                                {{ $session->updated_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($session->user_session && session('user_session') === $session->user_session)
                                            <div
                                                class="table-user-placeholder text-white text-center d-flex align-items-center justify-content-center">
                                                Current</div>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- @if ($session->user_session && session('user_session') !== $session->user_session) --}}
                                        <a style="width:56px;"
                                            href="{{ route('head_office.end_head_office_user_session', ['id' =>$session->user_session,'_token' => csrf_token()]) }}"
                                            data-msg="Are you sure, you want to end this session?"
                                            class="delete_button d-flex">
                                            Log out
                                        </a>
                                        {{-- @endif --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal fade file_upload_model" id="add_new_holiday"
    @if (isset($remove_backdrop)) data-backdrop="false" @endif tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('head_office.update_head_office_user_holidays') }}" method="post">
                @csrf
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        Add New Holiday
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="text-info">Start Date</h6>
                    <input id="away_from" required="" onchange="disable_return_on()" type="date"
                        name="away_from" class="form-control ">
                    <h6 class="text-info">End Date</h6>
                    <input id="return_on" onchange="calculate_date()" required="" type="date"
                        name="return_on" class="form-control">
                    <h6 class="text-info">Holiday Type</h6>
                    <input id=""  required type="text"
                        name="type" class="form-control">


                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="submit" class="btn btn-light">Save <i class="fa fa-arrow-right"></i></button>
                        <button type="button" class="btn  btn-primary" data-bs-dismiss="modal">Close </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="add_contact_detail" @if (isset($remove_backdrop)) data-backdrop="false" @endif
    tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('head_office.add_contact_detail') }}" method="post">
                @csrf
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        Add New Contact
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="text-info">Type</h6>
                    <select name="type" class="form-control" onchange="changeType(this)">
                        <option value="0">Phone Number</option>
                        <option value="1">Email</option>
                    </select>
                    <h6 class="text-info">Contact</h6>
                    <input required type="text" id="type_contact" name="contact" class="form-control">
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="submit" class="btn btn-white">Save <i class="fa fa-arrow-right"></i></button>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Close <i
                                class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="head_office_add_area" @if (isset($remove_backdrop)) data-backdrop="false" @endif
    tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('head_office.head_office_add_area') }}" method="post">
                @csrf
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        Add New Area
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="text-info">Area</h6>
                    <input required type="text" name="contact" class="form-control">
                    <h6 class="text-info">Level</h6>
                    <input required type="text" name="contact" class="form-control">
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="submit" class="btn btn-white">Save <i class="fa fa-arrow-right"></i></button>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Close <i
                                class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="draggable" class="bottom-nav position-fixed " style="z-index: 9999;" aria-describedby="drag" >
    <div class="left-side">
        <div class="info-wrapper">
            <div class="selected-show">
                <h5 id="count">0</h5>
            </div>
            <div class="info-heading" style="max-width: 180px;overflow:hidden;">
                <p>Items Selected</p>
                <div class="dots-wrapper">
                    <span class="dot"></span>
                </div>
            </div>
        </div>

        <div class="btn-wrapper">
            <button id='delete-btn' class="bar-btn" title="Remove selected records">
                <img src="{{ asset('images/trash-01.svg') }}" alt="icon">
                <p>Remove</p>
            </button>
            <button id="log-btn" class="bar-btn" style="display: none;width:100px;" title="Logout all devices">
                <img src="{{ asset('images/log-out-04.svg') }}" alt="icon">
                <p>Log-out All</p>
            </button>

        </div>
    </div>
    <button class="drag-btn">
        <img src="{{ asset('images/dots-horizontal.svg') }}" alt="svg">
        <img style="margin-top:-15px;" src="{{ asset('images/dots-horizontal.svg') }}" alt="svg">
    </button>
</div>

<div class="d-none" hidden>
    <form id="delete-form" hidden action="{{route('head_office.remove_session_records')}}" method="POST">
        @csrf
        <input type="text" name="sessionIds[]" value="" id="sessionid-input">
    </form>
    <form id="log-form" hidden action="{{route('head_office.log_session_records')}}" method="POST">
        @csrf
        <input type="text" name="sessionIds[]" value="" id="sessionid-log">
    </form>
</div>
<input type="hidden" value="{{ route('head_office.update_email') }}" id="update_email_route">
<input type="hidden" value="{{ route('head_office.update_position') }}" id="update_position_route">
<input type="hidden" value="{{ route('head_office.update_phone') }}" id="update_phone_route">
<input type="hidden" value="{{ route('head_office.update_about') }}" id="update_about_route">
<input type="hidden" value="{{ route('head_office.update_location') }}" id="update_location_route">
<input type="hidden" value="{{ route('head_office.update_area') }}" id="update_area_route">
<input type="hidden" value="{{ route('user.update_picture') }}" id="update_profile">

<!-- Crop Modal (Bootstrap Modal) -->
<div id="cropModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="cropperContainer" style="width: 400px; height: 400px; margin: auto;">
                    <img id="cropperImage" style="max-width: 100%; height: 100%; display: block;" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="cropImageButton">Crop & Save</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
@if(Session::has('success'))
    <script>
        alertify.success("{{ Session::get('success') }}");
    </script>
@elseif(Session::has('error'))
<script>
    alertify.success("{{ Session::get('error') }}");
</script>
@endif
    <script src="{{ asset('admin_assets/js/ho_view_profile.js') }}"></script>
    <script>
        
        $(document).ready(function() {

            telnumber = $("#telephone").intlTelInput({
        fixDropdownWidth:true,
        showSelectedDialCode:true,
        strictMode:true,
        preventInvalidNumbers: true,
        initialCountry:'gb'
    })

        $(".phone").intlTelInput({
                fixDropdownWidth: true,
                showSelectedDialCode: true,
                strictMode: true,
                utilsScript: "{{asset('admin_assets/js/utils.js')}}",
                preventInvalidNumbers: true,
            })


            loadActiveTab();
            // changeTabUrl('aboutMeProfile')
            if(window.location.search.split('=')[1] != undefined){
                changeTabUrl(window.location.search.split('=')[1])
            }
            const dataTable = new DataTable('#logs-table', {
                autoWidth:false,
                paging: true,
                info: false,
                language: {
                    search: ""
                },
            });
            let table = new DataTable('#session-dataTable', {
                paging: true,
                info: false,
                language: {
                    search: ""
                },
                'columnDefs': [{
                    "select": 'multi',
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'className': '',
                    'render': function(data, type, full, meta) {
                        return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(
                            data).html() + '">';
                    }
                }],
            });

            $('#dataTable-select-all').on('click', function() {
                var rows = table.rows({
                    'search': 'applied'
                }).nodes();
                // Check/uncheck checkboxes for all rows in the table
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            let sessionIds = []; // Array to store data-session-id values
            table.on('change', 'input', function() {

                let rowData = table.column(0).nodes().filter(function(value, index) {
                    let inputElement = $(value).find('input');
                    return inputElement.prop('checked');
                });

                sessionIds = [];
                $.each(rowData, function(index, obj) {
                    let sessionId = $(obj).data('session-id');
                    if (sessionId) {
                        sessionIds.push(sessionId);
                    }
                });


                if (rowData.length > 0) {
                    $('#draggable').addClass('anim').removeClass('reverse-anim');
                } else {
                    $('#draggable').addClass('reverse-anim').removeClass('anim');
                }


                const dotsWrapper = $('.dots-wrapper');
                dotsWrapper.empty();

                for (let i = 0; i < rowData.length; i++) {
                    dotsWrapper.append('<span class="dot" style="width:8px;height:8px;"></span>')
                }
                if (rowData.length > 1) {
                    $('#log-btn').fadeIn()
                    $('#delete-btn').fadeOut()
                } else {
                    $('#log-btn').fadeOut('fast')
                    $('#delete-btn').fadeIn()
                }
                $('#count').text(rowData.length);
            });


            $('#delete-btn').on('click',function(){
                $('#sessionid-input').val(sessionIds);
                $('#delete-form').submit();
            })
            $('#log-btn').on('click',function(){
                $('#sessionid-log').val(sessionIds);
                $('#log-form').submit();
            })

        });


        function loadActiveTab(tab = null) {
            if (tab == null) {
                tab = window.location.hash;
            }
            console.log(tab);
            $('.nav-tabs a[data-bs-target="' + tab + '"]').tab('show');
        }

        function myFunction() {
            var popup = document.getElementById("myPopup");
            $('#myPopup').addClass('show').fadeIn();
        }
        // removes the pop-up in email
        $(".email-pop-wrapper").on("mouseout", function(event) {
            if (!$(event.relatedTarget).closest('.inputSection').length && !$(event.relatedTarget).is('#myPopup') && !$(event.relatedTarget).is('#myPopup *')) {
                $('#myPopup').fadeOut();
            }
        });

        function byPhone() {
            var popup = document.getElementById("myPopupPhone");
            $('#myPopupPhone').addClass('show').fadeIn();
        }
        // removes the pop-up in phone
        $(".popup").on("mouseout", function(event) {
            event.preventDefault();
            if (!$(event.relatedTarget).closest('.inputSection').length && !$(event.relatedTarget).is('#myPopupPhone') && !$(event.relatedTarget).is('#myPopupPhone *')) {
                $('#myPopupPhone').fadeOut();
            }
        });

        function changeType(element) {
            val = $(element).val();
            if (val == 0) {
                $('#type_contact').prop("type", "number");
            } else {

                $('#type_contact').prop("type", "text");
            }
        }

        function updateEmail(id, element) {
            var value = $(element).val();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                alertify.notify('Invalid Email', 'error');
                return;
            }
            var _token = $('#_token').val();
            var data = {
                id: id,
                value: value,
                type: 1,
                _token: _token
            }
            var route = $('#update_email_route').val();
            $.post(route, data)
                .then(function(response) {
                    $(element).parent().remove();
                    if (response.result) {
                        window.location.reload();
                        var r = "{{ route('head_office.delete_contact', '+response.data.id+') }}";
                        var r2 =
                            "{{ route('contact.hide.email', ['type' => 1, 'contact_id' => '+response.data.id+']) }}"
                        $("#email_div").append(
                            '<label class="inputGroup popup">Email : <input type="text" placeholder="Add email" value="' +
                            response.data.contact + '" id="email_' + response.data.id +
                            '" onfocusout="updateEmail(' + response.data.id +
                            ',this)" type="text"><div class="custom_overlay"><span class="custom_overlay_inner "><div class="d-flex align-items-center gap-2"><a href="' +
                            r +
                            '" class="delete_button" data-msg="Are you sure you want to delete this contact?"><svg width="20" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></a><div><a href="http://127.0.0.1:8000/head_office/hide_email/1?contact_id=2" title="hide this contact?" data-msg="Are you sure you want to hide this contact?" class="hide_email"><svg width="20" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.7429 5.09232C11.1494 5.03223 11.5686 5 12.0004 5C17.1054 5 20.4553 9.50484 21.5807 11.2868C21.7169 11.5025 21.785 11.6103 21.8231 11.7767C21.8518 11.9016 21.8517 12.0987 21.8231 12.2236C21.7849 12.3899 21.7164 12.4985 21.5792 12.7156C21.2793 13.1901 20.8222 13.8571 20.2165 14.5805M6.72432 6.71504C4.56225 8.1817 3.09445 10.2194 2.42111 11.2853C2.28428 11.5019 2.21587 11.6102 2.17774 11.7765C2.1491 11.9014 2.14909 12.0984 2.17771 12.2234C2.21583 12.3897 2.28393 12.4975 2.42013 12.7132C3.54554 14.4952 6.89541 19 12.0004 19C14.0588 19 15.8319 18.2676 17.2888 17.2766M3.00042 3L21.0004 21M9.8791 9.87868C9.3362 10.4216 9.00042 11.1716 9.00042 12C9.00042 13.6569 10.3436 15 12.0004 15C12.8288 15 13.5788 14.6642 14.1217 14.1213" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></a></div></div></span></div></label>'
                        );
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
        }

        function updatePosition(id, element) {
            var value = $(element).val();
            var _token = $('#_token').val();
            var data = {
                id: id,
                value: value,
                type: 1,
                _token: _token
            }
            var route = $('#update_position_route').val();
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        alertify.notify('User Position Updated!', 'success', 5)
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
        }

        function updateAbout(id, element) {
            var value = $(element).val();
            var _token = $('#_token').val();
            var data = {
                id: id,
                value: value,
                type: 1,
                _token: _token
            }
            var route = $('#update_about_route').val();
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        alertify.notify('User about Updated!', 'success', 5)
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
        }

        function updatePhone(id, element) {
            var value = $(element).val();
            var _token = $('#_token').val();
            var data = {
                id: id,
                value: value,
                type: 0,
                _token: _token
            }
            var route = $('#update_email_route').val();

            if($(element).intlTelInput("isValidNumber") == false || $(element).intlTelInput("isValidNumber") == undefined){
                alertify.notify('Invalid Phone','error')
            }else{
                console.log('datadfaf')
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        window.location.reload();
                        var r = "{{ route('head_office.delete_contact', '+response.data.id+') }}";
                        // $("#phone_div").append(
                        //     '<label class="inputGroup popup">Phone : <input type="text" placeholder="Add phone" value="' +
                        //     response.data.contact + '" id="phone_' + response.data.id +
                        //     '" onfocusout="updatePhone(' + response.data.id +
                        //     ',this)" type="text"><div class="custom_overlay"><span class="custom_overlay_inner"><a href="' +
                        //     r +
                        //     '" class="delete_button" data-msg="Are you sure you want to delete this contact?"><svg width="20" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></a></span></div></label>'
                        // );
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })

            }


        }

        function add_email() {
            $("#email_div").append(
                '<label class="inputGroup popup">Email : <input type="text" placeholder="Add email" value="" onfocusout="updateEmail(0,this)" type="text"></label>'
            );
        }

        function add_phone() {
            $("#phone_div").append(
                '<label class="inputGroup popup">Phone : <input type="text" class="phone" placeholder="Add Phone" value="" onfocusout="updatePhone(0,this)" type="text"></label>'
            );
            $(".phone").intlTelInput({
            fixDropdownWidth: true,
            showSelectedDialCode: true,
            strictMode: true,
            utilsScript: "{{asset('admin_assets/js/utils.js')}}",
            preventInvalidNumbers: true,
        })
        }

        function updateLocation(element) {
            var value = $(element).val();
            var route = $('#update_location_route').val();
            var data = {
                location: value,
                _token: $('#_token').val()
            }
            $.post(route, data)
                .then(function(response) {
                    console.log(response)
                })
                .catch(function(error) {
                    console.log(error);
                })
        }

        function add_area() {
            $("#area_div").empty();
            $("#area_div").append(
                '<label class="inputGroup popup">Area : <input type="text" placeholder="Add Area" onfocusout="focusOnLevel()" id="area" value="" type="text"></label><label class="inputGroup popup">Level : <input type="text" id="level" placeholder="Add Level" value="" onfocusout="updateArea(this)" type="text"></label>'
            );
        }

        function updateArea(element) {
            var area = $(element).parent().parent().find('#area').val();
            var level = $(element).val();

            var route = $('#update_area_route').val();
            if (area) {
                var data = {
                    area: area,
                    level: level,
                    _token: $('#_token').val()
                };
                $.post(route, data)
                    .then(function(response) {
                        if (response.result) {
                            window.location.reload();
                            $('#area_values').append('<label class="inputGroup" id="' + data.id +
                                '">Area:<input style="width:40%" type="text" placeholder="Add area" value="' + data
                                .area + '"><input style="width:40%" type="text" placeholder="Add Level" value="' +
                                data.level + '"></label>');
                            console.log(response);
                            $(element).parent().parent().remove();
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    })
            } else {
                $(element).parent().parent().find('#area').focus();
            }
        }

        function focusOnLevel() {
            $('#level').focus();
        }
        var loadFile = function(event) {
    var image = document.getElementById("output"); // This is the image element where the preview is shown
    image.src = URL.createObjectURL(event.target.files[0]); // Set the preview image

    var image_show = document.getElementById("cropperImage"); // This is the image element for cropping
    image_show.src = URL.createObjectURL(event.target.files[0]); // Set the image for cropping

    var route = $('#update_profile').val();  // URL for profile update
    var token = $('#_token').val();  // CSRF token for security
    let file = event.target.files[0];

    const reader = new FileReader();
    reader.onload = (evt) => {
        console.log(evt.target.result);
        result = evt.target.result;

        var data = {
                    file: result,
                    _token: token
                }
        $.post(route, data).then(function(response) {
                    console.log(response);
                });
        
        // Show the crop modal
        $("#cropModal").modal("show");
        
        // Set the image source to the file data
        image.src = result;

        // Wait for the modal to be fully shown and then initialize the cropper
        $('#cropModal').on('shown.bs.modal', function () {
            // Initialize Cropper.js once the modal is shown
            image_show.onload = function() {
                if (typeof cropper !== "undefined") {
                    cropper.destroy(); // Destroy any previous cropper instance
                }

                cropper = new Cropper(image_show, {
                    aspectRatio: 1,    // Aspect ratio (1:1 for square crop)
                    viewMode: 2,       // Allow image to overflow container
                    scalable: true,    // Allow scaling of the image
                    movable: true,     // Allow moving of the image
                    zoomable: true,    // Allow zooming of the image
                    minContainerWidth: 400, // Minimum container width
                    minContainerHeight: 400, // Minimum container height
                    responsive: true,  // Make cropper responsive
                });
            };
            
            // Trigger the onload if the image is already loaded (in case it was cached or loaded quickly)
            if (image_show.complete) {
                image_show.onload();
            }
        });
    };

    reader.readAsDataURL(file);
};

// Triggered when the user clicks the crop button
$("#cropImageButton").click(function() {
    if (!cropper) return; // Ensure the cropper is initialized

    // Get the cropped image as a Base64-encoded string
    const croppedImageBase64 = cropper.getCroppedCanvas({
        width: 300, // Adjust dimensions as needed
        height: 300,
    }).toDataURL("image/jpeg"); // Convert canvas to Base64 string (JPEG format)

    var route = $('#update_profile').val(); // URL for profile update

    // Prepare the data to send via AJAX
    const data = {
        file: croppedImageBase64, // Base64 string
        _token: $('#_token').val() // CSRF token
    };

    // Send the Base64 image to the server using AJAX
    $.ajax({
        url: route,
        type: 'POST',
        data: data,
        success: function(response) {
            console.log(response);
            // Handle the response (e.g., update the profile with the cropped image)
        },
        error: function(err) {
            console.error("Error uploading cropped image:", err);
        }
    });

    // Optionally, close the modal after submission
    $('#cropModal').modal('hide'); // Hide the modal after cropping
});




        $(document).on("click", ".hide_email", function(e) {
            e.preventDefault();
            let href = $(this).attr('href');
            let msg = $(this).data('msg');
            alertify.confirm("Are you sure?", msg,
                function() {
                    window.location.href = href;
                },
                function(i) {}).set('labels', {ok:'Yes', cancel:'No'});
        });
        $(document).on("click", ".hide_phone", function(e) {
            e.preventDefault();
            let href = $(this).attr('href');
            let msg = $(this).data('msg');
            alertify.confirm("Are you sure?", msg,
                function() {
                    window.location.href = href;
                },
                function(i) {}).set('labels', {ok:'Yes', cancel:'No'});
        });

        $(document).ready(function(){
            const dragBtn = document.querySelector('.drag-btn');
        })
        const draggable = document.getElementById('draggable');

    var posX = 0,
        posY = 0,
        mouseX = 0,
        mouseY = 0;

    dragBtn.addEventListener('mousedown', mouseDown, false);
    window.addEventListener('mouseup', mouseUp, false);

    function mouseDown(e) {
        e.preventDefault();
        posX = e.clientX - draggable.offsetLeft;
        posY = e.clientY - draggable.offsetTop;
        window.addEventListener('mousemove', moveElement, false);
    }

    function mouseUp() {
        window.removeEventListener('mousemove', moveElement, false);
    }

    function moveElement(e) {
        mouseX = e.clientX - posX;
        mouseY = e.clientY - posY;

        const maxX = 1000 ;
        const maxY = window.innerHeight - draggable.offsetHeight;
        console.log(maxX)

        mouseX = Math.min(Math.max(mouseX, 0), maxX);
        mouseY = Math.min(Math.max(mouseY, 0), maxY);
        draggable.style.left = mouseX + 'px';
        draggable.style.top = mouseY + 'px';
    }
    </script>
@endsection
@endsection
