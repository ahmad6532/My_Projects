@extends('layouts.location_app')
@section('title', 'Be Spoke Form Records')
@section('content')
@php
    $location = Auth::guard('location')->user();
    $forms = $location->group_forms();
    $headOffice = $location->head_office();
    $near_miss = $headOffice->near_miss;
@endphp
<style>
    .select2-container--open {
        z-index: 9999999 !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected]{
        background-color: #4BC2D9 !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple{
        border:solid #d5d2d2 1px;
    }
    #report-now-menu-menu{
        top: 0px !important;
    }
</style>
    <div id="content" >
        <div>
            <div class="headingWithSearch" style="width:100%; margin-top:20px">

                <div class="heading-center" style="font-weight: normal !important;">
                    Reported
                </div>

            </div>
        </div>
        <style>
            .btn.active-btn{
                background: #fafafa;
                box-shadow: 0 .5rem 1rem rgba(0, 0, 0, 0.055)!important;
            }
        </style>
        <div class="position-relative btn-group btn-group-sm float-left d-flex flex-column" role="group" style="width:fit-content;padding-right:2rem;top:20px;">
            <div class="w-100">
                {{-- <button data-target="#report-now-menu-menu" 
                    class="btn btn-info font-weight-bold  " style="padding: 8px 30px; white-space: nowrap; width: 170px;background:#6bc1b7;border:none;border-radius:3px !important;" >
            Report Now
                </button> --}}

                <div>
                    <a href="#" class="report-now-menu e-drop-down py-2 text-white btn-sm primary-btn flex align-items-center justify-content-center"
                        data-target="#report-now-menu-menu"> Report Now
                        <span></span>
                    </a>
                </div>

                






                <div id="report-now-menu-menu" style="z-index: 999999;top:0px !important;left:0px !important;"
                class="custom-menu report-now-menu-tr general-dropdown-menu hidden report-now-menu-menu-color">
                <div class="row">
                    <div class="form-group">
                        <label for="searchInput">Search forms:</label>
                        <input type="text" class="form-control p-1 custom-input" id="searchInput"
                            placeholder="Search forms..." style="height:40px;">
                    </div>
    
                    <hr class="my-2 w-75 mx-auto bg-info text-info border-info">
                    <div class="col-12">
                        <ul style="list-style: none; font-weight: bold;">
                            @if ($near_miss->isActive && isset($near_miss->category))
                                <li>
                                    <div class="blue-heading" style="color: var(--location-section-heading-color)">{{ isset($near_miss->category) ? $near_miss->category->name : 'Near Miss' }}</div>
                                    <a style="font-weight: normal" class="collapse-item report-now-menu-menu-color"
                                        href="{{ route('location.near_miss') }}">
                                        Near Miss
                                    </a>
                                </li>
                            @endif
                            <hr class="my-1 w-75 mx-auto opacity-0 " style="opacity: 0;">
                            {{-- <li>
                                <a class="collapse-item report-now-menu-menu-color" href="{{route('location.dispensing_incidents')}}">
                                    Dispensing Incident
                                </a>
                            </li> --}}
                            @if (count($forms))
                                @foreach ($forms->groupBy('category.name') as $category => $forms2)
                                    <li>
                                        <div class="blue-heading" style="color: var(--location-section-heading-color)">{{ $category }}</div>
                                        <ul style="list-style: none; padding-left: 0">
                                            @foreach ($forms2 as $form)
                                                @if ($form->is_active)
                                                    <li class="formItem">
                                                        <a class="collapse-item report-now-menu-menu-color fw-normal"
                                                            href="{{ route('location.form.check_form_limits', ['formId' => $form->id]) }}"
                                                            @if (!$form->checkFormLimits()) target="_blank" @endif>{{ $form->name }}</a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                    <hr class="my-1 w-75 mx-auto opacity-0 " style="opacity: 0;">
                                @endforeach
                            @endif
                        </ul>
                    </div>
    
    
                </div>
            </div>


            




                











            </div>
            <div class="d-flex gap-1 p-1 mt-2"  style="width: 170px;background:#ececec;border-radius:3px;" >
                <a style="color: #696363" href="{{route('be_spoke_forms.be_spoke_form.records',['format'=>'timeline'])}}" class="btn py-2 m-0  {{ request()->route()->getName() == 'be_spoke_forms.be_spoke_form.records' && request()->query('format') != 'table' ? 'active-btn' : '' }}  w-50"
                    title=" View as Timeline">
                    Timeline
                </a>
                <a style="color: #696363" href="{{route('be_spoke_forms.be_spoke_form.records',['format'=>'table'])}}" class="btn py-2 m-0 {{ request()->route()->getName() == 'be_spoke_forms.be_spoke_form.records' && request('format') == 'table' ? 'active-btn' : '' }} w-50"
                    title=" View as List">
                    Table
                </a>
            </div>

            
        </div>
        @if ($reminders->count() > 0)
        <div class="mt-5" style="width: 170px;">
            <p class="fw-bold mb-0">Reminders</p>
            @foreach ($reminders as $reminder)
                @php
                    $form = $location->group_forms()->where('id', $reminder->form_id)->first();
                    $reminderTime = \Carbon\Carbon::parse($reminder['time']);
                    $now = now();
                    $timeDifference = $now->diffInMinutes($reminderTime, false); 

                    // Determine status
                    if ($timeDifference < 0) {
                        $status = 'Overdue (' . $reminderTime->diffForHumans($now) . ')';
                    } elseif ($timeDifference <= 60) {
                        $status = 'Within ' . $reminderTime->diffForHumans($now) ;
                    } elseif ($reminderTime->isToday()) {
                        $status = 'Today (' . $reminderTime->diffForHumans($now) . ')';
                    } else {
                        $status = 'Upcoming (' . $reminderTime->diffForHumans($now) . ')';
                    }

                @endphp

                @if ($form && isset($form->name))
                <a data-toggle="tooltip" title="{{ \Carbon\Carbon::parse($reminder['created_at'])->format('d F, Y h:i a')   }}"  href="{{ route('location.form.check_form_limits', ['formId' => $form->id]) }}"
                    @if (!$form->checkFormLimits()) target="_blank" @endif style="white-space: wrap" class="badge {{$timeDifference < 0 ? 'bg-danger' : 'bg-success'}} text-white">{{ $form->name }} - {{ $status }}</a>
                @endif
            @endforeach
        </div>
            
        @endif

        {{-- <form method="get" class="form search-form print-display-none" style="margin-top: -78px;">
            <div class="input-group form-group mb-3 search-wrapper">
                <div class="form-group-search">
                    <input type="text" class="form-control search-nearmiss" name="search"
                        @if (request()->query('search')) value="{{ request()->query('search') }}" @endif>
                </div>
                <div class="form-group-search">
                    <input type="text" name="start_date" class="datepicker form-control"
                        @if (request()->query('start_date')) value="{{ request()->query('start_date') }}" @else value="{{ date('d/m/Y', strtotime('-1 week')) }}" @endif>
                </div>
                <div class="form-group-search">
                    <input type="text" name="end_date" class="datepicker form-control"
                        @if (request()->query('end_date')) value="{{ request()->query('end_date') }}" @else value="{{ date('d/m/Y') }}" @endif>
                </div>
                <div class="form-group-search">
                    <select name="status" class="form-control psa_topbar_actions_select">
                        <select name="status" class="form-control psa_topbar_actions_select"
                        onchange="this.form.submit()">
                        <option value="all" @if (request()->query('status') == 'all') selected @endif>Show All Records
                        </option>
                        <option value="near_miss" @if (request()->query('status') == 'near_miss') selected @endif>Near Miss
                        </option>
                        @foreach ($location->assigned_bespoke_forms as $form)
                            <option value="{{ $form->form->id }}" @if (request()->query('status') == $form->form->id) selected @endif>
                                {{ $form->form->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if (request()->query('format'))
                    <input type="hidden" name="format" value="{{ request()->query('format') }}">
                @endif
                <button type="submit" class="btn btn-info search_button"><i class="fa fa-search"></i></button>
            </div>


        </form> --}}

        @include('layouts.error')

        @if (!$records)
            <h5 class="text-info text-center">No Record Available</h5>
        @else
            {{-- <div class="table-responsive">
        <table class="table table-bordered" id="dataTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($form->records as $record)
                <tr>
                    <td>{{$record->createdDate()}}</td>
                    <td>{{$record->location->name()}}</td>
                    <td>
                        <a href="{{route('be_spoke_forms.be_spoke_form.record.preview', $record->id)}}">Preview</a>
                        <a href="{{route('be_spoke_forms.be_spoke_form.record.root_cause_analysis', $record->id)}}">Root
                            cause analysis</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div> --}}
                <style>
                    .border-add{
                        position: relative;
                    }
                    .border-add::before{
                        position: absolute;
                        content: '';
                        width: 1px;
                        height: 100%;
                        left: 0;
                        top: -140px;
                        background: #dddcdf ;
                    }
                    .border-top-form{
                        position: relative;
                    }
                    .border-top-form::after{
                        position: absolute;
                        content: '';
                        height: 1px;
                        width: 100%;
                        left: 0;
                        top: -100px !important;
                        background: #dddcdf ;
                    }
                    .fixed-expand-buttons {
                        position: fixed;
                        bottom: 20px;
                        right: 20px;
                        display: flex;
                        gap: 10px;
                        z-index: 1000;
                    }

                    .button-style {
                        background-color: #e0e0e0;
                        color: #000;
                        padding: 10px 20px;
                        border-radius: 5px;
                        text-decoration: none;
                        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                        font-weight: bold;
                        transition: background-color 0.3s ease, box-shadow 0.3s ease;
                    }

                    .button-style:hover {
                        background-color: #c0c0c0;
                        box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.2);
                        color: #333;
                    }

                </style>
            <form id="search_form" method="GET" action="{{ route('be_spoke_forms.be_spoke_form.records') }}" role="search" style="width: 100%;">
                <div class="search-wrapper text-left d-flex"style="position: absolute; right: 50px; margin-top: 40px; margin-bottom: 2rem; border: 1px solid #dddcdf; padding: 6px 10px; border-radius: 3px;">
                <button type="submit" class="input-icon  search_btn bg-transparent"  style="    border: none;" id="basic-addon1"><i
                            class="fa fa-search"></i></button>    
                <input id="search-input" value="{{ request()->query('ad_search') }}" class="search-class" style="border: none; outline: none;" type="text"
                placeholder="Type to search" aria-label="search" name="ad_search">
                    

                    <div class="filters-wrapper">
                        <div class="filter-header">
                            <p>Show: </p>
                            <p id="selectedNum">0 selected</p>
                        </div>
                        <div class="filter-body">

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="selectAll">
                                <label class="form-check-label" for="flexCheckDefault1">
                                    Select All
                                </label>
                            </div>
                            <div>
                                <div class="heading-wrapper">
                                    <p class="cat-heading">Near Miss</p>
                                    <input class="form-check-input" type="checkbox" id="nearMissChk" value="parent_near_miss_chk" checked>
                                </div>
                                <hr>
                                <div class="chk-wrapper">


                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="error_by"
                                            id="reported_by_chk">
                                        <label class="form-check-label" for="reported_by_chk">
                                            Error by
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="point_of_detection"
                                            id="point_of_detection_chk">
                                        <label class="form-check-label" for="point_of_detection_chk">
                                            Point of Detection
                                        </label>
                                    </div>
                                    <div class="w-100" id="point_of_detection_multi_div" style="display: none;">
                                        <select name="point_of_detection_multi[]" id="point_of_detection_multi" multiple class="select2 w-100">
                                            <option value="Labelling">Labelling</option>
                                            <option value="Bagging">Bagging</option>
                                            <option value="Filing Away">Filing Away</option>
                                            <option value="Delivering">Delivering</option>
                                            <option value="Picking">Picking</option>
                                            <option value="Final Check">Final Check</option>
                                            <option value="Handing Out">Handing Out</option>
                                        </select>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="error_detected_by"
                                            id="detected_by_chk">
                                        <label class="form-check-label" for="detected_by_chk">
                                            Detected by
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="type_of_error"
                                            id="type_of_error">
                                        <label class="form-check-label" for="type_of_error">
                                            Type of Error
                                        </label>
                                    </div>
                                    <div class="w-100" id="type_of_error_multi_div" style="display: none">
                                        <select name="type_of_error_multi[]" id="type_of_error_multi" multiple class="select2 w-100">
                                            <optgroup  label="Prescription" class="optgroup">
                                                @foreach(App\Models\NearMiss::$errorTypePrescription as $field => $label)
                                                <option value="{{$field}}">{{$label}}</option>
                                                @endforeach
                                            </optgroup>
                                            <optgroup label="Labelling" class="optgroup">
                                                @foreach(App\Models\NearMiss::$errorTypeLabelling as $field => $label)
                                                <option value="{{$field}}">{{$label}}</option>
                                                @endforeach
                                            </optgroup>
                                            <optgroup label="Picking">
                                                @foreach(App\Models\NearMiss::$errorTypePicking as $field => $label)
                                                <option value="{{$field}}">{{$label}}</option>
                                                @endforeach
                                            </optgroup>
                                            <optgroup label="Placing into basket">
                                                @foreach(App\Models\NearMiss::$errorTypePlacingIntoBasket as $field => $label)
                                                <option value="{{$field}}">{{$label}}</option>
                                                @endforeach
                                            </optgroup>
                                            <optgroup label="Bagging">
                                                @foreach(App\Models\NearMiss::$errorTypeBagging as $field => $label)
                                                <option value="{{$field}}">{{$label}}</option>
                                                @endforeach
                                            </optgroup>
                                            <optgroup label="Preparing Dosette Tray">
                                                @foreach(App\Models\NearMiss::$errorTypePreparingDosetteTray as $field => $label)
                                                <option value="{{$field}}">{{$label}}</option>
                                                @endforeach
                                            </optgroup>
                                            <optgroup label="Handing Out">
                                                @foreach(App\Models\NearMiss::$errorTypeHandingOut as $field => $label)
                                                <option value="{{$field}}">{{$label}}</option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="contribution_chk"
                                            id="contribution_chk">
                                        <label class="form-check-label" for="contribution_chk">
                                            Contribution Factors
                                        </label>
                                    </div>
                                    <div class="w-100" id="contribution_chk_multi_div" style="display: none;">
                                        <select name="contribution_chk_multi[]" id="contribution_chk_multi" multiple class="select2 w-100">
                                            @foreach(App\Models\NearMiss::$contributingFactors as $main_label => $main_array)
                                                @if ($main_label != 'Other')
                                                    <optgroup label="{{$main_label}}">
                                                        @foreach($main_array as $field=> $label)
                                                            <option value="{{$field}}">{{$label}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="heading-wrapper">
                                    <p class="cat-heading">Incidents</p>
                                    <input id="incidentChk" class="form-check-input" type="checkbox" value="parent_incident_chk" checked>
                                </div>
                                <hr>
                                <div class="chk-wrapper">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="name" id="incedentChk1">
                                        <label class="form-check-label" for="incedentChk1">
                                            Name
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="description"
                                            id="incedentChk2">
                                        <label class="form-check-label" for="incedentChk2">
                                            Description
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div >
                                    <div class="form-group" style="color: #ACA9A9;">
                                        <input style="color: #ACA9A9;" type="datetime-local" class="form-control p-2" id="start_date" class="start_date" name="start_date" value="{{request()->query('start_date')}}">
                                        <label style="font-size: 14px;top:-9px;" for="start_date">Start Date</label>
                                    </div>
                                    <div class="form-group" style="color: #ACA9A9;">
                                        <input style="color: #ACA9A9;" type="datetime-local" class="form-control p-2" id="end_date" name="end_date" value="{{request()->query('end_date')}}">
                                        <label style="font-size: 14px;top:-9px;" for="end_date">End Date</label>
                                    </div>
                                </div>

                                <div class="actions">
                                    <button type="reset"
                                        class="btn btn-outline-secondary btn-outline-custom btn-sm">Clear</button>
                                    <button id="search_btn" type="button" class="btn btn-primary btn-sm search_btn">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="format" value="{{request()->query('format')}}">
            </form>
            @if (request()->query('format') !== 'table')
            <div class="timeline timeline_nearmiss border-add nearmiss-timeline" style="margin-left:13rem;">
                <div class="border-top-form"></div>
                @include('location.be_spoke_forms.record_data')


                <div class="line line-date line-reloading print-display-none" style="display:none">
                    <div class="timeline-label"><i class="spinning_icon fa-spin fa fa-spinner"></i></div>
                </div>
                <div class="line line-date last-line">
                    <div class="timeline-label">Start</div>
                </div>
                <div class="account_created center" style="margin: unset;">
                    <h4 class="timeline_category_title">Account Created</h4>
                    <p>{{ date('D jS F Y', strtotime(Auth::guard('location')->user()->created_at)) }}</p>
                </div>
                <div class="fixed-expand-buttons">
                    <a href="#" class="expand-all-btn button-style">Expand All</a>
                    <a href="#" class="show-less-all-btn button-style" style="display: none;">Show Less All</a>
                </div> 
            </div>
            @else
                @include('location.be_spoke_forms.record_data_table')
            @endif
        @endif

    </div>

    <style>
        .line.right-record .date {
            left: -114px !important;
        }

        .line.left-record .date {
            right: -114px !important;
        }
    </style>
@section('scripts')

    <script>

        $(document).ready(function(){
            $('.select2').select2();
        })
        $(document).on('click', '.select2-results__group', function(event) {
    event.stopPropagation(); 
    const optgroupLabel = $(this).text();
    const $select = $('#type_of_error_multi');
    const $optgroup = $select.find('optgroup[label="' + optgroupLabel + '"]');
    const isSelected = $optgroup.find('option:first').prop('selected');
    $optgroup.find('option').prop('selected', !isSelected);
    $select.trigger('change');
});



        // ========== Start Search Menu ============
        // These functions controls the visiblity of Search Menu 
        jQuery(document).on('click', function(event) {
            if (!jQuery(event.target).closest('.filters-wrapper, #search-input').length) {
                jQuery('.filters-wrapper').fadeOut('fast');
            }
        });

        jQuery('.filters-wrapper').on('click', function(event) {
            event.stopPropagation();
        });

        jQuery('#search-input').on('focus', function() {
            jQuery('.filters-wrapper').fadeIn()
        });
        // ========== End Search Menu ============

        // =========== Toggles Disable/Enable for the Categories =======================
        $('#nearMissChk').on('click', function(event) {
            if (!$('#nearMissChk').prop('checked')) {
                const chkWrapper = $('#nearMissChk').parent().siblings().closest('.chk-wrapper').addClass(
                    'disable').slideToggle();
                $(chkWrapper).find('input').prop('disabled', true)
            } else {
                const chkWrapper = $('#nearMissChk').parent().siblings().closest('.chk-wrapper').removeClass(
                    'disable').slideToggle();
                $(chkWrapper).find('input').prop('disabled', false)
            }
        })

        $('#incidentChk').on('click', function() {
            if (!$('#incidentChk').prop('checked')) {
                const chkWrapper = $('#incidentChk').parent().siblings().closest('.chk-wrapper').addClass(
                    'disable').slideToggle();
                $(chkWrapper).find('input').prop('disabled', true)
            } else {
                const chkWrapper = $('#incidentChk').parent().siblings().closest('.chk-wrapper').removeClass(
                    'disable').slideToggle();
                $(chkWrapper).find('input').prop('disabled', false)
            }
        })

        $('#type_of_error').on('click',function(){
            $('#type_of_error_multi_div').slideToggle();
        })
        $('#contribution_chk').on('click',function(){
            $('#contribution_chk_multi_div').slideToggle();
        })
        $('#point_of_detection_chk').on('click',function(){
            $('#point_of_detection_multi_div').slideToggle();
        })
        // =========== End of Toggles Disable/Enable for the Categories =======================

        // =================== Select all Check box function ===================================
        $('#selectAll').on('click', function() {
            const inputs = $('#selectAll').parents().closest('.filter-body').find('.chk-wrapper').find('input');
            if ($('#selectAll').prop('checked')) {
                $(inputs).each((index, input) => {
                    if (!$(input).prop('disabled')) {
                        $(input).prop('checked', true)
                    }
                })
            } else {
                $(inputs).each((index, input) => {
                    if (!$(input).prop('disabled')) {
                        $(input).prop('checked', false)
                    }
                })
            }
        })

        // ================== End of Select all ========================



        // ======== Checks and Selects All the inputs inside Search Menu and stores state in local storage =============
        $('.filter-body').on('click', 'input[type="checkbox"]', function() {
            const inputs = $('.filter-body').find('.chk-wrapper').find('input:not(:disabled)');
            const inputsChecked = inputs.map(function() {
                return {
                    id: this.id,
                    checked: this.checked
                };
            }).get();

            localStorage.setItem('checkboxState', JSON.stringify(inputsChecked));

            updateLocalStorage();

            $('#selectedNum').text(inputsChecked.filter(value => value.checked).length + ' selected');
        });

        function handleCheckboxClick(checkboxId) {
            const checkbox = $('#' + checkboxId);
            const chkWrapper = checkbox.parent().siblings().closest('.chk-wrapper');

            if (!checkbox.prop('checked')) {
                // chkWrapper.addClass('disable');
                // chkWrapper.find('input').prop('disabled', true);
            } else {
                // chkWrapper.removeClass('disable');
                // chkWrapper.find('input').prop('disabled', false);
            }

            
        }

        function updateLocalStorage() {
            const checkboxesState = {
                nearMissChk: $('#nearMissChk').prop('checked'),
                incidentChk: $('#incidentChk').prop('checked'),
                reported_by_chk: $('#reported_by_chk').prop('checked'),
                point_of_detection_chk: $('#point_of_detection_chk').prop('checked'),
                detected_by_chk: $('#detected_by_chk').prop('checked'),
                incedentChk1: $('#incedentChk1').prop('checked'),
                incedentChk2: $('#incedentChk2').prop('checked'),
                type_of_error: $('#type_of_error').prop('checked'),
                contribution_chk: $('#contribution_chk').prop('checked'),
                type_of_error_options: $('#type_of_error_multi').val(),
            };

            localStorage.setItem('checkboxesState', JSON.stringify(checkboxesState));
        }

        function applyCheckboxState() {
            const storedCheckboxState = localStorage.getItem('checkboxesState');
            if (storedCheckboxState) {
                const checkboxesState = JSON.parse(storedCheckboxState);

                Object.keys(checkboxesState).forEach(function(checkboxId) {
                    const checkboxState = checkboxesState[checkboxId];
                    $('#' + checkboxId).prop('checked', checkboxState);
                    handleCheckboxClick(checkboxId); // Apply the checkbox state
                    if((checkboxId == 'nearMissChk' && checkboxesState[checkboxId] == false) || (checkboxId == 'incidentChk'  && checkboxesState[checkboxId] == false)){
                        $('#' + checkboxId).parent().siblings().closest('.chk-wrapper').addClass('disable').slideToggle().find('input').prop('disabled', true);;
                    }else if(checkboxId == 'type_of_error' && checkboxesState[checkboxId] == true){
                        $('#type_of_error_multi_div').slideToggle();
                    }
                    else if(checkboxId == 'contribution_chk' && checkboxesState[checkboxId] == true){
                        $('#contribution_chk_multi_div').slideToggle();
                    }
                    else if(checkboxId == 'point_of_detection_chk' && checkboxesState[checkboxId] == true){
                        $('#point_of_detection_multi_div').slideToggle();
                    }else if(checkboxId == 'type_of_error_options'){
                        $('#type_of_error_multi').val(checkboxesState[checkboxId]).trigger('change');
                    }
                });
            }
        }

        $(document).ready(function() {
            applyCheckboxState();
        });

        // ======== End of Local Storage State handle =============



        // ============== Submits the Search on button Click inside the search menu ========
        $('.search_btn').on('click', function() {
            const inputValuesObject = {
                "near_miss": $('.filter-body').find('.chk-wrapper').eq(0).find('input:not(:disabled):checked')
                    .map(
                        function() {
                            if(this.id != 'type_of_error' && this.id != 'contribution_chk'){
                                return $(this).val();
                            }
                        }).get(),
                "incident": $('.filter-body').find('.chk-wrapper').eq(1).find('input:not(:disabled):checked')
                    .map(
                        function() {
                            return $(this).val();
                        }).get(),
            }

            if($('#nearMissChk').prop('checked')){
                inputValuesObject.near_miss.push('parent_near_miss_chk')
            }
            if($('#incidentChk').prop('checked')){
                inputValuesObject.incident.push('parent_incident_chk')
            }
            updateLocalStorage();
            const inputValuesJSON = JSON.stringify(inputValuesObject);

            $('<input>').attr({
                type: 'hidden',
                name: 'input_values',
                value: inputValuesJSON
            }).appendTo('#search_form');

            $('#search_form').submit();
        })

        // ============== End of Search ========




        jQuery(document).on('change', '.commentMultipleFiles', function(e) {
            e = e.originalEvent;
            var files = e.target.files;
            var form = $(this).closest('form');
            uploadDocumentCaseManager(files, form);
        });

        function uploadDocumentCaseManager(files, form = false) {
            var url = document.getElementById('route_document').value;
            for (let i = 0; i < files.length; i++) {
                let number = Math.floor(Math.random() * 1000000);
                var formData = new FormData();
                formData.append("file", files.item(i));
                formData.append("_token", $('input[name=_token]').val());
                formData.append("type", 'case_manager');
                var progress = $([
                    "<li class='item_" + number + "'><span class='fa fa-file'></span>&nbsp; " + files.item(i).name +
                    "<a href='#' title='Delete File' class='remove_btn'> <span class='fa fa-times'></span></a>",
                    "    <div class='progress'>",
                    "        <div class='progress-bar progress-bar-striped active' role='progressbar'",
                    "            aria-valuenow='0' aria-valuemin='0' aria-valuemax='" + files.item(i).size + "'>",
                    "            <span class='sr-only'>0%</span>",
                    "        </div>",
                    "    </div>",
                    "</li>",
                ].join(""));

                var progress2 = $([
                    "<li class='item_" + number + "'><span class='fa fa-file'></span>&nbsp; " + files.item(i).name +
                    "<a href='#' title='Delete File' class='remove_btn'> <span class='fa fa-times'></span></a>",
                    "</li>",
                ].join(""));

                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    processData: false,
                    cache: false,
                    contentType: false,
                    beforeSend: function() {
                        var percentage = 0;
                        if (form) {
                            $(form).find(".uploaded_files").append(progress);
                            $(form).find(".uploaded_files2").append(progress2);
                        } else {
                            $(".uploaded_files").append(progress);
                            $(".uploaded_files2").append(progress2);
                        }

                        $('.item_' + number + ' .progress .progress-bar').css("width", percentage + '%',
                            function() {
                                return $(this).attr("aria-valuenow", percentage) + "%";
                            });
                    },
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total) * 100;
                                $('.item_' + number + ' .progress .progress-bar').css("width",
                                    percentComplete + '%',
                                    function() {
                                        return $(this).attr("aria-valuenow", percentComplete) + "%";
                                    });
                            }
                        }, false);
                        xhr.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total) * 100;
                                $('.item_' + number + ' .progress .progress-bar').css("width",
                                    percentComplete + '%',
                                    function() {
                                        return $(this).attr("aria-valuenow", percentComplete) + "%";
                                    });
                            }
                        }, false);
                        return xhr;
                    },
                }).done(function(data) {
                    $('.item_' + number + ' .progress').remove();
                    try {
                        var input = "<input type='hidden' name='documents[]' class='file document' id='" + data.id +
                            "' value='" + data.id + "'>";
                        //$('.item_'+number).append(input);
                        $('.item_' + number + ":last").append(input);
                    } catch (e) {
                        console.log(e);
                    }

                });
            }
        }
        jQuery(document).on('click', '.remove_btn', function(e) {
            e.preventDefault();
            var route = $("#route_document_removedHashed").val();
            var val = $('.' + $(this).parent().attr('class'));
            var data = {
                'hashed': $('.' + $(this).parent().attr('class')).find("input[name='documents[]']").val(),
                '_token': "{{ csrf_token() }}"
            }
            $.post(route, data)
                .then(function(response) {
                    val.remove();
                })
                .catch(function(error) {
                    console.log(error);
                })


        });
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const expandAllBtn = document.querySelector(".expand-all-btn");
        const showLessAllBtn = document.querySelector(".show-less-all-btn");
        const individualExpandBtns = document.querySelectorAll(".see_details_btn");
        const individualShowLessBtns = document.querySelectorAll(".show_less_btn");

        function expandAllCards() {
        document.querySelectorAll(".details").forEach(detail => {
        detail.style.display = "block";
        });
        individualExpandBtns.forEach(btn => btn.style.display = "none");
        individualShowLessBtns.forEach(btn => btn.style.display = "inline-block"); 
        expandAllBtn.style.display = "none";
        showLessAllBtn.style.display = "inline-block";
        }

        function showLessAllCards() {
        document.querySelectorAll(".details").forEach(detail => {
        detail.style.display = "none";
        });
        individualExpandBtns.forEach(btn => btn.style.display = "inline-block"); 
        individualShowLessBtns.forEach(btn => btn.style.display = "none"); 
        expandAllBtn.style.display = "inline-block";
        showLessAllBtn.style.display = "none";
        }

        expandAllBtn.addEventListener("click", function(event) {
        event.preventDefault();
        expandAllCards();
        });

        showLessAllBtn.addEventListener("click", function(event) {
        event.preventDefault();
        showLessAllCards();
        });

        individualExpandBtns.forEach((btn, index) => {
        btn.addEventListener("click", function(event) {
        event.preventDefault();
        const details = btn.closest(".content-timeline").querySelector(".details");
        details.style.display = "block";
        btn.style.display = "none";
        individualShowLessBtns[index].style.display = "inline-block"; 
        });
        });

        individualShowLessBtns.forEach((btn, index) => {
        btn.addEventListener("click", function(event) {
        event.preventDefault();
        const details = btn.closest(".content-timeline").querySelector(".details");
        details.style.display = "none";
        btn.style.display = "none";
        individualExpandBtns[index].style.display = "inline-block";
        
        const anyExpanded = Array.from(document.querySelectorAll(".details")).some(detail => detail.style.display === "block");
        if (!anyExpanded) {
        expandAllBtn.style.display = "inline-block";
        showLessAllBtn.style.display = "none";
        }
        });
        });
        });


</script>

@endsection
@endsection
