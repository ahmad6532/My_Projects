@extends('layouts.users_app')
@section('title', 'user shared cases')
@section('content')

<div class="bac_button" style="text-align: left; position: absolute; left: 25px; top: 10px;">
    <button class="header-back-btn"> <a href="{{route('user.shared_cases')}}"  class="link-dark fw-semibold">
        <i class="fa-solid fa-arrow-left"></i> Back</button>
</div>
    <div class="container-fluid">

        <div class="row justify-content-center ">
            <div class="col-m">
                <div class="card" style="3400px">
                    <div class="card-body">

                        <div class="row">
                            <div class="col" style="display: flex; justify-content: space-between; width: 100%; hight:2000px">
                                <!-- Navigation Tabs on the Left -->
                                <nav class="nav nav-tabs" id="myTab" role="tablist" style="flex-grow: 1; padding-top: 35px;padding-bottom: 35px;">
                                    <a onclick="changeTabUrl('details')" id="details" href="#" class="nav-item active" data-bs-toggle="tab" data-bs-target="#case_details" 
                                    style="color: gray; text-decoration: none; font-size: 16px; font-weight: bold; position: relative; padding-bottom: 5px; border-bottom: 2px solid transparent;" 
                                    onmouseover="this.style.color='#74c4bc'; this.style.borderBottom='2px solid #74c4bc';" 
                                    onmouseout="this.style.color='gray'; this.style.borderBottom='2px solid transparent';">
                                        <span class="item_with_border">Details</span>
                                    </a>
                                
                                    @if ($shared_case->is_allow_two_way)
                                    <a onclick="changeTabUrl('commmunication-tab')" id="commmunication-tab" href="#" class="nav-item" data-bs-toggle="tab" data-bs-target="#communication_tab" 
                                       style="color: gray; text-decoration: none; font-size: 16px; font-weight: bold; position: relative; padding-bottom: 5px; border-bottom: 2px solid transparent;" 
                                       onmouseover="this.style.color='#74c4bc'; this.style.borderBottom='2px solid #74c4bc';" 
                                       onmouseout="this.style.color='gray'; this.style.borderBottom='2px solid transparent';">
                                        <span class="item_with_border">Communication</span>
                                    </a>
                                    @endif
                                
                                    <a onclick="changeTabUrl('activity-tab')" id="activity-tab" href="#" class="nav-item" data-bs-toggle="tab" data-bs-target="#shared_case_log" 
                                       style="color: gray; text-decoration: none; font-size: 16px; font-weight: bold; position: relative; padding-bottom: 5px; border-bottom: 2px solid transparent;" 
                                       onmouseover="this.style.color='#74c4bc'; this.style.borderBottom='2px solid #74c4bc';" 
                                       onmouseout="this.style.color='gray'; this.style.borderBottom='2px solid transparent';">
                                        <span class="item_with_border">Activity</span>
                                    </a>
                                
                                    @if ($shared_case->is_log_viewable)
                                    <a onclick="changeTabUrl('case-log-tab')" id="case-log-tab" href="#" class="nav-item" data-bs-toggle="tab" data-bs-target="#shared_case_log_read_only" 
                                       style="color: gray; text-decoration: none; font-size: 16ppx; font-weight: bold; position: relative; padding-bottom: 5px; border-bottom: 2px solid transparent;" 
                                       onmouseover="this.style.color='#74c4bc'; this.style.borderBottom='2px solid #74c4bc';" 
                                       onmouseout="this.style.color='gray'; this.style.borderBottom='2px solid transparent';">
                                        <span class="item_with_border">Case Log</span>
                                    </a>
                                    @endif
                                
                                    @if ($shared_case->is_viewable)
                                    <a onclick="changeTabUrl('report-tab')" id="report-tab" href="#" class="nav-item" data-bs-toggle="tab" data-bs-target="#shared_case_report" 
                                       style="color: gray; text-decoration: none; font-size: 16ppx; font-weight: bold; position: relative; padding-bottom: 5px; border-bottom: 2px solid transparent;" 
                                       onmouseover="this.style.color='#74c4bc'; this.style.borderBottom='2px solid #74c4bc';" 
                                       onmouseout="this.style.color='gray'; this.style.borderBottom='2px solid transparent';">
                                        <span class="item_with_border">View Report</span>
                                    </a>
                                    @endif
                                </nav>
                                
                                
                                
                                
                        
                                
                                <div class="s" style="height: 35px">
                                    <div class="" id="cm_case_overview" style="text-align: center;">

                                        <div class="d-flex flex-column align-items-center p-4">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <p style="font-weight: bold; font-size: 16px;">Expires within</p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <div class="text-center">
                                                    <p class="p-0 m-0" style="font-size: 12px">Days</p>
                                                    <p class="p-0 m-0" id="days" style="font-size: 24px"></p>
                                                </div>
                                                <div class="text-center">
                                                    <p class="p-0 m-0" style="font-size: 12px">Hours</p>
                                                    <p class="p-0 m-0" id="hours" style="font-size: 24px"></p>
                                                </div>
                                                <div class="text-center">
                                                    <p class="p-0 m-0" style="font-size: 12px">Minutes</p>
                                                    <p class="p-0 m-0" id="minutes" style="font-size: 24px"></p>
                                                </div>
                                                <div class="text-center">
                                                    <p class="p-0 m-0" style="font-size: 12px">Seconds</p>
                                                    <p class="p-0 m-0" id="seconds" style="font-size: 24px"></p>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                @php
                                                    use Carbon\Carbon;
                                                @endphp
                                                @if ($shared_case->removed_by_user)
                                                    <span class="badge badge-secondary badge-user">Cancelled</span>
                                                @elseif ($shared_case->is_revoked || $shared_case->duration_of_access < Carbon::now())
                                                    @if ($shared_case->share_case_extension->where('status', 0)->last())
                                                        <span data-bs-toggle="tooltip" title="Extension Requested" class="badge badge-info badge-user">Extension Requested</span>
                                                    @elseif ($shared_case->share_case_extension->where('status', 2)->last() &&
                                                             $shared_case->share_case_extension->where('status', 2)->last()->status == $shared_case->share_case_extension->last()->status)
                                                        <a data-bs-toggle="modal" data-bs-target="#request_access_{{ $shared_case->id }}"
                                                           href="{{ route('user.share_case', $shared_case->id) }}" class="badge badge-danger badge-user">
                                                           Extension Rejected, Requested Again</a>
                                                    @else
                                                        <a data-bs-toggle="modal" data-bs-target="#request_access_{{ $shared_case->id }}"
                                                           href="{{ route('user.share_case', $shared_case->id) }}" class="outline-btn btn-sm">Extend</a>
                                                    @endif
                                                    @include('user.request_access', ['share' => $shared_case])
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                        
                        
</div>

<style>
    /* Disable text selection */
body {
    user-select: none; /* Standard property */
    -webkit-user-select: none; /* Chrome, Safari */
    -moz-user-select: none; /* Firefox */
    -ms-user-select: none; /* IE/Edge */
}

/* Disable right-click */
body {
    -webkit-touch-callout: none; /* Disable touch context menu on iOS */
}

    .nav {
        
        display: block;
        padding-top: 80px;
        margin: 0; 
    }
    .nav-item {
        
        margin-right: 25px;
        text-decoration: none;
        color: black;
        padding: 10px 15px;
        display: inline-block;
    }
</style>

<script>
    document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
}, false);
document.addEventListener('fullscreenchange', (event) => {
    if (document.fullscreenElement) {
        alert('Fullscreen mode activated. Screenshots are discouraged.');
    }
});

// Detect F12/DevTools shortcut key presses
document.onkeydown = function(e) {
    if (e.keyCode == 123) { // F12
        e.preventDefault();
        return false;
    }
    if (e.ctrlKey && e.shiftKey && e.keyCode == 73) { // Ctrl+Shift+I
        e.preventDefault();
        return false;
    }
};

    const navItems = document.querySelectorAll('.nav-item');

    navItems.forEach(item => {
        item.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default anchor behavior
            navItems.forEach(i => i.classList.remove('active')); // Remove active class from all
            this.classList.add('active'); // Add active class to the clicked item
        });
    });
</script>

                            <div class="col-12">
                                <div class="tab-content" id="myTabContent">
                                    <div id="case_details"
                                        class="cm_case_tasks scrollbar_custom_green relative tab-pane active">
                                        <div class="col-sm-12 normal-hide">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="cm_case_overview" id="cm_case_overview">
                                                        <img style="width: 100px;"
                                                            src="{{ $shared_case->case->case_head_office->getLogoAttribute() }}"
                                                            alt="headoffice logo">
                                                        <p class="py-4 m-0">Case Shared by
                                                            {{ $shared_case->case->case_head_office->company_name }} on
                                                            {{ $shared_case->case->created_at->format('D d/m/Y \a\t h:ia') }}
                                                        </p>
                                                        <p class="py-2 m-0">{{ $shared_case->note }}</p>
                                                        {{-- @dd($shared_case->documents) --}}
                                                        <div class="uploaded_files mt-2 mb-2">
                                                            @if (isset($shared_case))
                                                                @foreach ($shared_case->documents as $doc)
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
                                                                        
                                                                    </li>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        {{-- <div>Head Office: <b>
                                                                {{ $shared_case->case->case_head_office->company_name }} at
                                                            </b></div>
                                                        <div>
                                                            Case ID: <b> {{ $shared_case->case->id }}</b>
                                                        </div>
                                                        <div>
                                                            Date of incident: <b> {{ $shared_case->case->created_at }}</b>
                                                        </div>

                                                        

                                                        <div>
                                                            Date of incident: <b>{{ $shared_case->case->created_at->format('D d/m/Y \a\t h:ia') }}</b>
                                                        </div>
                                                        

                                                        <div>
                                                            Shared By: <b> {{ $shared_case->sharedBy->first_name }}
                                                                {{ $shared_case->sharedBy->surname }}</b>
                                                        </div> --}}
                                                    </div>
                                                    <input type="hidden" name="" id="duration_of_access"
                                                        value="{{ $shared_case->duration_of_access }}">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class=" is_visiable_to_pserson_data">
                                                                <div class="intelligence-container">
                                                                    @foreach ($shared_case->case->link_case_with_form->form->stages as $key => $stage)
                                                                        <div
                                                                            class="card stages stage_{{ $stage->id }} stage_data_{{ $key + 1 }}">

                                                                            <div class="card-body">
                                                                                <h5>
                                                                                    {{ $stage->stage_name }}
                                                                                </h5>
                                                                                @foreach ($stage->groups as $gk => $group)
                                                                                    <div
                                                                                        class="card group group_{{ $group->id }} g_show_{{ $key }}_{{ $gk }}">
                                                                                        <div class="card-header">
                                                                                            <h5 class="form-group-name">
                                                                                                &nbsp;{{ $group->group_name }}

                                                                                            </h5>
                                                                                        </div>
                                                                                        <div class="card-body">
                                                                                            <div class="row">
                                                                                                @foreach ($group->questions as $question)
                                                                                                    <div class="col-md-6">
                                                                                                        <div
                                                                                                            class="form-contorl">
                                                                                                            @if ($shared_case->case->link_case_with_form->data->where('question_id', $question->id)->first())
                                                                                                                @php$value = $shared_case->case->link_case_with_form->data
                                                                                                                        ->where(
                                                                                                                            'question_id',
                                                                                                                            $question->id,
                                                                                                                        )
                                                                                                                        ->first();
                                                                                                        @endphp ?>
                                                                                                                @if (isset($shared_case) &&
                                                                                                                        $shared_case->share_case_data_radact()->where('data_id', $value->id)->first())
                                                                                                                    <div
                                                                                                                        class="answer_{{ $value->id }}">
                                                                                                                        <label
                                                                                                                            class="has-group-{{ $key }}-{{ $gk }}"
                                                                                                                            for="question_{{ $question->id }}">{{ $question->question_title }}</label>
                                                                                                                        :
                                                                                                                        {{ $value->question_value }}
                                                                                                                    </div>
                                                                                                                @endif
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
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="shared_case_log"
                                        class="cm_case_tasks scrollbar_custom_green relative tab-pane ">
                                        <div class="col-sm-12 normal-hide">
                                            <div class="card">
                                                <div class="card-body">
                                                    @foreach ($shared_case->logs()->orderBy('id', 'desc')->get() as $key => $log)
                                                        <div data-comment="{{ $log->id }}"
                                                            class="cm_comment card @if ($key % 2 == 0) cm_comment_grey @endif">
                                                            <div class="cm_comment_author_date">
                                                                {{ $log->log }}
                                                                {{ $log->created_at->diffForHumans() }}
                                                                <span
                                                                    class="float-right">{{ $log->created_at->format(config('app.dateFormat')) }}
                                                                    {{ $log->created_at->format(config('app.timeFormat')) }}</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>    
                                    @php
                                    $questionsJson = [
                                        'pages' => [
                                            [
                                                'items' => [
                                                    [
                                                        'label' => 'Question 1',
                                                        'input' => [
                                                            'type' => 'text',
                                                            'value' => 'Sample answer',
                                                        ],
                                                        'id' => 1,
                                                    ],
                                                    [
                                                        'label' => 'Question 2',
                                                        'input' => [
                                                            'type' => 'email',
                                                            'value' => 'example@example.com',
                                                        ],
                                                        'id' => 2,
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ];
                                    @endphp
                                    <div id="shared_case_report"
                                        class="cm_case_tasks scrollbar_custom_green relative tab-pane ">
                                        <div class="col-sm-12 normal-hide">
                                            @php
                                            $record = $shared_case->case->link_case_with_form;
                                            $updated_records = $record->all_linked_records()->last();
                                            if (isset($updated_records->raw_form)) {
                                                $questionsJson = json_decode($updated_records->raw_form, true);
                                            } else {
                                                $questionsJson = json_decode($record->raw_form, true);
                                            }
                                            $data_objects = [];
                                            if ($record->raw_form) {
                                                $questionIds = json_decode($shared_case->question_ids, true);
                                                if (is_array($questionIds)) {
                                                    foreach ($questionsJson['pages'] as $page) {
                                                        if (isset($page['items']) && count($page['items']) > 0) {
                                                            foreach ($page['items'] as $item) {
                                                                if (
                                                                    isset(
                                                                        $item['label'],
                                                                        $item['input'],
                                                                        $item['input']['type'],
                                                                        $item['input']['value']
                                                                    ) && in_array($item['id'], $questionIds)
                                                                ) {
                                                                    $type = $item['input']['type'];
                                                                    $value = $item['input']['value'];
        
                                                                    switch ($type) {
                                                                        case 'text':
                                                                        case 'email':
                                                                        case 'phone':
                                                                        case 'textarea':
                                                                            $data_objects[] = [
                                                                                'label' => $item['label'],
                                                                                'value' => $value,
                                                                            ];
                                                                            break;
        
                                                                        case 'number':
                                                                            $data_objects[] = [
                                                                                'label' => $item['label'],
                                                                                'value' => $value,
                                                                            ];
                                                                            break;
        
                                                                        case 'date':
                                                                            $date = date('Y-m-d', strtotime($value));
                                                                            $data_objects[] = [
                                                                                'label' => $item['label'],
                                                                                'value' => $date,
                                                                            ];
                                                                            break;
        
                                                                        case 'time':
                                                                            $time = date('H:i', strtotime($value));
                                                                            $data_objects[] = [
                                                                                'label' => $item['label'],
                                                                                'value' => $time,
                                                                            ];
                                                                            break;
        
                                                                        case 'radio':
                                                                            $data_objects[] = [
                                                                                'label' => $item['label'],
                                                                                'value' => $value,
                                                                            ];
                                                                            break;
        
                                                                        case 'checkbox':
                                                                            $data_objects[] = [
                                                                                'label' => $item['label'],
                                                                                'value' => implode(', ', $value),
                                                                            ];
                                                                            break;
        
                                                                        case 'select':
                                                                            if (is_array($value)) {
                                                                                $data_objects[] = [
                                                                                    'label' => $item['label'],
                                                                                    'value' => isset($value['val'])
                                                                                        ? $value['val']
                                                                                        : (isset($value['text'])
                                                                                            ? $value['text']
                                                                                            : ' '),
                                                                                ];
                                                                            } else {
                                                                                $data_objects[] = [
                                                                                    'label' => $item['label'],
                                                                                    'value' => $value,
                                                                                ];
                                                                            }
                                                                            break;
        
                                                                        case 'dmd':
                                                                            $records = $item['input']['records'] ?? [];
                                                                            $dmd_values = [];
                                                                            foreach ($records as $record2) {
                                                                                $vtm = $record2['vtm']['vtm_string'] ?? '';
                                                                                $vmp = $record2['vmp']['vp_string'] ?? '';
                                                                                $other = $record2['other'] ?? '';
                                                                                $dmd_values[] = implode(
                                                                                    ', ',
                                                                                    array_filter([$vtm, $vmp, $other]),
                                                                                );
                                                                            }
                                                                            if (!empty($dmd_values)) {
                                                                                $data_objects[] = [
                                                                                    'label' => $item['label'],
                                                                                    'value' => implode('; ', $dmd_values),
                                                                                ];
                                                                            }
                                                                            break;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
        
                                        <div class="d-flex flex-column gap-3 m-3">
                                            @foreach ($data_objects as $data)
                                                <div style="line-height: 15px;">
        
                                                    <p class="m-0" style="font-size:12px; color:gray;line-height: inherit;">
                                                        {{ $data['label'] }}
                                                    </p>
                                                    <p class="m-0">
                                                        {{ $data['value'] }}
                                                    </p>
                                                </div>
                                            @endforeach
                                        </div>
                                        </div>
                                    </div>
                                    <div id="communication_tab"
                                        class="cm_case_tasks scrollbar_custom_green relative tab-pane ">
                                        <div class="col-sm-12 normal-hide">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="cm_comments_wrapper relative ">

                                                        @if(count($shared_case->extension))
                                                <hr>
                                                <div data-comment="{{$shared_case->id}}" class="cm_comment card @if( $key % 2 == 0 ) cm_comment_grey @endif">
                                                    <div class="cm_comment_author_date">
                                                        @php $extension = $shared_case->extension->last(); @endphp
                                                        <b>{{$extension->requested_by_user->name}}</b> requested extension {{$extension->created_at->diffForHumans()}}
                                                        <span class="float-right">{{$extension->created_at->format(config('app.dateFormat'))}} {{$extension->created_at->format(config('app.timeFormat'))}}</span>
                                                    </div>
                                                    <div class="cm_comment_comment">
                                                        Extension Request until <b>{{$extension->extension_time->format(config('app.dateFormat'))}} {{$extension->extension_time->format(config('app.timeFormat'))}}</b> (additional {{$extension->extension_time->diffForHumans()}})
                                                    </div>
                                                    <div class="cm_comment_comment">
                                                        {!! $extension->note !!}
                                                    </div>
                                                    <div class="cm_comment_people">
                                                    <a href="{{ route('user.share_case.request_extension_remove', [$shared_case->id, $extension->id]) }}"
                                                        data-msg="Are you sure you want to remove this?"
                                                        class="badge bg-danger badge-user delete_extension">Cancel Request</a></div>
                                                </div>
                                            @endif

                                                        @if (!count($shared_case->communications))
                                                            <p class="font-italic">No comments are found!</p>
                                                        @else
                                                            <div id="contextMenu" class="context-menu"
                                                                style="display: none">
                                                                <ul class="menu_comment_link">
                                                                    <li class="share"><a href="#"
                                                                            onclick="copy_to_clipboard(this)"
                                                                            id="t_link_item" data-link=""><i
                                                                                class="fa fa-copy" aria-hidden="true"></i>
                                                                            Copy Tracking Link</a>
                                                                    </li>
                                                                    <li class="share"><a href="#"
                                                                            onclick="copy_to_clipboard(this)"
                                                                            id="o_link_item" data-link=""><i
                                                                                class="fa fa-copy" aria-hidden="true"></i>
                                                                            Copy Original Link</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        @endif
                                                        @foreach ($shared_case->communications as $key => $comment)
                                                            @include(
                                                                'user.view_comments',
                                                                compact('comment'))
                                                        @endforeach
                                                        <div class="cm_new_comment ">
                                                            <!-- <p>Add New Comment</p> -->
                                                            @include('user.form_comment', [
                                                                'shared_case' => $shared_case,
                                                            ])
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($shared_case->is_log_viewable)
                                        <div id="shared_case_log_read_only" class="cm_case_tasks  relative tab-pane ">
                                            <div class="scrollbar_custom_green cm_case_tasks cm_comments_wrapper relative p-5">
                                                @foreach($shared_case->case->comments->reverse() as $key=> $comment)
                                                    @include('head_office.case_manager.notes.view_comments',compact('comment'))
                                                @endforeach

                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade file_upload_model" id="add_files"
        @if (isset($remove_backdrop)) data-backdrop="false" @endif tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        <p class="text-success"><i class="fa fa-cloud fa-3x"></i></p>
                        File Upload Box
                    </h4>
                    <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="uploaded_files mt-2 mb-2">
                    </div>
                    <h6 class="text-info">Select documents/images to upload</h6>
                    <input type="file" name="file" multiple value=""
                        class="form-control commentMultipleFiles">

                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Continue <i
                                class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_links" @if (isset($remove_backdrop)) data-backdrop="false" @endif tabindex="-1"
        role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        <p class="text-success"><i class="fa fa-paperclip fa-flip-horizontal fa-3x"></i></p>
                        External Link Box
                    </h4>
                    <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="new_link_wrapper">
                        <div class="form-group">
                            <label>Link Title</label>
                            <input type="text" name="link_title" id="link_title" class="form-control link_title">
                        </div>
                        <div class="form-group">
                            <label>Link Url</label>
                            <input type="text" name="link_url" id="link_url" class="form-control link_url">
                        </div>
                        <div class="form-inline">
                            <h5>File Removal Reminder</h5>
                            <p>If this is a link to a file, you can set reminder to notify you to remove this files in line
                                with your data retention policy when the retention period is exceeded. </p>

                            <label>Remind Me</label>&nbsp;&nbsp;
                            <input type="checkbox" name="is_remind_me" id="is_remind_me"
                                class="form-control is_remind_me">
                            &nbsp;&nbsp;&nbsp;

                            <label>Notify Me in</label>&nbsp;&nbsp;&nbsp;
                            <input type="number" name="duration" style="width: 100px" class="form-control duration">
                            <select class="form-control duration_units">
                                <option value="days">Days</option>
                                <option value="weeks">Weeks</option>
                                <option value="months">Months</option>
                                <option value="years">Years</option>
                            </select>
                            <input type="hidden" name="reminder_links[]" multiple="multiple" class="reminder_links">
                        </div>
                        <button data-dismiss="modal" class="btn btn-white add_link">Add Link</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        jQuery(document).on('input', '.cm_comment_box', function(e) {
            let html = this.innerHTML;
            jQuery(this).siblings('textarea.cm_comment_box_hidden').html(html);
        });
        $(document).ready(function() {

            loadActiveTab();
            if (window.location.search.split('=')[1] != undefined) {
                changeTabUrl(window.location.search.split('=')[1])
                $('#profile-tab').click()
            }

            });

            function loadActiveTab(tab = null) {
            if (tab == null) {
                tab = window.location.hash;
            }
            console.log(tab);
            //$(window.location)[0].replace(url);
            // window.location.replace(url);
            $('.nav-tabs button[data-target="' + tab + '"]').tab('show');
            }
        $(document).ready(function() {
            initializeClock($('#duration_of_access').val())
        })

        function getTimeRemaining(endtime) {
            console.log(endtime);
            const total = Date.parse(endtime) - Date.parse(new Date());
            const seconds = Math.floor((total / 1000) % 60);
            const minutes = Math.floor((total / 1000 / 60) % 60);
            const hours = Math.floor((total / (1000 * 60 * 60)) % 24);
            const days = Math.floor(total / (1000 * 60 * 60 * 24));

            return {
                total,
                days,
                hours,
                minutes,
                seconds
            };
        }

        function initializeClock(endtime) {
            // const clock = document.getElementById('clockdiv');
            const timeinterval = setInterval(() => {
                const t = getTimeRemaining(endtime);
                // clock.innerHTML = 'days: ' + t.days + ' time: ' + t.hours + ':' + t.minutes + ':' + t.seconds;
                document.getElementById("days").innerText = t.days + " : "
                document.getElementById("hours").innerText = t.hours + " : "
                document.getElementById("minutes").innerText = t.minutes + " :"
                document.getElementById("seconds").innerText = t.seconds
                if (t.total <= 0) {
                    clearInterval(timeinterval);
                }
            }, 1000);
        }

        function hide_extra_groups() {
            for (let j = 0; j < 10; j++) {
                for (let i = 0; i < 10; i++) {
                    if ($('.has-group-' + i + "-" + j).length == 0) {
                        $('.g_show_' + i + "_" + j).remove();
                    }
                }
            }


            for (let i = 0; i < 10; i++) {
                if ($("[class*='g_show_" + i + "']").length == 0) {
                    $('.stage_data_' + (i + 1)).remove();
                }
            }
        }

        hide_extra_groups();
    </script>
    <script src="{{ asset('js/alertify.min.js') }}"></script>
    <script src="{{asset('admin_assets/speech-to-text.js')}}"></script>
    <script src="{{asset('admin_assets/head-office-script.js')}}"></script>
    @endsection
