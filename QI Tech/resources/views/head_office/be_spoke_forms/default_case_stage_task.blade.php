<style>
    .form-page-contents .inputGroup {
        border: none !important;
        outline: none !important;
    }

    .select2-container--default .select2-selection--multiple {
        background: rgba(242, 246, 247, 255) !important;
        border: 1px solid #e2e3e5 !important;
    }

    .select2-container--default .select2-selection {
        background: rgba(242, 246, 247, 255) !important;
        border: 1px solid #e2e3e5 !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered li {
        border: none;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 26px;
    }
</style>
<div class="collapse right-sidebar-settings "
    @if (isset($task)) id="task_{{ $task->id }}" @else id="stage_{{ $stage->id }}" @endif>
    <div data-bs-toggle="collapse" onclick="this.form.reset();"
        @if (isset($task)) data-bs-target="#task_{{ $task->id }}" @else data-bs-target="#stage_{{ $stage->id }}" @endif
        class="position-fixed" style="top: 0;left:0;width:100%;height:100%;background:rgba(0, 0, 0, 0.1)"></div>
    <div class="card" style="min-height: 99%;z-index:110;" onclick="function(event) { event.stopPropagation(); }">
        <div class="card-body">
            <h3 class="text-center text-success">Task</h3>
            <form method="post" action="{{ route('head_office.be_spoke_form.stage.default_task_save') }}"
                class="cm_task_form">
                @csrf
                @if (isset($stage))
                    <input type="hidden" name="stage_id" value="{{ $stage->id }}">
                @endif
                @if (isset($task))
                    <input type="hidden" name="default_task_id" value="{{ $task->id }}">
                @endif
                <div class="file_upload_model "
                    @if (isset($task)) id="default_task_form_{{ $task->id }}" @else
                    id="default_task_form" @endif
                    tabindex="-1" role="dialog" aria-hidden="true">

                    {{-- <div class="content-page-heading">
                            @if (isset($task))
                                Edit
                            @else
                                Add
                            @endif Default Stage Task
                        </div> --}}
                    <div class="organisation-structure-add-content hide-placeholder-parent">
                        <div class="d-flex align-items-center gap-2">
                            <p class="mb-0">Name</p>

                        </div>
                        <div class="mb-1 d-flex align-items-center gap-2">
                            <label for="StageName" class="form-label fw-bold mb-0">Name</label>
                            <input  type="text" required class="form-control" name="title"
                                placeholder="Please enter title name" value="{{ isset($task) ? $task->title : '' }}">
                        </div>

                        <label class="inputGrou fw-bold pb-3">Task Description:
                            <textarea spellcheck="true"  spellcheck="true"spellcheck="true" type="text" name="description" id="task-rich" class="w-auto pt-2 task-rich">
@if (isset($task))
{{ $task->description }}
@endif
</textarea>
                        </label>
                        <div>
                            <div class="uploaded_files mt-2 mb-2">
                                @if (isset($task))
                                    @foreach ($task->documents as $doc)
                                        <li>
                                            <input type='hidden' name='documents[]' class='file document'
                                                value='{{ $doc->document->unique_id }}'>
                                            {{-- <span class="fa fa-file"></span>&nbsp;{{$doc->document->original_file_name()}}
                                    <a href="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}"
                                        target="_blank" title='Preview' class="preview_btn"> <span
                                            class="fa fa-eye"></span></a> --}}
                                            <a class="relative @if ($doc->type == 'image') cm_image_link @endif "
                                                href="{{ route('headoffice.view.attachment', $doc->document->unique_id) . $doc->document->extension() }}"
                                                target="_blank"><i class="fa fa-link"></i>
                                                {{ $doc->document->original_file_name() }}
                                                @if ($doc->type == 'image')
                                                    <div class="cm_image_hover">
                                                        <div class="card shadow">
                                                            <div class="card-body">
                                                                <img class="image-responsive" width="300"
                                                                    src="{{ route('headoffice.view.attachment', $doc->document->unique_id) . $doc->document->extension() }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </a>
                                            <a href="#" title='Delete File' class="remove_btn"> <span
                                                    class="fa fa-times"></span></a>
                                        </li>
                                    @endforeach
                                @endif
                            </div>
                            <h6 class="text-black">Task documents/images to upload</h6>
                            <div class="cm_upload_box_with_model center">
                                <i class="fa fa-cloud-upload-alt" style="font-size:48px"></i><br>Drop files here
                            </div>

                            <input type="file" name="file" multiple value=""
                                class="form-control commentMultipleFiles">
                        </div>
                        <div class=" d-flex align-items-center gap-2 mt-3">
                            <label for="" class="fw-bold">Set as mandatory</label>
                            <input style="width: fit-content;flex:unset;" class="m-0" type="checkbox" name="mandatory"
                                id="" {{ isset($task) && $task->mandatory ? 'checked':''  }}>
                        </div>

                        <div class="d-flex flex-column gap-2 my-3">
                            <label class="inputGrou fw-semibold text-info" style="font-size: 14px;">Assign To:
                            </label>
                            <select class="form-select select_user_type w-auto select-btn" name="select_user_type">

                                <option value="2" @if (isset($task) && $task->type == 2) selected @endif>Leave
                                    Unassigned
                                </option>
                                <option value="0" @if (isset($task) && $task->type == 0) selected @endif>Users
                                </option>

                                <option value="1" @if (isset($task) && $task->type == 1) selected @endif>Profiles
                                </option>
                            </select>
                        </div>
                        <div class="users" @if (!isset($task) || $task->type !== 0) style="display: none;" @endif>
                            @php
                                $user = Auth::guard('web')->user()->selected_head_office;
                            @endphp
                            <label class="inputGroup">
                                Select Users:
                                <select class="select_2 w-100 w-auto" name="users[]" multiple="multiple">
                                    @foreach ($user->users as $u)
                                        <option value="{{ $u->user->id }}"
                                            @if (isset($task) && $task->type == 0 && $task->type_ids && in_array($u->user->id, json_decode($task->type_ids))) selected @endif>{{ $u->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </label>

                        </div>

                        <div class="profiles"
                            @if (!isset($task)) style="display: none;" @elseif($task->type !== 1)
                                style="display: none;" @endif>

                            <label class="inputGroup">
                                Select Profiles:
                                <select class="select_2 w-100 w-auto" name="profiles[]" multiple="multiple"
                                    style="display: flow-root;">
                                    @foreach ($user->head_office_user_profiles as $user)
                                            @if (isset($user))
                                            <option value="{{ $user->id }}"
                                                @if (isset($task) && $task->type == 1 && isset($task->type_ids) && in_array($user->id, json_decode($task->type_ids) ?: [])) 
                                                    selected 
                                                @endif>
                                                {{ $user->profile_name }}
                                            </option>
                                        @endif
                                
                                    @endforeach
                                </select>
                            </label>
                            <label class="inputGroup">
                                Add Users:
                                <select class="select_2 w-100 w-auto" name="add_users" style="display: flow-root;">
                                    <option value="1">Single random user of this profile</option>
                                    <option value="1">All users of this profile</option>
                                </select>
                            </label>

                        </div>

                        @include('head_office.be_spoke_forms.default_case_stage_task_over_due')




                        @if (isset($task->deadline_records) && count($task->deadline_records) !== 0)
                            <table class="table new-table w-100">
                                <thead>
                                    <th>Deadline</th>
                                    <th>Duration</th>
                                    <th>Option</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @foreach ($task->deadline_records as $rec)
                                        <tr>
                                            <td>{{ $rec->task_type }}</td>
                                            <td>{{ $rec->duration }} {{ $rec->unit }}</td>
                                            <td>{{ strlen($formattedString = ucwords(str_replace('_', ' ', $rec->action_option))) > 15 ? substr($formattedString, 0, 15) . '...' : $formattedString }}
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1 justify-content-center align-items-center mx-auto"
                                                    style="width: fit-content;">
                                                    <button data-task="{{ json_encode($rec) }}" type="button" data-bs-toggle="modal" data-bs-target="#edit_task_record" class="btn p-0 px-2 shadow-none task-button"
                                                        title="edit this condition">
                                                        <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
                                                    </button>
                                                    <a href="{{route('head_office.be_spoke_form.stage.default_task_delete',['id'=>$rec->id,'_token'=>csrf_token()])}}" type="button" class="btn p-0 px-2 shadow-none"
                                                        title="Remove this action">
                                                        <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        @endif

                        @if (isset($task))
                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <p class="fw-semibold text-info mb-0" style="font-size: 14px;">Questions</p>
                                <div class="d-flex align-items-center gap-2">
                                    @if (!isset($task->form_json))
                                        <a href="/bespoke_form_v3/#!/form_task/{{ $task->id }}?ho={{$stage->form->form_owner->id}}" class="text-muted">Add</a>
                                    @else
                                        <a href="/bespoke_form_v3/#!/form_task/{{ $task->id }}?ho={{$stage->form->form_owner->id}}" class="text-muted">Edit</a>
                                        <a href="#" class="text-muted task-del-btn" data-task_id="{{ $task->id }}">Delete</a>
                                    @endif
                                </div>
                            </div>

                            <div>
                                @php
                                $data_objects = [];
                                    $task_json = json_decode($task->form_json,true);
                                    if ($task_json['pages'] && count($task_json['pages']) > 0) {
                                            foreach ($task_json['pages'] as $page) {
                                                if ($page['items'] && count($page['items']) > 0) {
                                                    foreach ($page['items'] as $item) {
                                                        if (
                                                            isset(
                                                                $item['name'],
                                                                $item['input'],
                                                                $item['input']['type'],
                                                            )
                                                        ) {
                                                            $type = $item['input']['type'];
                                                            $value = null;
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
                                @endphp

                                @foreach ($data_objects as $data )
                                <p class="m-0 mt-1" style="font-size:14px; color:gray;line-height: inherit;">
                                    {{ $data['label'] }}
                                </p>
                                @endforeach
                            </div>
                        @endif

                    </div>
                    <div class="modal-footer mt-5">
                        <div class="btn-grou w-100 d-flex justify-content-around  mt-3">
                            <button type="submit" class="btn text-black fw-bold">Save</button>
                            <button type="button" class="btn text-black fw-bold" onclick="this.form.reset();"
                                @if (isset($task)) data-bs-target="#task_{{ $task->id }}" @else data-bs-target="#stage_{{ $stage->id }}" @endif
                                data-bs-toggle="collapse">Delete</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            tinymce.init({
                selector: '.task-rich',
                font_formats:"Littera Text",
                content_style: "body { font-family: 'Littera Text', sans-serif; }",
                menubar: false,
                skin: false,
                height: 200,
                content_css: false,
                forced_root_block: false,
                promotion: false,
                branding: false,
                browser_spellcheck: true,
                setup: function(editor) {
                    editor.on('init change', function() {
                        editor.save();
                    });

                }
            });

        });
    </script>
</div>
