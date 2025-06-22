@extends('layouts.head_office_app')
@section('title', 'Bespoke Form Question Edit')
@section('top-nav-title', 'Bespoke Form Question Edit')
@section('content')
{{-- <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('head_office.be_spoke_form.index')}}">Bespoke Forms</a></li>
        <li class="breadcrumb-item"><a
                href="{{route('head_office.be_spoke_forms_templates.form_template',$question->form_id)}}">Form -
                {{substr($question->form->name,0,30)}} </a></li>
        <li class="breadcrumb-item"><a
                href="{{route('head_office.be_spoke_forms_templates.form_template',$question->form_id)}}">Stage -
                {{substr($question->stage->stage_name,0,30)}} </a></li>
        <li class="breadcrumb-item">Group</li>
        <li class="breadcrumb-item "><a
                href="{{route('head_office.be_spoke_forms_templates.form_stage_questions',[$question->stage_id,$question->group_id])}}">Questions</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
</nav> --}}
<div id="content">
    <div class="content-page-heading">
        Stage - {{$question->stage->stage_name}}
    </div>
    @include('layouts.error')
    <div class="card-body">
        <!-- Custom Designs starts from here -->
        <h4><strong>Edit Question</strong></h4>
        <form name="fields_form" method="post"
            action="{{route('head_office.be_spoke_forms_templates.form_stage_question.save',[$question->stage_id,$question->group_id])}}">
            <input type="hidden" name="question_id" value="{{$question->id}}">
            @csrf
            @include('head_office.be_spoke_forms.field')
            <br>
            <button type="submit" name="submit" class="nav-link btn btn-info inline"><i class="fas fa-save"></i> Save
                Question</button>
        </form>
    </div>
    <div class="card-footer text-center">
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

<script>
    $(document).ready(function(){
    if($('.form_card_id').val() !== 0 )
    {
        var id = $(".form_card_id").val();
        var route = $("#form_card_fields").val();
        var data = {'id' : id
            }
        var default_card_field_id = $("#selected_field_id").val();
        $.post(route, data).then(function(response){
            if(response.result)
            {
                var text = ""; 
                response.fields.forEach(element => {
                    if(element.id === parseInt(default_card_field_id))
                        text += "<option value="+element.id+" selected>"+element.field_name+"</option>";
                    else
                        text += "<option value="+element.id+">"+element.field_name+"</option>"
                });
                $(".card_fields").show();
                $("#default_card_field_id").empty();
                $("#default_card_field_id").append(text)
            }
        })
    }
})
</script>
@endsection