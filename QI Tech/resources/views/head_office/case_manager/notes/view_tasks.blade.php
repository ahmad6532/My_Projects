{{-- <div class="cm_case_task_add  p-0">
    @if($case->status == 'open')
    <a href="#" data-bs-toggle="modal" data-bs-target="#case_task_model" class="btn btn-outline-cirlce bg-white"><i
            class="fa fa-plus"></i></a>
    @endif
</div> --}}
{{-- <nav class="nav nav-tabs nav-h-bordered">
    <a href="#" class="active" data-bs-toggle="tab" data-bs-target="#defualt_tasks"><span
            class="item_with_border">Default Tasks</span> <span
            class="badge badge-danger">{{$tasks->where('is_default_task',1)->count()}}</span></a>
    <a href="#" data-bs-toggle="tab" data-bs-target="#uploaded_tasks"><span class="item_with_border">Uploaded
            Tasks</span><span class="badge badge-danger">{{$tasks->where('is_default_task',0)->count()}}</span></a>
</nav> --}}

{{-- <div class="inputSection activeColor activeColor">{{$case->percentComplete()}}%</div> --}}
{{-- <br> --}}
@if(!count($stages)) <p class="font-italic">No tasks are found!</p> @endif
{{-- <div class="tab-content" id="myTabContent"> --}}
    {{-- <div id="defualt_tasks" class="defualt_tasks scrollbar_custom_green relative tab-pane show active"> --}}
        @foreach ($stages as $key => $stage)
        <div style="cursor: pointer" class="card border-left-secondary shadow w-100" data-bs-toggle="collapse" data-bs-target="#collapse_{{$stage->id}}" aria-expanded="false" aria-controls="collapse_{{$stage->id}}" >
            <div class="card-body">
                <div class="row align-items-center justify-content-between">
                    <!-- Left-most column (Stage name) -->
                    <div class="col-sm-3">
                        <span class="cm_incident_type">{{$stage->name}}</span>
                    </div>
                    <div class="col-sm-9 d-flex justify-content-end align-items-center gap-4">
                        @if(!$stage->is_current_stage && $stage->status())
                            <span class="badge bg-success">{{$stage->status()}}</span>
                        @elseif($stage->is_current_stage)
                            <span class="badge bg-warning text-dark">Current</span>
                        @endif
                        <div class="progress position-relative" style="width:100%; max-width: 200px;">
                            <div class="progress-bar bg-success" style="width:{{$stage->percentComplete()}}%"></div>
                            <div class="position-absolute text-white" style="top: 50%; right: 50%; transform:translate(50%,-50%)">
                                {{$stage->percentComplete()}}%
                            </div>
                        </div>
                        <div style="width: 60px; overflow-x:auto; scrollbar-color:unset;" class="scrollbar_custom_green-vertical custom-scroll">
                            <div class="d-flex align-items-center">
                                @php
                                    $taskUsers = [];
                                    foreach ($stage->tasks as $task) {
                                        foreach($task->assigned as $view) {
                                            $userId = $view->head_office_user->user->id;
                                            if (!isset($taskUsers[$userId])) {
                                                $taskUsers[$userId] = $view->head_office_user->user;
                                            }
                                        }
                                    }
                                    $handlerUsers = [];
                                    foreach ($stage->stage_case_handlers as $handler) {
                                        $user = $handler->case_handler->case_head_office_user->user;
                                        $userId = $user->id;
                                        if (!isset($handlerUsers[$userId])) {
                                            $handlerUsers[$userId] = $user;
                                        }
                                    }
                                    $allUsers = array_merge($taskUsers, $handlerUsers);
                                @endphp
                                
                                @foreach ($allUsers as $user)
                                    <div class="user-icon-circle-2 new-card-wrap" title="{{ $user->name }}" 
                                         style="margin-left: {{ !$loop->first && count($allUsers) > 1 ? '-10px' : '' }}">
                                        @if ($user->logo)
                                            <img src="{{ $user->logo }}" alt="png_img"
                                                 style="width: 30px; height: 30px; border-radius: 50%;">
                                        @else
                                            <div class="user-img-placeholder" id="user-img-place"
                                                 style="width: 30px; height: 30px;">
                                                {{ implode('', array_map(function ($word) { return strtoupper($word[0]); }, explode(' ', $user->name))) }}
                                            </div>
                                        @endif

                                         @include('head_office.user_card_component')
                                    </div>
                                @endforeach
                            </div>
                        </div>
                
                        @if($case->status == 'open' && $case->isArchived == false)
                        <div class="content_right">
                            <span data-bs-target="#case_task_model_{{$stage->id}}" data-bs-toggle="modal">
                                <img src="{{asset('v2/images/icons/plus.svg')}}" alt="">
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
            @include('head_office.case_manager.notes.form_task',['task'=>null,'stage' => $stage])
            @foreach($stage->tasks as $key=>$task)
            {{-- @if($task->is_default_task) --}}
            <div class="cm_tasks_timeline relative collapse" id="collapse_{{$stage->id}}">
                <div
                    class="cm_case_task @if($task->status == 'completed' || $task->status == 'completed_not_applicable'  ) completed @endif relative">
                    <div class="mb-2 shadow cm_case_task_wrapper">
                        <div class="cm_case_task_title">
                            {{$task->title}}

                            @if(isset($task->form_json))
                                <div>
                                    @include('head_office.case_manager.notes.angular_form_task_embed')
                                </div>
                            @endif
                            
                            <div class="dropdown" style="float: right">
                                
                                &nbsp; &nbsp;
                                <span role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{asset('v2/images/icons/list.svg')}}" alt="">
                                </span>
                                
                                @if($case->status == 'open' && $case->isArchived == false)
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                                  @if($task->currentUserIsAuthor())
                                  <li><a href="#" data-bs-toggle="modal" data-bs-target="#task_form_{{$task->id}}"
                                        class="dropdown-item">Edit Task</a></li>
                                        <li><a href="{{route('case_manager.delete_task',['task_id'=>$task->id,'_token'=>csrf_token()])}}"
                                        data-msg="Are you sure, you want to delete this task?"
                                        class="dropdown-item delete_button text-danger">Delete Task</a></li>
                                    @endif
                                    @if($task->status !='completed' && $task->status !='completed_not_applicable' )
                                    <li><a href="{{route('case_manager.task.change_status',['stage_id' => $stage->id,'task_id' => $task->id,'case_id' => $case->id])}}" class="dropdown-item">Mark
                                        as Complete</a></li>
                                        <li><a href="{{route('case_manager.task.change_status',[$stage->id,$task->id,'not_applicable'=>true,'case_id' => $case->id])}}"
                                        class="dropdown-item">Mark as not applicable</a></li>
                                    @endif
                                    @if($task->status =='completed' || $task->status == 'completed_not_applicable' )
                                    <li><a href="{{route('case_manager.task.change_status',[$stage->id,$task->id,'re_open'=>true,'case_id' => $case->id])}}"
                                        class="dropdown-item">Reopen task</a></li>
                                    @endif

                                    @if($task->deadline_records->isNotEmpty())
                                    <li>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#task_form_{{$task->id}}" class="dropdown-item">Set new deadline</a>
                                    </li>
                                    
                                    <li> 
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#task_assign_user" class="dropdown-item selected_task_btn" data-task_id={{$task->id}}>Assign task</a>
                                    </li>
                                    @endif


                                    @endif
                                </ul>
                                &nbsp; &nbsp;
                            </div>
                            @if(!$task->is_default_task)
                            @php
                            $f_name = \App\Models\User::find($task->user_id)->first_name ?? 'N/A';
                            @endphp                           
                            <img src="{{ asset('v2/images/icons/face-smile.svg') }}" alt="Smile Icon" style="float: right" title="Task Created by {{ $f_name }} {{ $task->created_at->diffForHumans() }}">
                            @endif
                        </div>
                        
                        <div class="cm_case_description">{!! $task->description !!}</div>

                        <div class="cm_case_task_attachments mt-1">
                            <ul class="list-style-none p-0">
                                @foreach($task->documents as $doc)
                                <li class="relative">
                                    <a class="relative @if($doc->type == 'image') cm_image_link @endif"
                                        href="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}"
                                        target="_blank" onClick="openImageAndDownload(event, '{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}')">
                                        <i class="fa fa-link"></i> {{$doc->document->original_file_name()}}
                                        @if($doc->type == 'image')
                                        <div class="cm_image_hover">
                                            <div class="card shadow">
                                                <div class="card-body">
                                                    <img class="image-responsive" width="300"
                                                        src="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}">
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </a>
                                </li>
                                
                                <script>
                                     function openImageAndDownload(event, url) {
                                        event.preventDefault();


                                        const newTab = window.open(url, '_blank');


                                        const downloadButton = document.createElement('button');
                                        downloadButton.style.position = 'absolute';
                                        downloadButton.style.top = '20px'; 
                                        downloadButton.style.right = '20px'; 
                                        downloadButton.style.backgroundColor = 'transparent'; 
                                        downloadButton.style.border = 'none'; 
                                        downloadButton.style.cursor = 'pointer';
                                        downloadButton.style.zIndex = '1000'; 


                                        const svg = `
                                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" 
                                                 xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" 
                                                 width="50px" height="50px" viewBox="0 0 122.433 122.88" 
                                                 enable-background="new 0 0 122.433 122.88" xml:space="preserve">
                                                <g>
                                                    <polygon fill="#007bff" fill-rule="evenodd" clip-rule="evenodd" 
                                                             points="61.216,122.88 0,59.207 39.403,59.207 39.403,0 83.033,0 
                                                             83.033,59.207 122.433,59.207 61.216,122.88"/>
                                                </g>
                                            </svg>`;
                                        
                                        downloadButton.innerHTML = svg;
                                        
                                        
                                        downloadButton.onclick = function() {
                                            const downloadLink = document.createElement('a');
                                            downloadLink.href = url;
                                            downloadLink.download = ''; 
                                            downloadLink.click();
                                        };
                                    
                                        newTab.onload = function() {
                                            const style = newTab.document.createElement('style');
                                            style.innerHTML = `
                                                body {
                                                    position: relative; /* Allow absolute positioning */
                                                    margin: 0; /* Remove default margin */
                                                    background-color: #fff; /* Ensure white background */
                                                }
                                            `;
                                            newTab.document.head.appendChild(style);
                                            newTab.document.body.appendChild(downloadButton);
                                        };
                                    }

                                </script>
                                

                                <style>
                                    .download-button {
                                        background-color: #007bff;
                                        color: #fff; 
                                        border: none;
                                        padding: 10px 15px;
                                        font-size: 200px;
                                        cursor: pointer;
                                        margin: 10px;
                                        border-radius: 5px;
                                        transition: background-color 0.3s;
                                    }

                                    .download-button:hover {
                                        background-color: #0056b3;
                                    }

                                </style>
                                
                                
                                @endforeach
                            </ul>
                        </div>

                        <div class="cm_case_task_assigned_to mt-1">
                            <b>Assigned To</b><br>
                            @foreach($task->assigned as $view)
                            <span data-bs-toggle="tooltip"
                                class="badge badge-primary badge-user">{{$view->head_office_user->user->name}}</span>
                            @endforeach
                            <br>
                            @if ($task->is_dead_line)
                            <b>Deadline Set? &nbsp;</b>
                            <div class="d-flex gap-3">
                                <span data-bs-toggle="tooltip" class="badge badge-success badge-user">Yes</span>
                                <p class="m-0" style="color: #999;font-size:14px;font-weight:600;">{{$task->dead_line_duration}} {{$task->dead_line_unit}} | {{ \Carbon\Carbon::parse($task->dead_line_date)->diffInDays(now()) }} days remaining</p>
                            </div>
                            <br>
                            @else
                            <span data-bs-toggle="tooltip" class="badge badge-danger">No</span>
                            <br>
                            @endif
                            @if ($task->is_dead_line)
                            <b>Deadline Assigned to? &nbsp;</b>
                            @foreach($task->dead_line as $name)
                            <span data-bs-toggle="tooltip" class="badge badge-primary badge-user">{{$name}}</span>
                            @endforeach
                            <br>
                            @endif

                            @if ($task->is_task_over_due)
                            <b>Overdue Assigned to? &nbsp;</b>
                            @foreach($task->over_due as $name)
                            <span data-bs-toggle="tooltip" class="badge badge-primary badge-user">{{$name}}</span>
                            @endforeach
                            <br>
                            @endif
                        </div>
                    </div>
                    @include('head_office.case_manager.notes.form_task',['task'=>$task])
                </div>
            </div>
            {{-- @endif --}}
            @endforeach
        @endforeach
    {{-- </div> --}}
    {{-- <div id="uploaded_tasks" class="tab-pane fade uploaded_tasks">
        @foreach($tasks as $key=>$task)
        @if(!$task->is_default_task)
        <div class="cm_tasks_timeline relative">
            <div
                class="cm_case_task @if($task->status == 'completed' || $task->status == 'completed_not_applicable'  ) completed @endif relative">
                <div class="mb-2 shadow cm_case_task_wrapper">
                    <div class="cm_case_task_title">
                        <b>{{$task->title}}</b>
                    </div>
                    <div class="cm_case_task_actions dropdown no-arrow">
                        @if($case->status == 'open')
                        <a href="#" class="btn btn-outline-cirlce float-right" id="dropdownMenuButton_x"
                            data-bs-toggle="dropdown">
                            <i class="fa fa-ellipsis-h"></i>
                        </a>
                        <div class="dropdown-menu  animated--fade-in" aria-labelledby="dropdownMenuButton_x">
                            @if($task->currentUserIsAuthor())
                            <a href="#" data-bs-toggle="modal" data-bs-target="#task_form_{{$task->id}}"
                                class="dropdown-item">Edit Task</a>
                            <a href="{{route('case_manager.delete_task',$task->id)}}"
                                data-msg="Are you sure, you want to delete this task?"
                                class="dropdown-item delete_button text-danger">Delete Task</a>
                            @endif
                            @if($task->status !='completed' && $task->status !='completed_not_applicable' )
                            <a href="{{route('case_manager.task.change_status',$task->id)}}" class="dropdown-item">Mark
                                as Complete</a>
                            <a href="{{route('case_manager.task.change_status',[$task->id,'not_applicable'=>true])}}"
                                class="dropdown-item">Mark as Complete / Not Applicable</a>
                            @endif
                            @if($task->status =='completed' || $task->status == 'completed_not_applicable' )
                            <a href="{{route('case_manager.task.change_status',[$task->id,'re_open'=>true])}}"
                                class="dropdown-item">Reopen Task</a>
                            @endif
                        </div>
                        @endif
                    </div>
                    <div class="cm_case_description">{{$task->description}}</div>

                    <div class="cm_case_task_attachments mt-1">
                        <ul class="list-style-none p-0">
                            @foreach($task->documents as $doc)
                            <li class="relative ">
                                <a class="relative @if($doc->type == 'image') cm_image_link @endif "
                                    href="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}"
                                    target="_blank"><i class="fa fa-link"></i> {{$doc->document->original_file_name()}}
                                    @if($doc->type == 'image')
                                    <div class="cm_image_hover">
                                        <div class="card shadow">
                                            <div class="card-body">
                                                <img class="image-responsive" width="300"
                                                    src="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}">
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="cm_case_task_assigned_to mt-1">
                        <b>Assigned To</b><br>
                        @foreach($task->assigned as $view)
                        <span data-bs-toggle="tooltip"
                            class="badge badge-primary badge-user">{{$view->head_office_user->user->name}}</span>
                        @endforeach
                        <br>
                        <b>Deadline Set? &nbsp;</b>
                        @if ($task->is_dead_line)
                        <span data-bs-toggle="tooltip" class="badge badge-success badge-user">Yes</span>
                        <br>
                        @else
                        <span data-bs-toggle="tooltip" class="badge badge-danger">No</span>
                        <br>
                        @endif
                        @if ($task->is_dead_line)
                        <b>Deadline Assigned to? &nbsp;</b>
                        @foreach($task->dead_line as $name)
                        <span data-bs-toggle="tooltip" class="badge badge-primary badge-user">{{$name}}</span>
                        @endforeach
                        <br>
                        @endif

                        @if ($task->is_task_over_due)
                        <b>Overdue Assigned to? &nbsp;</b>
                        @foreach($task->over_due as $name)
                        <span data-bs-toggle="tooltip" class="badge badge-primary badge-user">{{$name}}</span>
                        @endforeach
                        <br>
                        @endif
                    </div>
                </div>
                @include('head_office.case_manager.notes.form_task',['task'=>$task])
            </div>
        </div>
        @endif
        @endforeach
    </div> --}}
    
{{-- </div> --}}