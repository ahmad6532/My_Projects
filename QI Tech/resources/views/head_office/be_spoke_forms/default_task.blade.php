<form method="post" action="{{route('head_office.be_spoke_form.default_task_save')}}" class="cm_task_form">
    @csrf

    @if(isset($form))
    <input type="hidden" name="form_id" value="{{$form->id}}">
    @endif
    @if(isset($task))
    <input type="hidden" name="default_task_id" value="{{$task->id}}">
    @endif
    <div class="modal fade file_upload_model  " @if(isset($task)) id="default_task_form_{{$task->id}}" @else
        id="default_task_form1" @endif tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        <p class="text-success"><i class="fa fa-tasks fa-2x"></i></p>Default Task
                    </h4>
                    <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Task Title</label>
                        <input spellcheck="true" type="text" name="title" class="form-control" required @if(isset($task))
                            value="{{$task->title}}" @endif>
                    </div>
                    <div class="form-group">
                        <label>Task Description</label>
                        <textarea spellcheck="true"  spellcheck="true" type="text" name="description" class="form-control"
                            required>@if(isset($task)){{$task->description}}@endif</textarea>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Select User Type</label>
                                <select class="form-control select_user_type" name="select_user_type">
        
                                    <option value="2" @if(isset($task) && $task->type == 2) selected @endif>Leave Unassigned
                                    </option>
                                    <option value="0" @if(isset($task) && $task->type == 0) selected @endif>Users</option>
        
                                    <option value="1" @if(isset($task) && $task->type == 1) selected @endif>Profiles</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group users" @if(isset($task) && $task->type !== 0) style="display: none;" @endif>
                                @php
                                $user = Auth::guard('web')->user()->selected_head_office;
                                @endphp
                                <label>
                                    Select Users
                                </label>
                                <select class="form-control select_2 w-100" name="users[]" multiple="multiple">
                                    @foreach ($user->users as $u)
                                    <option value="{{$u->user->id}}" @if (isset($task) && $task->type == 0 && $task->type_ids &&
                                        in_array($u->user->id,json_decode($task->type_ids))) selected @endif >{{$u->user->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
        
                            <div class="form-group profiles" @if(!isset($task)) style="display: none;" @elseif($task->type !== 1)
                                style="display: none;" @endif>
        
                                <label>
                                    Select Profiles
                                </label>
                                <select class="form-control select_2 w-100" name="profiles[]" multiple="multiple"
                                    style="display: flow-root;">
                                    @foreach ($user->head_office_user_profiles as $user)
                                    <option value="{{$user->id}}" @if (isset($task) && $task->type == 1 && $task->type_ids &&
                                        in_array($user->id,json_decode($task->type_ids))) selected @endif >{{$user->profile_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    {{-- @include('head_office.be_spoke_forms.default_task_over_due') --}}

                    
                
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
                            data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>