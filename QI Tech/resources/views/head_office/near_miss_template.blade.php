@extends('layouts.near_miss_app')
@section('title', 'Report Near Miss')
@section('top-nav-title', 'Report Near Miss')
@section('content')
<style>
    .step .step-item::before {
  display: none;
}
.progress-bar-wrap {
  position: relative;
  height: 10px;
  width: 83%;
  border-radius: 10px;
  margin-inline: auto;
  overflow: hidden;
}
.progress-bar {
  background: #e7e9ed ;
  position: relative;
  width: 100%;
  height: 100%;
}
.progress-bar-fill {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 0%;
  height: 100%;
  background: #72c4ba;
}
    .highlight{
        outline: 2px solid transparent;
        transition: 0.2s ease;
    }
    .navbar{
        display: none !important;
    }
    .highlight:hover{
        outline: 2px solid #FF7F08;
        padding: 0.2rem;
        cursor: pointer;
        box-shadow: 0 0 10px 5px #ff7f0817;
    }
    .right-sidebar-settings{
    position: fixed;
    right: 0px;
    top: 0px;
    height: 100%;
    width: 500px;
    transition: right 0.2s;
    overflow-y: auto;
    z-index: 999999;
}
.select2-dropdown--below{
            z-index: 99999999;
        }
    .hidden-placeholder{
        opacity: 0.4;
    }
</style>
    <div id="content" style="height: 100vh; display:grid; place-items:center;">
        <input type="text" hidden value="company" id="company">
        <div class="card vh-75 container ">
            <div class="container mt-5">
                <a href="{{ route('head_office.be_spoke_form.index') }}" class="text-info"><i class="fa fa-arrow-left"></i>
                    Cancel</a>
                @if ($nearmiss)
                    <p class="badge badge-info center">Editing</p><br>
                @endif
            </div>
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

                <br>
                <form method="post"
                    action="#">
                    @csrf
                    <input type="hidden" name="id"
                        @if ($nearmiss) value="{{ $nearmiss->id }}" @endif>
                    <div class="stages stage_data_1">
                        <div class="m-t-10 highlight" data-list="location-list">
                            <h5 class="text-info" style="width: fit-content;">Where?</h5>
                            <select name="location_id" class="form-control" required>
                                <option value="" disabled selected> </option>
                                {{-- @foreach ($where as $loc)
                                    <option value="{{ $loc->location->id }}">
                                        {{ $loc->location->name() }}
                                    </option>
                                @endforeach --}}
                            </select>
                            
                            
                            
                            
                            @if (isset($location->near_miss_prescirption_dispensed_at_hub))
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
                        <h5 class="text-info m-t-10">When?</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h6><strong>Time</strong></h6>
                                <input type="time" name="time" class="form-control"
                                    value="@if (!$nearmiss) {{ date('H:i') }}@elseif($nearmiss){{ $nearmiss->time }} @endif">
                            </div>
                            <div class="col-md-6">
                                <h6><strong>Date</strong></h6>
                                <input type="text" class="form-control datepicker" name="date"
                                    value="@if (!$nearmiss) {{ date('d/m/Y') }}@elseif($nearmiss){{ $nearmiss->date() }} @endif">
                            </div>
                        </div>
                        @if (isset($location->near_miss_ask_for_who))
                        <div class="highlight mt-2" data-list="Who-new" id="whole-error-block">
                            
                            <h5 class="text-info m-t-10" style="width:fit-content">Who?</h5>
                            <div class="row" style="z-index: 0;pointer-events: none">
                                <div class="col-md-6 error_by_who {{ $data && $data['who']['error_by_who'] ? 'hidden-placeholder': '' }}" id="error_by_block">
                                    <h6 style="width:fit-content;"><strong>Error By</strong></h6>
                                    <select name="error_by" class="form-control error_by1 select2">
                                        <option>Please select a value</option>
                                        <option value="Unknown" @if ($nearmiss && $nearmiss->error_by == 'Unknown') selected @endif>Unknown
                                        </option>
                                        <option value="Other" @if ($nearmiss && $nearmiss->error_by == 'Other') selected @endif>Other
                                        </option>
                                    </select>
                                    <div class="error_by_other form-group"
                                        @if ($nearmiss && $nearmiss->error_by == 'Other') @else style="display:none" @endif>
                                        <label>Please Enter User Name</label>
                                        <input type="text" class="form-control error_by_other_field"
                                            name="error_by_other"
                                            @if ($nearmiss) value="{{ $nearmiss->error_by_other }}" @endif>
                                    </div>
                                </div>
                                <div class="col-md-6 error_detected_by_who {{ $data && $data['who']['error_detected_by_who'] ? 'hidden-placeholder': '' }}" id="error_detected_by_block">
                                    <h6 style="width:fit-content;"><strong>Error Detected By</strong></h6>
                                    <select name="error_detected_by" class="form-control error_detected_by1 select2">
                                        <option>Please select a value</option>
                
                                        <option value="Unknown" @if ($nearmiss && $nearmiss->error_detected_by == 'Unknown') selected @endif>Unknown
                                        </option>
                                        <option value="Other" @if ($nearmiss && $nearmiss->error_detected_by == 'Other') selected @endif>Other
                                        </option>
                                    </select>
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
                        @include('head_office.near_miss_template_stage_2')
                    </div>
                    <div class="stages stage_data_3 nearmiss_whys">
                        @include('head_office.near_miss_template_stage_3')
                    </div>
                    <div class="stages stage_data_4 {{$data && $data['contribution']['hidden'] ? 'hidden-placeholder' : ''}}" >
                        <div class="row">
                            @include('head_office.near_miss_template_stage_4')
                        </div>
                    </div>
                    <div class="stages stage_data_5 highlight {{ isset($data['actions']) && is_array($data['actions']) && $data['actions']['hidden'] ? 'hidden-placeholder' : '' }}" data-list="action">
                        <h5 class="text-info">Actions</h5>
                        <div class="form-group">
                            <label for="action_notes">Action Notes</label>
                            <textarea name="action_notes" id="actions" class="form-control" rows="4" placeholder="Enter action notes here...">@if($nearmiss){{ $nearmiss->action_notes }}@endif</textarea>
                        </div>
                    </div>
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
                            <input type="button" style="" class="btn btn-info right formSubmitButton1"
                                name="save" value="Save Changes">
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div class=" right-sidebar-settings custom-scroll" id="stage-side-wrapper" style="display:none;">
            <div onclick="$('#stage-side-wrapper').fadeOut()" class="position-fixed"
                style="top: 0;left:0;width:100%;height:100%;background:rgba(0, 0, 0, 0.1);">
            </div>
            <div class="card" style="min-height: 99%;z-index:110;"
                >
                <div class="card-body">

                    <form method="POST" action="{{route('head_office.near_miss.template_submit')}}" id="near_template_form">
                        @csrf
                        <input hidden type="text" name="setting" value="{{$setting->id}}">
                        <div class="d-flex align-items-center gap-2">
                            <p class="mb-0 mr-2" style="font-weight: 500;color:black;">Field:</p>
                            <p class="mb-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" id="field-placeholder"></p>
                        </div>
                        {{-- location --}}
                        <div id='loc-wrapper'>
                            <div class="mt-4">
                                <input type="checkbox" name="responder_diff_location" id="location_diff" {{$data && $data['who']['allow_responder_to_report_near_miss'] ? 'checked' : ''}}>
                                <label style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="location_diff">Allow responder to report near miss at a different location</label>
                            </div>
                            <select name="locations[]"  class="select2" style="width: 100%;" multiple="multiple" >
                                @foreach ($where as $loc)
                                    <option value="{{$loc->location->id}}" {{$data && $data['who']['location_ids'] && in_array($loc->location->id,$data['who']['location_ids']) ? 'selected' : ''}}>{{$loc->location->trading_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- who --}}
                        <div id='who-wrapper'>
                            <div class="mt-4">
                                <input type="checkbox" class="error_by_opt" name="error_by_who" id="error_by_who" {{$data && $data['who']['error_by_who'] ? 'checked' : ''}}>
                                <span>Hide </span><input type="text" name="error_by_label" value="{{$data && isset($data['who']['error_by_who_label']) ? $data['who']['error_by_who_label'] : 'Error By'}}" style="border: none;">
                            </div>
                            <div class="">
                                <input type="checkbox" class="error_by_opt" name="error_detected_by_who" id="error_detected_by_who" {{$data && isset($data['who']['error_detected_by_who']) && $data['who']['error_detected_by_who'] ? 'checked' : ''}}>
                                <span>Hide </span><input type="text" name="error_detected_label" value="{{$data && isset($data['who']['error_detected_by_who_label']) ? $data['who']['error_detected_by_who_label'] : 'Error Detected By'}}" style="border: none;">
                            </div>
                        </div>
                        {{-- Point of detection  --}}
                        <div id="point-wrapper" class="mt-4">
                            <button type="button" class="btn btn-outline-secondary" id="point-hide-btn">@if(!isset($data)) 
                                Hide
                                @else
                                {{$data['what']['point_of_detection']['hidden'] ? 'unHide' : 'Hide'}}
                                @endif</button>
                            <input type="checkbox" name="point_hide" id="point_hide" hidden
                            @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['what']['point_of_detection']['hidden'] ? 'checked' : ''}}
                                    @endif>
                            <div>
                                <div>
                                    <input  class="checkbox" type="checkbox" name="labelling" id="labelling"  
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['what']['point_of_detection']['labelling'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="labelling_text" id="labelling_text" 
                                        value="@if(isset($data) && !empty($data['what']['point_of_detection']['labelling_text'])) {{ $data['what']['point_of_detection']['labelling_text'] }} @else Labelling @endif"
                                        placeholder="Enter additional info" 
                                        style="border: none;">
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="bagging" id="bagging"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['what']['point_of_detection']['bagging'] ? 'checked' : ''}}
                                    @endif
                                    >
                                    {{-- <label style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="bagging">Bagging</label> --}}
                                    <input type="text" name="bagging_text" id="bagging_text" 
                                    value="@if(isset($data)) {{ $data['what']['point_of_detection']['bagging_text'] ?? 'Bagging' }} @else Bagging @endif" 
                                    placeholder="Enter additional info for Bagging" 
                                    style = "border: none;">
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="filling_away" id="filling_away"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['what']['point_of_detection']['filling_away'] ? 'checked' : ''}}
                                    @endif
                                    >
                                    {{-- <label style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="filling_away">Filling Away</label> --}}
                                    <input type="text" name="filling_away_text" id="filling_away_text" 
                                        value="@if(isset($data)) {{ $data['what']['point_of_detection']['filling_away_text'] ?? 'Filling Away' }} @else Filling Away @endif" 
                                        placeholder="Enter additional info for Filling Away" 
                                        style = "border: none;">
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="delivering" id="delivering" 
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['what']['point_of_detection']['delivering'] ? 'checked' : ''}}
                                    @endif
                                    >
                                    {{-- <label style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="delivering">Delivering</label> --}}
                                    <input type="text" name="delivering_text" id="delivering_text" 
                                        value="@if(isset($data)) {{ $data['what']['point_of_detection']['delivering_text'] ?? 'Delivering' }} @else Delivering @endif" 
                                        placeholder="Enter additional info for Delivering" 
                                        style = "border: none;">
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="picking" id="picking"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['what']['point_of_detection']['picking'] ? 'checked' : ''}}
                                    @endif>
                                    {{-- <label style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="picking">Picking</label> --}}
                                    <input type="text" name="picking_text" id="picking_text" 
                                    value="@if(isset($data)) {{ $data['what']['point_of_detection']['picking_text'] ?? 'Picking' }} @else Picking @endif" 
                                    placeholder="Enter additional info for Picking" 
                                    style = "border: none;">
                                </div>
                                <div>
                                    <input checked class="checkbox" type="checkbox" name="final_check" id="final_check" 
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['what']['point_of_detection']['final_check'] ? 'checked' : ''}}
                                    @endif>
                                    {{-- <label style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="final_check">Final Check</label> --}}
                                    <input type="text" name="final_check_text" id="final_check_text" 
                                     value="@if(isset($data)) {{ $data['what']['point_of_detection']['final_check_text']  ?? 'Final Check' }} @else Final Check @endif" 
                                     placeholder="Enter additional info for Final Check" 
                                     style = "border: none;">
                                </div>
                                <div>
                                    <input checked class="checkbox" type="checkbox" name="handing_out" id="handing_out" 
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['what']['point_of_detection']['handing_out'] ? 'checked' : ''}}
                                    @endif>
                                    {{-- <label style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="handing_out">Handing Out</label> --}}
                                    <input type="text" name="handing_out_text" id="handing_out_text" 
                                        value="@if(isset($data)) {{ $data['what']['point_of_detection']['handing_out_text'] ?? 'Handing Out' }} @else Handing Out @endif" 
                                        placeholder="Enter additional info for Handing Out" 
                                        style = "border: none;">
                                </div>
                            </div>
                        </div>
                        {{-- Was error  --}}
                        <div id="error-wrapper" class="mt-4">
                            <div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="error_prescription" id="error_prescription"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['what']['what_was_error']['error_prescription'] ? 'checked' : ''}}
                                    @endif>
                                    <input 
                                        type="text" 
                                        name="what_was_error[error_prescription][name]" 
                                        id="error_prescription_name" 
                                        value="{{ $data['what']['what_was_error']['error_prescription_name'] ?? 'Prescription' }}" 
                                        style="border: none; border: none;"
                                    >



                                    {{-- <label style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="error_prescription">Prescription</label> --}}
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="error_labelling" id="error_labelling"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['what']['what_was_error']['error_labelling'] ? 'checked' : ''}}
                                    @endif>
                                    
                                    <input 
                                        type="text" 
                                        name="what_was_error[error_labelling][name]" 
                                        id="error_labelling_name" 
                                        value="{{ $data['what']['what_was_error']['error_labelling_name'] ?? 'Labelling' }}" 
                                        style="border: none; border: none;"
                                    >
                                </div>
                                
                                <div>
                                    <input class="checkbox" type="checkbox" name="error_picking" id="error_picking"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['what']['what_was_error']['error_picking'] ? 'checked' : ''}}
                                    @endif>
                                
                                    <input 
                                        type="text" 
                                        name="what_was_error[error_picking][name]" 
                                        id="error_picking_name" 
                                        value="{{ $data['what']['what_was_error']['error_picking_name'] ?? 'Picking' }}" 
                                        style="border: none; border: none;"
                                    >
                                </div>
                                
                                <div>
                                    <input class="checkbox" type="checkbox" name="error_placing_into_basket" id="error_placing_into_basket"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['what']['what_was_error']['error_placing_into_basket'] ? 'checked' : ''}}
                                    @endif>
                                
                                    <input 
                                        type="text" 
                                        name="what_was_error[error_placing_into_basket][name]" 
                                        id="error_placing_into_basket_name" 
                                        value="{{ $data['what']['what_was_error']['error_placing_into_basket_name'] ?? 'Placing into Basket' }}" 
                                        style="border: none; border: none;"
                                    >
                                </div>
                                
                                <div>
                                    <input class="checkbox" type="checkbox" name="error_bagging" id="error_bagging"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['what']['what_was_error']['error_bagging'] ? 'checked' : ''}}
                                    @endif>
                                
                                    <input 
                                        type="text" 
                                        name="what_was_error[error_bagging][name]" 
                                        id="error_bagging_name" 
                                        value="{{ $data['what']['what_was_error']['error_bagging_name'] ?? 'Bagging' }}" 
                                        style="border: none; border: none;"
                                    >
                                </div>
                                
                                <div>
                                    <input class="checkbox" type="checkbox" name="error_preparing_dosette_tray" id="error_preparing_dosette_tray"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['what']['what_was_error']['error_preparing_dosette_tray'] ? 'checked' : ''}}
                                    @endif>
                                
                                    <input 
                                        type="text" 
                                        name="what_was_error[error_preparing_dosette_tray][name]" 
                                        id="error_preparing_dosette_tray_name" 
                                        value="{{ $data['what']['what_was_error']['error_preparing_dosette_tray_name'] ?? 'Preparing Dosette Tray' }}" 
                                        style="border: none; border: none;"
                                    >
                                </div>
                                
                                <div>
                                    <input class="checkbox" type="checkbox" name="error_handing_out" id="error_handing_out"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['what']['what_was_error']['error_handing_out'] ? 'checked' : ''}}
                                    @endif>
                                
                                    <input 
                                        type="text" 
                                        name="what_was_error[error_handing_out][name]" 
                                        id="error_handing_out_name" 
                                        value="{{ $data['what']['what_was_error']['error_handing_out_name'] ?? 'Handing Out' }}" 
                                        style="border: none; border: none;"
                                    >
                                </div>
                                
                            </div>
                        </div>
                        {{-- was error fields --}}
                        <div>
                            {{-- prescription --}}
                            <div class="mt-3" id="extra_prescription">
                                <div>
                                    <input class="checkbox" type="checkbox" name="missing_signature" id="missing_signature"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['prescription']['missing_signature']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="missing_signature_label" value="{{$data && isset($data['extra_fields']['prescription']['missing_signature']['label']) ? $data['extra_fields']['prescription']['missing_signature']['label'] : 'Missing signature'}}" style="border: none;">
                                    {{-- <label style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="missing_signature">Missing signature</label> --}}
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="prescription_expired_field" id="prescription_expired"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['prescription']['prescription_expired']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="prescription_expired_label" value="{{$data && isset($data['extra_fields']['prescription']['prescription_expired']['label']) ? $data['extra_fields']['prescription']['prescription_expired']['label'] : 'Prescription expired'}}" style="border: none;">                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="old_treatment" id="old_treatment"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['prescription']['old_treatment']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="old_treatment_label" value="{{$data && isset($data['extra_fields']['prescription']['old_treatment']['label']) ? $data['extra_fields']['prescription']['old_treatment']['label'] : 'Old treatment'}}" style="border: none;">
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="fraudulent_tampered_prescription" id="fraudulent_tampered_prescription"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['prescription']['fraudulent_tampered_prescription']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="fraudulent_tampered_prescription_label" value="{{$data && isset($data['extra_fields']['prescription']['fraudulent_tampered_prescription']['label']) ? $data['extra_fields']['prescription']['fraudulent_tampered_prescription']['label'] : 'Fraudulent/tampered prescription'}}" style="border: none;">
                                </div>
                            </div>
                            {{-- labeling --}}
                            <div class="mt-3" id="labeling_wrap">
                                <div>
                                    <input class="checkbox" type="checkbox" name="wrong_brand" id="wrong_brand"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['labelling']['wrong_brand']['hidden'] ? "checked" : ""}}
                                    @endif>
                                    <input type="text" name="wrong_brand_text" value="{{$data['extra_fields']['labelling']['wrong_brand']['wrong_brand_text'] ?? 'Wrong brand'}}" style="border: none;">
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="wrong_direction" id="wrong_direction"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['labelling']['wrong_direction']['hidden'] ? "checked" : ""}}
                                    @endif>
                                    <input type="text" name="wrong_direction_label" value="{{ $data['extra_fields']['labelling']['wrong_direction']['wrong_direction_label'] ?? "Wrong direction" }}" style="border: none;">
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="wrong_item" id="wrong_item"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['labelling']['wrong_item']['hidden'] ? "checked" : ""}}
                                    @endif>
                                    <input type="text" name="wrong_item_text" value="{{ $data['extra_fields']['labelling']['wrong_item']['wrong_item_text'] ?? "Wrong item" }}" style="border: none;">
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="wrong_formulation" id="wrong_formulation"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['labelling']['wrong_formulation']['hidden'] ? "checked" : ""}}
                                    @endif>
                                    <input type="text" name="wrong_formulation_text" value="{{ $data['extra_fields']['labelling']['wrong_formulation']['wrong_formulation_text'] ?? "Wrong formulation" }}" style="border: none;">
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="wrong_patient" id="wrong_patient"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['labelling']['wrong_patient']['hidden'] ? "checked" : ""}}
                                    @endif>
                                    <input type="text" name="wrong_patient_label" value="{{ $data['extra_fields']['labelling']['wrong_patient']['wrong_patient_label'] ?? "Wrong patient" }}" style="border: none;">
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="wrong_quantity" id="wrong_quantity"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['labelling']['wrong_quantity']['hidden'] ? "checked" : ""}}
                                    @endif>
                                    <input type="text" name="wrong_quantity_text" value="{{ $data['extra_fields']['labelling']['wrong_quantity']['wrong_quantity_text'] ?? "Wrong quantity" }}" style="border: none;">
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="wrong_strength" id="wrong_strength"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['labelling']['wrong_strength']['hidden'] ? "checked" : ""}}
                                    @endif>
                                    <input type="text" name="wrong_strength_text" value="{{ $data['extra_fields']['labelling']['wrong_strength']['wrong_strength_text'] ?? "Wrong strength" }}" style="border: none;">
                                </div>
                            </div>
                            
                            {{-- picking --}}
                            <div class="mt-3" id="picking_wrap">
                                <!-- Out-of-date item -->
                                <div>
                                    <input class="checkbox" type="checkbox" name="out_of_date_item" id="out_of_date_item"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['picking']['out_of_date_item']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="out_of_date_item_label" value="{{ $data['extra_fields']['picking']['out_of_date_item']['label'] ?? 'Out-of-date item' }}" style="border: none;">
                                </div>
                            
                                <!-- Wrong brand -->
                                <div>
                                    <input class="checkbox" type="checkbox" name="picking_wrong_brand_field" id="wrong_brand"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['picking']['wrong_brand']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="wrong_brand_label" value="{{ $data['extra_fields']['picking']['wrong_brand']['label'] ?? 'Wrong brand' }}" style="border: none;">
                                </div>
                            
                                <!-- Wrong item -->
                                <div>
                                    <input class="checkbox" type="checkbox" name="picking_wrong_item_field" id="wrong_item"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['picking']['wrong_item']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="wrong_item_label" value="{{ $data['extra_fields']['picking']['wrong_item']['label'] ?? 'Wrong item' }}" style="border: none;">
                                </div>
                            
                                <!-- Wrong formulation -->
                                <div>
                                    <input class="checkbox" type="checkbox" name="picking_wrong_formulation_field" id="wrong_formulation"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['picking']['wrong_formulation']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="wrong_formulation_label" value="{{ $data['extra_fields']['picking']['wrong_formulation']['label'] ?? 'Wrong formulation' }}" style="border: none;">
                                </div>
                            
                                <!-- Wrong quantity -->
                                <div>
                                    <input class="checkbox" type="checkbox" name="picking_wrong_quantity_field" id="wrong_quantity"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['picking']['wrong_quantity']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="wrong_quantity_label" value="{{ $data['extra_fields']['picking']['wrong_quantity']['label'] ?? 'Wrong quantity' }}" style="border: none;">
                                </div>
                            
                                <!-- Wrong strength -->
                                <div>
                                    <input class="checkbox" type="checkbox" name="picking_wrong_strength_field" id="wrong_strength"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['picking']['wrong_strength']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="wrong_strength_label" value="{{ $data['extra_fields']['picking']['wrong_strength']['label'] ?? 'Wrong strength' }}" style="border: none;" >
                                </div>
                            </div>
                            
                            {{-- placing into basket --}}
                            <div class="mt-3" id="placing_into_basket_wrap">
                                <div>
                                    <input class="checkbox" type="checkbox" name="another_patient_label_basket" id="another_patient_label_basket"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['placing_to_basket']['another_patient_label_basket']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    {{-- <label style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="another_patient_label_basket">Another patient's labels in/on the basket</label> --}}
                                    <input type="text" name="another_patient_label_basket_label" value="{{$data && isset($data['extra_fields']['placing_to_basket']['another_patient_label_basket']['label']) ? $data['extra_fields']['placing_to_basket']['another_patient_label_basket']['label'] : 'Another patient\'s labels in/on the basket'}}" style="border: none;">
                                </div>
                            
                                <div>
                                    <input class="checkbox" type="checkbox" name="wrong_basket" id="wrong_basket"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['placing_to_basket']['wrong_basket']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    {{-- <label style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="wrong_basket">Placed into the wrong basket</label> --}}
                                    <input type="text" name="wrong_basket_label" value="{{$data && isset($data['extra_fields']['placing_to_basket']['wrong_basket']['label']) ? $data['extra_fields']['placing_to_basket']['wrong_basket']['label'] : 'Placed into the wrong basket'}}" style="border: none;">
                                </div>
                            
                                <div>
                                    <input class="checkbox" type="checkbox" name="missing_item" id="missing_item"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['placing_to_basket']['missing_item']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    {{-- <label style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="missing_item">Missing item</label> --}}
                                    <input type="text" name="missing_item_label" value="{{$data && isset($data['extra_fields']['placing_to_basket']['missing_item']['label']) ? $data['extra_fields']['placing_to_basket']['missing_item']['label'] : 'Missing item'}}" style="border: none;">
                                </div>
                            
                                <div>
                                    <input class="checkbox" type="checkbox" name="label_wrong_item" id="label_wrong_item"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['placing_to_basket']['label_wrong_item']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    {{-- <label style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="label_wrong_item">Label attached to the wrong item</label> --}}
                                    <input type="text" name="label_wrong_item_label" value="{{$data && isset($data['extra_fields']['placing_to_basket']['label_wrong_item']['label']) ? $data['extra_fields']['placing_to_basket']['label_wrong_item']['label'] : 'Label attached to the wrong item'}}" style="border: none;">
                                </div>
                            </div>
                            
                            {{-- bagging --}}
                            <div class="mt-3" id="bagging_wrap">
                                <div>
                                    <input class="checkbox" type="checkbox" name="wrong_bag_label" id="wrong_bag_label"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['bagging']['wrong_bag_label']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="wrong_bag_label_text" value="{{ $data && isset($data['extra_fields']['bagging']['wrong_bag_label']['label']) ? $data['extra_fields']['bagging']['wrong_bag_label']['label'] : 'Wrong bag label' }}" style="border: none;">
                                </div>
                            
                                <div>
                                    <input class="checkbox" type="checkbox" name="another_patient_med_in_bag" id="another_patient_med_in_bag"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['bagging']['another_patient_med_in_bag']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="another_patient_med_in_bag_text" value="{{ $data && isset($data['extra_fields']['bagging']['another_patient_med_in_bag']['label']) ? $data['extra_fields']['bagging']['another_patient_med_in_bag']['label'] : 'Another patient\'s medication in bag' }}" style="border: none;">
                                </div>
                            
                                <div>
                                    <input class="checkbox" type="checkbox" name="missed_items" id="missed_items"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['bagging']['missed_items']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="missed_items_text" value="{{ $data && isset($data['extra_fields']['bagging']['missed_items']['label']) ? $data['extra_fields']['bagging']['missed_items']['label'] : 'Missed out items' }}" style="border: none;">
                                </div>
                            </div>
                            
                            
                            {{-- Preparing Dosette Tray --}}
                            <div class="mt-3" id="preparing_dosette_tray_wrap">
                                <div>
                                    <input class="checkbox" type="checkbox" name="wrong_day_or_time_of_day" id="wrong_day_or_time_of_day"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['preparing_dosette_tray']["wrong_day_or_time_of_day"] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="wrong_day_or_time_of_day_label" value="{{$data && isset($data['extra_fields']['preparing_dosette_tray']['wrong_day_or_time_of_day']['label']) ? $data['extra_fields']['preparing_dosette_tray']['wrong_day_or_time_of_day']['label'] : 'Wrong day/time of day'}}" style="border: none;">                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="error_on_patient_mar_chart" id="error_on_patient_mar_chart"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['preparing_dosette_tray']["error_on_patient_mar_chart"] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="error_on_patient_mar_chart_label" value="{{$data && isset($data['extra_fields']['preparing_dosette_tray']['error_on_patient_mar_chart']['label']) ? $data['extra_fields']['preparing_dosette_tray']['error_on_patient_mar_chart']['label'] : 'Error on patient MAR Chart'}}" style="border: none;">
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="extra_quantity_in_tray" id="extra_quantity_in_tray"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['preparing_dosette_tray']['extra_quantity_in_tray'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="extra_quantity_in_tray_label" value="{{$data && isset($data['extra_fields']['preparing_dosette_tray']['extra_quantity_in_tray']['label']) ? $data['extra_fields']['preparing_dosette_tray']['extra_quantity_in_tray']['label'] : 'Extra quantity in tray'}}" style="border: none;">
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="error_in_description_of_the_medication" id="error_in_description_of_the_medication"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['preparing_dosette_tray']['error_in_description_of_the_medication'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="error_in_description_of_the_medication_label" value="{{$data && isset($data['extra_fields']['preparing_dosette_tray']['error_in_description_of_the_medication']['label']) ? $data['extra_fields']['preparing_dosette_tray']['error_in_description_of_the_medication']['label'] : 'Error in description of the medication'}}" style="border: none;">
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="tray_wrong_bag_label" id="tray_wrong_bag_label"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['preparing_dosette_tray']['wrong_bag_label'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="wrong_bag_label_label" value="{{$data && isset($data['extra_fields']['preparing_dosette_tray']['wrong_bag_label']['label']) ? $data['extra_fields']['preparing_dosette_tray']['wrong_bag_label']['label'] : 'Wrong bag label'}}" style="border: none;">
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="external_item_missing" id="external_item_missing"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['preparing_dosette_tray']['external_item_missing'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="external_item_missing_label" value="{{$data && isset($data['extra_fields']['preparing_dosette_tray']['external_item_missing']['label']) ? $data['extra_fields']['preparing_dosette_tray']['external_item_missing']['label'] : 'External item missing'}}" style="border: none;">
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="tray_item_missing" id="tray_item_missing"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['preparing_dosette_tray']['tray_item_missing'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="tray_item_missing_label" value="{{$data && isset($data['extra_fields']['preparing_dosette_tray']['tray_item_missing']['label']) ? $data['extra_fields']['preparing_dosette_tray']['tray_item_missing']['label'] : 'Tray item missing'}}" style="border: none;">
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="preparing_dosette_tray_error_on_blister_pack" id="preparing_dosette_tray_error_on_blister_pack"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['preparing_dosette_tray']['preparing_dosette_tray_error_on_blister_pack']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="preparing_dosette_tray_error_on_blister_pack_label" value="{{$data && isset($data['extra_fields']['preparing_dosette_tray']['preparing_dosette_tray_error_on_blister_pack']['label']) ? $data['extra_fields']['preparing_dosette_tray']['preparing_dosette_tray_error_on_blister_pack']['label'] : 'Error on blister pack guide sheet'}}" style="border: none;">
                                </div>
                            </div>
                            {{-- Handing Out --}}
                            <div class="mt-3" id="handing_out_wrap">
                                <div>
                                    <!-- Checkbox for handed_to_wrong_patient -->
                                    <input class="checkbox" type="checkbox" name="handed_to_wrong_patient" id="handed_to_wrong_patient"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['extra_fields']['handing_out']["handed_to_wrong_patient"]['hidden'] ? 'checked' : ''}}
                                    @endif>
                            
                                    <!-- Text input for custom label -->
                                    <input type="text" name="handed_to_wrong_patient_label" id="handed_to_wrong_patient_label" 
                                    value="{{ isset($data['extra_fields']['handing_out']['handed_to_wrong_patient_label']) ? $data['extra_fields']['handing_out']['handed_to_wrong_patient_label'] : 'Handed to wrong patient' }}" style="border: none;">
                                </div>                               
                            </div>
                            
                        </div>
                            {{-- more nested fields --}}
                            <div>
                                <div class="mt-3" id="label_wrong_brand_fields_wrap">
                                    <div>
                                        <h6 style="margin-bottom: 0.3rem;">Prescribed Item</h6>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="labelling_wrong_brand_prescribed_field" id="labelling_wrong_brand_prescribed_field"
                                            @if(!isset($data)) 
                                            checked
                                            @else
                                            {{$data['extra_fields']['labelling']['wrong_brand']['prescribed_item']['hidden'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="labelling_wrong_brand_prescribed_field">Show</label>
                                        </div>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="labelling_wrong_brand_prescribed_mandatory" id="labelling_wrong_brand_prescribed_mandatory"
                                            @if(!isset($data)) 
                                            
                                            @else
                                            {{$data['extra_fields']['labelling']['wrong_brand']['prescribed_item']['mandatory'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="labelling_wrong_brand_prescribed_mandatory">Mandatory</label>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <h6 style="margin-bottom: 0.3rem;">Labelled Item</h6>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="labelling_wrong_brand_labelled_field" id="labelling_wrong_brand_labelled_field"
                                            @if(!isset($data)) 
                                            checked
                                            @else
                                            {{$data['extra_fields']['labelling']['wrong_brand']['labelled_item']['hidden'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="labelling_wrong_brand_labelled_field">Show</label>
                                        </div>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="labelling_wrong_brand_labelled_mandatory" id="labelling_wrong_brand_labelled_mandatory"
                                            @if(!isset($data)) 
                                            
                                            @else
                                            {{$data['extra_fields']['labelling']['wrong_brand']['labelled_item']['mandatory'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="labelling_wrong_brand_labelled_mandatory">Mandatory</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3" id="label_wrong_item_fields_wrap">
                                    <div>
                                        <h6 style="margin-bottom: 0.3rem;">Prescribed Item</h6>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="labelling_wrong_item_prescribed_field" id="labelling_wrong_item_prescribed_field"
                                            @if(!isset($data)) 
                                            checked
                                            @else
                                            {{$data['extra_fields']['labelling']['wrong_item']['prescribed_item']['hidden'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="labelling_wrong_item_prescribed_field">Show</label>
                                        </div>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="labelling_wrong_item_prescribed_mandatory" id="labelling_wrong_item_prescribed_mandatory"
                                            @if(!isset($data)) 
                                            
                                            @else
                                            {{$data['extra_fields']['labelling']['wrong_item']['prescribed_item']['mandatory'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="labelling_wrong_item_prescribed_mandatory">Mandatory</label>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <h6 style="margin-bottom: 0.3rem;">Labelled Item</h6>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="labelling_wrong_item_labelled_field" id="labelling_wrong_item_labelled_field"
                                            @if(!isset($data)) 
                                            checked
                                            @else
                                            {{$data['extra_fields']['labelling']['wrong_item']['labelled_item']['hidden'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="labelling_wrong_item_labelled_field">Show</label>
                                        </div>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="labelling_wrong_item_labelled_mandatory" id="labelling_wrong_item_labelled_mandatory"
                                            @if(!isset($data)) 
                                            
                                            @else
                                            {{$data['extra_fields']['labelling']['wrong_item']['labelled_item']['mandatory'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="labelling_wrong_item_labelled_mandatory">Mandatory</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3" id="label_wrong_formulation_fields_wrap">
                                    <div>
                                        <h6 style="margin-bottom: 0.3rem;">Prescribed Item</h6>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="labelling_wrong_formulation_prescribed_field" id="labelling_wrong_formulation_prescribed_field"
                                            @if(!isset($data)) 
                                            checked
                                            @else
                                            {{$data['extra_fields']['labelling']['wrong_formulation']['prescribed_item']['hidden'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="labelling_wrong_formulation_prescribed_field">Show</label>
                                        </div>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="labelling_wrong_formulation_prescribed_mandatory" id="labelling_wrong_formulation_prescribed_mandatory"
                                            @if(!isset($data)) 
                                            
                                            @else
                                            {{$data['extra_fields']['labelling']['wrong_formulation']['prescribed_item']['mandatory'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="labelling_wrong_formulation_prescribed_mandatory">Mandatory</label>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <h6 style="margin-bottom: 0.3rem;">Labelled Item</h6>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="labelling_wrong_formulation_labelled_field" id="labelling_wrong_formulation_labelled_field"
                                            @if(!isset($data)) 
                                            checked
                                            @else
                                            {{$data['extra_fields']['labelling']['wrong_formulation']['labelled_item']['hidden'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="labelling_wrong_formulation_labelled_field">Show</label>
                                        </div>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="labelling_wrong_formulation_labelled_mandatory" id="labelling_wrong_formulation_labelled_mandatory"
                                            @if(!isset($data)) 
                                            
                                            @else
                                            {{$data['extra_fields']['labelling']['wrong_formulation']['labelled_item']['mandatory'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="labelling_wrong_formulation_labelled_mandatory">Mandatory</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3" id="label_wrong_strength_fields_wrap">
                                    <div>
                                        <h6 style="margin-bottom: 0.3rem;">Prescribed Item</h6>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="labelling_wrong_strength_prescribed_field" id="labelling_wrong_strength_prescribed_field"
                                            @if(!isset($data)) 
                                            checked
                                            @else
                                            {{$data['extra_fields']['labelling']['wrong_strength']['prescribed_item']['hidden'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="labelling_wrong_strength_prescribed_field">Show</label>
                                        </div>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="labelling_wrong_strength_prescribed_mandatory" id="labelling_wrong_strength_prescribed_mandatory"
                                            @if(!isset($data)) 
                                            
                                            @else
                                            {{$data['extra_fields']['labelling']['wrong_strength']['prescribed_item']['mandatory'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="labelling_wrong_strength_prescribed_mandatory">Mandatory</label>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <h6 style="margin-bottom: 0.3rem;">Labelled Item</h6>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="labelling_wrong_strength_labelled_field" id="labelling_wrong_strength_labelled_field"
                                            @if(!isset($data)) 
                                            checked
                                            @else
                                            {{$data['extra_fields']['labelling']['wrong_strength']['labelled_item']['hidden'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="labelling_wrong_strength_labelled_field">Show</label>
                                        </div>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="labelling_wrong_strength_labelled_mandatory" id="labelling_wrong_strength_labelled_mandatory"
                                            @if(!isset($data)) 
                                            
                                            @else
                                            {{$data['extra_fields']['labelling']['wrong_strength']['labelled_item']['mandatory'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="labelling_wrong_strength_labelled_mandatory">Mandatory</label>
                                        </div>
                                    </div>
                                </div>

                                {{-- picking --}}

                                <div class="mt-3" id="picking_wrong_brand_fields_wrap">
                                    <div>
                                        <h6 style="margin-bottom: 0.3rem;">Prescribed Item</h6>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="picking_wrong_brand_prescribed_field" id="picking_wrong_brand_prescribed_field"
                                            @if(!isset($data)) 
                                            checked
                                            @else
                                            {{$data['extra_fields']['picking']['wrong_brand']['prescribed_item']['hidden'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="picking_wrong_brand_prescribed_field">Show</label>
                                        </div>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="picking_wrong_brand_prescribed_mandatory" id="picking_wrong_brand_prescribed_mandatory"
                                            @if(!isset($data)) 
                                            
                                            @else
                                            {{$data['extra_fields']['picking']['wrong_brand']['prescribed_item']['mandatory'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="picking_wrong_brand_prescribed_mandatory">Mandatory</label>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <h6 style="margin-bottom: 0.3rem;">Labelled Item</h6>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="picking_wrong_brand_labelled_field" id="picking_wrong_brand_labelled_field"
                                            @if(!isset($data)) 
                                            checked
                                            @else
                                            {{$data['extra_fields']['picking']['wrong_brand']['labelled_item']['hidden'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="picking_wrong_brand_labelled_field">Show</label>
                                        </div>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="picking_wrong_brand_labelled_mandatory" id="picking_wrong_brand_labelled_mandatory"
                                            @if(!isset($data)) 
                                            
                                            @else
                                            {{$data['extra_fields']['picking']['wrong_brand']['labelled_item']['mandatory'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="picking_wrong_brand_labelled_mandatory">Mandatory</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3" id="picking_wrong_item_fields_wrap">
                                    <div>
                                        <h6 style="margin-bottom: 0.3rem;">Prescribed Item</h6>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="picking_wrong_item_prescribed_field" id="picking_wrong_item_prescribed_field"
                                            @if(!isset($data)) 
                                            checked
                                            @else
                                            {{$data['extra_fields']['picking']['wrong_item']['prescribed_item']['hidden'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="picking_wrong_item_prescribed_field">Show</label>
                                        </div>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="picking_wrong_item_prescribed_mandatory" id="picking_wrong_item_prescribed_mandatory"
                                            @if(!isset($data)) 
                                            
                                            @else
                                            {{$data['extra_fields']['picking']['wrong_item']['prescribed_item']['mandatory'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="picking_wrong_item_prescribed_mandatory">Mandatory</label>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <h6 style="margin-bottom: 0.3rem;">Labelled Item</h6>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="picking_wrong_item_labelled_field" id="picking_wrong_item_labelled_field"
                                            @if(!isset($data)) 
                                            checked
                                            @else
                                            {{$data['extra_fields']['picking']['wrong_item']['labelled_item']['hidden'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="picking_wrong_item_labelled_field">Show</label>
                                        </div>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="picking_wrong_item_labelled_mandatory" id="picking_wrong_item_labelled_mandatory"
                                            @if(!isset($data)) 
                                            
                                            @else
                                            {{$data['extra_fields']['picking']['wrong_item']['labelled_item']['mandatory'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="picking_wrong_item_labelled_mandatory">Mandatory</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3" id="picking_wrong_formulation_fields_wrap">
                                    <div>
                                        <h6 style="margin-bottom: 0.3rem;">Prescribed Item</h6>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="picking_wrong_formulation_prescribed_field" id="picking_wrong_formulation_prescribed_field"
                                            @if(!isset($data)) 
                                            checked
                                            @else
                                            {{$data['extra_fields']['picking']['wrong_formulation']['prescribed_item']['hidden'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="picking_wrong_formulation_prescribed_field">Show</label>
                                        </div>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="picking_wrong_formulation_prescribed_mandatory" id="picking_wrong_formulation_prescribed_mandatory"
                                            @if(!isset($data)) 
                                            
                                            @else
                                            {{$data['extra_fields']['picking']['wrong_formulation']['prescribed_item']['mandatory'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="picking_wrong_formulation_prescribed_mandatory">Mandatory</label>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <h6 style="margin-bottom: 0.3rem;">Labelled Item</h6>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="picking_wrong_formulation_labelled_field" id="picking_wrong_formulation_labelled_field"
                                            @if(!isset($data)) 
                                            checked
                                            @else
                                            {{$data['extra_fields']['picking']['wrong_formulation']['labelled_item']['hidden'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="picking_wrong_formulation_labelled_field">Show</label>
                                        </div>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="picking_wrong_formulation_labelled_mandatory" id="picking_wrong_formulation_labelled_mandatory"
                                            @if(!isset($data)) 
                                            
                                            @else
                                            {{$data['extra_fields']['picking']['wrong_formulation']['labelled_item']['mandatory'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="picking_wrong_formulation_labelled_mandatory">Mandatory</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3" id="picking_wrong_strength_fields_wrap">
                                    <div>
                                        <h6 style="margin-bottom: 0.3rem;">Prescribed Item</h6>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="picking_wrong_strength_prescribed_field" id="picking_wrong_strength_prescribed_field"
                                            @if(!isset($data)) 
                                            checked
                                            @else
                                            {{$data['extra_fields']['picking']['wrong_strength']['prescribed_item']['hidden'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="picking_wrong_strength_prescribed_field">Show</label>
                                        </div>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="picking_wrong_strength_prescribed_mandatory" id="picking_wrong_strength_prescribed_mandatory"
                                            @if(!isset($data)) 
                                            
                                            @else
                                            {{$data['extra_fields']['picking']['wrong_strength']['prescribed_item']['mandatory'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="picking_wrong_strength_prescribed_mandatory">Mandatory</label>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <h6 style="margin-bottom: 0.3rem;">Labelled Item</h6>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="picking_wrong_strength_labelled_field" id="picking_wrong_strength_labelled_field"
                                            @if(!isset($data)) 
                                            checked
                                            @else
                                            {{$data['extra_fields']['picking']['wrong_strength']['labelled_item']['hidden'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="picking_wrong_strength_labelled_field">Show</label>
                                        </div>
                                        <div>
                                            <input class="checkbox" type="checkbox" name="picking_wrong_strength_labelled_mandatory" id="picking_wrong_strength_labelled_mandatory"
                                            @if(!isset($data)) 
                                            
                                            @else
                                            {{$data['extra_fields']['picking']['wrong_strength']['labelled_item']['mandatory'] ? 'checked' : ''}}
                                            @endif>
                                            <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="picking_wrong_strength_labelled_mandatory">Mandatory</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- The reason fields --}}
                            <div>
                                <div class="mt-3" id="prescription_expired_reasons_wrap">
                                    @foreach(App\Models\NearMiss::$PrescriptionReasonsOfNearMiss['prescription_expired'] as $field=> $label)
                                        @if(Illuminate\Support\Str::contains($field,'other_field'))
                                            
                                        @else
                                        <div>
                                            <input class="checkbox" type="checkbox" name="{{$field.'_field'}}" id="{{$field}}_id"
                                            @if(!isset($data)) 
                                            checked
                                            @else
                                            {{$data['extra_fields']['prescription']['prescription_expired']['reason'][$field.'_field'] ? 'checked' : ''}}
                                            @endif>
                                            {{-- <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="{{$field}}_id">{{$label}}</label> --}}
                                            <input type="text" name="{{$field.'_label'}}" value="{{$data['extra_fields']['prescription']['prescription_expired']['reason'][$field.'_label'] ?? $label}}" style="border:none"> 
                                    </div>
                                        @endif
                                    @endforeach
                                    {{-- <button type="button" id="add-more-fields-prescription_expired_reasons_wrap">+</button> --}}
                                </div>
                                <script>
                                    document.getElementById('add-more-fields-prescription_expired_reasons_wrap').addEventListener('click', function () {
                                        const container = document.getElementById('prescription_expired_reasons_wrap');
                                        const newIndex = container.querySelectorAll('.prescription-field').length + 1;
                                        const newField = `
                                            <div class="prescription-field">
                                                <input class="checkbox" type="checkbox" name="reason[prescription_expired][new_field_${newIndex}_field]" id="new_field_${newIndex}">
                                                <input type="text" name="reason[prescription_expired][new_field_${newIndex}_label]" value="New Field ${newIndex}">
                                            </div>
                                        `;
                                        container.insertAdjacentHTML('beforeend', newField);
                                    });
                                </script>
                                

                                <div class="mt-3" id="prescription_missing_signature_reasons_wrap">
                                    @foreach(App\Models\NearMiss::$PrescriptionReasonsOfNearMiss['prescription_missing_signature'] as $field=> $label)
                                        @if(Illuminate\Support\Str::contains($field,'other_field'))
                                            
                                        @else
                                        <div>
                                            <input class="checkbox" type="checkbox" name="{{$field.'_field'}}" id="{{$field}}_id"
                                                @if(!isset($data))
                                                    checked
                                                @else
                                                    {{ isset($data['extra_fields']['prescription']['missing_signature']['reason'][$field.'_field']) && $data['extra_fields']['prescription']['missing_signature']['reason'][$field.'_field'] ? 'checked' : ''}}
                                                @endif> 
                                                <input type="text" name="{{$field.'_label'}}" value="{{$data['extra_fields']['prescription']['missing_signature']['reason'][$field.'_label'] ?? $label }}" style="border: none">
                                                {{-- <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="{{$field}}_id">{{$label}}</label> --}}
                                        </div>
                                        @endif
                                    @endforeach
                                    {{-- <button type="button" id="add-more-fields-btn-missing-signature">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </button> --}}
                                </div>
                                <script>
                                    document.getElementById('add-more-fields-btn-missing-signature').addEventListener('click', function () {
                                        const container = document.getElementById('prescription_missing_signature_reasons_wrap');
                                        const newIndex = container.querySelectorAll('.prescription-field').length + 1;
                                        const newField = `
                                            <div class="prescription-field">
                                                <input class="checkbox" type="checkbox" name="reason[missing_signature][new_field_${newIndex}_field]" id="new_field_${newIndex}">
                                                <input type="text" name="reason[missing_signature][new_field_${newIndex}_label]" value="New Field ${newIndex}" style="border: none">
                                            </div>
                                        `;
                                        container.insertAdjacentHTML('beforeend', newField);
                                    });
                                </script>
                                 
                                
                                

                                 <div class="mt-3" id="prescription_old_treatment_reasons_wrap">
                                    @foreach(App\Models\NearMiss::$PrescriptionReasonsOfNearMiss['prescription_old_treatment'] as $field=> $label)
                                        @if(Illuminate\Support\Str::contains($field,'other_field'))
                                            
                                        @else
                                        <div>
                                            <input class="checkbox" type="checkbox" name="{{$field.'_field'}}" id="{{$field}}_id"
                                            @if(!isset($data)) 
                                            checked
                                            @else
                                            {{ ($data['extra_fields']['prescription']['old_treatment']['reason'][$field.'_field'] ?? false) ? 'checked' : '' }}
                                            @endif>

                                            <input type="text" name="{{$field.'_label'}}" value="{{$data['extra_fields']['prescription']['old_treatment']['reason'][$field.'_label'] ?? $label}}" style="border:none">
                                            {{-- <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="{{$field}}_id">{{$label}}</label> --}}
                                    </div>
                                        @endif
                                    @endforeach
                                    {{-- <button type="button" id="add-more-fields-btn-old-treatment">+</button> --}}
                                </div>
                                <script>
                                    document.getElementById('add-more-fields-btn-old-treatment').addEventListener('click', function () {
                                        const container = document.getElementById('prescription_old_treatment_reasons_wrap');
                                        const newIndex = container.querySelectorAll('.prescription-field').length + 1;
                                        const newField = `
                                            <div class="prescription-field">
                                                <input class="checkbox" type="checkbox" name="reason[old_treatment][new_field_${newIndex}_field]" id="new_field_${newIndex}">
                                                <input type="text" name="reason[old_treatment][new_field_${newIndex}_label]" value="New Field ${newIndex}">
                                            </div>
                                        `;
                                        container.insertAdjacentHTML('beforeend', newField);
                                    });
                                </script>
                                


                                <div class="mt-3" id="prescription_tampered_reasons_wrap">
                                    @foreach(App\Models\NearMiss::$PrescriptionReasonsOfNearMiss['prescription_tampered'] as $field=> $label)
                                        @if(Illuminate\Support\Str::contains($field,'other_field'))
                                            
                                        @else
                                        <div>
                                            <input class="checkbox" type="checkbox" name="{{$field.'_field'}}" id="{{$field}}_id"
                                            @if(!isset($data)) 
                                            checked
                                            @else
                                            {{ ($data['extra_fields']['prescription']['fraudulent_tampered_prescription']['reason'][$field.'_field'] ?? false) ? 'checked' : '' }}
                                            @endif>
                                            {{-- <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="{{$field}}_id">{{$label}}</label> --}}
                                            <input type="text" name="{{$field.'_label'}}" value="{{$data['extra_fields']['prescription']['fraudulent_tampered_prescription']['reason'][$field.'_label'] ?? $label }}" style="border:none">
                                    </div>
                                        @endif
                                    @endforeach
                                    {{-- <button type="button" id="add-more-fields-btn-prescription_tampered_reasons_wrap">+</button> --}}
                                </div>
                                <script>
                                    document.getElementById('add-more-fields-btn-prescription_tampered_reasons_wrap').addEventListener('click', function () {
                                        const container = document.getElementById('prescription_tampered_reasons_wrap');
                                        const newIndex = container.querySelectorAll('.prescription-field').length + 1;
                                        const newField = `
                                            <div class="prescription-field">
                                                <input class="checkbox" type="checkbox" name="reason[fraudulent_tampered_prescription][new_field_${newIndex}_field]" id="new_field_${newIndex}">
                                                <input type="text" name="reason[fraudulent_tampered_prescription][new_field_${newIndex}_label]" value="New Field ${newIndex}">
                                            </div>
                                        `;
                                        container.insertAdjacentHTML('beforeend', newField);
                                    });
                                </script>
                                

                                @foreach(App\Models\NearMiss::$LabellingReasonsOfNearMiss as $main_label => $main_array)
                                    <div class="mt-3" id="{{$main_label}}_reasons_wrap">
                                        @foreach($main_array as $field=> $label)
                                            @if(Illuminate\Support\Str::contains($field,'other_field'))
                                                
                                            @else
                                            <div>
                                                <input class="checkbox" type="checkbox" name="{{$field.'_field'}}" id="{{$field}}_id"
                                                @if(!isset($data)) 
                                                checked
                                                @else
                                                {{$data['extra_fields']['labelling'][str_replace("labelling_", "", $main_label)]['reason'][$field.'_field'] ? 'checked' : ''}}
                                                @endif>
                                                {{-- <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="{{$field}}_id">{{$label}}</label> --}}
                                                <input type="text" name="{{$field.'_label'}}" value="{{$data['extra_fields']['labelling'][str_replace("labelling_", "", $main_label)]['reason'][$field.'_label'] ?? $label }}" style="border:none">
                                        </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach

                                @foreach(App\Models\NearMiss::$PickingReasonsOfNearMiss as $main_label => $main_array)
                                    <div class="mt-3" id="{{$main_label}}_reasons_wrap">
                                        @foreach($main_array as $field=> $label)
                                            @if(Illuminate\Support\Str::contains($field,'other_field'))
                                                
                                            @else
                                            <div>
                                                <input class="checkbox" type="checkbox" name="{{$field.'_field'}}" id="{{$field}}_id"
                                                @if(!isset($data)) 
                                                checked
                                                @else
                                                {{$data['extra_fields']['picking'][str_replace("picking_", "", $main_label)]['reason'][$field.'_field'] ? 'checked' : ''}}
                                                @endif>
                                                {{-- <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="{{$field}}_id">{{$label}}</label> --}}
                                                <input type="text" name="{{$field.'_label'}}" value="{{$data['extra_fields']['picking'][str_replace("picking_", "", $main_label)]['reason'][$field.'_label'] ?? $label}}" style="border:none">
                                        </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach

                                @foreach(App\Models\NearMiss::$PlacingIntoBasketReasonsOfNearMiss as $main_label => $main_array)
                                    <div class="mt-3" id="{{$main_label}}_reasons_wrap">
                                        @foreach($main_array as $field=> $label)
                                            @if(Illuminate\Support\Str::contains($field,'other_field'))
                                                
                                            @else
                                            <div>
                                                <input class="checkbox" type="checkbox" name="{{$field.'_field'}}" id="{{$field}}_id"
                                                @if(!isset($data)) 
                                                checked
                                                @else
                                                {{$data['extra_fields']['placing_to_basket'][str_replace("placing_basket_", "", $main_label)]['reason'][$field.'_field'] ? 'checked' : ''}}
                                                @endif>
                                                <input type="text" name="{{$field.'_label'}}"  value="{{$data['extra_fields']['placing_to_basket'][str_replace("placing_basket_", "", $main_label)]['reason'][$field.'_field'] ?? $label}}" style="border:none">
                                                {{-- <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="{{$field}}_id">{{$label}}</label> --}}
                                        </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach

                                @foreach(App\Models\NearMiss::$BaggingReasonsOfNearMiss as $main_label => $main_array)
                                    <div class="mt-3" id="{{$main_label}}_reasons_wrap">
                                        @foreach($main_array as $field=> $label)
                                            @if(Illuminate\Support\Str::contains($field,'other_field'))
                                                
                                            @else
                                            <div>
                                                <input class="checkbox" type="checkbox" name="{{$field.'_field'}}" id="{{$field}}_id"
                                                @if(!isset($data)) 
                                                checked
                                                @else
                                                {{$data['extra_fields']['bagging'][str_replace("bagging_", "", $main_label)]['reason'][$field.'_field'] ? 'checked' : ''}}
                                                @endif>
                                                {{-- <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="{{$field}}_id">{{$label}}</label> --}}
                                                <input type="text" name="{{$field.'_label'}}" value="{{$data['extra_fields']['bagging'][str_replace("bagging_", "", $main_label)]['reason'][$field.'_field'] ?? $label }}" style="border:none">
                                        </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach

                                @foreach(App\Models\NearMiss::$PreparingDosetteTrayReasonsOfNearMiss as $main_label => $main_array)
                                    <div class="mt-3" id="{{$main_label}}_reasons_wrap">
                                        @foreach($main_array as $field=> $label)
                                            @if(Illuminate\Support\Str::contains($field,'other_field'))
                                                
                                            @else
                                            <div>
                                                <input class="checkbox" type="checkbox" name="{{$field.'_field'}}" id="{{$field}}_id"
                                                @if(!isset($data)) 
                                                checked
                                                @else
                                                {{$data['extra_fields']['preparing_dosette_tray']['preparing_dosette_tray_error_on_blister_pack']['reason'][$field.'_field'] ? 'checked' : ''}}
                                                @endif>
                                                {{-- <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="{{$field}}_id">{{$label}}</label> --}}
                                                <input type="text" name="{{$field.'_label'}}" value="{{$data['extra_fields']['preparing_dosette_tray']['preparing_dosette_tray_error_on_blister_pack']['reason'][$field.'_field'] ?? $label}}" style="border:none">
                                        </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach

                                @foreach(App\Models\NearMiss::$HandingReasonsOfNearMiss as $main_label => $main_array)
                                    <div class="mt-3" id="{{$main_label}}_reasons_wrap">
                                        @foreach($main_array as $field=> $label)
                                            @if(Illuminate\Support\Str::contains($field,'other_field'))
                                                
                                            @else
                                            <div>
                                                <input class="checkbox" type="checkbox" name="{{$field.'_field'}}" id="{{$field}}_id"
                                                @if(!isset($data)) 
                                                checked
                                                @else
                                                {{$data['extra_fields']['handing_out']['handed_to_wrong_patient']['reason'][$field.'_field'] ? 'checked' : ''}}
                                                @endif>
                                                {{-- <label class="m-0" style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="{{$field}}_id">{{$label}}</label> --}}
                                                <input type="text" name="{{$field.'_label'}}" value="{{$data['extra_fields']['handing_out']['handed_to_wrong_patient']['reason'][$field.'_label'] ?? $label}}" style="border: none;">
                                        </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach

                            </div>
                        {{-- Contribution  --}}
                        <div>
                            <div>
                                <input type="checkbox" name="hide_contribution" id="hide_contribution"
                                @if(!isset($data)) 
                                    
                                    @else
                                    {{$data['contribution']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                <label style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="hide_contribution">Hide Section</label>
                            </div>

                            <div class="mt-2" id="Staff-wrap">
                                <div>
                                    <input class="section-hide" type="checkbox" name="Staff" id="Staff"
                                    @if(!isset($data)) 
                                    
                                    @else
                                    {{$data['contribution']['hidden'] ? 'checked' : ''}}
                                    @endif >
                                    <label style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="Staff">hide Staff</label>
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="fewer_staff_than_usual" id="fewer_staff_than_usual"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['contribution']['staff']['fewer_staff_than_usual'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="fewer_staff_than_usual_label" value="{{ $data['contribution']['staff']['fewer_staff_than_usual_label'] ?? 'Fewer staff than usual' }}" style="border: none">
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="not_the_usual_pharmacist" id="not_the_usual_pharmacist"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['contribution']['staff']['not_the_usual_pharmacist'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="not_the_usual_pharmacist_label" value="{{ $data['contribution']['staff']['not_the_usual_pharmacist_label'] ?? 'Not the usual pharmacist' }}" style="border: none">
                                    </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="not_the_usual_despneser" id="not_the_usual_despneser"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['contribution']['staff']['not_the_usual_despneser'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="not_the_usual_despneser_label" value="{{ $data['contribution']['staff']['not_the_usual_despneser_label'] ?? 'Not the usual dispenser' }}" style="border: none">
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="pharmacist_self_checking" id="pharmacist_self-checking"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['contribution']['staff']['pharmacist_self_checking'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="pharmacist_self_checking_label" value="{{ $data['contribution']['staff']['pharmacist_self_checking_label'] ?? 'Pharmacist self-checking' }}" style="border: none">

                                </div>
                            </div>
                            <div class="mt-2" id="Environment-wrap">
                                <div>
                                    <input class="section-hide" type="checkbox" name="Environment" id="Environment"
                                    @if(!isset($data)) 
                                    
                                    @else
                                    {{$data['contribution']['environment']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    <label style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="Environment">hide Environment</label>
                                </div>
                                <div>
                                    <input checked class="checkbox" type="checkbox" name="messy_environment" id="messy_environment"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['contribution']['environment']['messy_environment'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="messy_environment_label" value="{{ $data['contribution']['environment']['messy_environment_label'] ?? 'Messy environment' }}" style="border: none;">
                                </div>
                            </div>
                            <div class="mt-2" id="Tasks-wrap">
                                <div>
                                    <input class="section-hide" type="checkbox" name="Tasks" id="Tasks"
                                    @if(!isset($data)) 
                                    @else
                                    {{$data['contribution']['tasks']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    <label for="">Hide Tasks & Workload'</label>
                                    {{-- <span>Hide</span><input type="text" name="Tasks_label" value="{{ $data['contribution']['tasks']['Tasks_label'] ?? 'Hide Tasks & Workload' }}" style="border: none;"> --}}
                                </div>
                                <div>
                                    <input checked class="checkbox" type="checkbox" name="high_number_of_patients_waiting" id="high_number_of_patients_waiting"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['contribution']['tasks']['high_number_of_patients_waiting'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="high_number_of_patients_waiting_label" value="{{ $data['contribution']['tasks']['high_number_of_patients_waiting_label'] ?? 'High number of patients waiting' }}" style="border: none;">
                                </div>
                                <div>
                                    <input checked class="checkbox" type="checkbox" name="busy_otc_trade" id="busy_otc_trade"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['contribution']['tasks']['busy_otc_trade'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="busy_otc_trade_label" value="{{ $data['contribution']['tasks']['busy_otc_trade_label'] ?? 'Busy OTC trade' }}" style="border: none;">
                                </div>
                                <div>
                                    <input checked class="checkbox" type="checkbox" name="backlog_of_work" id="backlog_of_work"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['contribution']['tasks']['backlog_of_work'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="backlog_of_work_label" value="{{ $data['contribution']['tasks']['backlog_of_work_label'] ?? 'Backlog of work' }}" style="border: none;">
                                </div>
                                <div>
                                    <input checked class="checkbox" type="checkbox" name="quieter_than_usual" id="quieter_than_usual"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['contribution']['tasks']['quieter_than_usual'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="quieter_than_usual_label" value="{{ $data['contribution']['tasks']['quieter_than_usual_label'] ?? 'Quieter than usual' }}" style="border: none;">
                                </div>
                                <div>
                                    <input checked class="checkbox" type="checkbox" name="telephone_interruption" id="telephone_interruption"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['contribution']['tasks']['telephone_interruption'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="telephone_interruption_label" value="{{ $data['contribution']['tasks']['telephone_interruption_label'] ?? 'Telephone interruption' }}" style="border: none;">
                                </div>
                            </div>
                            
                            <div class="mt-2" id="Person-wrap">
                                <div>
                                    <input class="section-hide" type="checkbox" name="Person" id="Person"
                                    @if(!isset($data)) 
                                    @else
                                    {{$data['contribution']['person']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="Person_label" value="{{ $data['contribution']['person']['Person_label'] ?? 'Hide Person' }}" style="border: none;">
                                </div>
                                <div>
                                    <input checked class="checkbox" type="checkbox" name="dyslexia" id="dyslexia"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['contribution']['person']['dyslexia'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="dyslexia_label" value="{{ $data['contribution']['person']['dyslexia_label'] ?? 'Dyslexia' }}" style="border: none;">
                                </div>
                                <div>
                                    <input checked class="checkbox" type="checkbox" name="dyscalculia" id="dyscalculia"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data['contribution']['person']['dyscalculia'] ? 'checked' : ''}}
                                    @endif>
                                    <input type="text" name="dyscalculia_label" value="{{ $data['contribution']['person']['dyscalculia_label'] ?? 'Dyscalculia' }}" style="border: none;">
                                </div>
                            </div>
                            
                            <div class="mt-2" id="Training-wrap">
                                <div>
                                    <input class="section-hide" type="checkbox" name="Training" id="Training"
                                    @if(!isset($data)) 
                                    @else
                                    {{$data["contribution"]["training"]["hidden"] ? "checked" : ""}}
                                    @endif>
                                    <input type="text" name="Training_label" value="{{ $data["contribution"]["training"]["Training_label"] ?? "Training" }}" style="border: none;">
                                </div>
                                <div>
                                    <input checked class="checkbox" type="checkbox" name="person_in_training" id="person_in_training"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data["contribution"]["training"]["person_in_training"] ? "checked" : ""}}
                                    @endif>
                                    <input type="text" name="person_in_training_label" value="{{ $data["contribution"]["training"]["person_in_training_label"] ?? "Person in training" }}" style="border: none;">
                                </div>
                                <div>
                                    <input class="checkbox" type="checkbox" name="person_not_trained_in_this_area" id="person_not_trained_in_this_area"
                                    @if(!isset($data)) 
                                    checked
                                    @else
                                    {{$data["contribution"]["training"]["person_not_trained_in_this_area"] ? "checked" : ""}}
                                    @endif>
                                    <input type="text" name="person_not_trained_in_this_area_label" value="{{ $data["contribution"]["training"]["person_not_trained_in_this_area_label"] ?? "Person not trained in this area" }}" style="border: none;">
                                </div>
                            </div>
                            <div class="mt-2" id="Other-wrap">
                                <div>
                                    <input class="section-hide" type="checkbox" name="Other" id="Other" 
                                    @if(!isset($data)) 
                                    @else
                                    {{$data['contribution']['other']['hidden'] ? 'checked' : ''}}
                                    @endif>
                                    <label style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="Other">hide Other Section</label>
                                </div>
                            </div>
                        </div>
                        {{-- Actions --}}
                        <div id="action-wrapper">
                            <div>
                                <input type="checkbox" name="hide_actions" id="hide_actions" 
                                @if(!isset($data)) 
                                    @else
                                    {{ isset($data['actions']) && isset($data['actions']['hidden']) && $data['actions']['hidden'] ? 'checked' : '' }}
                                    @endif>
                                <label style="color:rgba(0, 0, 0, 0.907);font-weight: 500;" for="hide_actions">Hide Actions</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark mt-4 text-center">Submit Changes</button>
                    </form>
            </div>
        </div>
    </div>
    {{-- @dd($data) --}}
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('admin_assets/css/progress-step.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alertify.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('/js/alertify.min.js') }}"></script>

    <script>
        $('.formSubmitButton1').on('click',function(event){
            $('#near_template_form').submit();
        })
        $('.highlight').on('click',function(element){
            if($(element.target).hasClass('highlight')){
                $('#stage-side-wrapper').fadeIn();
            }
            const data = $(this).data('list');
            $('#point-wrapper').fadeOut('fast');
            $('#loc-wrapper').fadeOut('fast');
            $('#error-wrapper,#Staff-wrap,#Environment-wrap,#Person-wrap,#Training-wrap,#Tasks-wrap,#Other-wrap').fadeOut('fast');
            $('#Staff-wrap').parent().fadeOut('fast');
            $('#action-wrapper,#who-wrapper').fadeOut('fast');
            $('#extra_prescription,#labeling_wrap,#picking_wrap,#placing_into_basket_wrap,#bagging_wrap,#preparing_dosette_tray_wrap,#handing_out_wrap').fadeOut('fast')
            $('#label_wrong_brand_fields_wrap,#label_wrong_item_fields_wrap,#label_wrong_formulation_fields_wrap,#label_wrong_strength_fields_wrap').fadeOut('fast')
            $('#picking_wrong_brand_fields_wrap,#picking_wrong_item_fields_wrap,#picking_wrong_formulation_fields_wrap,#picking_wrong_strength_fields_wrap').fadeOut('fast')
            $('#prescription_missing_signature_reasons_wrap,#prescription_expired_reasons_wrap,#prescription_old_treatment_reasons_wrap,#prescription_tampered_reasons_wrap').fadeOut('fast')
            $('#labelling_wrong_brand_reasons_wrap,#labelling_wrong_item_reasons_wrap,#labelling_wrong_direction_reasons_wrap,#labelling_wrong_formulation_reasons_wrap,#labelling_wrong_patient_reasons_wrap,#labelling_wrong_quantity_reasons_wrap,#labelling_wrong_strength_reasons_wrap,#picking_out_of_date_item_reasons_wrap,#picking_wrong_brand_reasons_wrap,#picking_wrong_item_reasons_wrap,#picking_wrong_quantity_reasons_wrap,#picking_wrong_strength_reasons_wrap,#picking_wrong_formulation_reasons_wrap,#bagging_another_patient_med_in_bag_reasons_wrap,#bagging_wrong_bag_label_reasons_wrap,#placing_basket_label_wrong_item_reasons_wrap,#placing_basket_wrong_basket_reasons_wrap,#placing_basket_another_patient_label_basket_reasons_wrap,#placing_basket_missing_item_reasons_wrap,#bagging_missed_items_reasons_wrap,#preparing_dosette_tray_error_on_blister_pack_reasons_wrap,#handing_out_to_wrong_patient_reasons_wrap').fadeOut('fast')

            if(data == 'location-list'){
                $('#field-placeholder').text('Location List');
                $('#loc-wrapper').fadeIn();
            }
            else if(data == 'Who-new'){
                $('#field-placeholder').text('Who');
                $('#who-wrapper').fadeIn();
            }else if(data == 'point'){
                $('#field-placeholder').text('Point of detection');
                $('#point-wrapper').fadeIn();
            }else if(data == 'was_error'){
                $('#field-placeholder').text('What was the error');
                $('#error-wrapper').fadeIn();
            }else if(data == 'Staff'){
                $('#field-placeholder').text('Staff');
                $('#Staff-wrap').fadeIn();
                $('#Staff-wrap').parent().fadeIn();
            }else if(data == 'Environment'){
                $('#field-placeholder').text('Environment');
                $('#Staff-wrap').parent().fadeIn();
                $('#Environment-wrap').fadeIn();
            }else if(data == 'Tasks & Workload'){
                $('#field-placeholder').text('Tasks & Workload');
                $('#Tasks-wrap').fadeIn();
                $('#Tasks-wrap').parent().fadeIn();
            }else if(data == 'Person'){
                $('#field-placeholder').text('Person');
                $('#Staff-wrap').parent().fadeIn();
                $('#Person-wrap').fadeIn();
            }else if(data == 'Training'){
                $('#field-placeholder').text('Training');
                $('#Staff-wrap').parent().fadeIn();
                $('#Training-wrap').fadeIn();
            }else if(data == 'Other'){
                $('#field-placeholder').text('Other');
                $('#Staff-wrap').parent().fadeIn();
                $('#Other-wrap').fadeIn();
            }else if(data == 'action'){
                $('#field-placeholder').text('Actions');
                $('#action-wrapper').fadeIn();
            }
            // extra fields
            else if(data == 'error_prescription_chks'){
                $('#field-placeholder').text('Prescription');
                $('#extra_prescription').fadeIn();
            }else if(data == 'error_labelling_chks'){
                $('#field-placeholder').text('Labelling');
                $('#labeling_wrap').fadeIn();
            }else if(data == 'error_picking_chks'){
                $('#field-placeholder').text('Picking');
                $('#picking_wrap').fadeIn();
            }else if(data == 'error_placing_into_basket_chks'){
                $('#field-placeholder').text('Placing into basket');
                $('#placing_into_basket_wrap').fadeIn();
            }else if(data == 'error_bagging_chks'){
                $('#field-placeholder').text('Bagging');
                $('#bagging_wrap').fadeIn();
            }else if(data == 'error_dosette_tray_chks'){
                $('#field-placeholder').text('Preparing Dosette Tray');
                $('#preparing_dosette_tray_wrap').fadeIn();
            }
            else if(data == 'error_handing_out_chks'){
                $('#field-placeholder').text('Handing Out');
                $('#handing_out_wrap').fadeIn();
            }
            // more fields
            else if(data == 'label_wrong_brand_fields'){
                $('#field-placeholder').text('Label Wrong brand');
                $('#label_wrong_brand_fields_wrap').fadeIn();
            }
            else if(data == 'label_wrong_item_fields'){
                $('#field-placeholder').text('Label Wrong item');
                $('#label_wrong_item_fields_wrap').fadeIn();
            }
            else if(data == 'label_wrong_formulation_fields'){
                $('#field-placeholder').text('Label Wrong formulation');
                $('#label_wrong_formulation_fields_wrap').fadeIn();
            }
            else if(data == 'label_wrong_strength_fields'){
                $('#field-placeholder').text('Label Wrong strength');
                $('#label_wrong_strength_fields_wrap').fadeIn();
            }
            // picking more fields
            else if(data == 'picking_wrong_brand_fields'){
                $('#field-placeholder').text('Picking Wrong brand');
                $('#picking_wrong_brand_fields_wrap').fadeIn();
            }
            else if(data == 'picking_wrong_item_fields'){
                $('#field-placeholder').text('Picking Wrong item');
                $('#picking_wrong_item_fields_wrap').fadeIn();
            }
            else if(data == 'picking_wrong_formulation_fields'){
                $('#field-placeholder').text('Picking Wrong formulation');
                $('#picking_wrong_formulation_fields_wrap').fadeIn();
            }
            else if(data == 'picking_wrong_strength_fields'){
                $('#field-placeholder').text('Picking Wrong strength');
                $('#picking_wrong_strength_fields_wrap').fadeIn();
            }
            else{
                console.log(data);
                $('#field-placeholder').text(replaceAndCapitalize(data));
                $(`#${data}_wrap`).fadeIn();
            }
        });

        // $('#point-hide-btn').on('click',function(){
        //     if($(this).text() == 'Hide'){
        //         $(this).text('unHide');
        //         $('#point-stage-wrapper').addClass('hidden-placeholder');
        //         $('#point_hide').prop('checked', true);
        //     }else{
        //         $(this).text('Hide');
        //         $('#point-stage-wrapper').removeClass('hidden-placeholder');
        //         $('#point_hide').prop('checked', false);
        //     }
        // })
        $('#hide_contribution').on('change',function(){
            if($(this).prop('checked')){
                $('.stage_data_4').addClass('hidden-placeholder')
            }else{
                $('.stage_data_4').removeClass('hidden-placeholder')
            }
        })
        $('#hide_actions').on('change',function(){
            if($(this).prop('checked')){
                $('.stage_data_5').addClass('hidden-placeholder')
            }else{
                $('.stage_data_5').removeClass('hidden-placeholder')
            }
        })

        $('.checkbox').change(function() {
        var checkboxName = $(this).attr('name');
        if(checkboxName == 'pharmacist_self_checking'){
            checkboxName = 'pharmacist_self-checking'
        }
        var correspondingBtn = $('.'+checkboxName);
        
        if (!$(this).prop('checked')) { 
            correspondingBtn.fadeOut(); // Fade out corresponding buttons
        } else { 
            correspondingBtn.fadeIn(); 
        }
        });

        $('.section-hide').change(function() {
        var checkboxName = $(this).attr('name');
        if(checkboxName == 'Tasks & Workload'){
            checkboxName = 'Tasks'
        }
        var correspondingBtn = $('.'+checkboxName);
        
        if ($(this).prop('checked')) { 
            correspondingBtn.addClass('hidden-placeholder') // Fade out corresponding buttons
            correspondingBtn.fadeIn(); 
        } else { 
            correspondingBtn.removeClass('hidden-placeholder')
            correspondingBtn.fadeIn(); 
        }
        });

        $('.error_by_opt').change(function() {
        var checkboxName = $(this).attr('name');

        var correspondingBtn = $('.'+checkboxName);
        
        if ($(this).prop('checked')) { 
            correspondingBtn.addClass('hidden-placeholder') // Fade out corresponding buttons
            correspondingBtn.fadeIn(); 
        } else { 
            correspondingBtn.removeClass('hidden-placeholder')
            correspondingBtn.fadeIn(); 
        }
        });
        $(document).ready(function() {
            $('.select_2').select2();
        });
        function replaceAndCapitalize(str) {
            // Replace underscores with spaces
            str = str.replace(/_/g, ' ');
            
            // Capitalize the first letter
            str = str.charAt(0).toUpperCase() + str.slice(1);
            
            return str;
        }
    </script>
@if(Session::has('success'))
<script>
    alertify.success("{{ Session::get('success') }}");
</script>
@elseif(Session::has('error'))
<script>
alertify.success("{{ Session::get('error') }}");
</script>
@endif
@endsection
