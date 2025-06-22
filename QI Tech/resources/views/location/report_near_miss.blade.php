@extends('layouts.location_app' )
@section('title', 'Report Near Miss')
@section('top-nav-title', 'Report Near Miss')
@section('content')
<!-- Include Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Include Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


    <div id="content">
        <style>
            /* Style the entire Flatpickr time picker */
.flatpickr-time {
    background-color: #444; /* Dark grey for the entire time picker background */
    padding: 10px;
    border-radius: 5px;
}

/* Style the hour, minute, and second fields */
.flatpickr-time input {
    background-color: #444; /* Dark grey background for input fields (unselected) */
    color: white; /* White text */
    border: none;
    padding: 5px;
    border-radius: 3px;
    font-size: 18px;
    width: 50px;
    text-align: center;
}

/* Selected field will have black background with white text */
.flatpickr-time input:focus, .flatpickr-time input.selected {
    background-color: #000; /* Black background on selection */
    color: white; /* White text on selection */
}

/* Style the AM/PM toggle buttons */
.flatpickr-am, .flatpickr-pm {
    background-color: #444; /* Dark grey background for AM/PM */
    color: white; /* White text */
    padding: 5px 10px;
    border-radius: 3px;
    font-weight: bold;
    cursor: pointer;
}

/* Selected AM/PM should also have black background with white text */
.flatpickr-am.selected, .flatpickr-pm.selected {
    background-color: #000; /* Black background when selected */
    color: white; /* White text when selected */
}

        </style>
        <style>
            .hide {
                display: none;
            }

        </style>
        <div class="container mt-md-5 mt-sm-1">
            <a href="{{ route('location.dashboard') }}" class="text-info"><i class="fa fa-arrow-left"></i> Back</a>
            @if ($nearmiss)
                <p class="badge badge-info center">Editing - {{ $nearmiss->what_was_error }} {{ $nearmiss->error() }}</p><br>
            @endif
        </div>
        <div class="card vh-75 container ">
            <div class="card-body">
                <div class="progress-bar-wrap">
                    <div class="progress-bar"></div>
                    <div class="progress-bar-fill"></div>
                </div>
                <ul class="step d-flex flex-nowrap">
                    <li class="step-item step-1 active" data-stage="1" id="step1">
                        <a href="#!" class="text m-t-10 " style="color: #000;"><b>Who & When</b></a>
                    </li>
                    <li class="step-item step-2 " data-stage="2" id="step2">
                        <a href="#!" class="text m-t-10 " style="color: #000;"><b>What</b></a>
                    </li>
                    <li class="step-item step-3" data-stage="3" id="step3">
                        <a href="#!" class="text m-t-10 " style="color: #000;"><b>Why</b></a>
                    </li>
                    <li class="step-item step-4 {{ $data && $data['contribution']['hidden'] ? 'hide' : '' }}" data-stage="4"
                        id="step4">
                        <a href="#!" class="text m-t-10 " style="color: #000;"><b>Contributing Factors</b></a>
                    </li>
                    <li class="step-item step-5 " data-stage="5" id="step5">
            <a href="#!" class="text m-t-10 " style="color: #000;"><b>Actions</b></a>
        </li> 
                </ul>

                <input type="hidden" id="stages-count" value="{{ $data && $data['contribution']['hidden'] ? 3 : 4 }}">
                <form method="post"
                    action="{{ route(!isset($standalone) ? 'location.near_miss.save' : 'near_miss.standalone.save') }}">
                    @csrf
                    <input type="hidden" name="id"
                        @if ($nearmiss) value="{{ $nearmiss->id }}" @endif>
                    <div class="stages stage_data_1">
                        <div class="m-t-10">
                            <h5 class="fw-bold mb-2 mt-0" style="color: var(--location-section-heading-color)">Where?</h5>
                            <select name="location_id" class="form-control">
                                <option value="" disabled selected></option> <!-- Empty option -->
                            
                                @foreach ($where as $loc)
                                    @if (!isset($data['who']['location_ids']))
                                        <option value="{{ $loc->id }}">
                                            {{ $loc->name() }}</option>
                                    @elseif (in_array($loc->id, $data['who']['location_ids']))
                                        <option value="{{ $loc->id }}">
                                            {{ $loc->name() }}</option>
                                    @endif
                                @endforeach
                            
                                @if (empty($where))
                                    <option value="" disabled>No locations available</option>
                                @endif
                            </select>
                            
                            
                            
                            
                            @if ($location->near_miss_prescirption_dispensed_at_hub)
                                <br>
                                <h5 class="text-info">Prescriptions Dispensed?</h5>
                                <select name="dispensed_at_hub" class="form-control">
                                    <option value="Dispensed at Hub" @if ($nearmiss && $nearmiss->dispensed_at_hub == 'Dispensed at Hub') selected @endif>
                                        Dispensed at Hub</option>
                                    <option value="On Site" @if ($nearmiss && $nearmiss->dispensed_at_hub == 'On Site') selected @endif>On Site
                                    </option>
                                </select>
                            @endif
                        </div>
                        <h5 class="fw-bold mb-2 mt-0" style="color: var(--location-section-heading-color)">When?</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Time</h6>
                            
                                <!-- Input to display and edit UK time -->
                                <input id="time-near-miss" type="time" name="time" class="form-control flatpickr-input"
                                     value="@if (!$nearmiss) {{ date('H:i') }} @elseif($nearmiss) {{ $nearmiss->time }} @endif">

                                     <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            const timeInput = document.getElementById('time-near-miss');
                                            
                                            // Check if the input has a value, if not, set the current UK time
                                            if (!timeInput.value) {
                                                const now = new Date();
                                                // Convert to UK time (GMT/BST)
                                                const options = { timeZone: 'Europe/London', hour: '2-digit', minute: '2-digit', hour12: false };
                                                const timeString = now.toLocaleTimeString('en-GB', options);
                                                
                                                timeInput.value = timeString;
                                            }
                                        });
                                        </script>
                                        

                            
                                
                            </div>
                            
                            <div class="col-md-6">
                                <h6>Date</h6>
                                <input type="text" class="form-control datepicker" name="date"
                                    value="@if (!$nearmiss) {{ date('d/m/Y') }}@elseif($nearmiss){{ $nearmiss->date() }} @endif">
                            </div>
                        </div>
                        @if ($location->near_miss_ask_for_who)
                        <div @if($data && ($data['who']['error_by_who'] && $data['who']['error_detected_by_who'])) style="display: none;" @endif>
                            
                            <h5 class="fw-bold mb-2 mt-0" style="color: var(--location-section-heading-color)">Who?</h5>
                            <div class="row">
                                <div class="col-md-6" @if ( $data && $data['who']['error_by_who'])
                                    style="display: none;"
                                @endif>
                                
                                    <h6>Error By</h6>
                                    <div class="position-relative">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            style="position: absolute; right: 20px; top: 6px; z-index:2;"
                                            onclick="clearOption('error_by')" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M17 7L7 17M7 7L17 17" stroke="#888888" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                        <select name="error_by" class="form-control error_by select2" id="error_by">
                                            <option>Select a person</option>
                                            @if ($location->near_miss_ask_for_user_detail == 'name')
                                                @foreach ($who as $user)
                                                    <option value="{{ $user->nameWithPosition() }}"
                                                        @if ($nearmiss && $nearmiss->error_by == $user->nameWithPosition()) selected @endif>
                                                        {{ $user->nameWithPosition() }}</option>
                                                @endforeach
                                            @elseif($location->near_miss_ask_for_user_detail == 'position')
                                                @foreach ($positions as $p)
                                                    <option value="{{ $p->name }}"
                                                        @if ($nearmiss && $nearmiss->error_by == $p->name) selected @endif>
                                                        {{ $p->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                            @if ($location->near_miss_robot_in_use && !empty($location->near_miss_robot_name))
                                                <option value="Robot ({{ $location->near_miss_robot_name }})"
                                                    @if ($nearmiss && $nearmiss->error_by == 'Robot (' . $location->near_miss_robot_name . ')') selected @endif>Robot
                                                    ({{ $location->near_miss_robot_name }})</option>
                                            @endif
                                            <option value="Unknown" @if ($nearmiss && $nearmiss->error_by == 'Unknown') selected @endif>
                                                Unknown
                                            </option>
                                            <option value="Other" @if ($nearmiss && $nearmiss->error_by == 'Other') selected @endif>Other
                                            </option>
                                        </select>
                                    </div>
                                    <div class="error_by_other form-group"
                                        @if ($nearmiss && $nearmiss->error_by == 'Other') @else style="display:none" @endif>
                                        <label>Enter person’s name</label>
                                        <input type="text" class="form-control error_by_other_field"
                                            name="error_by_other"
                                            @if ($nearmiss) value="{{ $nearmiss->error_by_other }}" @endif>
                                    </div>
                                </div>
                                <div class="col-md-6" @if ( $data && $data['who']['error_detected_by_who'])
                                style="display: none;"
                            @endif>
                                    <h6>Error Detected By</h6>
                                    <div class="position-relative"><svg width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" style="position: absolute; right: 20px; top: 6px; z-index:2;"
                                            onclick="clearOption('error_detected_by')" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M17 7L7 17M7 7L17 17" stroke="#888888" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <select name="error_detected_by" class="form-control error_detected_by select2"
                                            id="error_detected_by">
                                            <option>Select a person</option>
                                            @if ($location->near_miss_ask_for_user_detail == 'name')
                                                @foreach ($who as $user)
                                                    <option value="{{ $user->nameWithPosition() }}"
                                                        @if ($nearmiss && $nearmiss->error_detected_by == $user->nameWithPosition()) selected @endif>
                                                        {{ $user->nameWithPosition() }}</option>
                                                @endforeach
                                            @elseif($location->near_miss_ask_for_user_detail == 'position')
                                                @foreach ($positions as $p)
                                                    <option value="{{ $p->name }}"
                                                        @if ($nearmiss && $nearmiss->error_detected_by == $p->name) selected @endif>
                                                        {{ $p->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                            @if ($location->near_miss_robot_in_use && !empty($location->near_miss_robot_name))
                                                <option value="Robot ({{ $location->near_miss_robot_name }})"
                                                    @if ($nearmiss && $nearmiss->error_detected_by == 'Robot (' . $location->near_miss_robot_name . ')') selected @endif>Robot
                                                    ({{ $location->near_miss_robot_name }})</option>
                                            @endif
                                            <option value="Unknown" @if ($nearmiss && $nearmiss->error_detected_by == 'Unknown') selected @endif>
                                                Unknown
                                            </option>
                                            <option value="Other" @if ($nearmiss && $nearmiss->error_detected_by == 'Other') selected @endif>
                                                Other
                                            </option>
                                        </select>
                                    </div>
                                    <div class="error_detected_by_other form-group"
                                        @if ($nearmiss && $nearmiss->error_detected_by == 'Other') @else style="display:none" @endif>
                                        <label>Please Enter User Name</label>
                                        <input type="text" class="form-control error_detected_by_other_field"
                                            name="error_detected_by_other"
                                            @if ($nearmiss) value="{{ $nearmiss->error_detected_by_other }}" @endif>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="stages stage_data_2">
                        @include('location.near_miss_stage_2')
                    </div>
                    <div class="stages stage_data_3 nearmiss_whys">
                        @include('location.near_miss_stage_3')
                    </div>
                    <div class="stages stage_data_4 {{ $data && $data['contribution']['hidden'] ? 'hide' : '' }}">
                        <div class="row">
                            @include('location.near_miss_stage_4')
                        </div>
                    </div>
                    <div class="stages stage_data_5">
                        <h5 class="text-info">Actions</h5>
                        <textarea name="action_notes" id="actions" class="form-control" rows="4" placeholder="Enter action notes here...">@if($nearmiss){{ $nearmiss->action_notes }}@endif</textarea>
                    </div>
                    {{-- @dd($nearmiss) --}}
                    <div class="center m-t-10">
                        <br>
                        <button type="button" class="btn btn-info nearMissStagePrevious">Previous</button>
                        @if (!isset($standalone))
                            @if (!$nearmiss || $nearmiss->status != 'active')
                                <input type="submit" style="" class="btn btn-outline-info draftButton"
                                    name="save_as_draft" value="Save as Draft">
                            @endif
                        @endif
                        <button type="button" class="btn btn-info nearMissStageNext">Next</button>
                        <div class="btn-group" role="group" aria-label="Save Buttons">
                            <input type="submit" style="" class="btn btn-info right formSubmitButton"
                                name="save" value="Save">
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('admin_assets/css/progress-step.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alertify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/easyautocomplete/easy-autocomplete.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('/easyautocomplete/jquery.easy-autocomplete.min.js') }}"></script>
    <script src="{{ asset('/js/alertify.min.js') }}"></script>
    <script>
        function clearOption(selectId) {
            $("#" + selectId).prop('selectedIndex', 0).trigger('change');
        }
    </script>



@endsection
