@extends('layouts.location_app')
@section('title', 'Bespoke Form Question Edit')

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('be_spoke_forms.be_spoke_form.index')}}">Bespoke Forms</a></li>
    <li class="breadcrumb-item"><a href="{{route('be_spoke_forms_templates.form_template',$question->form_id)}}">Form - {{substr($question->form->name,0,30)}} </a></li>
    <li class="breadcrumb-item"><a href="{{route('be_spoke_forms_templates.form_template',$question->form_id)}}">Stage - {{substr($question->stage->stage_name,0,30)}} </a></li>
    <li class="breadcrumb-item">Group</li>
    <li class="breadcrumb-item "><a href="{{route('be_spoke_forms_templates.form_stage_questions',[$question->stage_id,$question->group_id])}}">Questions</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
  </ol>
</nav>
    <div class="card">
        @include('layouts.error')
        <div class="card-header float-left">
            <h4 class="text-info font-weight-bold">Stage - {{$question->stage->stage_name}}</h4>
        </div>
        <div class="card-body">
            <!-- Custom Designs starts from here -->
            <h4><strong>Edit Question</strong></h4>
            <form name="fields_form" method="post"
                action="{{route('be_spoke_forms_templates.form_stage_question.save',[$question->stage_id,$question->group_id])}}">
                <input type="hidden" name="question_id" value="{{$question->id}}">
                @csrf
                @include('location.be_spoke_forms.field')
                <br>
                <button type="submit" name="submit" class="nav-link btn btn-info inline" ><i class="fas fa-save"></i> Save Question</button>
            </form>
        </div>
            <div class="card-footer text-center">
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
    <script src="{{asset('js/server_side/app.js')}}"></script>
@include('location.be_spoke_forms.script')
@endsection