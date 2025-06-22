{{--@dd($requested_information)--}}
{{--@dd($case->link_case_with_form->form->stages)--}}

@extends('layouts.head_office_app')
@section('title', 'Case '.$case->id())

@section('sub-header')
    @include('head_office.case_manager.notes.sub-header')
@endsection
<div class="loader-container" ng-show="UI.loading" style="display:none">
    <div class="loader"></div>
</div>
@section('content')
    <div id="content">
        @include('user.modal_single_statement')
        @include('layouts.error')
        <div class="card card-qi content_widthout_sidebar">
            <div class="card-body">
                <div class="cm_content pt-2">

                    <form method="POST" action="{{route('case_manager.request_information_update', ['case_id' => $case->id, 'request_id' => $requested_information->id])}}">
                        @csrf
                        <h5 class="font-weight-bold">Update Request Information</h5>
                        <div class="row">

                            <div class="col-sm-12 show_statement_provider">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="text-info">
                                            <b>
                                                Why are you requesting information?
                                            </b>
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-12">

                                                <div class="case-intelligence-container">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-sm-6">

                                                                    <div class="form-group">
                                                                        <label>Type a general overview for this statement
                                                                            provider <i
                                                                                    title="Enter specific information you require."
                                                                                    class="fa fa-questin"></i></label>
                                                                        <textarea spellcheck="true"  name="note" id="note"
                                                                                  class="form-control">{{$requested_information->note}}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="default_texts">
                                                                        @foreach ($default_texts as $text)
                                                                            <div class="">
                                                                                <a class="btn btn-info" href="javascript:void(0)"
                                                                                   onclick="update_note(this)">
                                                                                    {{$text->value}}
                                                                                </a>
                                                                                <a href="javascript:void(0);" class="btn btn-warning"
                                                                                   data-toggle="modal"
                                                                                   data-target="#edit_request_{{$text->id}}">Edit</a>
                                                                                <a class="btn btn-danger delete_value" href="{{route('head_office.case.default_request_information_text.delete',['id'=>$text->id,'_token'=>csrf_token()])}}" data-msg="You want to delete this value?"
                                                                                >Delete</a>

                                                                            </div>

                                                                            @include('head_office.case_manager.edit_request_new_information',['defualt_request_information'
                                                                            => $text])
                                                                        @endforeach
                                                                    </div>
                                                                    <a href="javascript:void(0)" data-toggle="modal"
                                                                       data-target="#request_information" class="btn btn-info">
                                                                        Add New
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="form-inline">
                                                        <label>Make report available to above person?</label> &nbsp;
                                                        <input type="checkbox" name="is_available_to_person"
                                                            id="is_available_to_person" class="form-control">
                                                    </div> --}}
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <h5>Questions you'd like to ask</h5>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <h5>Save*</h5>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="custom" id="custom">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        @foreach($requested_question as $question)
                                                                            <div class="row field-container">
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group" style="display: flex; align-items: center;"><label>Question</label>
                                                                                        <span class="drag-handle">::</span>
                                                                                        <input type="text" name="questions[]" multiple="multiple"
                                                                                               class="form-control question" value="{{$question['question']}}" required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-6 save_question">
                                                                                    {{--                                                                    <div class="col-sm-6 update_question">--}}
                                                                                    {{--                                                                        @foreach ($default_texts as $text)--}}
                                                                                    {{--                                                                        <div class=""><a href="javascript:void(0)"--}}
                                                                                    {{--                                                                                onclick="update_question(this,'{{$text->value}}')"--}}
                                                                                    {{--                                                                                class="btn btn-info text_value">{{$text->value}}</a><br>--}}
                                                                                    {{--                                                                        </div>--}}
                                                                                    {{--                                                                        @endforeach--}}
                                                                                    <div class="form-group" style="display: flex; align-items: center;">
                                                                                        <span class="drag-handle">::</span>
                                                                                        <input type="text" name="save_questions[]" multiple="multiple"
                                                                                               class="form-control save-question">
                                                                                        <span class="delete-field"><i class="fa-regular fa-trash-can"></i></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">

                                                            <div class="form-group">
                                                                <a id="custom_button" class="custom_button btn btn-info">
                                                                    <i class="fa fa-plus"></i>
                                                                    Add Another Section
                                                                </a>
                                                                <a class="btn btn-info" id="next_report_section">
                                                                    Next
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-12 show_report_section" style="">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="text-info"></h5>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="case-intelligence-container">
                                                    <div class="form-inline">
                                                        <label style="font-size: 18px;font-weight: bold;">Make the form viewable to responder?</label> &nbsp;
                                                        <div class="button-group">
                                                            <input type="radio" id="yes" name="choice" value="yes" {{$requested_information->is_available_to_person == true ? 'checked' : ''}}>
                                                            <label for="yes" class="yes">Yes</label>

                                                            <input type="radio" id="no" name="choice" value="no" {{$requested_information->is_available_to_person == false ? 'checked' : ''}}>
                                                            <label for="no" class="no">No</label>
                                                        </div>
                                                        {{--                                                <input type="checkbox" name="is_available_to_person"--}}
                                                        {{--                                                    id="is_available_to_person"--}}
                                                        {{--                                                    class="is_available_to_person">--}}
                                                    </div>
                                                    {{--                                            <div class="form-inline">--}}
                                                    {{--                                                <b>Note : </b> &nbsp;--}}
                                                    {{--                                                <label for="">If yes you can redact information you don't want to make--}}
                                                    {{--                                                    visiable.</label>--}}
                                                    {{--                                            </div>--}}
                                                    <div class=" is_visiable_to_pserson_data form_wrap" style="display:{{$requested_information->is_available_to_person == true ? 'block' : 'none'}}">
                                                        @php
                                                            $record = $case->link_case_with_form;
                                                            $updated_records = $record->all_linked_records()->last();
                                                            if (isset($updated_records->raw_form)) {
                                                                $questionsJson = json_decode($updated_records->raw_form, true);
                                                            } else {
                                                                $questionsJson = json_decode($record->raw_form, true);
                                                            }
                                                            $data_objects = [];
                                                        @endphp
                                                    
                                                        @if (!empty($gdprs))
                                                            <div class="col-sm-12">
                                                                <div class="card">
                                                                    <div class="card-body d-flex gap-2">
                                                                        @foreach ($gdprs as $gdpr)
                                                                            <button type="button" data-selected="0"
                                                                                onclick="selectGdpr({{ $gdpr->id }}, event)"
                                                                                class="outline-btn">{{ $gdpr->tag_name }}</button>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        @if (!empty($case->link_case_with_form->raw_form))
                                                            @if ($questionsJson['pages'] && count($questionsJson['pages']) > 0)
                                                            @foreach ($questionsJson['pages'] as $page)
                                                            @php
                                                                $validItems = array_filter($page['items'], function($item) {
                                                                if ($item['input']['type'] == 'dmd') {
                                                                    $dmdValue = $item['input']['value'] ?? [];
                                                                    return !empty($dmdValue['vmp']['vp_string']) || !empty($dmdValue['vtm']['vtm_string']) || !empty($dmdValue['other']);
                                                                }
                                                    
                                                                return isset($item['input']['value']) && !empty($item['input']['value']);
                                                            });
                                                    
                                                            @endphp
                                                        
                                                            @if (count($validItems) > 0)
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <div class=" d-flex align-items-center gap-1">
                                                                            <input type="checkbox"
                                                                                data-page-id="{{ $page['id'] }}"
                                                                                data-page-count="{{ count($validItems) }}"
                                                                                class="parent-page">
                                                                            <p class="fw-bold m-0 p-0">{{ $page['name'] }}</p>
                                                                        </div>
                                                                    </div>
                                                        {{-- @dd($requested_information->question_ids); --}}
                                                                    <div class="m-2">
                                                                        @foreach ($page['items'] as $item)
                                                                            @if (isset($item['label'], $item['input'], $item['input']['type']) && !empty($item['input']['value']))
                                                                                <div class="card" data-gdpr-id="{{ isset($item['gdpr']) ? $item['gdpr'] : '' }}">
                                                                                    <div class="card-body">
                                                                                        <div class="d-flex flex-column gap-1">
                                                                                            <div class='d-flex align-items-center gap-1'>
                                                                                                <input type="checkbox"
                                                                                                @if (!empty($requested_information->question_ids) && in_array($item['id'], json_decode($requested_information->question_ids, true) ?? [])) checked @endif
                                                                                                class="child-page"
                                                                                                    name="question_ids[]"
                                                                                                    value="{{ $item['id'] }}"
                                                                                                    data-page-id="{{ $page['id'] }}"
                                                                                                    data-check-gdpr-id="{{ isset($item['gdpr']) ? $item['gdpr'] : '' }}">
                                                            
                                                                                                <p class="fw-bold m-0 p-0">{{ $item['label'] }}</p>
                                                                                            </div>
                                                        
                                                                                            <!-- Handling different input types based on item input type -->
                                                                                            @php
                                                                                                $value = isset($item['input']['value']) ? $item['input']['value'] : '';
                                                                                            @endphp
                                                                                            
                                                                                            @switch($item['input']['type'])
                                                                                                @case('text')
                                                                                                @case('email')
                                                                                                @case('textarea')
                                                                                                    <div class="d-flex align-items-center gap-1">
                                                                                                        <p class="m-0">Response: </p>
                                                                                                        <p class="fw-bold m-0 p-0">{{ $value }}</p>
                                                                                                    </div>
                                                                                                    @break
                                                        
                                                                                                @case('number')
                                                                                                    <div class="d-flex align-items-center gap-1">
                                                                                                        <p class="m-0">Response: </p>
                                                                                                        <p class="fw-bold m-0 p-0">{{ $value }}</p>
                                                                                                    </div>
                                                                                                    @break
                                                        
                                                                                                @case('date')
                                                                                                    <div class="d-flex align-items-center gap-1">
                                                                                                        <p class="m-0">Response: </p>
                                                                                                        <p class="fw-bold m-0 p-0">{{ \Carbon\Carbon::parse($value)->format('Y-m-d') }}</p>
                                                                                                    </div>
                                                                                                    @break
                                                        
                                                                                                @case('time')
                                                                                                <div class="d-flex align-items-center gap-1">
                                                                                                    <p class="m-0">Response: </p>
                                                                                                    <p class="fw-bold m-0 p-0">{{ \Carbon\Carbon::parse($value)->format('H:i') }}</p>
                                                                                                </div>
                                                                                                    @break
                                                        
                                                                                                @case('radio')
                                                                                                    @if (is_array($value))
                                                                                                    <div class="d-flex align-items-center gap-1">
                                                                                                        <p class="m-0">Response: </p>
                                                                                                        <p class="fw-bold m-0 p-0">{{ $value['val'] ?? $value['text'] ?? ' ' }}</p>
                                                                                                    </div>
                                                                                                    @else
                                                                                                    <div class="d-flex align-items-center gap-1">
                                                                                                        <p class="m-0">Response: </p>
                                                                                                        <p class="fw-bold m-0 p-0">{{ $value }}</p>
                                                                                                    </div>
                                                                                                    @endif
                                                                                                    @break
                                                        
                                                                                                @case('checkbox')
                                                                                                <div class="d-flex align-items-center gap-1">
                                                                                                    <p class="m-0">Response: </p>
                                                                                                    <p class="fw-bold m-0 p-0">{{ implode(', ', (array) $value) }}</p>
                                                                                                </div>
                                                                                                    @break
                                                        
                                                                                                @case('select')
                                                                                                    @if (isset($item['input']['value']) && is_array($item['input']['value']))
                                                                                                        @php
                                                                                                            $values = [];
                                                                                                            foreach ($item['input']['value'] as $select_value) {
                                                                                                                $values[] = is_array($select_value)
                                                                                                                    ? ($select_value['val'] ?? $select_value['text'] ?? '')
                                                                                                                    : $select_value;
                                                                                                            }
                                                                                                        @endphp
                                                                                                        <div class="d-flex align-items-center gap-1">
                                                                                                            <p class="m-0">Response: </p>
                                                                                                            <p class="fw-bold m-0 p-0">{{ implode(', ', $values) }}</p>
                                                                                                        </div>
                                                                                                    @else
                                                                                                    <div class="d-flex align-items-center gap-1">
                                                                                                        <p class="m-0">Response: </p>
                                                                                                        <p class="fw-bold m-0 p-0">{{ $value }}</p>
                                                                                                    </div>
                                                                                                    @endif
                                                                                                    @break
                                                        
                                                                                                @case('dmd')
                                                                                                    @php
                                                                                                        $records = $item['input']['records'] ?? [];
                                                                                                        $dmd_values = [];
                                                                                                        foreach ($records as $record2) {
                                                                                                            $vtm = $record2['vtm']['vtm_string'] ?? '';
                                                                                                            $vmp = $record2['vmp']['vp_string'] ?? '';
                                                                                                            $other = $record2['other'] ?? '';
                                                                                                            $dmd_values[] = implode(', ', array_filter([$vtm, $vmp, $other]));
                                                                                                        }
                                                                                                    @endphp
                                                                                                    @if (!empty($dmd_values))
                                                                                                    <div class="d-flex align-items-center gap-1">
                                                                                                        <p class="m-0">Response: </p>
                                                                                                        <p class="fw-bold m-0 p-0">{{ implode('; ', $dmd_values) }}</p>
                                                                                                    </div>
                                                                                                    @endif
                                                                                                    @break
                                                                                                @default
                                                                                                <div class="d-flex align-items-center gap-1">
                                                                                                    <p class="m-0">Response: </p>
                                                                                                    <p class="fw-bold m-0 p-0">{{ is_array($value) ? implode(',', $value) : $value }}</p>
                                                                                                </div>
                                                                                            @endswitch
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                        
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <button class="btn btn-info">
                                                            Submit
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                            Preview Form
                                                        </button>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <input type="hidden" value="{{route('head_office.case.default_request_information_text')}}" id="create_text_route">
        <div id="update_form_data"></div>
    </div>
    @php
        $val = [];
        foreach ($default_texts as $key => $value) {
        $val[] = $value->value;
        }
        $val = json_encode($val);
    @endphp
    <input type="hidden" value="{{$val}}" id="default_texts">
    <input type="hidden" value="{{route('search_user')}}" id="route">
    @include('head_office.case_manager.edit_request_new_information',['defualt_request_information' => null])
@endsection

@section('styles')

    <link rel="stylesheet" href="{{asset('tribute/tribute.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

@endsection

@section('scripts')

    <script src="{{asset('admin_assets/js/view_case.js')}}"></script>
    <script src="{{asset('tribute/tribute.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    {{--<script src="{{asset('admin_assets/speech-to-text.js')}}"></script>--}}

    <script>
        $(document).ready(function(){
            $(".edit_custom_button").on('click',function(){
                if($('#request_div').find('.form-group'))
                    var len = 1 + parseInt($('#request_div').find('.form-group').length);
                else
                    var len = 1;

                $("#request_div").append('<div class="form-group"><label>Question '+ len +'</label><input type="text" name="questions[]" multiple="multiple" class="form-control question" required></div>');
            });
        })
        function update_note(value)
        {
            $('#note').val($.trim($(value).text()));
        }

        function update_question(elem,value)
        {
            $(elem).parent().parent().parent().find('input').val(value);
        }

        $(".delete" ).on( "click", function(e) {
            e.preventDefault();
            let href= $(this).attr('href');
            alertify.defaults.glossary.title = 'Alert!';
            alertify.confirm("Are you sure?", "Are you sure to delete this? ",
                function(){
                    window.location.href= href;
                },function(i){
                });
        });
        function search_user()
        {
            let registration,mobile,first_name,last_name, email, _location;
            registration = $('#registration').val();
            mobile = $('#mobile').val();
            first_name = $('#first_name').val();
            last_name = $('#last_name').val();
            email = $('#email').val();
            _location = $('#location').val();

            $(".users").empty();
            $('.loader-container').css('display', 'grid');

            if(!$.trim(registration) && !$.trim(mobile) && !$.trim(first_name) && !$.trim(last_name) && !$.trim(email) && !$.trim(_location))
            {
                $('#registration').focus();
                return;
            }
            var route = $('#route').val();
            var data = {
                'first_name' : first_name,
                'last_name' : last_name,
                'mobile' : mobile,
                'registration' : registration,
                'email' : email,
                'location' : _location,
                '_token' : "{{ csrf_token() }}"
            }
            $.post(route,data).
            then(function(response){

                if(response.result)
                {
                    $('.is_manually').hide();
                    $(".users").empty();
                    $('.loader-container').css('display', 'none');
                    let text = "";
                    response.users.forEach(element => {
                        let registration_no = '';
                        if(element.registration_no !== null){
                            registration_no = '[ '+ element.registration_no  +' ]';
                        }
                        text += '<input type="radio" name="user_id" id="user_'+element.id+'" value="'+ element.id +'"> <label for="user_'+element.id+'"> ' + element.name + ' ('+ element.position + ') '+ registration_no + '</label><br>'

                    });
                    $('.users').append(text);
                    $('.user_found').show();
                    $('#manual_first_name').prop('required',false);
                    $('#manual_last_name').prop('required',false);
                    $('#manual_email').prop('required',false);
                    $('#manual_confirm_email').prop('required',false);
                }
                else
                {
                    $(".search_user").hide();
                    $(".users").empty();
                    $('.users').append('No user found');
                    $('.is_manually').show();
                    $('#manual_first_name').prop('required',true);
                    $('#manual_last_name').prop('required',true);
                    $('#manual_email').prop('required',true);
                    $('#manual_confirm_email').prop('required',true);
                }

            }).
            catch(function(error){
                console.log(error);
            });
        }
        $('.search_again').on('click',function(){
            $('#manual_first_name').prop('required',true);
            $('#manual_last_name').prop('required',true);
            $('#manual_email').prop('required',true);
            $(".is_manually").hide();
            $(".search_user").show();
        });
        function next_section(val)
        {
            if(!val)
            {
                $(".show_statement_provider").show();
            }
            else{
                if(!$.trim($("#manual_first_name").val()))
                    $("#manual_first_name").focus();
                else if(!$.trim($("#manual_last_name").val()))
                    $("#manual_last_name").focus();
                else if(!$.trim($("#manual_email").val()))
                    $("#manual_email").focus();
                else if(!$.trim($("#manual_confirm_email").val()))
                    $("#manual_confirm_email").focus();
                else if($.trim($("#manual_confirm_email").val()) !== $.trim($("#manual_email").val()))
                {
                    alert('Email and confirm email does not match');
                    $("#manual_confirm_email").focus();
                }
                else
                    $(".show_statement_provider").show();
            }

        }

        $('#next_report_section').on('click',function(){
            $(".show_report_section").show();
            $(".show_class").show();
        });
        function is_checked_manual(val)
        {
            if($(val).is(':checked'))
            {
                $(".is_manually").show();

                $('#manual_first_name').prop('required',true);
                $('#manual_last_name').prop('required',true);
                $('#manual_email').prop('required',true);
            }
            else
            {
                $(".is_manually").hide();

                $('#manual_first_name').prop('required',false);
                $('#manual_last_name').prop('required',false);
                $('#manual_email').prop('required',false);
            }
        };
        $(document).ready(function(){
            $("#custom_button").on('click',function(){
                if($('#custom').find('.form-group'))
                    var len = 1 + parseInt($('#custom').find('.form-group').length);
                else
                    var len = 1;
                var default_texts = JSON.parse($("#default_texts").val());
                var text = "";
                default_texts.forEach(element => {
                    text += '<div class=""><a href="javascript:void(0)" onclick="update_question(this,'+"'"+element+"'"+')" class="btn btn-info text_value">'+element+'</a><br></div>'
                });
                // $("#custom").append('<div class="card"><div class="card-body"><div class="row"><div class="col-sm-6"><div class="form-group"><label>Question </label><input type="text" name="questions[]" multiple="multiple" class="form-control" required></div></div><div class="col-sm-6 update_question">'+text+'</div></div></div></div>');
                $("#custom .card .card-body").append(`<div class="row field-container"><div class="col-sm-6"><div class="form-group" style="display: flex;align-items: center;"><label>Question </label>
            <span class="drag-handle">::</span><input type="text" name="questions[]" multiple="multiple" class="form-control question" required>
            </div></div>
            <div class="col-sm-6 update_question">
            <div class="form-group" style="display: flex; align-items: center;">
                <span class="drag-handle">::</span>
                <input type="text" name="save_questions[]" multiple="multiple" class="form-control save-question">
                <span class="delete-field"><i class="fa-regular fa-trash-can"></i></span>
            </div>
            </div></div>`);
            });
        });

        $(document).on('click', '.delete-field', function() {
            $(this).closest('.field-container').remove();
        });

        $('#custom .card .card-body').sortable({
            handle: '.drag-handle',
            // containment: 'parent'
        });

        $('.button-group input[type="radio"]').on('change',function(){
            if($('#yes').is(':checked'))
            {
                $('.is_visiable_to_pserson_data').show();
                $('.form_wrap input[type="checkbox"]').closest('.card').css('opacity', '0.6');
            }
            else
            {

                $('.is_visiable_to_pserson_data').hide();
            }

        })
        $('.form_wrap input[type="checkbox"]').on('change', function() {
            if(this.checked) {
                $(this).closest('.card').css('opacity', '1');
            }else{
                $(this).closest('.card').css('opacity', '0.6');
            }
        })


        $('.stage_name').on('change',function(){

            var checkboxes = $(this).parent().parent().find($('input[type=checkbox]'));
            if($(this).is(':checked'))
            {
                checkboxes.each(function() {
                    $(this).prop('checked',true);
                });
            }
            else
            {
                checkboxes.each(function() {
                    $(this).prop('checked',false);
                });
            }

        })

        $('.group_name').on('change',function(){

            var checkboxes = $(this).parent().parent().parent().find($('input[type=checkbox]'));
            if($(this).is(':checked'))
            {
                checkboxes.each(function() {
                    $(this).prop('checked',true);
                });
            }
            else
            {
                checkboxes.each(function() {
                    $(this).prop('checked',false);
                });
            }

        })
        function save_information_text(route,item)
        {
            var text = $(item).parent().parent().parent().parent().find('.form-control').val();
            if(text)
            {
                var data = {
                    '_token' : "{{csrf_token()}}",
                    'value' : text
                }
                $.post(route,data)
                    .then(function(response){
                        if(response.result)
                        {
                            $(item).parent().parent().parent().parent().parent().modal('hide');;
                            $(".default_texts").empty();
                            $(".update_question").empty();
                            $("#update_form_data").empty();
                            var values = [];

                            response.values.forEach(element => {
                                values.push(element.value);
                                var route = '{{ route("head_office.case.default_request_information_text.delete") }}' + '/' + element.id + '?_token={{ csrf_token() }}';
                                var val = "'"+element.value+"'";
                                $(".default_texts").append('<div class=""><a class="btn btn-info" href="javascript:void(0)" onclick="update_note(this,'+val+')">'+element.value+'</a>&nbsp<a href="javascript:void(0);" class="btn btn-warning" data-toggle="modal" data-target="#edit_request_'+element.id+'">Edit</a>&nbsp<a class="btn btn-danger delete_value" data-msg="You want to delete this value?" href="'+route+'">Delete</a></div>')
                                $(".update_question").append('<div class=""><a class="btn btn-info" href="javascript:void(0)" onclick="update_question(this,'+val+')">'+element.value+'</a></div>')
                                var create_route_text = $('#create_text_route').val();
                                var route ="'"+create_route_text+"/"+element.id+"'";
                                text = '<form><div class="modal fade file_upload_model" id="edit_request_'+element.id+'" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog modal-xl" role="document"><div class="modal-content"><div class="modal-header text-center"><h4 class="modal-title text-info w-100"><p class="text-success"><i class="fa fa-tasks fa-2x"></i></p>Edit Default Text</h4><button type="button" class="close float-right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div><div class="modal-body"><div class="form-group"><label>Text</label><input type="text" name="value" value="'+element.value+'" class="form-control" required></div></div><div class="modal-footer"><div class="btn-group right"><button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();" data-dismiss="modal">Cancel</button><a href="javascript:void(0);" onclick="save_information_text('+route+',this)" class="btn btn-info">Save</a></div></div></div></div></div></form>';
                                $("#update_form_data").append(text);
                            });
                            $(".modal-backdrop").remove();
                            $('#default_texts').val(JSON.stringify(values));
                            alert('Updated successfully');
                        }
                        else
                        {
                            alert(response.msg);
                        }
                    })
                    .catch(function(error)
                    {
                        console.log(error);
                    })
            }
        }

        $(document).ready(function() {
            $('.form_wrap input[type="checkbox"]').each(function() {
            if(this.checked) {
                $(this).closest('.card').css('opacity', '1');
            }else{
                $(this).closest('.card').css('opacity', '0.6');
            }
        })
        })


        $(document).on( "click", ".delete_value", function(e) {
            e.preventDefault();
            let href= $(this).attr('href');
            let msg = $(this).data('msg');
            alertify.defaults.glossary.title = 'Alert!';
            alertify.confirm("Are you sure?", msg,
                function(){
                    var data = "{{csrf_token()}}"
                    $.get(href,data)
                        .then(function(response){
                            if(response.result)
                            {
                                $(".default_texts").empty();
                                $(".update_question").empty();
                                $("#update_form_data").empty();
                                var values = [];

                                response.values.forEach(element => {
                                    values.push(element.value);
                                    var route = '{{ route("head_office.case.default_request_information_text.delete") }}' + '/' + element.id + '?_token={{ csrf_token() }}';
                                    var val = "'"+element.value+"'";
                                    $(".default_texts").append('<div class=""><a class="btn btn-info" href="javascript:void(0)" onclick="update_note(this,'+val+')">'+element.value+'</a>&nbsp<a href="javascript:void(0);" class="btn btn-warning" data-toggle="modal" data-target="#edit_request_'+element.id+'">Edit</a>&nbsp<a class="btn btn-danger delete_value" data-msg="You want to delete this value?" href="'+route+'">Delete</a></div>')
                                    $(".update_question").append('<div class=""><a class="btn btn-info" href="javascript:void(0)" onclick="update_question(this,'+val+')">'+element.value+'</a></div>')
                                    var create_route_text = $('#create_text_route').val();
                                    var route ="'"+create_route_text+"/"+element.id+"'";
                                    text = '<form><div class="modal fade file_upload_model" id="edit_request_'+element.id+'" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog modal-xl" role="document"><div class="modal-content"><div class="modal-header text-center"><h4 class="modal-title text-info w-100"><p class="text-success"><i class="fa fa-tasks fa-2x"></i></p>Edit Default Text</h4><button type="button" class="close float-right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div><div class="modal-body"><div class="form-group"><label>Text</label><input type="text" name="value" value="'+element.value+'" class="form-control" required></div></div><div class="modal-footer"><div class="btn-group right"><button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();" data-dismiss="modal">Cancel</button><a href="javascript:void(0);" onclick="save_information_text('+route+',this)" class="btn btn-info">Save</a></div></div></div></div></div></form>';
                                    $("#update_form_data").append(text);
                                });

                                $('#default_texts').val(JSON.stringify(values));
                            }
                        })
                        .catch(function(response){
                            console.log(response);
                        })
                },function(i){
                    console.log(i);
                });
        });

        /**
         * @By HS
         * Apply radio button on search user
         * @Date 2024-07-03
         */
        $(function() {

            function enableSelectedInput() {
                // $('input[type="text"].user_input').prop('disabled', true);
                let checkedOption = $('input[name="option"]:checked').val();
                if (checkedOption) {
                    $('.input_' + checkedOption).prop('disabled', false);
                }
            }

            enableSelectedInput();
            let radioOption = $('input[name="option"]');
            radioOption.on('change', function() {
                enableSelectedInput();
            });

            radioOption.on('click', function() {
                if ($(this).is(':checked')) {
                    let selectedOption = $(this).val();
                    $('.user_input').prop('disabled', true).val('');
                    $('input[name="option"]').prop('checked', false);
                    $('.input_' + selectedOption).prop('disabled', false);
                    $(this).prop('checked', true);
                }
            });

            /**
             * @By HS
             * Last location search dropdown
             * @Date 2024-07-03
             */


            $.ajax({
                url: '/head_office/case/manager/request_information_save/{case_id}',
                method: 'GET',
                success: (response) => {
                    let dropdown = $('#location');
                    $.each(response, function(index, value) {
                        dropdown.append('<option value="' + value.id + '">' + value.location_code +' - '+ value.trading_name + ' '+value.address_line1+'</option>');
                    });
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });

            $('#location').select2();

        });

    </script>
@endsection