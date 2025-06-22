<style>

.popup {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%; 
    background-color: rgba(0, 0, 0, 0.5);
}

.popup-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 200px;
    height: 200px 
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

    .cm_case_number {
        font-size: 18px;
        font-weight: 400;
    }

    .bg-success {
        background: rgb(0, 205, 69) !important;
    }

    .text-success {
        color: rgb(0, 205, 69) !important;
    }
    .bg-primary{
        background: rgb(0, 89, 205) !important;
    }

    .bg-info{
        background: rgb(205, 198, 0) !important;
    }

    .border-both {
        border: 1px solid rgb(188, 188, 188);
        border-top: none;
        border-bottom: none;
    }
</style>

@foreach ($cases as $case)
    @if(isset($case->link_case_with_form->form)) 
    <div class="case_1 relative case-item">
        {{-- its just for filteration and dummy spans --}}
        @foreach ($case->stages as $stage)
            <span style="display: none;">{{ $stage->name }}</span>
        @endforeach
        @foreach ($case->root_cause_analysis as $root)
            <span style="display: none;">{{ isset($root) ? 'root_cause_analysis' : '' }}</span>
        @endforeach
        @foreach ($case->case_request_informations as $info)
            <span style="display: none;">{{ isset($info) ? 'requst_info_awaiting' : '' }}</span>
        @endforeach
        @foreach ($case->share_cases as $share)
            <span style="display: none;">{{ isset($share) ? 'shared' : '' }}</span>
        @endforeach
        @if ($case->getShareCaseExtensionsAttribute())
            <span style="display: none;">share_extended</span>
        @endif
        {{-- Checking the status of lfpse for the case --}}
        <span style="display: none;"
            class="lfpse_status">{{ isset($case->link_case_with_form->form) && $case->link_case_with_form->form->submitable_to_nhs_lfpse == true ? ($case->link_case_with_form->LfpseSubmissions && count($case->link_case_with_form->LfpseSubmissions) == 0 ? 'lfpse_no' : 'lfpse_yes') : 'not_lfpse_form' }}</span>
        {{-- its just for filteration and dummy spans --}}
        @if (!$case->case_closed)
            <input type="checkbox" class="inline w-21px cm_left_checkbox" data-locationcode="{{ $case->location->location_code }}" data-case-userid="{{ $case->getReporter()->id ?? $case->getExternal()->id }}" data-case-locaitonid="{{ $case->location_id }}" data-case-locationname="{{ $case->link_case_with_form && $case->link_case_with_form->hide ? 'External' : $case->location_name }}"
                data-case-name="{{ $case->getReporter() && $case->getReporter()->id ? ($case->getReporter()->email == 'external@qitech.com' ? 'External User' : $case->getReporter()->name) : '' }}" value="{{ $case->id }}" onchange="close_cases()"
                name="ids[]" multiple="multiple">
        @endif
        <div class="card border border-left-secondary shadow w-100"
            style="background: linear-gradient(90deg, {{ isset($case->link_case_with_form->form) && isset($case->link_case_with_form->form->color_code) ? $case->link_case_with_form->form->color_code : '#000000' }} 10px, rgba(255,255,255,1) 0%)">
            <div class="card-body">
                <div class="row ">
                    <div class="col-sm-2">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div class="cm_case_number text-black text-uppercase case_id_unique" title="Case Number">
                                #{{ $case->id() }}</div>
                            <div
                                class="cm_case_status font-weight-bold d-flex align-items-center fw-semibold @if ($case->isArchive == true) text-info @elseif($case->status == 'waiting' && $case->requires_final_approval == true) text-warning @elseif($case->status == 'open') text-success @elseif($case->status == 'closed') text-danger @endif">
                                <i class="fa-solid fa-circle mx-2" style="font-size: 6px;margin-top:3px;"></i>
                                @if ($case->isArchived)
                                    Archived
                                @elseif($case->status == 'waiting' && $case->requires_final_approval == true)
                                    Final Approval
                                @else
                                    {{ $case->status() }}
                                @endif
                            </div>
                        </div>
                        <div data-toggle="tooltip" data-bs-placement="auto" title="{{ $case->percentComplete() }}%"
                            class="progress mt-1" role="progressbar" aria-label="Case progress" aria-valuenow="75"
                            aria-valuemin="0" aria-valuemax="100"
                            style="height: 12px;width: 85%;margin-left:2px;cursor: pointer;">
                            <div class="progress-bar 
                        @if ($case->percentComplete() <= 20) bg-danger
                        @elseif($case->percentComplete() <= 40)
                        bg-info
                        @elseif($case->percentComplete() <= 60)
                        bg-primary
                        @elseif($case->percentComplete() <= 80)
                        bg-warning
                        @elseif($case->percentComplete() <= 100)
                        bg-success @endif
                        "
                            style="width: {{ $case->percentComplete() }}%"></div>
                        </div>
                        <h6 class="mt-2 fw-bold" style="margin-left: 2px;">
                            {{ isset($case->link_case_with_form->form->name) ? $case->link_case_with_form->form->name : 'Unknown' }}
                        </h6>
                        @php
                            $priority = $case->prority;
                            // Calculate color based on priority
                            if ($priority <= 50) {
                                // From green to yellow
                                $green = 255;
                                $red = intval(5.1 * $priority); // Scale from 0 to 255
                            } else {
                                // From yellow to red
                                $red = 255;
                                $green = intval(255 - 5.1 * ($priority - 50)); // Scale from 255 to 0
                            }
                            $color = "rgb($red, $green, 0)";
                        @endphp

                        @if ($priority != 0)
                            <div class="d-flex gap-1 align-items-center">
                                <svg width="22" height="22" viewBox="0 0 23 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="{{ $color }}"
                                        d="M12 16V12M12 8H12.01M7.8 21H16.2C17.8802 21 18.7202 21 19.362 20.673C19.9265 20.3854 20.3854 19.9265 20.673 19.362C21 18.7202 21 17.8802 21 16.2V7.8C21 6.11984 21 5.27976 20.673 4.63803C20.3854 4.07354 19.9265 3.6146 19.362 3.32698C18.7202 3 17.8802 3 16.2 3H7.8C6.11984 3 5.27976 3 4.63803 3.32698C4.07354 3.6146 3.6146 4.07354 3.32698 4.63803C3 5.27976 3 6.11984 3 7.8V16.2C3 17.8802 3 18.7202 3.32698 19.362C3.6146 19.9265 4.07354 20.3854 4.63803 20.673C5.27976 21 6.11984 21 7.8 21Z"
                                        stroke="black" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <p style="font-size: 18px;margin-top:2px !important;" class=" m-0">
                                    {{ $priority }}
                                </p>
                            </div>
                        @endif
                    </div>
                    <div class="col-sm-4  p-0" style="color: rgb(103,105,121);">
                        <div class="d-flex w-100 ">
                            <hr style="height: 80px;width:1.5px;background:rgb(158, 157, 157);border-radius:1rem;"
                                class="mx-3">
                            <div class=" d-flex flex-column w-100 gap-2">
                                <div style="font-size: 14px;" class=" d-flex gap-2 user-icon-circle new-card-wrap">
                                        <img class="img-profile rounded-circle" width="22" height="22"
                                         @if (count($case->case_handlers) != 0) 
                                          src="{{ $case->case_handlers[0]->case_head_office_user->user->logo }}" 
                                            @else 
                                                src="{{ asset('admin_assets/img/profile-pic.png') }}" 
                                            @endif
                                            >
                                                                    
                                    @if (isset($case->case_handlers[0]))
                                    @include('head_office.user_card_component', ['user' => $case->case_handlers[0]->case_head_office_user->user])
                                        
                                    @endif


                                
                                    Managed by
                                    @if (count($case->case_handlers) != 0)
                                    <p class="m-0 fw-semibold hover-text" style="cursor: pointer;">
                                        {{ implode(', ', $case->case_handlers->map(function ($handler) {
                                            return $handler->case_head_office_user->user->getNameAttribute();
                                        })->toArray()) }}
                                    </p>

                                    @else
                                        None
                                    @endif
                                </div>
                                <div class="d-flex align-items-center gap-2"
                                    style="font-size: 14px;white-space: nowrap;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke="rgb(103,105,121)"
                                            d="M12 2V6M12 18V22M6 12H2M22 12H18M19.0784 19.0784L16.25 16.25M19.0784 4.99994L16.25 7.82837M4.92157 19.0784L7.75 16.25M4.92157 4.99994L7.75 7.82837"
                                            stroke="black" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    Created
                                    <p class="m-0 fw-semibold">{{ $case->created_at->format('d M Y (D) h:i a') }} ({{$case->created_at->diffForHumans() }})</p>
                                </div>
                                <div class="d-flex align-items-center gap-2" style="font-size: 14px;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke="rgb(103,105,121)"
                                            d="M12 6V12L16 14M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z"
                                            stroke="black" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    Last Updated
                                    <p class="m-0 fw-semibold">{{ $case->updated_at->diffForHumans() }}</p>
                                </div>
                                @if (isset($case->link_case_with_form->form) && $case->link_case_with_form->form->name == 'NHS LFPSE')
                                    @if(isset($case->link_case_with_form->lfpse_deletes))
                                    <div class="d-flex align-items-center gap-2" style="font-size: 14px;">
                                        <div>Status: <b class="text-danger">Deleted</b> </div>
                                    </div>
                                    @elseif (count($case->link_case_with_form->LfpseSubmissions) == 0)
                                        <div class="d-flex align-items-center gap-2" style="font-size: 14px;">
                                            <div>Status: <b class="text-warning">Not Submitted</b></div>
                                        </div>
                                    @else
                                        <div>Status: <b class="text-success">&nbsp;Submitted</b></div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-sm-3 cm_description_tab d-flex">
                    <hr style="height: 80px;width:1.5px;background:rgb(158, 157, 157);border-radius:1rem;" class="mx-3">
                    @if (strlen($case->description) > 180)
                    {{substr($case->description,0,180)}}<span class="cm_dots">...</span><span
                        class="cm_more_text">{{substr($case->description,180)}}</span>
                    <a href="#" class="cm_see_more_btn">See more</a>
                    <a href="#" style="display:none" class="cm_see_less_btn">See less</a>
                    @else
                    {{$case->description}}
                    @endif
                </div> --}}
                    <div class="col-sm-3 cm_description_tab d-flex">
                        <hr style="height: 80px;width:1.5px;background:rgb(158, 157, 157);border-radius:1rem;"
                            class="mx-3">
                        @php
                            $record = $case->link_case_with_form;
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
                                                                $item['name'],
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
                                                                        'label' => $item['name'],
                                                                        'value' => $value,
                                                                        'is_display_case' => $item['input']['is_display_case'] ?? false,
                                                                    ];
                                                                    break;

                                                                case 'number':
                                                                    $data_objects[] = [
                                                                        'label' => $item['name'],
                                                                        'value' => $value,
                                                                        'is_display_case' => $item['input']['is_display_case']?? false
                                                                    ];
                                                                    break;

                                                                case 'date':
                                                                    $date = date('Y-m-d', strtotime($value));
                                                                    $data_objects[] = [
                                                                        'label' => $item['name'],
                                                                        'value' => $date,
                                                                        'is_display_case' => $item['input']['is_display_case']?? false
                                                                    ];
                                                                    break;

                                                                case 'time':
                                                                    $time = date('H:i', strtotime($value));
                                                                    $data_objects[] = [
                                                                        'label' => $item['name'],
                                                                        'value' => $time,
                                                                        'is_display_case' => $item['input']['is_display_case']?? false
                                                                    ];
                                                                    break;

                                                                case 'radio':
                                                                    $data_objects[] = [
                                                                        'label' => $item['name'],
                                                                        'value' => $value,
                                                                        'is_display_case' => $item['input']['is_display_case']?? false
                                                                    ];
                                                                    break;

                                                                case 'checkbox':
                                                                $data_objects[] = [
                                                                    'label' => $item['name'],
                                                                        'value' => implode(', ', (array)$item['input']['value']),
                                                                        'is_display_case' => $item['input']['is_display_case']?? false
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
                                                                        'label' => $item['name'],
                                                                        'value' => implode(', ', $values),
                                                                        'is_display_case' => $item['input']['is_display_case']?? false
                                                                    ];
                                                                    } else {
                                                                        $data_objects[] = [
                                                                            'label' => $item['name'],
                                                                            'value' => $value,
                                                                            'is_display_case' => $item['input']['is_display_case']?? false
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
                                                                            'label' => $item['name'],
                                                                            'value' => implode('; ', $dmd_values),
                                                                            'is_display_case' => $item['input']['is_display_case']?? false
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
                            
                            $is_display_case_data_objects = array_filter($data_objects, function ($data) {
                                            return $data['is_display_case'];
                                        });
                        @endphp

                        <div>
                            @if(!empty($is_display_case_data_objects))
                            @foreach ($is_display_case_data_objects as $data)
                                    @if (!empty($data['value']))
                                        <div style="line-height: 15px;">

                                            <p class="m-0 mt-1" style="font-size:12px; color:gray;line-height: inherit;">
                                                {{ $data['label'] }}
                                            </p>
                                            <p class="m-0" style="font-size: 13px">
                                                {{ is_array($data['value']) ? implode(', ', $data['value']) : $data['value'] }}
                                            </p>
                                        </div>
                                    @endif
                            @endforeach
                            @else
                            <p>N/A</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-2 d-flex align-items-center">
                        <hr style="height: 80px;width:1.5px;background:rgb(158, 157, 157);border-radius:1rem;"
                            class="mx-3">
                        @livewire('case-tags-manager', ['case_id' => $case->id])
                    </div>
                    <div class="col-sm-1 " style="align-self: center;">
                        <a href="{{ route('case_manager.view', $case->id) }}" target="_blank" class="float-right"><i
                                class="fa fa-angle-right fa-2x text-gray-300"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach
<script>
    function openPopup() {
    document.getElementById("popup").style.display = "block";
}

function closePopup() {
    document.getElementById("popup").style.display = "none";
}

</script>