@extends('layouts.head_office_app')
@section('title', 'Case ' . $case->id())

@section('sub-header')
    @include('head_office.case_manager.notes.sub-header')
@endsection

@section('content')

    <div id="content">
        @include('layouts.error')
        <div class="card card-qi content_widthout_sidebar">
            <style>
                .form-group>label {
                    font-size: 12px;
                    position: relative;
                    top: 0;
                    left: 0;
                }
            </style>
            <div class="card-body">
                <div class="cm_content pt-2">
                    <div class="row">
                        <div class="col-sm-8 normal-nav-hide">
                            <nav class="nav nav-tabs nav-h-bordered">
                                <a href="#" class="active" data-bs-toggle="tab" id="reported" data-bs-target="#report"><span
                                        class="item_with_border">Report</span></a>
                                    <a href="#" data-bs-toggle="tab" id="modificationed" data-bs-target="#modification"><span
                                            class="item_with_border">Modification</span></a>
                                    <a href="#" data-bs-toggle="tab" data-bs-target="#originaled"><span
                                            class="item_with_border">Original</span></a>
                                @if (count($case->link_case_with_form->updates))
                                    <a href="#" data-bs-toggle="tab" id="updatesed" data-bs-target="#updates"><span
                                            class="item_with_border">Updates</span></a>
                                @endif
                            </nav>
                        </div>
                        @php
                        use Illuminate\Support\Facades\Auth;
                        $headOffice = Auth::guard('web')->user()->selected_head_office;
                            $record = $case->link_case_with_form;
                            $updated_records = $record->all_linked_records()->last();
                            if (isset($updated_records->raw_form)) {
                                $questionsJson = json_decode($updated_records->raw_form, true);
                                $updated_form = $updated_records;
                            } else {
                                $questionsJson = json_decode($record->raw_form, true);
                                $updated_form = $record;
                            }
                        @endphp
                        <div class="col-sm-4 normal-nav-hide">
                            <button style="float: right" class="primary-btn dropdown-toggle inline-block" type="button"
                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">Actions</button>
                            <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
                                <a href="#" class="dropdown-item" onclick="event.preventDefault(); window.print();" title="Print Report">Print</a>

                                {{-- <a href="#" class="dropdown-item" title="Download Report">Download</a> --}}
                                @if ($case->isArchived == false && $record->recorded_case->status !== 'closed')
                                    <a href="/bespoke_form_v3/#!/edit/{{$updated_form->id}}?case_id={{$case->id}}&company_id={{$headOffice->id}}" class="dropdown-item"
                                        title="Edit Report">Edit Report </a>
                                @endif

                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="tab-content" id="myTabContent">
                                <div id="report" class="cm_case_tasks scrollbar_custom_green relative tab-pane active">
                                    <div class="col-sm-12 normal-hide">
                                        <div class="card">
                                            <div class="card-body">
                                                <div>
                                                    <img class="center" src="{{ asset('images/svg/black.svg') }}"
                                                        width="200">

                                                </div>
                                                <h5>
                                                    <b> {{ $case->link_case_with_form->form->name }}</b>
                                                </h5>
                                                <h6>
                                                    <b> Complete Report</b>
                                                </h6>
                                                <div>Case ID: <b>{{ $case->id() }}</b></div>

                                                <div>Location: <b>{{ $case->location_name }}</b></div>
                                                @php
                                                    use Carbon\Carbon;
                                                    

                                                @endphp
                                                <div>Printed By: <b>{{ Auth::guard('web')->user()->name }}</b></div>
                                                <div>Printed Date: <b>{{ Carbon::now()->format(config('app.dateFormat')) }}
                                                        {{ Carbon::now()->format(config('app.timeFormat')) }}</b></div>
                                            </div>
                                        </div>
                                    </div>


                                    @php
                                        $jsonData = isset($case->link_case_with_form->json_submission)
                                            ? json_decode($case->link_case_with_form->json_submission, true)
                                            : null;
                                    @endphp


                                    @if ($questionsJson)
                                        @php
                                            $data_objects = [];
                                            if ($record->raw_form) {
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
                                                                    $value = $item['input']['value'];

                                                                    switch ($type) {
                                                                        case 'text':
                                                                        case 'email':
                                                                        case 'textarea':
                                                                            $data_objects[] = [
                                                                                'label' => $item['name'],
                                                                                'value' => $value,
                                                                            ];
                                                                            break;

                                                                        case 'number':
                                                                            $data_objects[] = [
                                                                                'label' => $item['name'],
                                                                                'value' => $value,
                                                                            ];
                                                                            break;

                                                                        case 'date':
                                                                            $date = date('Y-m-d', strtotime($value));
                                                                            $data_objects[] = [
                                                                                'label' => $item['name'],
                                                                                'value' => $date,
                                                                            ];
                                                                            break;

                                                                        case 'time':
                                                                            $time = date('H:i', strtotime($value));
                                                                            $data_objects[] = [
                                                                                'label' => $item['name'],
                                                                                'value' => $time,
                                                                            ];
                                                                            break;

                                                                        case 'radio':
                                                                            $data_objects[] = [
                                                                                'label' => $item['name'],
                                                                                'value' => $value,
                                                                            ];
                                                                            break;

                                                                        case 'checkbox':
                                                                            $data_objects[] = [
                                                                                'label' => $item['name'],
                                                                                'value' => implode(', ', (array) $value),
                                                                            ];
                                                                            break;

                                                                        case 'select':
                                                                            if (is_array($value)) {
                                                                                $data_objects[] = [
                                                                                    'label' => $item['name'],
                                                                                    'value' => isset($value['val'])
                                                                                        ? $value['val']
                                                                                        : (isset($value['text'])
                                                                                            ? $value['text']
                                                                                            : ' '),
                                                                                ];
                                                                            } else {
                                                                                $data_objects[] = [
                                                                                    'label' => $item['name'],
                                                                                    'value' => $value,
                                                                                ];
                                                                            }
                                                                            break;

                                                                        case 'dmd':
                                                                            $records = $item['input']['records'] ?? [];
                                                                            $dmd_values = [];
                                                                            foreach ($records as $record2) {
                                                                                $vtm =
                                                                                    $record2['vtm']['vtm_string'] ?? '';
                                                                                $vmp =
                                                                                    $record2['vmp']['vp_string'] ?? '';
                                                                                $other = $record2['other'] ?? '';
                                                                                $dmd_values[] = implode(
                                                                                    ', ',
                                                                                    array_filter([$vtm, $vmp, $other]),
                                                                                );
                                                                            }
                                                                            if (!empty($dmd_values)) {
                                                                                $data_objects[] = [
                                                                                    'label' => $item['name'],
                                                                                    'value' => implode(
                                                                                        '; ',
                                                                                        $dmd_values,
                                                                                    ),
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
                                    @endif

                                    <div class="col-sm-12">
                                        <div class="card pb-4">
                                            {{-- <div class="card-body intelligence-container">
                                                @if (isset($data_objects)&&!empty($data_objects))


                                                    <div class="card stages " style="border: none;">

                                                        <div class="card-body">
                                                            <div class="row">
                                                                @foreach ($data_objects as $index => $data)
                                                                    <div class="col-md-6">
                                                                        <div
                                                                            class="form-group question_{{ $index }}">
                                                                            <label
                                                                                for="question_{{ $index }}">{{ $data['label'] }}</label>
                                                                            <input type="text" readonly
                                                                                class="form-control shadow-none"
                                                                                value="{{ $data['value'] }}"
                                                                                title="" />
                                                                        </div>
                                                                    </div>
                                                                @endforeach

                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div> --}}
                                                
                                                @include('head_office.case_manager.notes.angular_form_emed')

                                        </div>
                                    </div>










                                </div>
                                @if (count($record->get_modifications(true)) != 0)
                                
                                    <div id="modification"
                                        class="fade scrollbar_custom_green relative tab-pane modification">
                                        @if (count($record->get_modifications(true)) != 0)

                                        <div>
                                            @php $modifications = $record->get_modifications(true); @endphp
                                            @foreach ($modifications as $modification)
                                                @php
                                                    $json = isset($modification->modified_data)
                                                        ? json_decode($modification->modified_data, true)
                                                        : [];
                                                @endphp
                                                @foreach ($json as $id => $changes)
                                                    <div data-bs-comment="{{ $modification->id }}" class="cm_comment card cm_comment_grey">
                                                        <div class="cm_comment_author_date">
                                                            <b>{{ $modification->user->name }}</b> Modified
                                                            {{ $modification->created_at->diffForHumans() }}
                                                            <span
                                                                class="float-right">{{ $modification->created_at->format('d M Y (D) h:i a') }}</span>
                                                        </div>

                                                        <div class="cm_comment_comment">
                                                            <b>Field:</b> &nbsp; {{ $changes['original']['label'] ?? 'Unknown' }}
                                                        </div>
                                                        @php
                                                        $original_value = isset($changes['original']['value']) ? $changes['original']['value'] : null;
                                                        $modified_value = isset($changes['modified']['value']) ? $changes['modified']['value'] : null;

                                                        $details = $record->get_question_details_by_id($id,$modification->parent_record_id, $original_value);
                                                        $result = is_array($details) 
                                                            ? (!empty($details) ? implode(', ', $details) : 'N/A') 
                                                            : ($details ?? 'N/A');

                                                            $modified_details = $record->get_question_details_by_id($id,$modification->parent_record_id, $modified_value);
                                                            $modified_result = is_array($modified_details)
                                                                ? (!empty($modified_details) ? implode(', ', $modified_details) : 'N/A')
                                                                : ($modified_details ?? 'N/A');
                                                        @endphp



                                                        <div class="cm_comment_comment">
                                                            <b>Original:</b> &nbsp; {{ $result }}
                                                            <span style="font-size: 14px;">(Modified on:
                                                                {{ $modification->created_at->format('d M Y (D) h:i a') }})</span>
                                                        </div>

                                                        <div class="cm_comment_comment">
                                                            <b>Modified:</b> &nbsp; {{$modified_result}}
                                                            <span style="font-size: 14px;">(Modified on:
                                                                {{ $modification->created_at->format('d M Y (D) h:i a') }})</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="mt-4 ms-3">No modifications!</p>
                                    @endif
                                        
                                    </div>
                                    <div id="original" class="cm_case_tasks scrollbar_custom_green relative tab-pane">
                                        @php
                                            $questionsJson = json_decode($record->raw_form, true);
                                        @endphp
                                        @if ($questionsJson)
                                        @php
                                            $data_objects = [];
                                            if ($record->raw_form) {
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
                                                                                $vtm =
                                                                                    $record2['vtm']['vtm_string'] ?? '';
                                                                                $vmp =
                                                                                    $record2['vmp']['vp_string'] ?? '';
                                                                                $other = $record2['other'] ?? '';
                                                                                $dmd_values[] = implode(
                                                                                    ', ',
                                                                                    array_filter([$vtm, $vmp, $other]),
                                                                                );
                                                                            }
                                                                            if (!empty($dmd_values)) {
                                                                                $data_objects[] = [
                                                                                    'label' => $item['label'],
                                                                                    'value' => implode(
                                                                                        '; ',
                                                                                        $dmd_values,
                                                                                    ),
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
                                    @endif

                                    <div class="col-sm-12">
                                        <div class="card">
                                            <div class="card-body intelligence-container">
                                                @if (isset($data_objects)&&!empty($data_objects))


                                                    <div class="card stages " style="border: none;">

                                                        <div class="card-body">
                                                            <div class="row">
                                                                @foreach ($data_objects as $index => $data)
                                                                    <div class="col-md-6">
                                                                        <div
                                                                            class="form-group question_{{ $index }}">
                                                                            <label
                                                                                for="question_{{ $index }}">{{ $data['label'] }}</label>
                                                                            <input type="text" readonly
                                                                                class="form-control shadow-none"
                                                                                value="{{ $data['value'] }}"
                                                                                title="" />
                                                                        </div>
                                                                    </div>
                                                                @endforeach

                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                @endif
                                @if (count($case->link_case_with_form->updates))
                                    <div id="updates" class="fade scrollbar_custom_green relative tab-pane updates">
                                        <div class="col-sm-12 normal-hide">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5>
                                                        <b> {{ $case->link_case_with_form->form->name }}</b>
                                                    </h5>
                                                    <h6>
                                                        <b> Updates</b>
                                                    </h6>
                                                    <div>Case ID: <b>{{ $case->id() }}</b></div>

                                                    <div>Location: <b>{{ $case->location_name }}</b></div>

                                                    <div>Printed By: <b>{{ Auth::guard('web')->user()->name }}</b></div>
                                                    <div>Printed Date:
                                                        <b>{{ Carbon::now()->format(config('app.dateFormat')) }}
                                                            {{ Carbon::now()->format(config('app.timeFormat')) }}</b>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-12">

                                                    <div class="case-intelligence-container">
                                                        @php $updates = $case->link_case_with_form->updates()->orderBy('id','desc')->get() @endphp

                                                        @foreach ($updates as $key => $update)
                                                            <div data-bs-comment="{{ $update->id }}"
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
                                                                            <span data-bs-toggle="tooltip" title=""
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
                                            </div>
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
@endsection

@section('styles')

    <link rel="stylesheet" href="{{ asset('tribute/tribute.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .context-menu {
            position: absolute;
            z-index: 2;
        }

        .context-menu ul {
            list-style: none;
        }

        .menu_comment_link {
            display: flex;
            flex-direction: column;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgb(64 64 64 / 5%);
            padding: 10px 0;
        }

        .menu_comment_link>li>a {
            font: inherit;
            border: 0;
            padding: 10px 30px 10px 15px;
            width: 100%;
            display: flex;
            align-items: center;
            position: relative;
            text-decoration: unset;
            color: #000;
            font-weight: 500;
            transition: 0.5s linear;
            -webkit-transition: 0.5s linear;
            -moz-transition: 0.5s linear;
            -ms-transition: 0.5s linear;
            -o-transition: 0.5s linear;
        }

        .menu_comment_link>li>a:hover {
            background: #f1f3f7;
            color: #4b00ff;
        }

        .menu_comment_link>li>a>i {
            padding-right: 10px;
        }

        .menu_comment_link>li.trash>a:hover {
            color: red;
        }

        .normal-hide {

            display: none;
        }


        @media print {

            .normal-hide {
                display: block;
            }

            .normal-nav-hide {
                display: none;
            }
        }

        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
@endsection
@section('scripts')
    <script src="{{ asset('admin_assets/js/view_case.js') }}"></script>
    <script src="{{ asset('tribute/tribute.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('admin_assets/speech-to-text.js') }}"></script>
<script>
    $(document).ready(function(){
        loadActiveTab()
        if (window.location.search.split('=')[1] != undefined) {
            console.log(window.location.search.split('=')[1]);
                changeTabUrl(window.location.search.split('=')[1])
                $('#'+window.location.search.split('=')[1]).click()
            }
    })
    function changeTabUrl(tabId, subTabId = null) {
            const currentURL = new URL(window.location.href);
            currentURL.searchParams.set('tab', tabId);
            // if(subTabId !== null){
            //     currentURL.searchParams.set('subTab',subTabId);
            // }
            window.history.pushState({
                tabId: tabId
            }, null, currentURL.href);

            $('#' + tabId).tab('show');

            // if(subTabId !== null){
            //     $('#'+subTabId).tab('show');
            // }
        }
        function loadActiveTab(tab = null) {
            if (tab == null) {
                tab = window.location.hash;
            }
            $('.main_header > li > a[data-bs-target="' + tab + '"]').tab('show');
        }
</script>
@endsection
