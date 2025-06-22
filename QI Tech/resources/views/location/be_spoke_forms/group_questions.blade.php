@extends('layouts.location_app')
@section('title', 'Bespoke Form Questions')

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('be_spoke_forms.be_spoke_form.index')}}">Bespoke Forms</a></li>
    <li class="breadcrumb-item"><a href="{{route('be_spoke_forms_templates.form_template',$stage->form_id)}}">Form - {{substr($stage->form->name,0,30)}} </a></li>
    <li class="breadcrumb-item"><a href="{{route('be_spoke_forms_templates.form_template',$stage->form_id)}}">Stage - {{substr($stage->stage_name,0,30)}} </a></li>
    <li class="breadcrumb-item" aria-current="page">Group</li>
    <li class="breadcrumb-item active" aria-current="page">Questions</li>
  </ol>
</nav>
    <div class="card">
        @include('layouts.error')
        <div class="card-header float-left">
            <h4 class="text-info font-weight-bold">Group - {{$group->group_name}}</h4>
        </div>
        <div class="card-body">
                <!-- Custom Designs starts from here --> 
                <h4><strong>Please add question in this group</strong></h4>
                <form name="fields_form" method="post" action="{{route('be_spoke_forms_templates.form_stage_question.save',[$stage->id,$group_id])}}" >
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable">
                        <thead>
                            <tr>
                                <th>Question</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($group->questions as $q)
                            <tr>
                             <td>Type: {{ucwords(str_replace('_',' ',$q->question_type))}} <br>Name: {{$q->question_name}}<br> Label: {{$q->question_title}}
                                @if($q->question_required) <br> <span class="badge badge-primary">Required</span> @endif
                                @if(!empty(json_decode($q->question_values)))
                                <br> Values:     
                                    @foreach(json_decode($q->question_values) as $value)
                                        {{$value}}, 
                                        @endforeach
                                    @endif
                            </td>
                             <td>
                                   <a class="btn btn-info" href="{{route('be_spoke_forms_templates.form_stage_questions.edit',$q->id)}}"><i class="fas fa-edit"></i> Edit Question</a>
                                   <a class="btn btn-info" href="{{route('be_spoke_forms_templates.form_stage_questions.action',$q->id)}}"><i class="fas fa-tasks"></i> Actions</a>
                                   <a class="btn btn-danger delete_question"  href="{{route('be_spoke_forms_templates.form_stage_questions.delete',['question_id'=>$q->id,'_token'=>csrf_token()])}}"><i class="fas fa-times"></i> Delete Question</a>
                             </td>
                            </tr>

                            @endforeach
                            <tr>
                                <td colspan="2">
                                  <h4>New Question</h4>  <!-- <label for="stage_name">Question</label>  -->
                                @include('location.be_spoke_forms.field')
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <button type="submit" name="submit" class="nav-link btn btn-info inline" ><i class="fas fa-save"></i> Save Question</button>
                </div>
                </div>
                <!-- End custom design -->
            </form>
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
    <script src="{{asset('js/angular.min.js')}}"></script>
    <script src="{{asset('js/server_side/app.js')}}"></script>

    <script src="{{asset('js/server_side/bespokeController.js')}}"></script>
@include('location.be_spoke_forms.script')
@endsection