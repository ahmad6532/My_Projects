@extends('layouts.head_office_app')
@section('title','Create/Edit National Alert')
@section('content')
<div id="content">
    <div class="headingWithSearch">
        <div class="heading-center">
            @if(!isset($nationalAlert))Create New @else Edit @endif Patient Safety Alert
        </div>
    </div>
    <div class="btn-group btn-group-sm float-right" role="group" style="margin-top: -61px">
        <a href="{{ route('national_alerts.national_alert.index') }}" class="btn btn-info"
                title="Show All National Alert">
            <span class="fas fa-th-list" aria-hidden="true"></span>
        </a>
    </div>
        <div class="row">
            <div class="col-md-12">
                @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                @endif

                <form method="POST" action="{{ route('head_office.psa.holding_area.save') }}" accept-charset="UTF-8"
                    id="national_alert_form" name="national_alert_form" class="form-horizontal national_alert_form"
                    enctype="multipart/form-data">
                    @csrf
                    @if(request()->query('copying')) <div class="alert alert-info">Copying will create a new alert!
                    </div> @endif
                    @if(isset($nationalAlert) && $nationalAlert->created_by == 'head_office')
                    <input type="hidden" name="id" class="national_alert_id patient_safety_alert_id"
                        @if(isset($nationalAlert)) value="{{$nationalAlert->id}}" @endif>
                    @elseif(request()->query('cloning'))
                    <input type="hidden" name="parent_id" @if(isset($nationalAlert)) value="{{$nationalAlert->id}}"
                        @endif>
                    @endif
                    <div class="organisation-structure-add-content @if($errors->has('title')) has-error @endif">
                        <label for="title" class="inputGroup">Title
                            <input type="text" class="title" name="title" max="255" required
                            @if(isset($nationalAlert)) value="{{$nationalAlert->title}}" @endif placeholder="Add Title">
                        </label>
                    </div>
                    <div class="form-buttons">
                        <label for="Type">Type</label><br>
                        <input type="hidden" name="type" class="type" data-targets=".types" @if(isset($nationalAlert))
                            value="{{$nationalAlert->type}}" @endif>
                        @foreach(\App\Models\NationalAlert::$types as $type)
                        <button type="button" data-value="{{$type}}" data-target=".type"
                            class="types @if(isset($nationalAlert) && $nationalAlert->type == $type) active  @endif btn btn-outline btn-outline-info">{{$type}}</button>
                        @endforeach
                    </div>
                    <div class="p_l_recall_wrapper {{ $errors->has('patient_level_recall') ? 'has-error' : '' }}"
                        @if(!isset($nationalAlert) || ($nationalAlert->type != "Medicines Recall" &&
                        $nationalAlert->type != "Company-Led Medicines Recall/Notification")) style="display:none;"
                        @endif>
                        <span>Medicines Recall</span><br>
                        <label for="patient_level_recall">
                            <input class="patient_level_recall hide" id="patient_level_recall"
                                name="patient_level_recall" type="checkbox" value="1" @if(isset($nationalAlert) &&
                                $nationalAlert->patient_level_recall) checked @endif >
                            <span onclick="$(this).toggleClass('active')"
                                class="btn btn-outline-info @if(isset($nationalAlert) && $nationalAlert->patient_level_recall) active @endif">This
                                is a Patient Level Recall</span>
                        </label>
                        <br>
                    </div>

                    <div class="form-group custom_type_wrapper" @if(!isset($nationalAlert) || $nationalAlert->type !=
                        'Custom' ) style="display:none" @endif >
                        <label>Please Enter Custom Type</label>
                        <input type="text" name="custom_type" class="form-control custom_type" @if(isset($nationalAlert)
                            && $nationalAlert->type == 'Custom' ) value="{{$nationalAlert->custom_type}}" @endif>
                    </div>

                    <div class="form-buttons originator_wrapper" @if(!isset($nationalAlert)) style="display:none;"
                        @endif>
                        <label for="Type">Originator <small class="inline form-text text-muted">(You can select multiple
                                originators)</small></label><br>
                        @foreach(\App\Models\NationalAlert::$originators as $key=>$originator)
                        <input type="checkbox" @if(isset($nationalAlert) && $nationalAlert->hasOriginator($originator))
                        checked @endif name="originator[]" value="{{$originator}}" class="hide originator
                        originator_{{$key}}">
                        <button type="button" data-value="{{$originator}}" data-target=".originator_{{$key}}"
                            data-multiple="true"
                            class=" @if(isset($nationalAlert) && $nationalAlert->hasOriginator($originator)) active @endif btn btn-outline btn-outline-info">{{$originator}}</button>
                        @endforeach

                    </div>

                    <div class="form-group custom_originator_wrapper" @if(!isset($nationalAlert) || !$nationalAlert->
                        hasOriginator('Custom')) style="display:none" @endif>
                        <label>Please Enter Custom Originator</label>
                        <input type="text" name="custom_originator" class="form-control custom_originator"
                            @if(isset($nationalAlert) && $nationalAlert->hasOriginator('Custom'))
                        value="{{$nationalAlert->custom_originator}}" @endif>
                    </div>

                    <div class="form-buttons class_wrapper {{ $errors->has('class') ? 'has-error' : '' }}"
                        @if(!isset($nationalAlert)) style="display:none;" @endif>
                        <label for="class" class="">Class</label><br>
                        <input type="hidden" name="class" class="class" data-targets=".classes"
                            @if(isset($nationalAlert)) value="{{$nationalAlert->class}}" @endif>
                        @foreach(\App\Models\NationalAlert::$classes as $class)
                        <button type="button" data-value="{{$class}}" data-target=".class"
                            class="@if(isset($nationalAlert) &&  $nationalAlert->class == $class) active  @endif classes btn btn-outline btn-outline-info {{str_replace(' ','_',$class)}}">{{$class}}
                            {{\App\Models\NationalAlert::showClassTitle( $class )}}</button>
                        @endforeach
                    </div>

                    <div class="form-buttons action_wrapper {{ $errors->has('action_within') ? 'has-error' : '' }}"
                        @if(!isset($nationalAlert)) style="display:none;" @endif>
                        <label for="action_within" class="">Action Within</label><br>
                        <input type="hidden" name="action_within" class="action_within" data-targets=".actions"
                            @if(isset($nationalAlert)) value="{{$nationalAlert->action_within}}" @endif>
                        <button type="button" data-value="1" data-target=".action_within"
                            class="@if(isset($nationalAlert) &&  $nationalAlert->action_within == 1) active  @endif actions btn btn-outline btn-outline-info class1_action ">{{\App\Models\NationalAlert::actionWithInTitle(1)}}</button>
                        <button type="button" data-value="2" data-target=".action_within"
                            class="@if(isset($nationalAlert) &&  $nationalAlert->action_within == 2) active  @endif actions btn btn-outline btn-outline-info class2_action ">{{\App\Models\NationalAlert::actionWithInTitle(2)}}</button>
                        <button type="button" data-value="5" data-target=".action_within"
                            class="@if(isset($nationalAlert) &&  $nationalAlert->action_within == 5) active  @endif actions btn btn-outline btn-outline-info class3_action ">{{\App\Models\NationalAlert::actionWithInTitle(5)}}</button>
                        <button type="button" data-value="28" data-target=".action_within"
                            class=" @if(isset($nationalAlert) &&  $nationalAlert->action_within == 28) active  @endif actions btn btn-outline btn-outline-info class4_action ">{{\App\Models\NationalAlert::actionWithInTitle(28)}}</button>
                        <button type="button" data-value="Custom" data-target=".action_within"
                            class=" @if(isset($nationalAlert) &&  $nationalAlert->action_within == 'Custom') active  @endif actions btn btn-outline btn-outline-info class5_action ">{{\App\Models\NationalAlert::actionWithInTitle('Custom')}}</button>
                    </div>

                    <div class="form-group action_within_days_wrapper" @if(!isset($nationalAlert) || $nationalAlert->
                        action_within != 'Custom' ) style="display:none" @endif>
                        <label>Please Enter Action Within Days</label>
                        <input type="number" name="action_within_days" min="1" class="form-control action_within_days"
                            @if(isset($nationalAlert) && $nationalAlert->action_within == 'Custom')
                        value="{{$nationalAlert->action_within_days}}" @endif >
                    </div>

                    <div class="form-group summary_wrapper {{ $errors->has('summary') ? 'has-error' : '' }}"
                        @if(!isset($nationalAlert)) style="display:none;" @endif>
                        <label for="summary" class="">Summary</label>
                        <textarea spellcheck="true"  class="form-control" name="summary" id="summary" rows="3"
                            required>@if(isset($nationalAlert)){{$nationalAlert->summary}} @endif</textarea>
                    </div>
                    <div class="form-group summary_wrapper" @if(!isset($nationalAlert)) style="display:none;" @endif>
                        <label for="Suggested Actions" class="">Suggested Actions</label>
                        <textarea spellcheck="true"  class="form-control" name="suggested_actions" id="suggested_actions"
                            rows="3">@if(isset($nationalAlert)){{$nationalAlert->suggested_actions}} @endif</textarea>
                    </div>


                    <div class="form-buttons shared_with_wrapper {{ $errors->has('send_to_countries') ? 'has-error' : '' }}"
                        @if(!isset($nationalAlert)) style="display:none;" @endif>
                        <label for="country" class="">Send to Countries</label><br>
                        <input type="checkbox" @if(isset($nationalAlert) && $nationalAlert->send_to_all_countries)
                        checked @endif name="send_to_countries[]" value="all" class="hide send_to_countries
                        country_all">
                        <button type="button" data-value="all" data-target=".country_all" data-multiple="true"
                            class="@if(isset($nationalAlert) && $nationalAlert->send_to_all_countries ) active @endif country btn btn-outline btn-outline-info">All</button>
                        @foreach($countries as $key=>$country)
                        <input type="checkbox" @if(isset($nationalAlert) && $nationalAlert->hasCountry($country))
                        checked @endif name="send_to_countries[]" value="{{$country}}" class="hide send_to_countries
                        country_{{$key}}">
                        <button type="button" data-value="{{$country}}" data-target=".country_{{$key}}"
                            data-multiple="true"
                            class="@if(isset($nationalAlert) && $nationalAlert->hasCountry($country)) active @endif country btn btn-outline btn-outline-info">{{$country}}</button>
                        @endforeach
                        </select>
                    </div>
                    <div class="form-buttons shared_with_wrapper {{ $errors->has('send_to_designation') ? 'has-error' : '' }}"
                        @if(!isset($nationalAlert)) style="display:none;" @endif>
                        <label for="country" class="">Send to Designation</label> <br>
                        <input type="checkbox" @if(isset($nationalAlert) && $nationalAlert->send_to_all_designations)
                        checked @endif name="send_to_designation[]" value="all" class="hide send_to_designation
                        designation_all">
                        <button type="button" data-value="all" data-target=".designation_all" data-multiple="true"
                            class="@if(isset($nationalAlert) && $nationalAlert->send_to_all_designations) active @endif designation btn btn-outline btn-outline-info">All</button>
                        @foreach($designations as $key=>$designation)
                        <input type="checkbox" @if(isset($nationalAlert) &&
                            $nationalAlert->hasDesignation($designation->id)) checked @endif
                        name="send_to_designation[]" value="{{$designation->id}}" class="hide send_to_designation
                        designation_{{$key}}">
                        <button type="button" data-value="{{$designation->id}}" data-target=".designation_{{$key}}"
                            data-multiple="true"
                            class="@if(isset($nationalAlert) && $nationalAlert->hasDesignation($designation->id)) active @endif designation btn btn-outline btn-outline-info">{{$designation->name}}</button>
                        @endforeach
                    </div>
                    <div class="form-buttons shared_with_wrapper {{ $errors->has('send_to_head_offices_or_location') ? 'has-error' : '' }}"
                        @if(!isset($nationalAlert)) style="display:none;" @endif>
                        <input type="hidden" name="send_to_head_offices_or_location"
                            class="send_to_head_offices_or_location" value="locations">
                    </div>
                    <div class="form-buttons send_to_locations_wrapper" @if(!isset($nationalAlert))
                        style="display:none;" @endif>
                        <label for="Locations" class="">Branch/Pharmacies</label>
                        <select name="send_to_locations[]" class="form-control select2 send_to_locations" multiple>
                            <option value="all" @if(isset($nationalAlert) && $nationalAlert->send_to_all_locations)
                                selected @endif>All</option>
                            @foreach($locations as $loc)
                            <option value="{{$loc->location->id}}" @if(isset($nationalAlert) && $nationalAlert->
                                hasLocation($loc->location->id)) selected @endif >{{$loc->location->name()}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-buttons send_to_groups_buttons" @if(!isset($nationalAlert)) style="display:none"
                        @endif>
                        <label>Send to Tiers/Groups <small class="text-muted">(You can select multiple.)</small></label>
                        <!-- <select name="groups[]" multiple class="select2 form-control">
                <option value="all">All</option>
                @foreach($groups as $g)
                    <option value="{{$g->id}}">{{$g->parent_id}} - {{$g->group}}</option>
                @endforeach
            </select> -->
                        <br>
                        <input type="hidden" name="send_to_groups" class="send_to_groups" data-targets=".send_to_groups"
                            @if(isset($nationalAlert)) value="{{$nationalAlert->send_to_groups}}" @endif>
                        <button type="button" data-value="all" data-target=".send_to_groups"
                            class="@if(isset($nationalAlert) && $nationalAlert->send_to_groups == 'all') active @endif send_group_value btn btn-outline btn-outline-info">All</button>
                        <button type="button" data-value="specific" data-target=".send_to_groups"
                            class="@if(isset($nationalAlert) && $nationalAlert->send_to_groups == 'specific') active @endif send_group_value btn btn-outline btn-outline-info">Specific
                            Groups</button>
                        <div class="send_to_groups_wrapper" @if(!isset($nationalAlert) || $nationalAlert->send_to_groups
                            != 'specific') style="display:none" @endif>
                            <div class="card card-qi">
                                <div class="card-body">
                                    @if(isset($nationalAlert))
                                    <?php 
                        $parents = array();
                        foreach($nationalAlert->groups as $key=> $s){
                            $parents = array_merge($parents,App\Models\Headoffices\Organisation\Group::generateParentsArrayFromNode($s->group_id));
                        }
                        ?>
                                    @endif
                                    @include('head_office.my_organisation.tree-list',['groups' =>
                                    $groups,'multiple'=>true,'selected'=>isset($nationalAlert)?$nationalAlert->groups:null])
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-buttons schedule_wrapper" @if(!isset($nationalAlert)) style="display:none" @endif>
                        <label>Schedule for later time<small class="text-muted"> (Alert will only display after the
                                scheduled date/time is reached)</small></label><br>
                        <input type="hidden" name="schedule_later" class="schedule_later" data-targets=".schedule_value"
                            @if(isset($nationalAlert)) value="{{$nationalAlert->schedule_later}}" @endif>
                        <button type="button" data-value="no" data-target=".schedule_later"
                            class="@if(isset($nationalAlert) && $nationalAlert->schedule_later == 'no') active @endif schedule_value btn btn-outline btn-outline-info">No</button>
                        <button @if(request()->query('cloning') == 0 && isset($nationalAlert) &&
                            $nationalAlert->schedule_later =='no' && $nationalAlert->canEditScheduleDateTime() == false)
                            style="display:none" @endif type="button" data-value="yes" data-target=".schedule_later"
                            class="@if(isset($nationalAlert) && $nationalAlert->schedule_later == 'yes') active @endif
                            schedule_value btn btn-outline btn-outline-info">Yes</button>
                    </div>
                    <div class="schedule_date_time_wrapper" @if(!isset($nationalAlert) || $nationalAlert->schedule_later
                        != 'yes') style="display:none" @endif >
                        <div class="card card-qi">
                            <div class="card-body">
                                @if(request()->query('cloning') == 0 && isset($nationalAlert) &&
                                $nationalAlert->canEditScheduleDateTime() == false)
                                <small class="text-danger">You cannot change this option now. The alert is already
                                    dispatched.</small> @endif
                                <div class="form-group">
                                    <label for="schedule_date">Scheduled Date</label>
                                    <input type="date" @if(request()->query('cloning') == 0 && isset($nationalAlert) &&
                                    $nationalAlert->canEditScheduleDateTime() == false) readonly @endif
                                    name="schedule_date" class="datepicker schedule_date form-control"
                                    min="{{date('Y-m-d',strtotime('+1 day'))}}" @if(isset($nationalAlert) &&
                                    $nationalAlert->schedule_later == 'yes')
                                    value="{{date('Y-m-d',strtotime($nationalAlert->start_time))}}" @endif >
                                </div><br>
                                <div class="form-group">
                                    <label for="schedule_time">Scheduled Time</label>
                                    <input type="time" @if(request()->query('cloning') == 0 && isset($nationalAlert) &&
                                    $nationalAlert->canEditScheduleDateTime() == false) readonly @endif
                                    name="schedule_time" class="datepicker schedule_time form-control"
                                    @if(isset($nationalAlert) && $nationalAlert->schedule_later == 'yes')
                                    value="{{date('H:i',strtotime($nationalAlert->start_time))}}" @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="files files_wrapper card card-qi mt-5" @if(!isset($nationalAlert)) style="display:none;"
                        @endif>
                        <div class="card-body">
                            <label>Uploaded Files</label>
                            <ol class="uploaded_files">
                                @if(isset($nationalAlert))
                                @foreach($nationalAlert->documents as $doc)
                                <li>
                                    <input type='hidden' name='documents[]' class='file document'
                                        value='{{$doc->document->unique_id}}'>
                                    <span class="fa fa-file"></span>&nbsp;{{$doc->document->original_file_name()}}
                                    <a href="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}"
                                        target="_blank" title='Preview' class="preview_btn"> <span
                                            class="fa fa-eye"></span></a>
                                    <a href="#" title='Delete File' class="remove_btn"> <span
                                            class="fa fa-times"></span></a>

                                </li>
                                @endforeach
                                @endif
                            </ol>
                        </div>

                        <div class="upload_box center">
                            <i class="fa fa-cloud-upload-alt" style="font-size:48px"></i><br>Drop files here
                        </div>
                        <div class="custom-file {{ $errors->has('alert_documents') ? 'has-error' : '' }}">
                            <label for="formFileMultiple" class="custom-file-label">Select File</label>
                            <input class="custom-file-input" type="file" id="formFileMultiple" name="alert_documents[]"
                                multiple="multiple" />
                        </div>
                    </div>
                    <div class="form-group save_wrapper" @if(!isset($nationalAlert)) style="display:none;" @endif>
                        <div class="col-md-offset-2 col-md-10">
                            <button class="btn btn-info national_alert_submit" type="submit" value="Save"><i
                                    style="display:none" class="spinner fa-spin fa fa-spinner"></i> Save</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
</div> <!-- Card -->

@endsection

@section('styles')
<link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('scripts')
<script>
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var files = Array.from(this.files)
        var fileName = files.map(f =>{return f.name}).join(" , ")
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('/js/alertify.min.js')}}"></script>
@endsection