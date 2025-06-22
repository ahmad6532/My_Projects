@extends('layouts.head_office_app')
@section('title', 'Case '.$case->id())

@section('topbar_nav_items')
<li> <a class="@if(request()->route()->getName() == 'case_manager.view') active @endif"
        href="{{route('case_manager.view',$case->id)}}"><span>Case Notes</span></a> </li>
<li> <a class="@if(request()->route()->getName() == 'case_manager.view_report') active @endif"
        href="{{route('case_manager.view_report',$case->id)}}"><span>View Report</span></a> </li>
<li> <a class="@if(request()->route()->getName() == 'case_manager.view_root_cause_analysis') active @endif"
        href="{{route('case_manager.view_root_cause_analysis',$case->id)}}"><span>Root Cause Analysis</span></a> </li>
<li> <a class="@if(request()->route()->getName() == 'case_manager.view_sharing') active @endif"
        href="{{route('case_manager.view_sharing',$case->id)}}"><span>Sharing</span></a> </li>
<li> <a class="@if(request()->route()->getName() == 'case_manager.view_intelligence') active @endif"
        href="{{route('case_manager.view_intelligence',$case->id)}}"><span>Intelligence</span></a> </li>
<li> <a class="@if(request()->route()->getName() == 'case_manager.view_drafts') active @endif"
        href="{{route('case_manager.view_drafts',$case->id)}}"><span>Drafts</span></a> </li>
@endsection

@section('content')
@include('layouts.error')
@section('styles')
<link href="{{asset('admin_assets/css/fish-bone-model.css')}}" rel="stylesheet"/>
@endsection
<div class="row">
    <div class="col-12">
<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <div class="float-left">
                <h4 class="text-info font-weight-bold">Fish Bone</h4>
            </div>
        </div>

        
            <div class="col-12 fish-bone-model">

                <div class="header-area">
                    <div class="p1">Cause</div>
                    <div class="p2">Effect</div>
                </div>

                <div class="model-area p1">
                    <div class="centeral-line fish-bone-border-color"></div>
                    
                    @foreach ($root_cause_analysis->fish_bone_questions as  $question)
                    <div class="side-line fish-bone-border-color">
                        <span class="title">{{$question->question}}</span>
                        <!-- Heading -->
                        @if(!$root_cause_analysis->status || $root_cause_analysis->status == 2)
                        <input type="hidden">
                        <!-- Branches -->
                        @else
                        <input type="hidden">
                        @endif

                        <input type="hidden" class="route" value="{{route('route_cause_analysis.request.store_question_answer',['question_id' => $question->id,'root_cause_analysis_id' => $root_cause_analysis->id])}}">
                        @foreach ($question->answers as $answer)
                        <div class="question_answer_div"> 
                          
                            <input class="form-control question_answer" onfocusout="focus_out(this,id)" readonly name="question" value="{{$answer->answer}}" /> 

                            
                        </div> 
                        @endforeach
                    </div>
                    @endforeach

                </div>
                
                <div class="reason-area p2">
                    <textarea spellcheck="true"  class="text-box form-control" id="reason-text-area" name="problem"readonly placeholder="Enter a text here....">{{$root_cause_analysis->name}}</textarea>
                </div>
                
            </div>
            

        @if($root_cause_analysis->note)
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="">Note</label>
                    <textarea spellcheck="true"  readonly class="form-control">
                        {{$root_cause_analysis->note}}
                    </textarea>
                </div>
            </div>

        </div>
        @endif
    </div>
    

</div>
</div>
@endsection


@section('scripts')
<script>
    $('.custom_question').on('change',function(){
        $(".custome_question").is(':checked')
        {
            if($(this).val() === 'custom')
            {
                $('#custom').show();
                $("#custom_button").show();
                $("#custom").append('<div class="form-group"><label>Question 1</label><input type="text" name="questions[]" multiple="multiple" class="form-control" required=""></div>');
            }
            else
            {
                $('#custom').empty();
                $("#custom_button").hide()
                $('#custom').hide();
            }
        }
    });
    $("#custom_button").on('click',function(){
        if($('#custom').find('.form-group'))
            var len = 1 + parseInt($('#custom').find('.form-group').length);
        else
            var len = 1;
        if(len > 14)
        {
            alert('Questions can be more then 14');
        }
        else
            $("#custom").append('<div class="form-group"><label>Question '+ len +'</label><input type="text" name="questions[]" multiple="multiple" class="form-control" required=""></div>');
    });

    $('.five_why_custom_question').on('change',function(){
        $(".five_why_custome_question").is(':checked')
        {
            if($(this).val() === 'custom')
            {
                $('#five_why_custom').show();
                $("#five_why_custom_button").show();
                $("#five_why_custom").append('<div class="form-group"><label>Question 1</label><input type="text" name="questions[]" multiple="multiple" class="form-control" required=""></div>');
            }
            else
            {
                $('#five_why_custom').empty();
                $("#five_why_custom_button").hide()
                $('#five_why_custom').hide();
            }
        }
    });
    $("#five_why_custom_button").on('click',function(){
        if($('#five_why_custom').find('.form-group'))
            var len = 1 + parseInt($('#five_why_custom').find('.form-group').length);
        else
            var len = 1;
        if(len > 14)
        {
            alert('Questions can be more then 14');
        }
        else
            $("#five_why_custom").append('<div class="form-group"><label>Question '+ len +'</label><input type="text" name="questions[]" multiple="multiple" class="form-control" required=""></div>');
    });
</script>
<script src="{{asset('tribute/tribute.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('admin_assets/speech-to-text.js')}}"></script>
@endsection