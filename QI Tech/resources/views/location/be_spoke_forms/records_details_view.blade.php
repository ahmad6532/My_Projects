@extends('layouts.location_app')
@section('title', 'Be Spoke Forms dashboard')
@section('subHeader')

{{-- @dd(Session::has('success'), Session::has('error'), Session::get('success'), Session::get('error')) --}}
    @php
        use App\Models\HeadOffices\CaseManager\HeadOfficeCase;
        use App\Models\User;
    @endphp
    <div style="max-width: 70%;margin:0 auto;margin-block:2.5rem;margin-top:3rem">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button onclick="changeTabUrl('view_form')" style="background: transparent;font-weight:bold;"
                    class="nav-link active" id="view_form-tab" data-bs-toggle="tab" data-bs-target="#view_form" type="button"
                    role="tab" aria-controls="View form" aria-selected="true">View Form</button>
            </li>
            <li class="nav-item" role="presentation">
                <button onclick="changeTabUrl('updates')" style="background: transparent;font-weight:bold;" class="nav-link"
                    id="updates-tab" data-bs-toggle="tab" data-bs-target="#updates" type="button" role="tab"
                    aria-controls="updates" aria-selected="false">Updates</button>
            </li>
            <li class="nav-item" role="presentation">
                <button onclick="changeTabUrl('modification')" style="background: transparent;font-weight:bold;"
                    class="nav-link" id="modification-tab" data-bs-toggle="tab" data-bs-target="#modification"
                    type="button" role="tab" aria-controls="contact" aria-selected="false">Modifications</button>
            </li>

            {{-- <li class="nav-item" role="presentation">
                <button style="background: transparent;font-weight:bold;" class="nav-link" id="info-tab"
                    data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="profile"
                    aria-selected="false">Info</button>
            </li> --}}

            <!-- <li class="nav-item" role="presentation" title="Coming Soon">
                <button onclick="event.preventDefault();" style="background: transparent; font-weight: bold;"
                    class="nav-link" id="root_cause-tab" type="button" role="tab" aria-controls="contact" aria-selected="false">
                    Root Cause Analysis
                </button>
            </li> -->

            <li class="nav-item" role="presentation" style="position: relative;">
    <button 
        onclick="event.preventDefault();" 
        style="background: transparent; font-weight: bold;" 
        class="nav-link" 
        id="root_cause-tab" 
        type="button" 
        role="tab" 
        aria-controls="contact" 
        aria-selected="false">
        Root Cause Analysis
    </button>
    <div class="tooltip" style="display: none; position: absolute; top: -35px; left: 50%; transform: translateX(-50%); background: #333; color: #fff; padding: 5px 10px; border-radius: 4px; white-space: nowrap;">
        Coming Soon
        <div style="position: absolute; top: 100%; left: 50%; margin-left: -5px; border-width: 5px; border-style: solid; border-color: #333 transparent transparent transparent;"></div>
    </div>
</li>

<script>
    document.getElementById('root_cause-tab').addEventListener('mouseover', function() {
        this.nextElementSibling.style.display = 'block';
    });

    document.getElementById('root_cause-tab').addEventListener('mouseout', function() {
        this.nextElementSibling.style.display = 'none';
    });
</script>




            @if (count($record->getCaseFeedbacks()) != 0)
                <li class="nav-item" role="presentation">
                    <button onclick="changeTabUrl('feedbacks')" style="background: transparent;font-weight:bold;"
                        class="nav-link" id="feedbacks-tab" data-bs-toggle="tab" data-bs-target="#feedbacks" type="button"
                        role="tab" aria-controls="contact" aria-selected="false">Feedbacks</button>
                </li>
            @endif
        </ul>
    </div>
@endsection
@section('content')
    <div id="content"
        style="max-width: 70%;margin:2.5rem auto;box-shadow:0 0 15px rgba(0, 0, 0, 0.178);margin-top:4rem !important;">
        <div style="text-align: right;">
            <a href="{{ route('be_spoke_forms.be_spoke_form.records') }}" class="text-info "><i
                    class="fa fa-arrow-left"></i>
                Back</a>
        </div>

        <div class="user-info-location">
            <p style="font-size:32px;" class="m-0">{{ $record->form->name }}</p>
            <div class="d-flex w-100 ">
                <div class=" d-flex w-100 gap-2">
                    <div style="font-size: 14px;" class=" d-flex gap-2">
                        <img class="img-profile rounded-circle" width="22" height="22"
                            @if (isset($record->created_by->logo)) src="{{ $record->created_by->id == 0 ? asset('images/user-external.svg') : $record->created_by->logo }}"
                        @else
                        src="{{ asset('admin_assets/img/profile-pic.png') }}" @endif />
                        Completed by
                        <p class="m-0 fw-semibold">
                            {{ isset($record->created_by) ? $record->created_by->getNameAttribute() : 'Quick login' }}
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2" style="font-size: 14px;white-space: nowrap;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke="rgb(103,105,121)"
                                d="M12 2V6M12 18V22M6 12H2M22 12H18M19.0784 19.0784L16.25 16.25M19.0784 4.99994L16.25 7.82837M4.92157 19.0784L7.75 16.25M4.92157 4.99994L7.75 7.82837"
                                stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Created
                        <p class="m-0 fw-semibold">{{ $record->created_at->format('d M Y (D) h:i a') }}</p>
                    </div>
                    <div class="d-flex align-items-center gap-2" style="font-size: 14px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke="rgb(103,105,121)"
                                d="M12 6V12L16 14M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z"
                                stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Last Updated
                        <p class="m-0 fw-semibold"> @if (count($record->get_modifications())!=0)
                            {{last($record->get_modifications())->updated_at->diffForHumans()}}
                            @else
                            {{ $record->updated_at->diffForHumans() }}
                            
                        @endif</p>
                    </div>
                    @if(isset($record->recorded_case->saved_loc))
                       <div class="d-flex align-items-center gap-2" style="font-size: 14px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 8.00007L2 22.0001M18 15.0001H9M6.6 19.0001H13.3373C13.5818 19.0001 13.7041 19.0001 13.8192 18.9724C13.9213 18.9479 14.0188 18.9075 14.1083 18.8527C14.2092 18.7909 14.2957 18.7044 14.4686 18.5314L19.5 13.5001C19.739 13.2611 19.8584 13.1416 19.9546 13.0358C22.0348 10.7474 22.0348 7.25275 19.9546 4.9643C19.8584 4.85851 19.739 4.73903 19.5 4.50007C19.261 4.26111 19.1416 4.14163 19.0358 4.04547C16.7473 1.96531 13.2527 1.96531 10.9642 4.04547C10.8584 4.14163 10.739 4.26111 10.5 4.50007L5.46863 9.53144C5.29568 9.70439 5.2092 9.79087 5.14736 9.89179C5.09253 9.98126 5.05213 10.0788 5.02763 10.1808C5 10.2959 5 10.4182 5 10.6628V17.4001C5 17.9601 5 18.2401 5.10899 18.4541C5.20487 18.6422 5.35785 18.7952 5.54601 18.8911C5.75992 19.0001 6.03995 19.0001 6.6 19.0001Z" stroke="rgb(103,105,121)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            
                           Reported from
                           <p class="m-0 fw-semibold">{{ $record->recorded_case->saved_loc->trading_name }}</p>
                       </div>
                    @endif


                </div>
            </div>
        </div>

        @php
    use Carbon\Carbon;

$editingState = $record->form->allow_editing_state; // "minutes", "hour", "day", "week", or "always"
$editingTime = (int) $record->form->allow_editing_time;

// If editing is always allowed
$canEdit = false;
if ($editingState === 'always') {
    $canEdit = true;
} else {
    $createdAt = \Carbon\Carbon::parse($record->created_at);
    $currentTime = \Carbon\Carbon::now();

    switch ($editingState) {
        case 'minutes':
            $allowedUntil = $createdAt->addMinutes($editingTime);
            break;
        case 'hour':
            $allowedUntil = $createdAt->addHours($editingTime);
            break;
        case 'day':
            $allowedUntil = $createdAt->addDays($editingTime);
            break;
        case 'week':
            $allowedUntil = $createdAt->addWeeks($editingTime);
            break;
        default:
            $allowedUntil = null;
            break;
    }

    if (isset($allowedUntil)) {
        $canEdit = $currentTime->lessThanOrEqualTo($allowedUntil);
    }
    dd($canEdit);
}
@endphp

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active px-3" id="view_form" role="tabpanel" aria-labelledby="view_form-tab">
                @if ($record->form->allow_editing_state !== 'disable' && is_null($record->all_linked_records()->last()->id) != true && isset($record->recorded_case->id) && $record->recorded_case->status !== 'closed' && $canEdit)
                @if ( isset($location->organization_setting_assignment->organization_setting))

                <a href="/bespoke_form_v3/#!/edit/{{$record->all_linked_records()->last()->id}}?case_id={{$record->recorded_case->id}}&location_id={{$location->id}}&rec_id={{$record->id}}&setting_id={{$location->organization_setting_assignment->organization_setting->id}}" style="position: absolute;right:90px;top:13px;"  class="btn btn-info">Modify Report</a>
                @else
                <a href="/bespoke_form_v3/#!/edit/{{$record->all_linked_records()->last()->id}}?case_id={{$record->recorded_case->id}}&location_id={{$location->id}}&rec_id={{$record->id}}" style="position: absolute;right:90px;top:13px;"  class="btn btn-info">Modify Report</a>

                @endif   
                    <button style="position: absolute;right:90px;align-self: flex-end; display:none;" class="btn btn-info"
                        id="apply-btn" type="submit">Apply Changes</button>
                @endif

                <div>

                    <div>
                        <form id='modify-form' style="min-height: 65vh;margin-top:32px;"
                            action="{{ route('be_spoke_forms.be_spoke_form.mod.save', ['id' => $record->id]) }}"
                            method="POST">
                            @csrf
                            <div class="details-4 details_{{ $record->id }} d-flex flex-column gap-2">

                                @php
                                    $data_objects = [];
                                    if ($record->raw_form) {
                                        $updated_records = $record->all_linked_records()->last();
                                            if (isset($updated_records->raw_form)) {
                                                $questionsJson = json_decode($updated_records->raw_form, true);
                                            } else {
                                                $questionsJson = json_decode($record->raw_form, true);
                                            }
                                        
                                            
                                        if ($questionsJson['pages'] && count($questionsJson['pages']) > 0) {
                                            foreach ($questionsJson['pages'] as $page) {
                                                if ($page['items'] && count($page['items']) > 0) {
                                                    foreach ($page['items'] as $item) {
                                                        if (
                                                            isset(
                                                                $item['label'],
                                                                $item['input'],
                                                                $item['input']['type'],
                                                                $item['input']['value'],
                                                            )
                                                        ) {
                                                            $type = $item['input']['type'];
                                                            $value = isset($item['input']['value']) ? $record->get_question_details_by_id($item['id'],null, $item['input']['value']) : $item['input']['value'];

                                                            switch ($type) {
                                                                case 'text':
                                                                case 'email':
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

                                                                case 'checkbox':
                                                                $data_objects[] = [
                                                                    'label' => $item['label'],
                                                                        'value' => implode(', ', (array) $value),
                                                                    ];
                                                                    break;
                                                                    
                                                                case 'select':
                                                                if (is_array($item['input']['value'])) {
                                                                    $values = [];
                                                                    foreach ($item['input']['value'] as $value) {
                                                                        if (is_array($value)) {
                                                                            $values[] = isset($value['val'])
                                                                                ? $value['val']
                                                                                : (isset($value['text'])
                                                                                    ? $value['text']
                                                                                    : '');
                                                                        } else {
                                                                            $values[] = $value;
                                                                        }
                                                                    }

                                                                    $data_objects[] = [
                                                                        'label' => $item['label'],
                                                                        'value' => implode(', ', $values),
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

                                <div class="d-flex flex-column gap-3 mt-3">
                                    @foreach ($data_objects as $data)
                                    @if (!empty($data['value']))
                                    
                                        <div style="line-height: 15px;">

                                            <p class="m-0" style="font-size:12px; color:gray;line-height: inherit;">
                                                {{ $data['label'] }}
                                            </p>
                                            <p class="m-0">
                                                {{ $data['value'] }}
                                            </p>
                                        </div>
                                    @endif
                                    @endforeach
                                </div>
                                {{-- @php
                                    $jsonData = isset($record->json_submission) ? json_decode($record->json_submission,true) : null;
                                @endphp
                                @if ($jsonData)
                                    @foreach ($jsonData['mandatory_questions'] as $index => $data)
                                        @if ($data)
                                            <p style="margin: 0;" class="in-wrapper" >
                                                <span class="detail-title" style="width:15%;">
                                                    {{ $data['label'] }}:
                                                </span>
                                                <input type="text" name="{{$data['label']}}" value="{{ $data['value'] }}" style="width:85%;" class="modify-input" readonly>
                                            </p>
                                        @endif
                                    @endforeach
                                @else --}}
                                {{-- <p>No Valid Data found</p>
                                @endif --}}
                                {{-- @if ($record->recorded_case && count($record->recorded_case->root_cause_analysis) > 0)
                                    @foreach ($record->recorded_case->root_cause_analysis as $request)
                                        @if (!$request->status)
                                            <a style="width: fit-content;" target="_blank"
                                                href="{{ route('be_spoke_forms.be_spoke_form.root_cause_analysis_request', [$request->id, $request->type]) }}">{{ $request->root_cause_analysis_type }}</a>
                                        @endif
                                    @endforeach
                                    <button class="btn btn-info" style="align-self: flex-end; display:none;" id="apply-btn" type="submit">Apply Changes</button>
                                @endif --}}
                            </div>
                        </form>
                        @if ($record->form->allow_responder_update && count($record->updates) != 0)
                            <div style="text-align: right;">
                                <button class="btn btn-info" id="view-update-btn">View Updates</button>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <div class="tab-pane fade" id="info" role="tabpanel" aria-labelledby="info-tab">
                <h3 class="h5 bg-info text-white p-2 px-3 rounded-pill" style="margin-top:-1.9rem;width:fit-content;">
                    {{ $record->form->name }}</h3>
                <div class="mx-3 mt-3 d-flex align-items-center gap-2">
                    <h6 class="h6">Description:</h6>
                    <h6 class="h6 fw-normal text-muted"> {{ $record->form->note }}</h6>
                </div>
                <div class="mx-3 d-flex align-items-center gap-2">
                    <h6 class="h6">Submitted:</h6>
                    <h6 class="h6 fw-normal text-muted"> {{ $record->created_at->format('d M Y (D) h:i a') }}</h6>
                </div>
                <div class="mx-3 d-flex align-items-center gap-2">
                    <h6 class="h6">Completed By:</h6>
                    <h6 class="h6 fw-normal text-info"> {{ $record->location->trading_name }}</h6>
                    <h6 class="h6 fw-normal text-muted">
                        ,{{ isset($record->created_by) ? ($record->created_by->first_name . ' ' . $record->created_by->surname) : 'Quick login' }}</h6>
                </div>
            </div>

            <div class="tab-pane fade" id="updates" role="tabpanel" aria-labelledby="updates-tab">
                @if ($record->form->allow_responder_update)
                    <div class="">
                        @foreach ($record->updates as $key => $update)
                            <div data-comment="{{ $update->id }}" class="cm_comment card  cm_comment_grey ">
                                <div class="cm_comment_author_date">
                                    <b>{{ optional($update->user)->name }}</b> Updated
                                    {{ $update->created_at->diffForHumans() }}
                                    <span class="float-right">{{ $update->created_at->format('d M Y (D) h:i a') }}
                                    </span>
                                </div>
                                <div class="cm_comment_comment">
                                    {!! $update->update !!}
                                </div>
                                <div class="cm_comment_attachments mt-1">
                                    <div class="cm_comment_people">
                                        @foreach ($update->documents as $doc)
                                            <span data-toggle="tooltip" title=""
                                                class="badge badge-primary badge-user">
                                                <a style="color: white"
                                                    class="relative @if ($doc->type == 'image') cm_image_link @endif "
                                                    href="{{ route('head_office.be_spoke_forms.record.update.view.attachment', $doc->document->unique_id) . $doc->document->extension() }}"
                                                    target="_blank">
                                                    @if ($doc->type == 'image')
                                                    <i class="fa fa-image"></i> @else<i class="fa fa-link"></i>
                                                    @endif
                                                    {{ $doc->document->original_file_name() }}
                                                    @if ($doc->type == 'image')
                                                        <div class="cm_image_hover">
                                                            <div class="card shadow">
                                                                <div class="card-body">
                                                                    <img class="image-responsive" width="300"
                                                                        src="{{ route('head_office.be_spoke_forms.record.update.view.attachment', $doc->document->unique_id) . $doc->document->extension() }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </a>
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                            
                        @if ($record->form->update_time_left($record->created_at)['allowed'] == false && $record->form->allow_update_state != 'open')
                            <p class="mt-4 ms-3">Unfortunately, the time period to submit further updates has passed.
                                Please submit another form if you need to provide further information.</p>
                        @elseif ($record->recorded_case->status == 'closed' && $record->form->allow_update_state != 'open')
                        <p class="mt-4 ms-3">Case is Closed</p>

                        @else
                        
                            <div class="card update_card_{{ $record->id }} mt-3">
                                <div class="card-header">
                                    {{ $record->form->allow_responder_update == true ? 'New Update' : 'Updates Not Allowed' }}
                                </div>
                                <div class="card-body">
                                    <form class="cm_task_form" method="post"
                                        action="{{ route('be_spoke_forms.be_spoke_form.update', $record->id) }}">
                                        @csrf
                                        <div class="form-group">
                                            <label>Enter Update</label>
                                            <textarea spellcheck="true"  name="update" class="form-control" required 
                                                    {{ $record->form->allow_responder_update == true ? '' : 'disabled' }} 
                                                    rows="3"></textarea>
                                        </div>

                                        <div class="uploaded_files mt-2 mb-2">
                                    @foreach ($record->documents ?? [] as $doc)
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
                                </div>
                                        <h6 class="text-info">Select documents/images to upload</h6>
                                        <div class="cm_upload_box_with_model center"
                                            style="display: {{ $record->form->allow_responder_update == true ? 'block' : 'none' }};">
                                            <i class="fa fa-cloud-upload-alt" style="font-size:48px"></i><br>Drop files
                                            here
                                        </div>
                                        <input {{ $record->form->allow_responder_update == true ? '' : 'disabled' }}
                                            type="file" name="file" multiple value=""
                                            class="form-control commentMultipleFiles">
                                        <button
                                            type="{{ $record->form->allow_responder_update == true ? 'submit' : 'reset' }}"
                                            class="btn btn-info mt-2">Save</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="mt-4 ms-3">This form does not allow updates. Please submit another form if you need to
                        provide further information.</p>
                @endif
            </div>

            <div class="tab-pane fade" id="modification" role="tabpanel" aria-labelledby="modification-tab">
                @if (count($record->get_modifications()) != 0)
                    <div class="timeline-2">
                        @php
                            $modifications = collect($record->get_modifications())->sortByDesc('created_at');
                        @endphp
                        @foreach ($modifications as $modification)
                            @php
                                $json = isset($modification->modified_data) ? json_decode($modification->modified_data, true) : [];
                            @endphp
                            <div data-bs-comment="{{ $modification->id }}" class="timeline-entry">
                                <div class="timeline-entry-inner">
                                    <div class="cm_comment_author_date grey-background">
                                        <b>{{ $modification->user->name }}</b> 
                                        <span style="color: rgb(0, 128, 2)">Modified</span> a response
                                        <span class="float-right">{{ $modification->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="cm_comment_content">
                                        <b>Question:</b>
                                        <ul>
                                            @foreach ($json as $id => $changes)
                                                <li>{{ $changes['original']['label'] ?? 'Unknown' }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="cm_comment_response">
                                        <b>Response:</b>
                                        <ul>
                                            @foreach ($json as $id => $changes)
                                            @php
                                                $original_value = $changes['original']['value'] ?? null;
                                                $modified_value = $changes['modified']['value'] ?? null;
                                        
                                                // Handle multiple choice fields (you can expand this for other types)
                                                $is_multiple_choice = isset($changes['original']['type']) && $changes['original']['type'] == 'multiple_choice';
                                                
                                                if ($is_multiple_choice) {
                                                    // If it's a multiple choice, compare the selected options (which might be an array)
                                                    $original_value = is_array($original_value) ? implode(', ', $original_value) : $original_value;
                                                    $modified_value = is_array($modified_value) ? implode(', ', $modified_value) : $modified_value;
                                                }
                                                
                                                // Get question details for original value
                                                $details = $record->get_question_details_by_id($id, $modification->parent_record_id, $original_value);
                                                $result = is_array($details) 
                                                    ? (!empty($details) ? implode(', ', $details) : 'N/A') 
                                                    : ($details ?? 'N/A');
                                        
                                                // Get question details for modified value
                                                $modified_details = $record->get_question_details_by_id($id, $modification->parent_record_id, $modified_value);
                                                $modified_result = is_array($modified_details)
                                                    ? (!empty($modified_details) ? implode(', ', $modified_details) : 'N/A')
                                                    : ($modified_details ?? 'N/A');
                                            @endphp
                                            <li>
                                                <div class="response-comparison">
                                                    <span class="original-response">{{ $result ?? 'N/A' }}</span>
                                                    <span class="arrow">→</span>
                                                    <span class="modified-response">{{ $modified_result ?? 'N/A' }}</span>
                                                </div>
                                            </li>
                                        @endforeach
                                        
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mt-4 ms-3">No modifications!</p>
                @endif
            </div>
              <div class="tab-pane fade" id="root_cause" role="tabpanel" aria-labelledby="root_cause-tab">root</div>
            <div class="tab-pane fade" id="feedbacks" role="tabpanel" aria-labelledby="feedbacks-tab">
                <div class="px-4 mt-4">
                    @if (count($record->getCaseFeedbacks()) != 0)
                        @foreach ($record->getCaseFeedbacks() as $feedback)
                            @php
                                $case_ids = isset($feedback->case_ids) ? json_decode($feedback->case_ids, true) : null;
                            @endphp
                            <div class="p-4 d-flex mb-3"
                                style="border: 1px solid #dee2e6;border-radius:0.9rem;gap:5rem!important;">
                                <img style="width: 85px;height:85px;object-fit:cover;"
                                    src="{{ $feedback->headOffice->getLogoAttribute() }}" alt="headoffice logo">
                                <div>
                                    <p class="text-info m-0" style="font-size: 14px; margin-bottom:0.5rem;">Date</p>
                                    <h6 class="m-0 " style="font-weight: 400;">
                                        {{ $feedback->created_at->format('d/m/Y') }}</h6>
                                </div>
                                <div>
                                    <p class="text-info m-0" style="font-size: 14px; margin-bottom:0.5rem;">Reported</p>
                                    @isset($case_ids)
                                        @foreach ($case_ids as $case_id)
                                            @php
                                                $case = HeadOfficeCase::find($case_id)->first();
                                            @endphp
                                            <div class="d-flex flex-column mb-3">
                                                <p class="m-0 fw-semibold" style="font-weight: 400;line-height:1.2;">
                                                    {{ $case->created_at->format('d/m/Y') }}</p>
                                                <p class="m-0 fw-semibold" style="font-weight: 400;line-height:1.2;">
                                                    {{ $case->incident_type }}</p>
                                                <p class="m-0 fw-semibold" style="font-weight: 400;line-height:1.2;">
                                                    {{ $case->location_name }}</p>
                                            </div>
                                        @endforeach
                                    @endisset
                                </div>
                                @if ($feedback->is_feedback_location)
                                    <div>
                                        <p class="text-info m-0" style="font-size: 14px; margin-bottom:0.5rem;">Feedback
                                            by {{ $feedback->HeadOffice->name() }}</p>
                                        <p class="m-0 fw-semibold" style="font-weight: 400;line-height:1.2;">
                                            {!! isset($feedback->feedback_location)
                                                ? $feedback->feedback_location
                                                : $feedback->HeadOffice->name() . ' ' . "did'nt provided any feedback" !!}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="h5 text-center mt-3">No Feedbacks at the moment 🙂!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

<input type="hidden" value="{{route('location.document.uploadHashed')}}" id="route_document">
<input type="hidden" value="{{route('location.document.removedHashed')}}" id="route_document_removedHashed">

@section('scripts')
    <script>
        $('#view-update-btn').on('click', function() {
            $('#myTabContent').find('.active.show').removeClass('active show');
            $('#updates').addClass('active show');
            $('#myTab .nav-link.active').removeClass('active');
            $('#updates-tab').addClass('active');

        })

        $('#modify-btn').on('click', function() {
            $(this).fadeOut();
            $('#apply-btn').fadeIn();
            $('.in-wrapper').css('display', 'flex')
            $('.in-wrapper input').addClass('modify-input active').removeAttr('readonly')
            $('.in-wrapper input').eq(0).focus();

        })
        $('#apply-btn').on('click', function() {
            $('#modify-form').submit()
        });

        $(document).ready(function() {
            // loadActiveTab();
            // changeTabUrl('orgStructureClick');
            if (window.location.search.split('=')[1] != undefined) {
                // console.log(window.location.search.split('=')[1].split('&')[0]);
                changeTabUrl(window.location.search.split('=')[1])
            }
        });

        function changeTabUrl(tabId, subTabId = null) {
            const currentURL = new URL(window.location.href);
            currentURL.searchParams.set('tab', tabId);
            console.log(tabId)
            // if(subTabId !== null){
            //     currentURL.searchParams.set('subTab',subTabId);
            // }
            window.history.replaceState({}, '', currentURL.href);
            // window.history.pushState({
            //     tabId: tabId
            // }, null, currentURL.href);

            // $('#' + tabId).tab('show');
            showTabPane(tabId);
            console.log($('#' + tabId))

            // if(subTabId !== null){
            //     $('#'+subTabId).tab('show');
            // }
        }

        function updateQueryParameter(param, value) {
            // Get the current URL
            const url = new URL(window.location.href);

            // Set or update the query parameter
            url.searchParams.set(param, value);

            // Replace the current history entry with the new URL
            window.history.replaceState({}, '', url.href);
        }

        function loadActiveTab(tab = null) {
            if (tab == null) {
                tab = window.location.hash;
            }
            $('.main_header > li > a[data-bs-target="' + tab + '"]').tab('show');
        }

        function getTabPaneById(tabId) {
            var $tabPane = $('#' + tabId);
            if ($tabPane.hasClass('tab-pane')) {
                return $tabPane;
            } else {
                console.error('No tab-pane found with ID ' + tabId);
                return null;
            }
        }

        // Function to show a tab-pane by ID
        function showTabPane(tabId) {
            var $tabPane = getTabPaneById(tabId);
            if ($tabPane) {
                // Hide all tab panes
                $('.tab-pane').removeClass('show active').hide();

                // Show the specified tab pane
                $tabPane.addClass('show active').fadeIn();

                // Update the corresponding tab link
                var $tabLink = $('a[href="#' + tabId + '"]');
                $('.nav-link').removeClass('active');
                $tabLink.addClass('active');
            }
        }

        // Function to hide a tab-pane by ID
        function hideTabPane(tabId) {
            var $tabPane = getTabPaneById(tabId);
            if ($tabPane) {
                // Hide the specified tab pane
                $tabPane.removeClass('show active').hide();
            }
        }

        // Example usage
        var tabId = 'profile'; // Change this to your desired tab ID

        // Show the tab pane
        showTabPane(tabId);



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
@if(Session::has('success'))
<script>
    alertify.defaults.notifier.delay = 10; // Set the delay to 10 seconds
    alertify.success("{{ Session::get('success') }}");
</script>
@endif

@if(Session::has('error'))
<script>
    alertify.defaults.notifier.delay = 10; // Set the delay to 10 seconds
    alertify.error("{{ Session::get('error') }}");
</script>
@endif


    
@endsection


