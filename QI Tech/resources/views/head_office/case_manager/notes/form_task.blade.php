<form method="post" action="{{route('case_manager.save_task')}}" class="cm_task_form">
    @csrf
    <input type="hidden" name="case_id" value="{{$case->id}}">
    @if(isset($task))
    <input type="hidden" name="id" value="{{$task->id}}">
  
    <style>
        .select2-container--default .select2-selection--multiple {
        background: rgba(242, 246, 247, 255) !important;
        border: 1px solid #e2e3e5 !important;
    }

    .select2-container--default .select2-selection {
        background: rgba(242, 246, 247, 255) !important;
        border: 1px solid #e2e3e5 !important;
    }
    </style>
    

    @endif
    <input type="hidden" name="stage_id" value="{{$stage->id}}">
    <div class="modal fade file_upload_model " @if(isset($task)) id="task_form_{{$task->id}}" @else id="case_task_model_{{$stage->id}}"
        @endif role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        Task
                    </h4>
                    <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="organisation-structure-add-content">
                        <label class="">Task Title : 
                            <input type="text" name="title" class="form-control form-control-sm shadow-none border border-1 w-100" required @if(isset($task))
                                value="{{$task->title}}" @endif>
                        </label>
                    
                        <label class="">Task Description :
                        <textarea spellcheck="true"  type="text" name="description" class="task-rich form-control form-control-sm shadow-none border border-1 w-100"
                            >@if(isset($task)){{$task->description}}@endif</textarea>
                        </label>
                    
                        <label class="">Assign To :
                            <select type="text" name="assigned[]" style="width: 100%" class=" select_2_modal" required
                                multiple>
                                @foreach($head_office_users as $u)
                                    @isset($u->user)
                                        <option @if(isset($task) && $task->hasAssignedUser($u->id)) selected @endif
                                            
                                            value="{{$u->id}}">{{$u->user->name}}</option>
                                        
                                    @endisset
                                @endforeach
                            </select>
                        </label>

                        <div class=" d-flex align-items-center gap-2 mt-3">
                            <label for="" class="fw-bold">Set as mandatory</label>
                            <input style="width: fit-content;flex:unset;" class="m-0" type="checkbox" name="mandatory"
                                id="" {{ isset($task) && $task->mandatory ? 'checked':''  }}>
                        </div>
                    </div>
                    @include('head_office.be_spoke_forms.default_task_over_due')





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
                                    @php
                                        $rec = $rec->deadline;
                                    @endphp
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
                    <div class="uploaded_files mt-2 mb-2">
                        @if(isset($task))
                        @foreach($task->documents as $doc)
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
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<style>
    .select2-close-mask{
    z-index: 2099;
}
.select2-dropdown{
    z-index: 3051;
}
</style>
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
            setup: function(editor) {
                editor.on('init change', function() {
                    editor.save();
                });

            }
        });

    });
</script>
