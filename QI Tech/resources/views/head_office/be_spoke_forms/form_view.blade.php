@extends('layouts.head_office_app')
@section('title', 'Bespoke Form Template')
@section('sub-header')
<ul>
    <li> 
        <a class="{{request()->route()->getName() == 'head_office.be_spoke_form.index' ? 'active' : ''}}"
        href="{{route('head_office.be_spoke_form.index')}}">Bespoke Forms <span></span>
        </a>
    </li>
    <li> 
        <a class="active"
            href="{{route('case_manager.overview')}}">@if(isset($form)){{substr($form->name,0,30)}} @else New
            Bespoke Form @endif <span></span>
        </a>
    </li>
</ul>

@endsection
@section('content')
<div id="content">
    <div class="content-page-heading">
        Bespoke Form @if(isset($form)) - {{$form->name}} @endif
    </div>
    

    @include('layouts.error')
            <input type="hidden" name="id" value="@if(isset($form)){{$form->id}}@endif">
            <div class="form-page-contents hide-placeholder-parent">
                <label for="" class="inputGroup">Category:&nbsp;
                    <span class="borderedTest">{{$form->category->name}}</span>
                    
                </label>
                <label for="" class="inputGroup">Type: &nbsp;
                    <span class="borderedTest">@if($form->is_external_link) External @else Internal @endif</span>
                    
                </label>
                <label for="" class="inputGroup">Color Code: &nbsp;
                    <span style="background-color: {{$form->color_code}}" class="borderedTest">{{$form->color_code}}</span>
                </label>
                <label for="" class="inputGroup">Limits: &nbsp;
                </label>
                
                <label for="" class="inputGroup">Expiry: &nbsp;
                </label>
                
                <label for="" class="inputGroup">Allow drafts to be completed off-stie: &nbsp;
                </label>
                
                <label for="" class="inputGroup">Allow Reporting without logging-in: &nbsp;
                </label>
                <label for="" class="inputGroup">Generate: &nbsp;
                    <span class="borderedTest">@if($form->add_to_case_manager) Case @else Board @endif</span>
                    
                </label>
            </div>
        <!-- Custom Designs starts from here -->
        @if(isset($form))
        <h4><strong>Please add stages to your form</strong></h4>
        <form name="fields_form" method="post"
            action="{{route('head_office.be_spoke_forms_templates.form_stages_save')}}">
            @csrf
            <input type="hidden" name="form_id" value="@if(isset($form)){{$form->id}}@endif">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Stage Name</th>
                            <th>Arrange Question in Groups</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($form->stages as $stage)
                        <tr>
                            <td>
                                <input class="form-control" type="text" id="stage_name[{{$stage->id}}]"
                                    value="{{$stage->stage_name}}" name="stages[{{$stage->id}}]"
                                    placeholder="Enter Form name here" required>
                            </td>
                            <td>
                                @foreach($stage->groups as $group)
                                <p>
                                    <span class="group_name">{{substr($group->group_name,0,50)}}</span>
                                    <a class="text-primary"
                                        href="{{route('head_office.be_spoke_forms_templates.form_stage_questions',[$stage->id,$group->id])}}"><i
                                            class="fas fa-address-book"></i> Create Questions</a>
                                </p>
                                @endforeach
                            </td>
                            <td calss="row_icons">
                                <a class="btn btn-info toggle_ajax_model" data-bs-toggle="modal"
                                    data-bs-target="#stage_groups_model" href="#"
                                    data-href="{{route('head_office.be_spoke_forms_templates.stage_groups',$stage->id)}}"><i
                                        class="fas fa-address-book"></i> Stage Groups</a>
                                <a class="btn btn-danger delete_stage"
                                    href="{{route('head_office.be_spoke_forms_templates.form_stage_delete',['id'=>$stage->id,'_token'=>csrf_token()])}}"><i
                                        class="fas fa-times"></i> Delete Stage</a>
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td>
                                <label for="stage_name">New Stage Name</label>
                                <input class="form-control" type="text" id="stage_name" name="stage_name"
                                    placeholder="Enter Form name here">
                            </td>
                            <td calss="row_icons">
                            </td>
                            <td></td>
                        </tr>
                    </tbody>


                </table>
            </div>
            <div>
                <button type="submit" name="submit" class="nav-link btn btn-info inline"><i class="fas fa-save"></i>
                    Save Stages</button>
            </div>

        </form>
        <br>
        <div class="if_case_manager_checked">

            <div class="card" id="collapseCard">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="shared_case_approved_email_tab" data-bs-toggle="tab"
                                data-bs-target="#shared_case_approved_email" type="button" role="tab" aria-controls="shared_case_approved_email"
                                aria-selected="true">Shared Case Approved Email
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="default_task_tab" data-bs-toggle="tab" data-bs-target="#default_task" type="button"
                                role="tab" aria-controls="default_task" aria-selected="false">Default Task
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#stages" type="button"
                                role="tab" aria-controls="stages" aria-selected="false">Default Task
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="docuemnt_and_template_tab" data-bs-toggle="tab"
                                data-bs-target="#docuemnt_and_template" type="button" role="tab"
                                aria-controls="docuemnt_and_template" aria-selected="false">Document and Template
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="when_case_closed_tab" data-bs-toggle="tab" data-bs-target="#when_case_closed"
                                type="button" role="tab" aria-controls="when_case_closed" aria-selected="false">When Case Closed
                            </button>
                        </li>
                        {{-- <li class="nav-item" role="presentation">
                            <button class="nav-link" id="auto_case_close_tab" data-bs-toggle="tab"
                                data-bs-target="#auto_case_close" type="button" role="tab"
                                aria-controls="auto_case_close" aria-selected="false">Auto Case Close
                            </button>
                        </li> --}}
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cards_tab" data-bs-toggle="tab"
                                data-bs-target="#cards" type="button" role="tab"
                                aria-controls="cards" aria-selected="false">Involvements
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade" id="stages">
                            <div style="display: flex; justify-content: center; align-items: center;">
                                <div class="content-page-heading">
                                    Stages
                                </div>
                                <div style="position: absolute;right: 40px; cursor: pointer;" class="search add_new_stage" title="Add new Stage">
                                    <img src="{{asset('v2/images/icons/plus.svg')}}" alt="">
                                </div>
                            </div>
                            @foreach ($form->default_stages as $counter => $stage)
                            <div class="form-page-contents ">
                                <div class="timeline timeline_nearmiss">
                                    <div class="line line-date stage-{{$stage->id}}">
                                        <div class="timeline-label stage-time-line" data-id="{{$stage->id}}">{{$stage->name}}</div>
                                        <div class="action-bar card card-qi" style="left:187px">
                                            <span class="edit_stage">
                                                <img src="{{asset('v2/images/icons/edit-03.svg')}}" alt="">
                                            </span>
                                            <span class="stage_delete">
                                                <img src="{{asset('v2/images/icons/trash.svg')}}" alt="">
                                            </span>
                                            <span data-bs-toggle="collapse" data-bs-target="#stage_{{$stage->id}}">
                                                <img src="{{asset('v2/images/icons/plus.svg')}}" alt="">
                                            </span>                                      
                                        </div>
                                    </div>
                                    @if(!$stage->default_tasks()->count())
                                    <div class="line stage-line nearmiss_hidden right-record nearmiss_record nearmiss_stage_{{$stage->id}} nearmiss_status_active">
                                        <div class="content-timeline">
                                            <div class="actions" style="display:none">
                                                {{-- <a href="javascript:void(0)" title="Edit" class="" >
                                                    <img src="{{asset('v2/images/icons/edit-03.svg')}}" alt="">
                                                </a> --}}
                                            </div>
                                            No Task found
                                        </div>
                                    </div>
                                    @else
                                        @foreach ($stage->default_tasks()->get() as $task)
                                            <div class="line stage-line nearmiss_hidden right-record nearmiss_record nearmiss_stage_{{$task->id}} nearmiss_status_active ">
                                                <div class="content-timeline">
                                                    <div class="actions" style="display:none">
                                                        <a href="javascript:void(0)" title="Edit" class="" data-bs-toggle="collapse" data-bs-target="#task_{{$task->id}}" >
                                                            <img src="{{asset('v2/images/icons/edit-03.svg')}}" alt="">
                                                        </a>
                                                    </div>
                                                    <h2 class="timeline_category_title">
                                                        <span class="timeline_what_was_error_title well_title">{{$task->title}}</span>
                                                    </h2>
                                                    <p>
                                                        <span class="detail-title"> Name: </span>
                                                        new name
                                                    </p>
                                                </div>
                                            </div>
                                            @include('head_office.be_spoke_forms.default_case_stage_task',['stage' => $stage,'task' => $task])
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                           
                            @include('head_office.be_spoke_forms.default_case_stage_task',['stage' => $stage,'task' => null])
                                    
                            @endforeach
                        </div>
                        <div class="tab-pane fade active show" id="shared_case_approved_email" role="tabpanel"
                            aria-labelledby="shared_case_approved_email-tab">
                            <div class="row">
                               {{--  <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="text-center text-info h3 font-weight-bold">Shared Case Approved Email</h3>
                                            <form action="{{route('share_emails.share_email.store',$form->id)}}" method="POST">
                                                <input type="hidden" name="_token" value="Uz9Kj0nCjX1BelgaUcJTdVforFjLF79lJW31VUe9">
                                                <div class="form-group">
                                                    <label for="email"></label>
                                                    <input type="email" id="email" name="shared_case_approved_email" placeholder="Shared Case Approved Email" class="form-control" required="">
                                                </div>
                                                <div class="form-group">
                                                    <button class="btn btn-info" type="submit" name="submit">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="text-info h3 font-weight-bold">Share Case : Approved Emails
                                                <span style="float: right">
                                                    
                                                    
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#share_case" class="btn btn-info">
                                                        Add New
                                                    </a>
                                                </span>
                                            </h3>
            
                                            <table border="0" id="scheduleTable" class="table table-responsive table_full_width">
                                                <thead>
                                                    <tr>
                                                        <th>Emails</th>
                                                        <th>Description</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($form->shared_case_approved_emails as  $shared_case_approved_email)
                                                        <tr>
                                                            <td>
                                                            {{$shared_case_approved_email->email}}
                                                            </td>
                                                            <td>
                                                            {{$shared_case_approved_email->description}}
                                                            </td>
                                                            <td>
                                                                <a class="btn btn-danger delete_email" data-msg="Are you sure, you want to delete this email?" 
                                                                href="{!! route('share_emails.share_email.delete', ['id'=>$shared_case_approved_email->id,'_token'=>csrf_token()]) !!}"><i
                                                                class="fas fa-times"></i> Delete</a>
                                                                    |
                                                                    <a class="btn btn-info" data-bs-toggle="modal" data-bs-target="#share_case_{{$shared_case_approved_email->id}}"
                                                                href="#"><i
                                                                    class="fas fa-wrench"></i> Edit</a>
                                                                    

                                                            </td>
                                                        </tr>
                                                        @include('head_office.be_spoke_forms.share_case_approved_email',['shared_case_approved_email' => $shared_case_approved_email])
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <input type="checkbox" @if(isset($form) && $form->is_allow_non_approved_emails) checked @endif name="is_allow_non_approved_emails" id="is_allow_non_approved_emails">
                                            <label for="">
                                                Allow user to share case with non-approved emails
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            
                        <div class="tab-pane fade" id="default_task" role="tabpanel" aria-labelledby="default_task-tab">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="card">
                                        <div class="card-body">

                                            <h3 class="text-info h3 font-weight-bold">Default Tasks <a style="float: right !important;" href="#"
                                                data-bs-toggle="modal" data-bs-target="#default_task_form"
                                                class="btn btn-info">Add New Task</a></h3>
            
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="dataTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Task Title</th>
                                                            <th>Task Description</th>
                                                            <th>Assign Type</th>
                                                            <th>Assign To</th>

                                                            <th>Start Date</th>
                                                            
                                                            <th>Deadline Duration</th>
                                                            <th>Deadline Users</th>

                                                            
                                                            <th>Overdue Type</th>
                                                            <th>Overdue Users</th>


                                                            <th>Files</th>
                                                            <th>Action</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($form->defaultTasks as $task)
                                                        <tr>
                                                            <td>
                                                                {{$task->title}}
                                                            </td>
                                                            <td>
                                                                {{$task->description}}
                                                            </td>
                                                            
                                                            <td>
                                                                @if(!$task->type)Users @elseif($task->type == 1) Profiles @else Leave Unassigned @endif
                                                            </td>
                                                            <td>
                                                                {{$task->profiles ? $task->profiles : 'Leave Unassigned' }}
                                                            </td>
                                                            
                                                            <td>
                                                                {{$task->dead_line_start_from}}
                                                            </td>
                                                            <td>
                                                                {{$task->dead_line_duration}} {{$task->dead_line_unit}}
                                                            </td>
                                                            <td>
                                                                {{$task->dead_line}}
                                                            </td>

                                                            <td>
                                                                {{$task->task_over_due_duration}} {{$task->task_over_due_unit}}
                                                            </td>
                                                            <td>
                                                                {{$task->over_due}}
                                                            </td>

                                                            <td>
                                                                @if(count($task->documents))
                                                                <div class="cm_comment_attachments mt-1">
                                                                    <ul class="list-style-none p-0">
                                                                        @foreach($task->documents as $doc)
                                                                        <li class="relative ">
                                                                            <a class="relative @if($doc->type == 'image') cm_image_link @endif "
                                                                                href="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}"
                                                                                target="_blank"><i
                                                                                    class="fa fa-link"></i>
                                                                                {{$doc->document->original_file_name()}}
                                                                                @if($doc->type == 'image')
                                                                                <div class="cm_image_hover">
                                                                                    <div class="card shadow">
                                                                                        <div class="card-body">
                                                                                            <img class="image-responsive"
                                                                                                width="300"
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
                                                                @endif
                                                            </td>
                                                            <td calss="row_icons">
                                                                <div class="btn-group">
                                                                <a class="btn btn-danger delete_task"
                                                                    href="{{route('head_office.default_task.default_task_delete',$task->id)}}"><i
                                                                        class="fas fa-times"></i></a>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#default_task_form_{{$task->id}}"
                                                                    class="btn btn-warning"><i class="fa fa-wrench"></i></a>
                                                                </div>
                                                                @include('head_office.be_spoke_forms.default_task',['task'=> $task])
                                                            </td>
                                                        </tr>



                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="docuemnt_and_template" role="tabpanel"
                            aria-labelledby="docuemnt_and_template-tab">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="card">
                                        <div class="card-body">

                                            <h3 class="text-info h3 font-weight-bold">Default Documents <a style="float: right !important;" href="#"
                                                data-bs-toggle="modal" data-bs-target="#default_document_form"
                                                class="btn btn-info">Add New Document</a></h3>
            
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="dataTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Document Title</th>
                                                            <th>Document Description</th>
                                                            <th>Files</th>
                                                            <th>Action</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($form->defaultDocuments as $document)
                                                        <tr>
                                                            <td>
                                                                {{$document->title}}
                                                            </td>
                                                            <td>
                                                                {{$document->description}}
                                                            </td>
                                                            <td>
                                                                @if(count($document->documents))
                                                                <div class="cm_comment_attachments mt-1">
                                                                    <ul class="list-style-none p-0">
                                                                        @foreach($document->documents as $doc)
                                                                        <li class="relative ">
                                                                            <a class="relative @if($doc->type == 'image') cm_image_link @endif "
                                                                                href="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}"
                                                                                target="_blank"><i
                                                                                    class="fa fa-link"></i>
                                                                                {{$doc->document->original_file_name()}}
                                                                                @if($doc->type == 'image')
                                                                                <div class="cm_image_hover">
                                                                                    <div class="card shadow">
                                                                                        <div class="card-body">
                                                                                            <img class="image-responsive"
                                                                                                width="300"
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
                                                                @endif
                                                            </td>
                                                            <td calss="row_icons">
                                                                <a class="btn btn-danger delete_document" data-msg="Are you sure, you want to delete this document?"
                                                                    href="{{route('default_documents.default_document.delete',['id'=>$document->id,'_token'=>csrf_token()])}}"><i
                                                                        class="fas fa-times"></i> Delete Document</a>
                                                                |
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#default_document_form_{{$document->id}}"
                                                                    class="btn btn-warning"><i class="fa fa-wrench"></i>
                                                                    Edit </a>
                                                                @include('head_office.be_spoke_forms.default_document',['document'=> $document])
                                                            </td>
                                                        </tr>



                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            
                        <div class="tab-pane fade" id="when_case_closed" role="tabpanel" aria-labelledby="when_case_closed-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h3 class="text-center text-info h3 font-weight-bold">When case closed</h3>
                                                <form action="{{route('when_case_closed',$form->id)}}" method="POST">
                                                    @csrf
                                                    <div class="">
                                                        <label for="is_case_close_priority">Feed back location when case cloded</label>
                                                        <input type="checkbox" value="1" @if ($form->is_case_close_priority) checked @endif id="is_case_close_priority" name="is_case_close_priority">
                                                    </div>
                                                    <div class="">
                                                        <label for="requires_final_approval">Require final approval</label>
                                                        <input type="checkbox" value="1" @if ($form->requires_final_approval) checked @endif id="requires_final_approval" name="requires_final_approval">
                                                    </div>
                                                    <div class="form-group">
                                                        <button class="btn btn-info" type="submit" name="submit">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="cards" role="tabpanel"
                            aria-labelledby="cards-tab">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="card">
                                        <div class="card-body">

                                            <h3 class="text-info h3 font-weight-bold">Involvements <a style="float: right !important;" href="#"
                                                data-bs-toggle="modal" data-bs-target="#default_card_form"
                                                class="btn btn-info">Add New Involvement</a></h3>
            
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="dataTable">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            {{-- <th>Type</th> --}}
                                                            <th>Name</th>
                                                            <th>Connected with</th>
                                                            <th>Action</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($form->formCards as $card)
                                                        <tr>
                                                            <td>
                                                                {{$loop->iteration}}
                                                            </td>
                                                            {{-- <td>
                                                                {{$card->default_card->type}}
                                                            </td> --}}
                                                            <td>
                                                                {{$card->name}}
                                                            </td>
                                                            <td>
                                                                @foreach ($card->group() as $connected_card)
                                                                @if($connected_card->from_card && $connected_card->form_card_id != $card->id)
                                                                    {{ $connected_card->from_card->name }},
                                                                    @endif
                                                                @endforeach
                                                            </td>
                                                            
                                                            
                                                            <td calss="row_icons">
                                                                <div class="btn-group">
                                                                <a class="btn btn-danger delete_button"
                                                                data-msg="Are you sure, you want to delete this Card?"
                                                                    href="{{route('head_office.be_spoke_form.form_card_delete',['id'=>$card->id,'_token'=>csrf_token()])}}"><i
                                                                        class="fas fa-times" ></i></a>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#default_card_form_{{$card->id}}"
                                                                    class="btn btn-warning"><i class="fa fa-wrench"></i></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        
                                                        @include('head_office.be_spoke_forms.form_card',['card' => $card,'form_cards' => $form->formCards])
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @include('head_office.be_spoke_forms.default_task',['task' => null])
        @include('head_office.be_spoke_forms.default_document',['document' => null])
        @include('head_office.be_spoke_forms.share_case_approved_email',['shared_case_approved_email' => null])
        @include('head_office.be_spoke_forms.form_card',['card' => null,'form_cards' => $form->formCards])
        @else
        <!-- This will be called for the first time only when there will be no form ! -->
        <span class="text text-info"></span>
        @endif
    <!-- End custom design -->
</div>


<!-- Modal -->
<div class="modal modal-md fade" id="stage_groups_model" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mt-2">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="" id="is_allow_non_approved_emails_route" value="{{route('head_office.case.is_allow_non_approved_emails_route')}}">

@endsection
@section('styles')


<link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
<link rel="stylesheet" href="{{asset('admin_assets/css/style.css')}}">
@endsection

@section('scripts')
@if(isset($form))
<script>
    $(document).ready(function(){
        $("#is_allow_non_approved_emails").on('change',function(){
            var route = $("#is_allow_non_approved_emails_route").val();
            var is_allow_non_approved_emails = $("#is_allow_non_approved_emails").is(':checked') ? 1 :0;
            var data = {
                '_token' : "{{csrf_token()}}",
                'is_allow_non_approved_emails' : is_allow_non_approved_emails,
                'form_id' : {{$form->id}}
            }
            $.post(route,data)
            .then(function(response){
                console.log(is_allow_non_approved_emails);
            })
            .catch(function(response)
            {
                console.log(response);
            })
        });
    });
    
</script>
@endif
<script src="{{asset('js/alertify.min.js')}}"></script>
@include('head_office.be_spoke_forms.script')

<script src="{{asset('admin_assets/js/form-template.js')}}"></script>
<script src="{{asset('v2/js/stages.js')}}"></script>
@endsection