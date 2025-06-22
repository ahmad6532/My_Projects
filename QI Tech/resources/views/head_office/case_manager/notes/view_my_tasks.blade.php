@php
$tasks = $my_tasks;
@endphp

@if(!count($tasks)) <p class="font-italic">No tasks are found!</p> @endif

<nav class="nav nav-tabs nav-h-bordered">
    <a href="#" class="active" data-bs-toggle="tab" data-bs-target="#defualt_tasks"><span
            class="item_with_border">Default Tasks</span> <span
            class="badge badge-danger">{{$tasks->where('is_default_task',1)->count()}}</span></a>
    <a href="#" data-bs-toggle="tab" data-bs-target="#uploaded_tasks"><span class="item_with_border">Uploaded
            Tasks</span><span
            class="badge badge-danger">{{$tasks->where('is_default_task',0)->count()}}</span></a>
</nav>
<div class="tab-content" id="myTabContent">
    <div id="defualt_tasks" class="defualt_tasks scrollbar_custom_green relative tab-pane show active">
        @foreach($tasks as $key=>$task)
        @if($task->is_default_task)
        <div class="cm_tasks_timeline relative">
            <div
                class="cm_case_task @if($task->status == 'completed' || $task->status == 'completed_not_applicable'  ) completed @endif relative">
                <div class="mb-2 shadow cm_case_task_wrapper">
                    <div class="cm_case_task_title">
                        <b>{{$task->title}}</b>
                    </div>
                    <div class="cm_case_task_actions dropdown no-arrow">
                        <a href="#" class="btn btn-outline-cirlce float-right dropdown-toggle" id="dropdownMenuButton_x"
                            data-bs-toggle="dropdown">
                            <i class="fa fa-ellipsis-h"></i>
                        </a>
                        <div class="dropdown-menu  animated--fade-in" aria-labelledby="dropdownMenuButton_x">
                            @if($task->currentUserIsAuthor())
                            <a href="#" data-bs-toggle="modal" data-bs-target="#my_task_form_{{$task->id}}"
                                class="dropdown-item">Edit Task</a>
                            <a href="{{route('case_manager.delete_task',['task_id'=>$task->id,'_token'=>csrf_token()])}}"
                                data-bs-msg="Are you sure, you want to delete this task?"
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
                @include('head_office.case_manager.notes.my_form_task',['task'=>$task])
            </div>
        </div>
        @endif
        @endforeach

    </div>
    <div id="uploaded_tasks" class="tab-pane fade uploaded_tasks">
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
                        <a href="#" class="btn btn-outline-cirlce float-right dropdown-toggle" id="dropdownMenuButton_x"
                            data-bs-toggle="dropdown">
                            <i class="fa fa-ellipsis-h"></i>
                        </a>
                        <div class="dropdown-menu  animated--fade-in" aria-labelledby="dropdownMenuButton_x">
                            @if($task->currentUserIsAuthor())
                            <a href="#" data-bs-toggle="modal" data-bs-target="#my_task_form_{{$task->id}}"
                                class="dropdown-item">Edit Task</a>
                            <a href="{{route('case_manager.delete_task',['task_id'=>$task->id,'_token'=>csrf_token()])}}"
                                data-bs-msg="Are you sure, you want to delete this task?"
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
                @include('head_office.case_manager.notes.my_form_task',['task'=>$task])
            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>