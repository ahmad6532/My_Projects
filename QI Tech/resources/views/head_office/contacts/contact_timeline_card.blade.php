@foreach ($records as $date => $records)
    <div class="line line-date date_{{ date('Y_m_d', strtotime($date)) }}">
        <div class="timeline-label">
            @if ($date == date('Y-m-d'))
                TODAY
            @else
                {{ date(
                    'D jS F
                        Y',
                    strtotime($date),
                ) }}
            @endif
        </div>
    </div>
    @foreach ($records as $key => $record)
        
        
        @isset($record->link_case_with_form->form)
            <div
                class="line nearmiss_hidden case-item   right-record nearmiss_record nearmiss_{{ $record->id }} nearmiss_status_{{ strtolower(str_replace(' ', '_', $record->status)) }}">
                <div class="date time">{{ $record->created_at->format(config('app.dateFormat'))  }}</div>
                    <div class="content-timeline" data-color="{{ isset($record->link_case_with_form->form->color_code) ? $record->link_case_with_form->form->color_code : '#4dd6f0' }}">
                        <p class="fw-bold mb-2 mt-0" style="color: var(--location-section-heading-color)">
                            {{ $record->created_at->format('h:i A') }}</p>
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div class="cm_case_number text-black  case_id_unique" style="font-size:16px;" title="Case Number">
                                #{{ $record->id() }}  <span class="text-secondary">
                                    {{ $record->link_case_with_form->form->name  }}
                                </span></div>
                            <div
                                class="cm_case_status font-weight-bold d-flex align-items-center fw-semibold @if ($record->isArchive == true) text-info @elseif($record->status == 'waiting' && $record->requires_final_approval == true) text-warning @elseif($record->status == 'open') text-success @elseif($record->status == 'closed') text-danger @endif">
                                <i class="fa-solid fa-circle mx-2" style="font-size: 6px;margin-top:3px;"></i>
                                @if ($record->isArchived)
                                    Archived
                                @elseif($record->status == 'waiting' && $record->requires_final_approval == true)
                                    Final Approval
                                @else
                                    {{ $record->status() }}
                                @endif
                            </div>
                        </div>

                        
                        <h2 class="timeline_category_title mb-1" style="font-size:16px;">
                            <span>
                                {{ $record->incident_type  }}
                            </span>
                        </h2>
                        <h6 class=" p-0" style="color: #999;font-weight:normal;font-size:14px;">{{ $record->link_case_with_form->form->note }}</h6>






                        {{-- Start of timeline recores show --}}


                        @php
                            $record = $record->link_case_with_form;
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
                                                                        'is_display_case' => $item['input']['is_display_case'] ?? false,
                                                                    ];
                                                                    break;

                                                                case 'number':
                                                                    $data_objects[] = [
                                                                        'label' => $item['label'],
                                                                        'value' => $value,
                                                                        'is_display_case' => $item['input']['is_display_case']?? false
                                                                    ];
                                                                    break;

                                                                case 'date':
                                                                    $date = date('Y-m-d', strtotime($value));
                                                                    $data_objects[] = [
                                                                        'label' => $item['label'],
                                                                        'value' => $date,
                                                                        'is_display_case' => $item['input']['is_display_case']?? false
                                                                    ];
                                                                    break;

                                                                case 'time':
                                                                    $time = date('H:i', strtotime($value));
                                                                    $data_objects[] = [
                                                                        'label' => $item['label'],
                                                                        'value' => $time,
                                                                        'is_display_case' => $item['input']['is_display_case']?? false
                                                                    ];
                                                                    break;

                                                                case 'radio':
                                                                    $data_objects[] = [
                                                                        'label' => $item['label'],
                                                                        'value' => $value,
                                                                        'is_display_case' => $item['input']['is_display_case']?? false
                                                                    ];
                                                                    break;

                                                                case 'checkbox':
                                                                $data_objects[] = [
                                                                    'label' => $item['label'],
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
                                                                        'label' => $item['label'],
                                                                        'value' => implode(', ', $values),
                                                                        'is_display_case' => $item['input']['is_display_case']?? false
                                                                    ];
                                                                    } else {
                                                                        $data_objects[] = [
                                                                            'label' => $item['label'],
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
                                                                            'label' => $item['label'],
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

                       

                        {{-- end of timeline recoreds show --}}
                        @if (isset($record->recorded_case))
                            <div class="actions-wrap-btn">
                                <a href="{{ route('case_manager.view', $record->recorded_case->id) }}">Go to Case</a>
                                
                            </div>
                        @endif

                    </div>
                    
                    
                    
                    
                </div>
                @endisset
        <!-- Modal -->
        <div class="modal modal-md fade" id="delete_model_{{ $record->id }}" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><span class="text-danger">Why are you deleting this?</span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-2">
                            <form method="post" action="{{ route('location.near_miss.delete', $record->id) }}">
                                @csrf
                                <div class="form-group">
                                    <label>Reason for deleting this near misss?</label>
                                    <input type="text" name="delete_reason" class="form-control" required
                                        value="">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-info">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
@endforeach



<input type="hidden" value="{{ route('location.document.uploadHashed') }}" id="route_document">
<input type="hidden" value="{{ route('location.document.removedHashed') }}" id="route_document_removedHashed">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.content-timeline').each(function() {
                var color = $(this).data('color');
                $(this).css('--location-form-setting-color', color);
            });
        });
    </script>