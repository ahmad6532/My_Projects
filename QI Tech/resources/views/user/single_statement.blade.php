@extends('layouts.users_app')
@section('title', 'user statement')
@section('sidebar')
    @include('layouts.user.sidebar-header')
@endsection
@section('content')


    <div class="profile-center-area">
        <div style="display: flex; justify-content: center; align-items: center; margin-top:-42px">
            <div class="content-page-heading">
                Information Request {{ $case_request_information->case->id }}
            </div>
            {{-- <div style="position: absolute;left: 40px;" class="search">
            <input type="search" placeholder="Search" />
            <i style="margin-left: -25px; color: #777;" class="fa fa-search icon"></i>
        </div> --}}

        </div>


















        <nav class="nav nav-tabs main_header nav-h-bordered d-flex align-items-center justify-content-start gap-2">
            <a class="active"  style="cursor: pointer;" data-bs-toggle="tab" data-bs-target="#details"><span class="item_with_border">Details</span></a>
            @if ($case_request_information->is_available_to_person == 1)
                <a style="cursor: pointer;" data-bs-toggle="tab" data-bs-target="#view_form"><span class="item_with_border">View Form</span> </a>
            @endif
            <a style="cursor: pointer;" data-bs-toggle="tab" data-bs-target="#submit_information"><span class="item_with_border">Submit
                    Information</span></a>
        </nav>


        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active " id="details">
                <img style="width: 100px"
                    src={{ $case_request_information->case->case_head_office->getLogoAttribute() }}></img>

                <div>{{ $case_request_information->created_at->diffForHumans() }}</div>

                <div>Requested By: {{ $case_request_information->first_name }} {{ $case_request_information->last_name }}
                </div>
                <div>
                    Reason for requesting:
                    {{ isset($case_request_information->note) ? $case_request_information->note : 'No description available' }}
                </div>
                <div class="uploaded_files mt-2 mb-2">
                    @if(isset($case_request_information) && $case_request_information->attachment)
                        <li>
                            <span class="fa fa-file"></span>&nbsp;{{ basename($case_request_information->attachment) }}
                            <a href="{{ asset('storage/' . $case_request_information->attachment) }}" 
                                target="_blank" title="Preview" class="preview_btn">
                                 <span class="fa fa-eye"></span>
                             </a>
                             
                        </li>
                    @endif
                </div>
            </div>
            @if ($case_request_information->is_available_to_person == 1)
                <div class="tab-pane fade" id="view_form">
                    <div class="col-sm-12 normal-hide">
                        @php
                        $record = $case_request_information->case->link_case_with_form;
                        $updated_records = $record->all_linked_records()->last();
                        if (isset($updated_records->raw_form)) {
                            $questionsJson = json_decode($updated_records->raw_form, true);
                        } else {
                            $questionsJson = json_decode($record->raw_form, true);
                        }
                        $data_objects = [];
                        if ($record->raw_form) {
                            $questionIds = json_decode($case_request_information->question_ids, true);
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
                        @foreach ($data_objects as $index => $data)
                                                    <div class="col-md-6">
                                                        <div class="form-group question_{{ $index }}">
                                                            <div style="margin-bottom: 3px;"> <!-- Margin for label -->
                                                                <label for="question_{{ $index }}">{{ $data['label'] }}</label>
                                                            </div>
                                                            <div style="margin-top: 3px;"> <!-- Margin for input -->
                                                                <input type="text" readonly class="form-control" value="{{ $data['value'] }}" title="" />
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                @endforeach
                    </div>
                    </div>
                </div>
            @endif

            <div class="tab-pane fade" id="submit_information">
                <div class="row">
                    <div class="col-sm-12">
                        @foreach ($case_request_information->case->link_case_with_form->form->stages as $key => $stage)
                            <div class="card stages stage_{{ $stage->id }} stage_data_{{ $key + 1 }}">

                                <div class="card-body">
                                    <h5>{{ $stage->stage_name }}</h5>
                                    @foreach ($stage->groups as $group)
                                        <div class=" group group_{{ $group->id }}">
                                            <div class="">
                                                <h5 class="form-group-name">{{ $group->group_name }}</h5>
                                                <div class="row">
                                                    @foreach ($group->questions as $question)
                                                        @php $value = $case_request_information->case->link_case_with_form->data->where('question_id', $question->id)->first(); @endphp
                                                        @if ($value && $value->radact)
                                                            <div class="col-md-6">
                                                                <div class="form-group question_{{ $question->id }}">
                                                                    <label
                                                                        for="question_{{ $question->id }}">{{ $question->question_title }}</label>

                                                                    <input type="text" readonly class="form-control"
                                                                        value="{{ $value->question_value }}"
                                                                        title="" />
                                                                </div>
                                                            </div>
                                                        @endif
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
                        <span class="detail-title"> {{$data->question->question_name}}: </span>
                        {{$data->question_value}}
                    </p>
                    <br>
                    @endif
                    @endforeach --}}
                    </div>
                </div>
                @if ($case_request_information->note)
                    <br>
                    <b>{{ $case_request_information->note }}</b><br>
                @endif




                <div class="">
                    @if ($case_request_information->status == 0)
                        <form method="post"
                            action="{{ route('user.statement.single_statement_update', [$case_request_information->id, 0]) }}">
                            @csrf
                            @foreach ($case_request_information->questions as $question)
                                <div class="form-group">
                                    <label>{{ $question->question }}</label>
                                    <textarea spellcheck="true"  class="form-control" required name="answer_{{ $question->id }}"></textarea>
                                    <br>
                                </div>
                            @endforeach
                            <div class="uploaded_files mt-2 mb-2">
                                <input type="file" name="file" multiple value=""
                                    class="form-control commentMultipleFiles">
                            </div>
                            <div class="form-group">
                                <label for="note">Note</label>
                                <textarea spellcheck="true"  class="form-control" name="note"></textarea>
                            </div>
                            <br>
                            <div class="from-group">
                                <button type="submit" class="btn btn-info">Submit</button>
                                {{--                <a href="#" data-bs-toggle="modal" data-bs-target="#submit_by_phone" class="btn btn-warning">Submit By --}}
                                {{--                    Phone</a> --}}
                            </div>
                        </form>
                    @else
                        @foreach ($case_request_information->questions as $question)
                            <b>{{ $question->question }}</b>
                            <p>{{ $question->answer }}</p>
                        @endforeach
                        @foreach ($case_request_information->documents as $doc)
                            <li>
                                <input type='hidden' name='documents[]' class='file document'
                                    value='{{ $doc->document->document->unique_id }}'>
                                <span class="fa fa-file"></span>&nbsp;{{ $doc->document->document->original_file_name() }}
                                <a href="{{ route('user.view.attachment', $doc->document->document->unique_id) . $doc->document->document->extension() }}"
                                    target="_blank" title='Preview' class="preview_btn"> <span class="fa fa-eye"></span></a>
                                <a href="#" title='Delete File' class="remove_btn"> <span
                                        class="fa fa-times"></span></a>
                            </li>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>


        <div class="modal fade file_upload_model" id="submit_by_phone" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title text-info w-100">
                            <p class="text-success"><i class="fa fa-phone"></i></p>Give Account Over the Phone
                        </h4>
                        <button type="button" class="close float-right" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form method="post"
                        action="{{ route('user.statement.single_statement_update', [$case_request_information->id, 1]) }}">
                        @csrf
                        <div class="modal-body">

                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="note" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Confirm Phone Number</label>
                                <input type="text" name="confirm_note" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-group right">
                                <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-info">Save</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    </div>










@section('scripts')
    <script>
        function uploadDocumentCaseManager(files, form = false) {
            var url = '/user/document/upload/hashed';
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
                        var input = "<input type='hidden' name='documents[]' class='file document' value='" + data
                            .id + "'>";
                        //$('.item_'+number).append(input);
                        $('.item_' + number + ":last").append(input);
                    } catch (e) {
                        console.log(e);
                    }

                });
            }
        }
        jQuery(document).on('change', '.commentMultipleFiles', function(e) {
            e = e.originalEvent;
            var files = e.target.files;
            var form = $(this).closest('form');
            uploadDocumentCaseManager(files, form);
        });
    </script>
@endsection
@endsection
