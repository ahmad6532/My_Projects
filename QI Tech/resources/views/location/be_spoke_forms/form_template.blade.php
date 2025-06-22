@extends('layouts.location_app')
@section('title', 'Bespoke Form Template')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('be_spoke_forms.be_spoke_form.index')}}">Bespoke Forms</a></li>
        <li class="breadcrumb-item active" aria-current="page">@if(isset($form)){{substr($form->name,0,30)}} @else New
            Bespoke Form @endif</li>
    </ol>
</nav>
<div class="card">
    @include('layouts.error')

    <div class="card-header float-left">
        <h4 class="text-info font-weight-bold">Bespoke Form @if(isset($form)) - {{$form->name}} @endif</h4>
    </div>
    <div class="card-body">
        <form name="fields_form" method="post" action="{{route('be_spoke_forms_templates.form_template_save',$id)}}">
            @csrf
            <input type="hidden" name="id" value="@if(isset($form)){{$form->id}}@endif">

            <div class="row mb-3 justify-content-center text-center">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="form_name">Form Name</label>
                        
                        <select name="be_spoke_form_category_id" class="form-control">
                            @foreach (Auth::guard('location')->user()->beSpokeFormCategories as $location_category)
                            <option value="{{$location_category->id}}" @if(isset($form) && $location_category->id == $form->be_spoke_form_category_id) selected @endif>{{$location_category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="form_name">Form Name</label>
                        <input class="form-control" type="text" value="@if(isset($form)){{$form->name}}@endif"
                            id="form_name" name="form_name" placeholder="Enter Form name here" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="add_to_case_manager">Add To HO Case Manager</label>
                        <input class="form-control" type="checkbox" @if(isset($form)){{$form->add_to_case_manager ?
                        'checked' : ''}}@endif
                        id="add_to_case_manager" name="add_to_case_manager">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="color_code">Color Code</label>
                        <input class="form-control" type="color" @if(isset($form) && $form->color_code)value="{{$form->color_code}}" @endif
                        id="color_code" name="color_code">
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" name="submit" class="mt-3 btn btn-info">Save Form Name</button>
                </div>
            </div>
            <div>
            </div>
        </form>
        <!-- Custom Designs starts from here -->
        @if(isset($form))
        <h4><strong>Please add stages to your form</strong></h4>
        <form name="fields_form" method="post" action="{{route('be_spoke_forms_templates.form_stages_save')}}">
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
                                        href="{{route('be_spoke_forms_templates.form_stage_questions',[$stage->id,$group->id])}}"><i
                                            class="fas fa-address-book"></i> Create Questions</a>
                                </p>
                                @endforeach
                            </td>
                            <td calss="row_icons">
                                <a class="btn btn-info toggle_ajax_model" data-toggle="modal"
                                    data-target="#stage_groups_model" href="#"
                                    data-href="{{route('be_spoke_forms_templates.stage_groups',$stage->id)}}"><i
                                        class="fas fa-address-book"></i> Stage Groups</a>
                                <a class="btn btn-danger delete_stage"
                                    href="{{route('be_spoke_forms_templates.form_stage_delete',['id'=>$stage->id,'_token'=>csrf_token()])}}"><i
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

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Default Tasks <a href="#" data-toggle="modal" data-target="#default_task_form"
                        class="btn btn-outline-cirlce bg-white"><i class="fa fa-plus"></i></a></li>
                </ol>
            </nav>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Task Title</th>
                            <th>Task Description</th>
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
                                @if(count($task->documents))
                                <div class="cm_comment_attachments mt-1">
                                    <ul class="list-style-none p-0">
                                        @foreach($task->documents as $doc)
                                        <li class="relative ">
                                            <a class="relative @if($doc->type == 'image') cm_image_link @endif "
                                                href="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}"
                                                target="_blank"><i class="fa fa-link"></i>
                                                {{$doc->document->original_file_name()}}
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
                                @endif
                            </td>
                            <td calss="row_icons">
                                <a class="btn btn-danger delete_task"
                                    href="{{route('head_office.default_task.default_task_delete',$task->id)}}"><i
                                        class="fas fa-times"></i> Delete Task</a>
                                |
                                <a href="#" data-toggle="modal" data-target="#default_task_form_{{$task->id}}"
                                    class="btn btn-warning"><i class="fa fa-wrench"></i> Edit </a>
                                @include('head_office.be_spoke_forms.default_task',['task' => $task])
                            </td>
                        </tr>



                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @include('location.be_spoke_forms.default_task',['task' => null])
        @else
        <span class="text text-info"></span>
        @endif
    </div>
    <!-- End custom design -->
</div>

<div class="card-footer text-center">

</div>

<!-- Modal -->
<div class="modal modal-md fade" id="stage_groups_model" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mt-2">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

</div>
</div>
@endsection
@section('styles')
<link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
@endsection

@section('scripts')
<script src="{{asset('js/alertify.min.js')}}"></script>
@include('location.be_spoke_forms.script')
@endsection