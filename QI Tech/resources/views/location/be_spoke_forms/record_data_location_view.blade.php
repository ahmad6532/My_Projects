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
        @if ($record->getTable() == 'near_misses')
            @php
                $nearMiss = $record;
            @endphp
            <div
                class="line nearmiss-timeline nearmiss_hidden   right-record  nearmiss_record nearmiss_{{ $nearMiss->id }} nearmiss_status_{{ strtolower(str_replace(' ', '_', $nearMiss->status)) }}">
                {{-- <div class="date time">{{ $nearMiss->time() }}</div> --}}
                @if ($nearMiss->status == 'deleted')
                    <div class="nearmiss_mini">
                        <span class=" badge badge-danger"><i class="fa fa-eye"></i>
                            {{ strtoupper($nearMiss->status) }}<br>
                            <a href="#" style="display:none" class="click_to_view">Click to view</a>
                        </span>

                    </div>
                @endif
                <div class="content-timeline">
                    @if ($nearMiss->status == 'draft' || $nearMiss->status == 'deleted')
                        <span
                            class="nearmiss_status nearmiss_status_red badge badge-danger center">{{ strtoupper($nearMiss->status) }}</span>
                    @endif
                    @if ($nearMiss->status != 'deleted')
                        <div class="actions" style="display:none">
                            <a href="{{ route('location.near_miss', $nearMiss->id) }}" title="Edit"
                                class="text-info"><i class="fa fa-edit"></i></a><br>
                            @if ($nearMiss->canDelete())
                                <a href="#" data-toggle="modal" data-target="#delete_model_{{ $nearMiss->id }}"
                                    title="Delete" class="text-info"><i class="fa fa-trash"></i></a>
                            @endif
                        </div>
                    @endif
                    <p class="fw-bold mb-2 mt-0" style="color: var(--location-section-heading-color)">
                        {{ $nearMiss->time() }}</p>
                    <p class="fw-bold mb-2 mt-0">Near Miss</p>
                    <h2 class="timeline_category_title">
                        <img class="timeline_icon icon" style="position: relative;left:unset;margin-right:8px"
                            src="{{ asset('images/' . $nearMiss->icon()) }}" width="28">
                        <span
                            class="timeline_what_was_error_title {{ strtolower(str_replace(' ', '_', $nearMiss->what_was_error)) }}_title">{{ $nearMiss->what_was_error }}
                            - </span>
                        <span class="timeline_error_title" style="color: #999;">{{ $nearMiss->error() }}</span>
                    </h2>
                    @if ($nearMiss->hasDrugsData())
                        <p class="timeline-drugs-data">{!! $nearMiss->generateDrugsData() !!}</p>
                    @endif
                    @if ($nearMiss->status == 'deleted')
                        <p><span class="detail-title">Deleted By: </span> {{ $nearMiss->deletedBy() }}</p>
                        <p><span class="detail-title">Deleted At: </span>
                            {{ date('d/m/Y h:i a', strtotime($nearMiss->deleted_timestamp)) }}</p>
                        <p><span class="detail-title">Deleted Reason: </span> {{ $nearMiss->delete_reason }}</p>
                    @endif
                    <div class="details details_{{ $nearMiss->id }}" style="display:none">
                        @if ($location->near_miss_prescirption_dispensed_at_hub)
                            <p><span class="detail-title">Prescriptions Dispensed: </span>
                                {{ $nearMiss->dispensed_at_hub }}</p>
                        @endif
                        <p><span class="detail-title">Point of Detection: </span> {{ $nearMiss->point_of_detection }}
                        </p>
                        <p><span class="detail-title">Error By: </span> {{ $nearMiss->errorBy() }}</p>
                        <p><span class="detail-title">Error Detected By: </span> {{ $nearMiss->errorDetectedBy() }}</p>
                        <span class="detail-title">Reason:</span>
                        <ul>
                            @foreach ($nearMiss->reasons() as $reason)
                                <li>{{ $reason }}</li>
                            @endforeach
                        </ul>
                        <span class="detail-title">Contributing Factors:</span>
                        @if (!count($nearMiss->generateContributingFactorsData()))
                            No Factors Found
                        @endif
                        <div class="contributing-factors">
                            @foreach ($nearMiss->generateContributingFactorsData() as $title => $item)
                                {{ $title }}
                                <ul>
                                    @foreach ($item as $checkbox)
                                        <li>{{ $checkbox }}</li>
                                    @endforeach
                                </ul>
                            @endforeach

                        </div>
                    </div>
                    <p class="see_details">
                        <a href="#" data-id="{{ $nearMiss->id }}"
                            class=" see_details_btn see_details_btn_{{ $nearMiss->id }} ">Expand</a>
                        {{-- <a href="#" style="display:none" data-id="{{ $nearMiss->id }}"
                class="text-info show_less_btn show_less_btn_{{ $nearMiss->id }} ">Show Less</a> --}}
                        @if ($nearMiss->status == 'deleted')
                            <a href="#" data-id="{{ $nearMiss->id }}"
                                class="text-info hide_deleted_btn hide_deleted_btn_{{ $nearMiss->id }} ">| Hide</a>
                        @endif
                    </p>
                    <div class="d-flex w-100 justify-content-end">
                        <a href="#" style="display:none; color:#5887de !important;margin-right:60px;"
                            data-id="{{ $nearMiss->id }}"
                            class="text-info show_less_btn show_less_btn_{{ $nearMiss->id }} ">Show Less</a>
                    </div>
                    <div class="actions-wrap-btn">

                        <a href="{{ route('location.near_miss.delete_near', ['id'=>$nearMiss->id,'_token'=> csrf_token()]) }}"
                            style="box-shadow: none;"><svg title="Delete" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                    stroke="#dadada" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <a href="{{ route('location.near_miss', $nearMiss->id) }}" style="box-shadow: none;"><svg
                                title="Edit" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M18 10L14 6M2.49997 21.5L5.88434 21.124C6.29783 21.078 6.50457 21.055 6.69782 20.9925C6.86926 20.937 7.03242 20.8586 7.18286 20.7594C7.35242 20.6475 7.49951 20.5005 7.7937 20.2063L21 7C22.1046 5.89543 22.1046 4.10457 21 3C19.8954 1.89543 18.1046 1.89543 17 3L3.7937 16.2063C3.49952 16.5005 3.35242 16.6475 3.24061 16.8171C3.1414 16.9676 3.06298 17.1307 3.00748 17.3022C2.94493 17.4954 2.92195 17.7021 2.87601 18.1156L2.49997 21.5Z"
                                    stroke="#dadada" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            </i></a>
                    </div>
                    <!-- <p class="see_details">
                
            </p> -->
                </div>
            </div>
        @else
            <div
                class="line nearmiss_hidden   right-record nearmiss_record nearmiss_{{ $record->id }} nearmiss_status_{{ strtolower(str_replace(' ', '_', $record->status)) }}">
                <div class="date time">{{ $record->created_at->format(config('app.dateFormat')) }}</div>
                @isset($record->form)
                    <div class="content-timeline" data-color="{{ isset($record->form->color_code) ? $record->form->color_code : '#4dd6f0' }}">
                        {{-- @dd($record->recorded_case->id) --}}
                        <div class="actions">
                            @if ($record->created_at > Carbon\Carbon::now()->sub(config('app.incident_edit_capability_time_out')))
                                <a
                                    href="/bespoke_form_v3/#!/edit/{{$record->id}}?case_id={{$record->recorded_case && $record->recorded_case->id}}" target="_blank"
                                    title="Edit" class="text-info"><i class="fa fa-edit"></i></a><br>
                            @endif
                            {{-- <a href="javascript:void(0)" data-id="{{ $record->id }}" target="_blank" title="Updates"
                                class="text-info see_update_btn"><i class="fa fa-wrench"></i></a><br> --}}
                            @if (count($record->getCaseFeedbacks()) != 0)
                                <a href="{{ route('be_spoke_forms.be_spoke_form.records_view', ['id' => $record->id]) }}"
                                    title="feedback provide by company" class="btn m-0 p-0 b-0">
                                    <svg width="20" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11 4H7.8C6.11984 4 5.27976 4 4.63803 4.32698C4.07354 4.6146 3.6146 5.07354 3.32698 5.63803C3 6.27976 3 7.11984 3 8.8V14C3 14.93 3 15.395 3.10222 15.7765C3.37962 16.8117 4.18827 17.6204 5.22354 17.8978C5.60504 18 6.07003 18 7 18V20.3355C7 20.8684 7 21.1348 7.10923 21.2716C7.20422 21.3906 7.34827 21.4599 7.50054 21.4597C7.67563 21.4595 7.88367 21.2931 8.29976 20.9602L10.6852 19.0518C11.1725 18.662 11.4162 18.4671 11.6875 18.3285C11.9282 18.2055 12.1844 18.1156 12.4492 18.0613C12.7477 18 13.0597 18 13.6837 18H15.2C16.8802 18 17.7202 18 18.362 17.673C18.9265 17.3854 19.3854 16.9265 19.673 16.362C20 15.7202 20 14.8802 20 13.2V13M20.1213 3.87868C21.2929 5.05025 21.2929 6.94975 20.1213 8.12132C18.9497 9.29289 17.0503 9.29289 15.8787 8.12132C14.7071 6.94975 14.7071 5.05025 15.8787 3.87868C17.0503 2.70711 18.9497 2.70711 20.1213 3.87868Z"
                                            stroke="#2BAFA5" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </a>
                            @endif
                        </div>

                        <p class="fw-bold mb-2 mt-0" style="color: var(--location-section-heading-color)">
                            {{ $record->created_at->format('h:i A') }}</p>
                        <h2 class="timeline_category_title mb-0">
                            <span>
                                {{ $record->form->name  }}
                            </span>
                            {{-- <img class="timeline_icon icon" src="{{ asset('images/prescription_timeline.png') }}" width="32"> --}}
                            {{-- <span
                        class="timeline_what_was_error_title {{ strtolower(str_replace(' ', '_', $record->location->trading_name)) }}_title">{{
                        $record->location->trading_name }}</span> --}}
                        </h2>
                        <h6 class=" p-0" style="color: #999;font-weight:normal">{{ $record->form->note }}</h6>

                        <div class="details details_{{ $record->id }}" style="display:none">

                            @foreach ($record->data as $data)
                                @if ($data->question)
                                    <p>
                                        <span class="detail-title"> {{ $data->question->question_name }}: </span>
                                        {{ $data->question_value }}
                                    </p>
                                    <br>
                                @endif
                            @endforeach
                            @if ($record->recorded_case && count($record->recorded_case->root_cause_analysis) > 0)
                                @foreach ($record->recorded_case->root_cause_analysis as $request)
                                    @if (!$request->status)
                                        <a target="_blank"
                                            href="{{ route('be_spoke_forms.be_spoke_form.root_cause_analysis_request', [$request->id, $request->type]) }}">{{ $request->root_cause_analysis_type }}</a>
                                    @endif
                                @endforeach
                            @endif
                            @if (count($record->updates))
                                <div class="">
                                    {{-- <div class="card-header">
                            Updates
                        </div> --}}
                                    <div class="">
                                        @foreach ($record->updates as $key => $update)
                                            <div data-comment="{{ $update->id }}"
                                                class="cm_comment card @if ($key % 2 == 0) cm_comment_grey @endif">
                                                <div class="cm_comment_author_date">
                                                    <b>{{ optional($update->user)->name }}</b> Updated
                                                    {{ $update->created_at->diffForHumans() }}
                                                    <span
                                                        class="float-right">{{ $update->created_at->format(config('app.dateFormat')) }}
                                                        {{ $update->created_at->format(config('app.timeFormat')) }}</span>
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
                                                                    <i class="fa fa-image"></i> @else<i
                                                                            class="fa fa-link"></i>
                                                                    @endif
                                                                    {{ $doc->document->original_file_name() }}
                                                                    @if ($doc->type == 'image')
                                                                        <div class="cm_image_hover">
                                                                            <div class="card shadow">
                                                                                <div class="card-body">
                                                                                    <img class="image-responsive"
                                                                                        width="300"
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
                                    </div>
                                </div>
                            @endif
                            <div class="card update_card_{{ $record->id }}" style="display: none;">
                                <div class="card-header">
                                    New Update
                                </div>
                                <div class="card-body">
                                    <form class="cm_task_form" method="post"
                                        action="{{ route('be_spoke_forms.be_spoke_form.update', $record->id) }}">
                                        @csrf
                                        <div class="form-group">
                                            <label>Data</label>
                                            <input type="text" name="update" class="form-control" required>
                                        </div>
                                        <div class="uploaded_files mt-2 mb-2">
                                        </div>
                                        {{-- <div class="uploaded_files mt-2 mb-2">
                                @foreach ($record->documents as $doc)
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
                            </div> --}}
                                        <h6 class="text-info">Select documents/images to upload</h6>
                                        <div class="cm_upload_box_with_model center">
                                            <i class="fa fa-cloud-upload-alt" style="font-size:48px"></i><br>Drop files
                                            here
                                        </div>
                                        <input type="file" name="file" multiple value=""
                                            class="form-control commentMultipleFiles">
                                        <button type="submit" class="btn btn-info">Save</button>
                                    </form>
                                </div>
                            </div>
                        </div>





                        {{-- Start of timeline recores show --}}



                        @php
                            $data_objects = [];
                            if ($record->raw_form) {
                                $questionsJson = json_decode($record->raw_form, true);
                                if ($questionsJson['pages'] && count($questionsJson['pages']) > 0) {
                                    foreach ($questionsJson['pages'] as $page) {
                                        if ($page['items'] && count($page['items']) > 0) {
                                            foreach ($page['items'] as $item) {
                                                if (
                                                    isset(
                                                        $item['label'],
                                                        $item['input'],
                                                        $item['input']['value'],
                                                        $item['input']['type'],
                                                        $item['input']['is_display_summary'],
                                                    ) &&
                                                    $item['input']['is_display_summary']
                                                ) {
                                                    $type = $item['input']['type'];
                                                    $value = $item['input']['value'];

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
                                                            $data_objects[] = ['label' => $item['label'], 'value' => $date];
                                                            break;

                                                        case 'time':
                                                            $time = date('H:i', strtotime($value));
                                                            $data_objects[] = ['label' => $item['label'], 'value' => $time];
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
                                                                'value' => implode(', ', (array) $value),
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

                        <div class="d-flex flex-column gap-2 mt-3">
                            @foreach ($data_objects as $data)
                                <div style="line-height: 10px;">

                                    <p class="" style="font-size:12px; color:gray;line-height: inherit;">
                                        {{ $data['label'] }}
                                    </p>
                                    <p class="">
                                        {{ $data['value'] }}
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        {{-- end of timeline recoreds show --}}
                        <div class="actions-wrap-btn">
                            <a href="#" style="box-shadow: none;"><svg title="Delete" width="20"
                                    height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                        stroke="#dadada" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg></a>
                            <a href="{{ route('be_spoke_forms.be_spoke_form.records_view', ['id' => $record->id]) }}"
                                style="box-shadow: none;"><svg title="Edit" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M18 10L14 6M2.49997 21.5L5.88434 21.124C6.29783 21.078 6.50457 21.055 6.69782 20.9925C6.86926 20.937 7.03242 20.8586 7.18286 20.7594C7.35242 20.6475 7.49951 20.5005 7.7937 20.2063L21 7C22.1046 5.89543 22.1046 4.10457 21 3C19.8954 1.89543 18.1046 1.89543 17 3L3.7937 16.2063C3.49952 16.5005 3.35242 16.6475 3.24061 16.8171C3.1414 16.9676 3.06298 17.1307 3.00748 17.3022C2.94493 17.4954 2.92195 17.7021 2.87601 18.1156L2.49997 21.5Z"
                                        stroke="#dadada" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg></a>
                        </div>

                    </div>
                    
                @endisset



            </div>
        @endif
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