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

            <form method="POST" action="{{route('case_manager.request_information_save',$case->id)}}" enctype="multipart/form-data">
                @csrf
                <h5 class="font-weight-bold">Request Information</h5>
                <div class="row">
                    {{-- <div class="col-sm-4">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>From</th>
                                    <th>Days Since</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($case->case_request_informations as $case_request_information)
                                <tr>
                                    <td>{{$case_request_information->created_at->format(config('app.dateFormat'))}}</td>

                                    <td>{{$case_request_information->case->case_head_office->company_name}}</td>
                                    <td>{{$case_request_information->created_at->diff(\Carbon\Carbon::now())->days}}
                                    </td>
                                    <td>@if ($case_request_information->status)
                                        Submitted
                                        @else
                                        Waiting
                                        @endif</td>
                                    <td>
                                        @if($case_request_information->status)
                                        <a class="btn btn-warning" data-toggle="modal"
                                            data-target="#edit_request_information_{{$case_request_information->id}}"><i
                                                class="fa fa-wrench"></i></a>
                                        <a class="btn btn-danger delete"
                                            href="{{route('head_office.statement.single_statement_delete',[$case->id,$case_request_information->id])}}"><i
                                                class="fa fa-trash"></i></a>
                                        @endif
                                        <a class="btn btn-info" target="_blank"
                                            href="{{route('head_office.statement.single_statement',[$case->id,$case_request_information->id])}}"><i
                                                class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                                @include('head_office.case_manager.edit_case_request_information',['case_request_information'
                                => $case_request_information])
                                @endforeach
                            </tbody>
                        </table>
                    </div> --}}
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-4 search_user">
                                <div class="card">


                                    <div class="card-body intelligence-container">
                                        <h5>
                                            <b>
                                                Who do you need information from?
                                            </b>
                                        </h5>
                                        <!-- <p>Add New Comment</p> -->
                                        <b>
                                            <label class="text-info" for="">Search User</label>
                                        </b>
                                        <div class="form-group">
                                            <div class="label-wrap">
                                                <input type="radio" name="option" value="1">
                                                <label>By Registration Number</label>
                                            </div>
                                            <input type="text" name="registration" id="registration" value="" class="form-control input_1 user_input" disabled>
                                        </div>

                                        <div class="form-group">
                                            <div class="label-wrap">
                                                <input type="radio" name="option" value="2">
                                                <label>By Mobile Number</label>
                                            </div>
                                            <input type="text" name="mobile" id="mobile" value="" class="form-control input_2 user_input" disabled>
                                        </div>

                                        <div class="form-group">
                                            <div class="label-wrap">
                                                <input type="radio" name="option" value="3">
                                                <label>By Email</label>
                                            </div>
                                            <input type="text" name="email" id="email" value="" class="form-control input_3 user_input" disabled>
                                        </div>
                                        <div class="form-group">
                                            <div class="label-wrap">
                                                <input type="radio" name="option" value="4">
                                                <label>By Last Location Login</label>
                                            </div>
                                            <select id="location" class="input_4 user_input" name="location" style="width:100%" disabled>
                                                <option value="">Select Location</option>
                                            </select>
{{--                                            <input type="text" name="location" id="location" value="" class="form-control input_4 user_input" disabled>--}}
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-info search-btn" onclick="search_user()" disabled>Search</button>
                                        </div>

                                        <br>

                                        {{-- <div class="form-inline">
                                            <label>Enter details manually</label>
                                            <input type="checkbox" name="manual" onchange="is_checked_manual(this)"
                                                class="form-control">
                                        </div> --}}

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 is_manually" style="display: none;">

                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="text-info">
                                            <b>
                                                We are sorry, this user can't be found. Enter the details of person
                                                below to request information from them.
                                            </b>
                                        </h5>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-inline">
                                                    {{-- <div class="form-group">
                                                        <label>By First Name</label>
                                                        <input type="text" name="manual_first_name"
                                                            id="manual_first_name" value="" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>By Last Name</label>
                                                        <input type="text" name="manual_last_name" id="manual_last_name"
                                                            value="" class="form-control">
                                                    </div> --}}
                                                </div>
                                                <div class="form-group">
                                                    <label>Email Address</label>
                                                    <input type="text" name="manual_email" id="manual_email" value=""
                                                        class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Retype Email Address</label>
                                                    <input type="text" name="manual_confirm_email"
                                                        id="manual_confirm_email" value="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <a href="javascript:void(0)" class="btn btn-info search_again">Back to Search
                                            Again</a>
                                        <a href="javascript:void(0)" onclick="next_section(1)" class="btn btn-info next_section">Next</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="card user_found" style="display: none">
                                    <div class="card-header">
                                        Results
                                    </div>
                                    <div class="card-body">
                                        <div class="users">
                                            
                                        </div>
                                        <br><a class="btn btn-info next_section" onclick="next_section(0)" href="javascript:void(0)" >Next</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 show_statement_provider" style="display: none;">
                        <div class="card">
                            <div class="card-body d-flex">
                                <div class="left-content w-50">
                                    <h5 class="text-info"><b>Reason for Requesting</b></h5>
                                    <div class="form-group">
                                        <label>
                                            This will be displayed to the respondent
                                            <i title="Enter specific information you require." class="fa fa-question-circle"></i>
                                        </label>
                                        <textarea spellcheck="true" name="note" id="note" class="form-control"></textarea>
                                    </div>
                                    <p class="fw-bold">Attach files to share</p>
                                    <div class="cm_upload_box_with_model center">
                                        <i class="fa fa-cloud-upload-alt" style="font-size:48px"></i><br>Drop files here
                                    </div>
                                    <input type="file" name="attachment" class="form-control mt-2">
                                </div>
                        
                                <!-- Right Content -->
                                <div class="right-content w-50">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 show_statement_provider" style="display: none;">
                            <div class="card">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="custom" id="custom">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <h5>Questions you'd like to ask</h5>
                                                            <div class="fields-wrap" style="max-height: 200px; overflow-y: auto;">
{{--                                                                            <div class="row">--}}
                                                                    <div class="field-container">
                                                                        <div class="form-group" style="display: flex; align-items: center;"><label>Question</label>
                                                                            <span class="drag-handle">::</span>
                                                                            <input type="text" name="questions[]" multiple="multiple" class="form-control question" data-id="1" required>
                                                                            <span class="delete-field"><i class="fa-regular fa-trash-can"></i></span>
                                                                        </div>
                                                                    </div>
{{--                                                                                <div class="col-sm-6 save_question field-container">--}}
                                                                        {{--                                                                    <div class="col-sm-6 update_question">--}}
                                                                        {{--                                                                        @foreach ($default_texts as $text)--}}
                                                                        {{--                                                                        <div class=""><a href="javascript:void(0)"--}}
                                                                        {{--                                                                                onclick="update_question(this,'{{$text->value}}')"--}}
                                                                        {{--                                                                                class="btn btn-info text_value">{{$text->value}}</a><br>--}}
                                                                        {{--                                                                        </div>--}}
                                                                        {{--                                                                        @endforeach--}}
{{--                                                                                </div>--}}
{{--                                                                            </div>--}}
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <a id="custom_button" class="custom_button btn btn-info">
                                                                        <i class="fa fa-plus"></i>
                                                                        Add Another
                                                                    </a>
                                                                    <a class="btn btn-info" id="next_report_section">
                                                                        Next
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6" style="position: relative"s>
                                                            <div class="heading-wrap">
                                                                <p class="m-0 p-0 fw-bold" id="saved-reasons"><i class="fa-solid fa-star px-2"
                                                                        style="color:#f7d16c"></i>No saved reasons</p>
                                                                <button type="button"
                                                                    class="btn btn-info add-question d-flex justify-content-center align-items-center"
                                                                    data-bs-toggle="modal" data-bs-target="#saveModal"><i
                                                                        class="fa fa-plus" aria-hidden="true"></i></button>
                                                            </div>

                                                            <script>
                                                                $('.save-text-btn').on('click', function() {
                                                                            const text = $('.save-question').val();
                                                                            if (text) {
                                                                            $('#saved-reasons').fadeOut(function() {
                                                                                $(this).html('<p class="m-0 p-0 fw-bold"><i class="fa-solid fa-star px-2"style="color:#f7d16c"></i>Saved</p>').fadeIn();
                                                                            });
                                                                        
                                                                            if (currentTile) {
                                                                                // Update existing tile text
                                                                                currentTile.find('.text-tile').text(text);
                                                                                currentTile.find('.hidden-text-tile').val(text);
                                                                                currentTile = null; // Reset currentTile after updating
                                                                            } else {
                                                                                // Create a new tile
                                                                                const tile = $('<div class="text-wrap"></div>');
                                                                                const textTile = $('<button class="text-tile" type="button"></button>').text(text);
                                                                                const hiddenTextField = $(
                                                                                    `<input type="hidden" class="hidden-text-tile" name="s_questions[]" value="${text}">`);
                                                                                const deleteBtn = $(
                                                                                    '<span class="delete-sfield"><i class="fa-regular fa-trash-can"></i></span>');
                                                                                const editBtn = $('<span class="edit-sfield"><i class="fa fa-edit"></i></span>');
                                                                            
                                                                                tile.append(textTile, hiddenTextField, deleteBtn, editBtn);
                                                                                $('.questions-list').append(tile);
                                                                            }
                                                                        
                                                                            // Clear input and hide modal
                                                                            $('.save-question').val('');
                                                                            $('#saveModal').modal('hide');
                                                                        } else {
                                                                            alertify.alert("Alert!", "Please add question.", function() {});
                                                                        }
                                                                        });
                                                                    
                                                                    
                                                                    $(document).on('click', '.delete-sfield', function() {
                                                                        $(this).closest('.text-wrap').remove();
                                                                    
                                                                        if ($('.questions-list .text-wrap').length === 0) {
                                                                            $('#saved-reasons').fadeOut(function() {
                                                                                $(this).html('<p class="m-0 p-0 fw-bold"><i class="fa-solid fa-star px-2" style="color:#f7d16c"></i>No saved reasons</p>').fadeIn();
                                                                            });
                                                                        }
                                                                    });
                                                            </script>

                                                            <div class="save-fields-wrap" style="max-height: 200px; overflow-y: auto;">
                                                                <div class="save_question field-container">
                                                                    <div class="questions-list">
                                                                        @foreach($saved_questions as $sq)
                                                                            <div class="text-wrap"><button class="text-tile" type="button">{{$sq->saved_question}}</button><input type="hidden" class="hidden-text-tile" name="s_questions[]" value="{{$sq->saved_question}}"><span class="delete-sfield"><i class="fa-regular fa-trash-can" aria-hidden="true"></i></span><span class="edit-sfield"><i class="fa fa-edit" aria-hidden="true"></i></span></div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-sm-6" style="padding-left:10px; padding-bottom:10px;">
                                                    <div class="uploaded_files mt-2 mb-2">
                                                        @if (isset($share_case))
                                                            @foreach ($share_case->documents as $doc)
                                                                <li>
                                                                    <input type='hidden' name='documents[]'
                                                                        class='file document'
                                                                        value='{{ $doc->document->unique_id }}'>
                                                                    <span
                                                                        class="fa fa-file"></span>&nbsp;{{ $doc->document->original_file_name() }}
                                                                    <a href="{{ route('headoffice.view.attachment', $doc->document->unique_id) . $doc->document->extension() }}"
                                                                        target="_blank" title='Preview'
                                                                        class="preview_btn">
                                                                        <span class="fa fa-eye"></span></a>
                                                                    <a href="#" title='Delete File'
                                                                        class="remove_btn"> <span
                                                                            class="fa fa-times"></span></a>
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                    </div>                                

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <div class="row">
                                    <div class="col-md-12">


                                        <div class="case-intelligence-container">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-6">

                                                           
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
                                                            {{-- <a href="javascript:void(0)" data-toggle="modal"
                                                                data-target="#request_information" class="btn btn-info">
                                                                Add New
                                                            </a> --}}
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
                                            </div>
                                            
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-12 show_report_section" style="display: none;">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="text-info">
{{--                                    <b>--}}
{{--                                        Make the report viewable?--}}
{{--                                    </b>--}}
                                </h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="case-intelligence-container">
                                            <div class="form-inline">
                                                <label style="font-size: 18px;font-weight: bold;">Make the form viewable to responder?</label> &nbsp;
                                                <div class="button-group">
                                                    <input type="radio" id="yes" name="choice" value="yes">
                                                    <label for="yes" class="yes">Yes</label>

                                                    <input type="radio" id="no" name="choice" value="no">
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
<div class=" is_visiable_to_pserson_data form_wrap" style="display:none">
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
    
                <div class="m-2">
                    @foreach ($page['items'] as $item)
                        @if (isset($item['label'], $item['input'], $item['input']['type']) && !empty($item['input']['value']))
                            <div class="card" data-gdpr-id="{{ isset($item['gdpr']) ? $item['gdpr'] : '' }}">
                                <div class="card-body">
                                    <div class="d-flex flex-column gap-1">
                                        <div class='d-flex align-items-center gap-1'>
                                            <input type="checkbox"
                                            @if (!empty($share_case->question_ids) && in_array($item['id'], json_decode($share_case->question_ids, true) ?? [])) checked @endif
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
                                                    Form Preview
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
    });

    // // Drag and drop functionality
    // $('.draggable').on('dragstart', function(e) {
    //     e.originalEvent.dataTransfer.setData('text', $(this).text());
    // });
    //
    // $('.question').on('dragover', function(e) {
    //     e.preventDefault();
    // });
    //
    // $('.question').on('drop', function(e) {
    //     e.preventDefault();
    //     const text = e.originalEvent.dataTransfer.getData('text');
    //     $(this).val(text).prop('disabled', false);
    // });

    // Enable the input field when a corresponding draggable item is clicked
    // $('.draggable').on('click', function() {
    //     const id = $(this).data('id');
    //     $(`.question[data-id="${id}"]`).prop('disabled', false).focus();
    // });
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
        $(".user_found").hide();


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
                $('.show_statement_provider').css('display', 'none');
                $(".user_found").hide();
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
                $('.loader-container').css('display', 'none');
                $('.show_statement_provider').css('display', 'none');
                $(".user_found").hide();
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
            const lastInput = $('#custom').find('.question').last();
            if (lastInput.val() === '') {
                alertify
                    .alert('Alert!',"Please fill the existing question first.", function(){});
            } else {
                if($('#custom .fields-wrap').find('.question'))
                    var len = 1 + parseInt($('#custom').find('.question').length);
                else
                    var len = 1;
                var default_texts = JSON.parse($("#default_texts").val());
                var text = "";
                default_texts.forEach(element => {
                    text += '<div class=""><a href="javascript:void(0)" onclick="update_question(this,'+"'"+element+"'"+')" class="btn btn-info text_value">'+element+'</a><br></div>'
                });
                // $("#custom").append('<div class="card"><div class="card-body"><div class="row"><div class="col-sm-6"><div class="form-group"><label>Question </label><input type="text" name="questions[]" multiple="multiple" class="form-control" required></div></div><div class="col-sm-6 update_question">'+text+'</div></div></div></div>');
                $("#custom .card .card-body .fields-wrap").append(`<div class="field-container"><div class=""><div class="form-group" style="display: flex;align-items: center;"><label>Question </label>
                <span class="drag-handle">::</span><input type="text" name="questions[]" multiple="multiple" class="form-control question" data-id="" required><span class="delete-field"><i class="fa-regular fa-trash-can"></i></span>
                </div></div>
                </div>`);
            }

        });

        //Save Questions
        $("#save_custom_button").on('click',function(){

            $("#custom .card .card-body .save-fields-wrap")
                .append(`<div class="save_question field-container">
                            <div class="form-group" style="display: flex; align-items: center;">
                                <span class="drag-handle">::</span>
                                <input type="text" name="save_questions[]" multiple="multiple" class="form-control save-question">
                                <span class="delete-field"><i class="fa-regular fa-trash-can"></i></span>
                            </div>
                          </div>`);

        });

    });

    $(document).on('click', '.delete-field', function() {
        $(this).closest('.field-container').remove();
    });


    $('#custom .card .card-body .fields-wrap').sortable({
        handle: '.drag-handle',
        // containment: 'parent'
    });
    $('#custom .card .card-body .save-fields-wrap').sortable({
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
        // Search Lock
        function enableSelectedInput() {
            const checkedOption = $('input[name="option"]:checked').val();
            if (checkedOption) {
                const checkInput = $('.input_' + checkedOption);
                const locationSelector = $('#location');
                const searchBtn = $('.search-btn');

                checkInput.prop('disabled', false);
                searchBtn.prop('disabled', true);

                function toggleSearchButton() {
                    let isInputEmpty = checkInput.val() === '';
                    searchBtn.prop('disabled', isInputEmpty );
                }

                locationSelector.on('change', toggleSearchButton);
                checkInput.on('keyup', toggleSearchButton);
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