@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::guard('user')->user();
    $share_cases = $user->share_cases->where('removed_by_user', 0);
@endphp
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
                <div class="content-timeline" data-color="{{ is_null($nearMiss->near_miss_manager_relation()) == false ? $nearMiss->near_miss_manager_relation()->color : '#4dd6f0' }}">
                    
                    @if ($nearMiss->status == 'draft' || $nearMiss->status == 'deleted')
                        <span
                            class="nearmiss_status nearmiss_status_red badge badge-danger center">{{ strtoupper($nearMiss->status) }}</span>
                    @endif
                    @if ($nearMiss->status != 'deleted')
                        <div class="actions" style="display:none">
                            {{-- <a href="{{ route('location.near_miss', $nearMiss->id) }}" title="Edit"
                                class="text-info"><i class="fa fa-edit"></i></a><br> --}}
                            {{-- @if ($nearMiss->canDelete())
                                <a href="#" data-toggle="modal" data-target="#delete_model_{{ $nearMiss->id }}"
                                    title="Delete" class="text-info"><i class="fa fa-trash"></i></a>
                            @endif --}}
                        </div>
                    @endif
                    <p class="fw-bold mb-2 mt-0" style="display: flex; justify-content: space-between; align-items: center; color: var(--location-section-heading-color);">
                        {{ $nearMiss->time() }}
                            @if($nearMiss->what_was_error == 'Prescription')
                            <img src="{{asset('images/prescription-active.png')}}" alt="" style="height: 50px; width:50px;">
                        @elseif($nearMiss->what_was_error == 'Labelling')
                            <img src="{{asset('images/labelling-active.png')}}" alt="" style="height: 50px; width:50px;">

                            @elseif($nearMiss->what_was_error == 'Picking')
                        <img src="{{asset('images/picking-active.png')}}" alt="" style="height: 50px; width:50px;">

                        @elseif($nearMiss->what_was_error == 'Placing into Basket')
                            <img src="{{asset('images/placing_in_basket.png')}}" alt="" style="height: 50px; width:50px;">
                        
                        @elseif($nearMiss->what_was_error == 'Bagging')
                        <img src="{{asset('images/bagging-active.png')}}" alt="" style="height: 50px; width:50px;">
                        
                        @elseif($nearMiss->what_was_error == 'Preparing Dosette Tray')
                        <img src="{{asset('images/desette_tray-active.png')}}" alt="" style="height: 50px; width:50px;">

                        @elseif($nearMiss->what_was_error == 'Handeling Out')
                        <img src="{{asset('images/handing_out-active.png')}}" alt="" style="height: 50px; width:50px;">

                        @else
                            <img src="{{asset('images/labelling-active.png')}}" alt="" style="height: 50px; width:50px;">
                        @endif
                        

                        </p>
                    <p class="near-miss" style="color: gray; font-size: 25px; padding-bottom: 30px">Near Miss</p>
                    <h2 class="timeline_category_title">
                        
                        {{-- <span
                            class="timeline_what_was_error_title {{ strtolower(str_replace(' ', '_', $nearMiss->what_was_error)) }}_title">{{ $nearMiss->what_was_error }}
                            </span> <span>(</span>
                        <span class="timeline_error_title" style="color: #999;">{{ $nearMiss->error() }}  <span>)</span></span></span> --}}
                        <span
                        class="near_miss_error" style="color: #999">{{ $nearMiss->what_was_error }}
                        </span> <span>(</span>
                    <span class="timeline_error_title" style="color: #999;">{{ $nearMiss->error() }}  <span>)</span></span></span>
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
                        <span class="detail-title" style="margin-top: 20px; display: block;">Reason:</span>
                        <ul style="color: rgb(144, 144, 144)">
                            @foreach ($nearMiss->reasons() as $reason)
                                <li>{{ $reason }}</li>
                            @endforeach
                        </ul>
                        @if (count($nearMiss->generateContributingFactorsData()))
                            <span class="detail-title">Contributing Factors:</span>
                        @else
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
            @if(isset($record->form))
                <div
                    class="line nearmiss_hidden   right-record nearmiss_record nearmiss_{{ $record->id }} nearmiss_status_{{ strtolower(str_replace(' ', '_', $record->status)) }}">
                    <div class="date time">{{ $record->created_at->format(config('app.dateFormat')) }}</div>
                    <div class="content-timeline" data-color="{{ isset($record->form->color_code) ? $record->form->color_code : '#4dd6f0' }}">
                        {{-- @dd($record->recorded_case->id) --}}
                        <div class="actions">
                            {{-- @if ($record->created_at > Carbon\Carbon::now()->sub(config('app.incident_edit_capability_time_out')))
                                <a
                                    href="/bespoke_form_v3/#!/edit/{{$record->id}}?case_id={{$record->recorded_case && $record->recorded_case->id}}" target="_blank"
                                    title="Edit" class="text-info"><i class="fa fa-edit"></i></a><br>
                            @endif --}}
                            {{-- <a href="javascript:void(0)" data-id="{{ $record->id }}" target="_blank" title="Updates"
                                class="text-info see_update_btn"><i class="fa fa-wrench"></i></a><br> --}}
                        </div>

                        <p class="fw-bold mb-2 mt-0" style="color: var(--location-section-heading-color)">
                            {{ $record->created_at->format('h:i A') }}</p>
                        <h2 class="timeline_category_title mb-0">
                            <span>
                                {{ $record->form->name }}
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
                        </div>





                        {{-- Start of timeline recores show --}}



                        @php
                                
                                $data_objects = [];
                                if ($record->raw_form) {
                                    $updated_records = $record->all_linked_records()->last();
                                    if (isset($updated_records->raw_form)) {
                                        $questionsJson = json_decode($updated_records->raw_form, true);
                                    } else {
                                        $questionsJson = json_decode($record->raw_form, true);
                                    }
                                    $questionsJson = json_decode($record->raw_form, true);
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

                                <div>
                                    @if(!empty($data_objects))
                                    @foreach ($data_objects as $data)
                                            @if (!empty($data['value']))
                                                <div style="line-height: 15px;">

                                                    <p class="m-0 mt-1" style="font-size:12px; color:gray;line-height: inherit;">
                                                        {{ $data['label'] }}
                                                    </p>
                                                    @if(is_array($data['value']))
                                                    @foreach($data['value'] as $item)
                                                        <p class="m-0" style="font-size: 13px">{{ $item }}</p>
                                                    @endforeach
                                                    @else
                                                        <p class="m-0" style="font-size: 13px">{{ $data['value'] }}</p>
                                                    @endif
                                                </div>
                                            @endif
                                    @endforeach
                                    @else
                                    @endif
                                </div>

                        <div class="actions-wrap-btn">
                            <a href="#" style="box-shadow: none;"><svg title="Delete" width="20"
                                    height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16 6V5.2C16 4.0799 16 3.51984 15.782 3.09202C15.5903 2.71569 15.2843 2.40973 14.908 2.21799C14.4802 2 13.9201 2 12.8 2H11.2C10.0799 2 9.51984 2 9.09202 2.21799C8.71569 2.40973 8.40973 2.71569 8.21799 3.09202C8 3.51984 8 4.0799 8 5.2V6M10 11.5V16.5M14 11.5V16.5M3 6H21M19 6V17.2C19 18.8802 19 19.7202 18.673 20.362C18.3854 20.9265 17.9265 21.3854 17.362 21.673C16.7202 22 15.8802 22 14.2 22H9.8C8.11984 22 7.27976 22 6.63803 21.673C6.07354 21.3854 5.6146 20.9265 5.32698 20.362C5 19.7202 5 18.8802 5 17.2V6"
                                        stroke="#dadada" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg></a>
                                @if($record->form->allow_responder_update)
                            <a href="{{ route('be_spoke_forms.be_spoke_form.records_view', $record->id) }}?tab=updates" style="box-shadow: none;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path stroke="#dadada" d="M4 16.2422C2.79401 15.435 2 14.0602 2 12.5C2 10.1564 3.79151 8.23129 6.07974 8.01937C6.54781 5.17213 9.02024 3 12 3C14.9798 3 17.4522 5.17213 17.9203 8.01937C20.2085 8.23129 22 10.1564 22 12.5C22 14.0602 21.206 15.435 20 16.2422M8 16L12 12M12 12L16 16M12 12V21" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                </a>
                                @endif

                                
                                @if ($record->recorded_case->status !== 'closed')
                                    @if (isset($record->recorded_case->id))
                                    <a href="/bespoke_form_v3/#!/edit/{{$record->all_linked_records()->last()->id}}?case_id={{$record->recorded_case->id}}&location_id={{$location->id}}&rec_id={{$record->id}}"
                                @endif
"
                                style="box-shadow: none;"><svg title="Edit" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M18 10L14 6M2.49997 21.5L5.88434 21.124C6.29783 21.078 6.50457 21.055 6.69782 20.9925C6.86926 20.937 7.03242 20.8586 7.18286 20.7594C7.35242 20.6475 7.49951 20.5005 7.7937 20.2063L21 7C22.1046 5.89543 22.1046 4.10457 21 3C19.8954 1.89543 18.1046 1.89543 17 3L3.7937 16.2063C3.49952 16.5005 3.35242 16.6475 3.24061 16.8171C3.1414 16.9676 3.06298 17.1307 3.00748 17.3022C2.94493 17.4954 2.92195 17.7021 2.87601 18.1156L2.49997 21.5Z"
                                        stroke="#dadada" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg></a>
                                @endif

                            

                                <a href="{{ route('be_spoke_forms.be_spoke_form.records_view',$record->id) }}" style="display: flex; align-items: center; background-color: #3a3939; border: none; border-radius: 5px; padding: 5px 10px; box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3); cursor: pointer; font-size: 12px; text-decoration: none; color: black;">
                                    Full Report
                                    <i class="fas fa-arrow-right" style="margin-left: 4px; font-size: 12px;"></i> <!-- Font Awesome forward arrow -->
                                </a>

                            @if (count($record->getCaseFeedbacks()) != 0)
                                <a href="{{ route('be_spoke_forms.be_spoke_form.records_view',$record->id) }}?tab=feedbacks" style="display: flex; align-items: center; background-color: white; border: 2px solid #000000; border-radius: 5px; padding: 5px 10px; box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3); cursor: pointer; font-size: 12px; text-decoration: none; color: black;">
                                    <svg width="20" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11 4H7.8C6.11984 4 5.27976 4 4.63803 4.32698C4.07354 4.6146 3.6146 5.07354 3.32698 5.63803C3 6.27976 3 7.11984 3 8.8V14C3 14.93 3 15.395 3.10222 15.7765C3.37962 16.8117 4.18827 17.6204 5.22354 17.8978C5.60504 18 6.07003 18 7 18V20.3355C7 20.8684 7 21.1348 7.10923 21.2716C7.20422 21.3906 7.34827 21.4599 7.50054 21.4597C7.67563 21.4595 7.88367 21.2931 8.29976 20.9602L10.6852 19.0518C11.1725 18.662 11.4162 18.4671 11.6875 18.3285C11.9282 18.2055 12.1844 18.1156 12.4492 18.0613C12.7477 18 13.0597 18 13.6837 18H15.2C16.8802 18 17.7202 18 18.362 17.673C18.9265 17.3854 19.3854 16.9265 19.673 16.362C20 15.7202 20 14.8802 20 13.2V13M20.1213 3.87868C21.2929 5.05025 21.2929 6.94975 20.1213 8.12132C18.9497 9.29289 17.0503 9.29289 15.8787 8.12132C14.7071 6.94975 14.7071 5.05025 15.8787 3.87868C17.0503 2.70711 18.9497 2.70711 20.1213 3.87868Z" 
                                            stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <span style="margin-left: 6px; color:black; font-size: 14px;">Feedback</span>
                                    
                                    @if ($user->getCaseFeedbacks()->where('mark_read', 1)->count() == 1)
                                        <svg width="20px" height="20px" viewBox="0 -0.5 25 25" fill="#2BAFA5" xmlns="http://www.w3.org/2000/svg">
                                            <path fill="#2BAFA5" d="M5.03033 11.4697C4.73744 11.1768 4.26256 11.1768 3.96967 11.4697C3.67678 11.7626 3.67678 12.2374 3.96967 12.5303L5.03033 11.4697ZM8.5 16L7.96967 16.5303C8.26256 16.8232 8.73744 16.8232 9.03033 16.5303L8.5 16ZM17.0303 8.53033C17.3232 8.23744 17.3232 7.76256 17.0303 7.46967C16.7374 7.17678 16.2626 7.17678 15.9697 7.46967L17.0303 8.53033ZM9.03033 11.4697C8.73744 11.1768 8.26256 11.1768 7.96967 11.4697C7.67678 11.7626 7.67678 12.2374 7.96967 12.5303L9.03033 11.4697ZM12.5 16L11.9697 16.5303C12.2626 16.8232 12.7374 16.8232 13.0303 16.5303L12.5 16ZM21.0303 8.53033C21.3232 8.23744 21.3232 7.76256 21.0303 7.46967C20.7374 7.17678 20.2626 7.17678 19.9697 7.46967L21.0303 8.53033ZM3.96967 12.5303L7.96967 16.5303L9.03033 15.4697L5.03033 11.4697L3.96967 12.5303ZM9.03033 16.5303L17.0303 8.53033L15.9697 7.46967L7.96967 15.4697L9.03033 16.5303ZM7.96967 12.5303L11.9697 16.5303L13.0303 15.4697L9.03033 11.4697L7.96967 12.5303ZM13.0303 16.5303L21.0303 8.53033L19.9697 7.46967L11.9697 15.4697L13.0303 16.5303Z" fill="#000000"/>
                                        </svg>
                                    @endif

                                    
                                </a>
                            @endif
                        </div>
                    </div>



                </div>
            @endif
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
        <?php $counter++; ?>
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
<script src="{{asset('js/alertify.min.js')}}"></script>
@if(Session::has('success'))
<script>
    alertify.success("{{ Session::get('success') }}");
</script>
@elseif(Session::has('error'))
<script>
alertify.success("{{ Session::get('error') }}");
</script>
@endif