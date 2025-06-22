
@extends('layouts.head_office_app')
@section('title', 'Case ' . $case->id())

@section('sub-header')
    @include('head_office.case_manager.notes.sub-header')
@endsection

@section('content')
    <div id="content">
        @include('layouts.error')
        <div class="content-page-heading">
            Share Case
        </div>
        <div class="">
            <div class="">
                <div class="">
                    <div class="row">
                        <div class="col-sm-12">
                            <form
                                @if (isset($share_case)) action="{{ route('head_office.case.share_case', [$case->id, $share_case->id]) }}"

                    @else
                    action="{{ route('head_office.case.share_case', $case->id) }}" @endif
                                method="post" class="cm_task_form">
                                @csrf
                                <div class="card-body intelligence-container">


                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <p class="m-0 p-0 fw-bold">Who do you want to share this case
                                                                with?</p>
                                                            <div
                                                                class="organisation-structure-add-content hide-placeholder-parent form-group">
                                                                <label class="" style="font-size: 14px;">Email Address
                                                                </label>
                                                                @if (isset($share_case))
                                                                    <input type="text" readonly
                                                                        name="share_case_emails[]" class="form-control p-2"
                                                                        value="{{ $share_case->email }}">
                                                                @else
                                                                    <select name="share_case_emails[]" multiple
                                                                        id="share_case_emails"
                                                                        class="form-contorl select_2_custom"
                                                                        style="width: 100%">
                                                                        @foreach ($case->link_case_with_form->form->shared_case_approved_emails as $email)
                                                                            <option value="{{ $email->email }}">
                                                                                {{ $email->email }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                @endif

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>







                                        <div class="modal" id="saveModal" tabindex="-1" aria-labelledby="saveModalLabel"
                                            aria-modal="true" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-3" id="saveModalLabel"
                                                            style="text-align: center;width: 100%;">Create a saved reason
                                                        </h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group" style="display: flex; align-items: center;">
                                                            <input type="text" name="save_questions"
                                                                class="form-control save-question" value="">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary save-text-btn">Create</button>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal" aria-label="Close">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>







                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p class="m-0 p-0 fw-bold">Reason for sharing?</p>
                                                    <div class="row">


                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label style="font-size: 14px;">This will be dispalyed to the person</label>
                                                                <textarea spellcheck="true"  class="form-control p-2" name="note" rows="7" id="note"> @if (isset($share_case))
                                                                    {{ $share_case->note }}
                                                                    @endif </textarea>

                                                            </div>
                                                        </div>


                                                        <div class="col-sm-6 py-4" style="position: relative">
                                                            <div class="heading-wrap">
                                                                <p class="m-0 p-0 fw-bold" id="saved-reasons"><i class="fa-solid fa-star px-2"
                                                                        style="color:#f7d16c"></i>No saved reasons</p>
                                                                <button type="button"
                                                                    class="btn btn-info add-question d-flex justify-content-center align-items-center"
                                                                    data-bs-toggle="modal" data-bs-target="#saveModal"><i
                                                                        class="fa fa-plus" aria-hidden="true"></i></button>
                                                            </div>

                                                            <div class="save-fields-wrap ui-sortable">
                                                                <div class="save_question field-container">
                                                                    <div class="questions-list">
                                                                        @if (isset($all_qustions) && count($all_qustions) > 0)
                                                                            @foreach ($all_qustions as $question)
                                                                                <div class="text-wrap"><button
                                                                                        class="text-tile"
                                                                                        type="button">{{ $question->description }}</button><input
                                                                                        type="hidden"
                                                                                        class="hidden-text-tile"
                                                                                        name="s_questions[]"
                                                                                        value="{{ $question->description }}"><span
                                                                                        class="delete-sfield"><i
                                                                                            class="fa-regular fa-trash-can"
                                                                                            aria-hidden="true"></i></span><span
                                                                                        class="edit-sfield"><i
                                                                                            class="fa fa-edit"
                                                                                            aria-hidden="true"></i></span>
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>





                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-6">
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
                                                                <p class="fw-bold">Attach files to share</p>
                                                                <div class="cm_upload_box_with_model center">
                                                                    <i class="fa fa-cloud-upload-alt"
                                                                        style="font-size:48px"></i><br>Drop files here
                                                                </div>
                                                                <input type="file" name="file" multiple
                                                                    value=""
                                                                    class="form-control commentMultipleFiles">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="m-0 p-0 fw-bold">Share Settings</p>
                                                        <div class="row">
                                                            <div class="col-md-5">



                                                                @if (!isset($share_case))
                                                                    <div
                                                                        class="row dead_line_date d-flex align-items-center">
                                                                        <p class="col-sm-2 mt-4">Share for</p>
                                                                        <div class="col-sm-2">

                                                                            <input type="number"
                                                                                name="duration_of_access_number"
                                                                                value="1" min="1"
                                                                                class="form-control duration_of_access_number">

                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <select
                                                                                class="form-control dead_line_units duration_of_access_type"
                                                                                name="duration_of_access_type"
                                                                                id="duration_of_access_type">
                                                                                <option value="days">Days
                                                                                </option>
                                                                                <option value="months">Months
                                                                                </option>
                                                                                <option value="years">Years
                                                                                </option>
                                                                            </select>

                                                                        </div>
                                                                    </div>
                                                                    <span class="date col-sm-4"
                                                                        style="font-size: 14px;padding-left:110px;"></span>
                                                                @endif



                                                                <div class="case-intelligence-container">
                                                                    <div class="form-inline">
                                                                        <label>Allow Two-Way Communication?</label> &nbsp;
                                                                        <div class="button-group">
                                                                            <input type="radio" id="yes"
                                                                                name="is_allow_two_way" value="1"
                                                                                @if (isset($share_case) && $share_case->is_allow_two_way) checked @endif>
                                                                            {{-- @dd($share_case->is_allow_two_way) --}}
                                                                            <label for="yes"
                                                                                class="yes">Yes</label>

                                                                            <input type="radio" id="no"
                                                                                name="is_allow_two_way" value="0"
                                                                                @if (isset($share_case) && $share_case->is_allow_two_way == false) checked @endif>
                                                                            <label for="no"
                                                                                class="no">No</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-inline">
                                                                        <label>Make Case log viewable?</label> &nbsp;
                                                                        <div class="button-group">
                                                                            <input type="radio" id="yeslog"
                                                                                name="is_log_viewable" value="1"
                                                                                @if (isset($share_case) && $share_case->is_log_viewable) checked @endif>
                                                                            {{-- @dd($share_case->is_allow_two_way) --}}
                                                                            <label for="yeslog"
                                                                                class="yes">Yes</label>

                                                                            <input type="radio" id="nolog"
                                                                                name="is_log_viewable" value="0"
                                                                                @if (isset($share_case) && $share_case->is_log_viewable == false) checked @endif>
                                                                            <label for="nolog"
                                                                                class="no">No</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                <div class="case-intelligence-container">

                                                                    
                                                                    <div class="form-inline">
                                                                        <label class="fw-bold">Make form viewable</label> &nbsp;
                                                                        <div class="button-group">
                                                                            <input type="radio" id="yes2"
                                                                                name="is_viewable"
                                                                                @if (isset($share_case) && $share_case->is_viewable) checked @endif>
                                                                            <label for="yes2"
                                                                                class="yes">Yes</label>

                                                                            <input type="radio" id="no2"
                                                                                @if (isset($share_case) && $share_case->is_viewable == false) checked @endif
                                                                                name="is_viewable" value="0">
                                                                            <label for="no2"
                                                                                class="no">No</label>
                                                                        </div>
                                                                    </div>



                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>


                                            </div>


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


                                            <div class="col-sm-12">
                                                <div>
                                                    <div class="intelligence-container">
                                                        @foreach ($case->link_case_with_form->form->stages as $key => $stage)
                                                            <div
                                                                class="card stages stage_{{ $stage->id }} stage_data_{{ $key + 1 }}">

                                                                <div class="card-body">
                                                                    <h5>
                                                                        <input type="checkbox"
                                                                            name="stage_name_{{ $stage->id }}"
                                                                            class="stage_name">
                                                                        &nbsp;{{ $stage->stage_name }}
                                                                        <button
                                                                            class="btn btn-info dropdown-toggle inline-block"
                                                                            type="button" id="dropdownMenuButton"
                                                                            data-toggle="dropdown" aria-haspopup="true"
                                                                            aria-expanded="false">Quick Actions</button>
                                                                        <div class="dropdown-menu animated--fade-in"
                                                                            aria-labelledby="dropdownMenuButton">
                                                                            <a href="javascript:void(0)"
                                                                                class="dropdown-item hide-sensitive-data"
                                                                                title="Share Case">Hide Sensitive Data</a>
                                                                        </div>
                                                                    </h5>
                                                                    @foreach ($stage->groups as $group)
                                                                        <div class="card group group_{{ $group->id }}">
                                                                            <div class="card-header">
                                                                                <h5 class="form-group-name"><input
                                                                                        type="checkbox"
                                                                                        name="group_name_{{ $group->id }}"
                                                                                        class="group_name">
                                                                                    &nbsp;{{ $group->group_name }}

                                                                                </h5>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <div class="row">
                                                                                    @foreach ($group->questions as $question)
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-contorl">
                                                                                                @if ($case->link_case_with_form->data->where('question_id', $question->id)->first())
                                                                                                    @php $value = $case->link_case_with_form->data
                                                                                                    ->where(
                                                                                                        'question_id',
                                                                                                        $question->id,
                                                                                                    )
                                                                                                    ->first();
                                                                                                    @endphp 
                                                                                                    
                                                                                                    <div
                                                                                                        class="answer_{{ $value->id }}">
                                                                                                        <input
                                                                                                            type="checkbox"
                                                                                                            @if (isset($share_case) &&
                                                                                                                    $share_case->share_case_data_radact()->where('data_id', $value->id)->first()) checked @endif
                                                                                                            name="answer_{{ $value->id }}"
                                                                                                            class="group_name @if ($question->form_card_id) form_card_field @endif">

                                                                                                        <label
                                                                                                            for="question_{{ $question->id }}">{{ $question->question_title }}</label>
                                                                                                        :
                                                                                                        {{ $value->question_value }}
                                                                                                    </div>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                        {{-- @foreach ($case->link_case_with_form->data as $data)
                                            @if ($data->question)
                                            <p>
                                                <span class="detail-title"> {{$data->question->question_name}}:
                                                </span>
                                                {{$data->question_value}}
                                            </p>
                                            <br>
                                            @endif
                                            @endforeach --}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="btn-group">

                                                    <button type="submit" class="btn btn-info">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="   " id="share_case" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header text-center">
                                        <h4 class="modal-title text-info w-100">
                                            <p class="text-success"><i class="fa fa-tasks fa-2x"></i></p>Share Case
                                        </h4>
                                        <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Select emails</label>
                                            <select name="share_case_emails[]" multiple id="share_case_emails" class="form-contorl select_2_custom" style="width: 100%">
                                                @foreach ($case->link_case_with_form->form->shared_case_approved_emails as $email)
                                                <option value="{{$email->email}}">{{$email->email}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Duration of access</label>
                                            <input type="date" name="duration_of_access" value="{{date('Y-m-d')}}" min="{{date('Y-m-d')}}" class="form-control" required>
                                        </div>
                                        <label>Allow end user to</label>
                                        <div class="form-group">
                                            <label>View incident report</label>
                                            <input type="checkbox" class="" name="is_viewable">
                                        </div>
                                        <div class="form-group">
                                            <label>Redact staff involved in report</label>
                                            <input type="checkbox" class="" name="is_radact">
                                        </div>
                                        <div class="uploaded_files mt-2 mb-2">
                                            @if (isset($task))
                                            @foreach ($task->documents as $doc)
                                            <li>
                                                <input type='hidden' name='documents[]' class='file document'
                                                    value='{{$doc->document->unique_id}}'>
                                                <span class="fa fa-file"></span>&nbsp;{{$doc->document->original_file_name()}}
                                                <a href="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}"
                                                    target="_blank" title='Preview' class="preview_btn"> <span class="fa fa-eye"></span></a>
                                                <a href="#" title='Delete File' class="remove_btn"> <span class="fa fa-times"></span></a>
                                            </li>
                                            @endforeach
                                            @endif 
                                        </div>
                                        <h6 class="text-info">Select documents/images to upload</h6>
                                        <div class="cm_upload_box_with_model center">
                                            <i class="fa fa-cloud-upload-alt" style="font-size:48px"></i><br>Drop files here
                                        </div>
                                        <input type="file" name="file" multiple value="" class="form-control commentMultipleFiles">
                                    </div>
                                    <div class="modal-footer">
                                        <div class="btn-group right">
                                            <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();"
                                                data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-info">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@section('styles')

    <link rel="stylesheet" href="{{ asset('tribute/tribute.css') }}">
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}

@endsection

@section('scripts')
    <script src="{{ asset('admin_assets/js/view_case.js') }}"></script>
    <script src="{{ asset('admin_assets/js/form-template.js') }}"></script>
    <script>
        //document.onclick = hideMenu; 
        window.addEventListener('DOMContentLoaded', (event) => {
            $(document).ready(function() {
                $('.select_2_custom').select2({
                    tags: true,
                    createTag: function(params) {
                        var term = $.trim(params.term);

                        if (validateEmail(term)) {
                            return {
                                id: term,
                                text: term,
                                newTag: true // add additional parameters
                            };
                        }

                        return null;
                    },
                    insertTag: function(data, tag) {
                        // Insert the tag only if it is valid
                        if (tag.newTag) {
                            data.push(tag);
                        }
                    }
                });
            });
        });

        

        function validateEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        if ($('.is_available_to_person').is(':checked')) {
            $('.is_visiable_to_pserson_data').show();
        }

        $('.is_available_to_person').on('change', function() {
            if ($('.is_available_to_person').is(':checked')) {
                $('.is_visiable_to_pserson_data').show();
            } else {

                $('.is_visiable_to_pserson_data').hide();
            }

        })
        $('.stage_name').on('change', function() {

            var checkboxes = $(this).parent().parent().find($('input[type=checkbox]'));
            if ($(this).is(':checked')) {
                checkboxes.each(function() {
                    $(this).prop('checked', true);
                });
            } else {
                checkboxes.each(function() {
                    $(this).prop('checked', false);
                });
            }

        })
        $('.group_name').on('change', function() {

            var checkboxes = $(this).parent().parent().parent().find($('input[type=checkbox]'));
            if ($(this).is(':checked')) {
                checkboxes.each(function() {
                    $(this).prop('checked', true);
                });
            } else {
                checkboxes.each(function() {
                    $(this).prop('checked', false);
                });
            }

        })
        $(".hide-sensitive-data").on('click', function() {
            var checkboxes = $(this).parent().parent().parent().parent().find('.form_card_field');

            checkboxes.each(function() {
                console.log(this);
                $(this).prop('checked', false);
            });
        })
        $(".duration_of_access_number, .duration_of_access_type").on('change', function() {
            var value = $(".duration_of_access_number").val();
            var duration_of_access_type = $(".duration_of_access_type").val();
            var now = new Date();
            value = parseInt(value);

            var newDate;
            if (duration_of_access_type == 'days') {
                newDate = new Date(now.setDate(now.getDate() + value));
            } else if (duration_of_access_type == 'weeks') {
                newDate = new Date(now.setDate(now.getDate() + value * 7));
            } else if (duration_of_access_type == 'months') {
                newDate = new Date(now.setMonth(now.getMonth() + value));
            } else if (duration_of_access_type == 'years') {
                newDate = new Date(now.setFullYear(now.getFullYear() + value));
            }

            // Format the date
            var day = newDate.getDate().toString().padStart(2, '0');
            var month = (newDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-based
            var year = newDate.getFullYear();
            var hours = newDate.getHours();
            var minutes = newDate.getMinutes().toString().padStart(2, '0');
            var seconds = newDate.getSeconds().toString().padStart(2, '0');

            // Convert to 12-hour format
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            hours = hours.toString().padStart(2, '0');

            var formattedDate = '<span class="fw-bold">' + day + '/' + month + '/' + year + '</span>' + ' at ' +
                hours + ':' + minutes + ' ' + ampm;

            $(".date").html('Share access will be revoked on ' + formattedDate);
        });

        // $(".duration_of_access_type").on('change',function(){
        //     var value = $(".duration_of_access_number").val();
        //     var duration_of_access_type = $(".duration_of_access_type").val();
        //     var now = Date();
        //     var date1 = new Date(now);
        //     var dateNow = new Date(now);
        //     value = parseInt(value);
        //     if(duration_of_access_type == 'days')
        //     {
        //         var newDate = new Date(date1.setDate(date1.getDate()+value));
        //     }
        //     else if(duration_of_access_type == 'weeks')
        //     {
        //         var newDate = new Date(date1.setDate(date1.getDate() + value * 7));
        //     }
        //     else if(duration_of_access_type == 'months')
        //     {
        //         var newDate = new Date(date1.setMonth(date1.getMonth()+value));
        //     }
        //     else if(duration_of_access_type == 'years')
        //     {
        //         var newDate = new Date(date1.setFullYear(date1.getFullYear()+value));
        //     }
        //     $(".date").text('case access will be revoked after '+ DaysBetween(dateNow,newDate) + ' days ' +newDate);
        //
        // })
        function DaysBetween(StartDate, EndDate) {
            // The number of milliseconds in all UTC days (no DST)
            const oneDay = 1000 * 60 * 60 * 24;

            // A day in UTC always lasts 24 hours (unlike in other time formats)
            const start = Date.UTC(EndDate.getFullYear(), EndDate.getMonth(), EndDate.getDate());
            const end = Date.UTC(StartDate.getFullYear(), StartDate.getMonth(), StartDate.getDate());
            console.log((start - end) / oneDay)
            // so it's safe to divide by 24 hours
            return (start - end) / oneDay;
        }

        let currentTile = null; // Variable to store the tile being edited

        // Clear input and reset currentTile when the modal is shown
        $('#saveModal').on('show.bs.modal', function(event) {
            if (!currentTile) {
                $('.save-question').val('');
            }
        });

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

        // Event delegation for delete button
        $(document).on('click', '.delete-sfield', function() {
            $(this).closest('.text-wrap').remove();
        });

        $(document).on('click', 'button.text-tile', function() {
            const tileText = $(this).text();
            $("#note").val(tileText);
        });

        $('.parent-page').on('change', function() {
            if ($(this).is(':checked')) {
                $parentPageId = $(this).data('page-id');
                $('.child-page[data-page-id="' + $parentPageId + '"]').prop('checked', true)
            } else {
                $parentPageId = $(this).data('page-id');
                $('.child-page[data-page-id="' + $parentPageId + '"]').prop('checked', false);
            }
        })
        $('.child-page').on('change', function() {
            let $parentPageId = $(this).data('page-id');
            let $parentCheckbox = $('.parent-page[data-page-id="' + $parentPageId + '"]');
            let $childCheckboxes = $('.child-page[data-page-id="' + $parentPageId + '"]');
            let $checkedChildCheckboxes = $childCheckboxes.filter(':checked');
            if ($checkedChildCheckboxes.length === $childCheckboxes.length) {
                $parentCheckbox.prop('checked', true);
            } else {
                $parentCheckbox.prop('checked', false)
            }
        });


        $(document).ready(function() {
            $('.parent-page').each(function() {
                let $parentPageId = $(this).data('page-id');
                let $childCheckboxes = $('.child-page[data-page-id="' + $parentPageId + '"]');
                let $checkedChildCheckboxes = $childCheckboxes.filter(':checked');

                if ($checkedChildCheckboxes.length === $childCheckboxes.length) {
                    $(this).prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                }
            });

            function toggleVisibility() {
                if ($('#yes2').is(':checked')) {
                    $('.is_visiable_to_pserson_data').show();
                    $('.form_wrap input[type="checkbox"]').closest('.card').css('opacity', '0.6');
                } else if ($('#no2').is(':checked')) {
                    $('.is_visiable_to_pserson_data').hide();
                }
            }

            toggleVisibility();

            $('input[name="is_viewable"]').on('change', function() {
                toggleVisibility();
            });


        });

        $('.form_wrap input[type="checkbox"]').on('change', function() {
            if(this.checked) {
                $(this).closest('.card').css('opacity', '1');
            }else{
                $(this).closest('.card').css('opacity', '0.6');
            }
        })

        const selectGdpr = (id, event) => {
            const button = $(event.currentTarget);
            const selected = button.data('selected');
            if (selected === 0) {
                button.removeClass('outline-btn').addClass('primary-btn');
                button.data('selected', 1);
                $(`[data-gdpr-id="${id}"]`).css('opacity', '60%');
                $(`[data-check-gdpr-id="${id}"]`).prop('checked', false);
            } else {
                button.removeClass('primary-btn').addClass('outline-btn');
                button.data('selected', 0);
                $(`[data-gdpr-id="${id}"]`).css('opacity', '100%');
            }
        };
    </script>
@endsection
@endsection
