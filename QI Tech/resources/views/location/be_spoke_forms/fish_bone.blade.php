@extends('layouts.location_app')
@section('title', 'Root cause analysis request')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item" aria-current="page">Root cause analysis Fish Bone</li>
    </ol>
</nav>

@include('layouts.error')
@section('styles')
<link href="{{asset('admin_assets/css/fish-bone-model.css')}}" rel="stylesheet"/>
@endsection
<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <div class="float-left">
                <h4 class="text-info font-weight-bold">Fish Bone</h4>
            </div>
        </div>

        
        <form method="post" action="{{route('root_cause_analysis_save',$request->id)}}">
            @csrf
            <div class="col-12 fish-bone-model">

                <div class="header-area">
                    <div class="p1">Cause</div>
                    <div class="p2">Effect</div>
                </div>

                <div class="model-area p1">
                    <div class="centeral-line fish-bone-border-color"></div>
                    
                    @foreach ($request->fish_bone_questions as  $question)
                    <div class="side-line fish-bone-border-color">
                        <span class="title">{{$question->question}}</span>
                        <!-- Heading -->
                        @if(!$request->status || $request->status == 2)
                        <a href="javascript:void(0)" class="btn btn-info add_branch" data-content="{{$question->question}}" data-id="{{$question->id}}"><span class="fa fa-plus"></span></a>
                        <!-- Branches -->
                        @else
                        <input type="hidden">
                        @endif

                        <input type="hidden" class="route" value="{{route('route_cause_analysis.request.store_question_answer',['question_id' => $question->id,'root_cause_analysis_id' => $request->id])}}">
                        @foreach ($question->answers as $answer)
                        <div class="question_answer_div"> 
                            @if(!$request->status || $request->status == 2)
                           
                            <div class="custom_overlay">
                                <span class="custom_overlay_inner">
                                    <a href="#" class="delete-btn-item"><i class="fa fa-trash" onclick="del_item(this)" data-content="{{$answer->id}}"></i></a>
                                </span>
                            </div>
                            @endif
                            <input class="form-control question_answer" onfocusout="focus_out(this,id)" name="question" value="{{$answer->answer}}" /> 
                            <input type="hidden" value="{{$answer->id}}" class="question_answer_id">
                            
                        </div> 
                        @endforeach
                    </div>
                    @endforeach

                </div>
                
                <div class="reason-area p2">
                    <textarea spellcheck="true"  class="text-box form-control" id="reason-text-area" name="problem" @if(!$request->is_editable) readonly @endif placeholder="Enter a text here....">{{$request->name}}</textarea>
                </div>
                
            </div>
            <div style="width: 300px; margin:auto">
                @if(!$request->status)
                <button type="submit" class="btn btn-info">Submit</button>
                @endif
            </div>
        </form>
        @if($request->note)
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="">Note</label>
                    <textarea spellcheck="true"  readonly class="form-control">
                        {{$request->note}}
                    </textarea>
                </div>
            </div>

        </div>
        @endif
    </div>
    

</div>
<input type="hidden" id="text_answer" >

<input type="hidden" id="record_id" value="{{$id}}" >

<input type="hidden" id="delete_answer" value="{{route('root_cause_analysis_answer_delete')}}" >
@section('scripts')
<script>
    jQuery(document).on('click','.organisation-tag, .btn-level ',function(e){
        // Prevent child clicks hidding the modal
        if (e.target !== this){
            //return;
        }
        jQuery('.action-bar').not(jQuery(this).find('.action-bar')).removeClass('show');
        jQuery(this).find('.action-bar').first().toggleClass('show');

    });
    
</script>
<script>
    $('.model-area a').on('click', function(e) {
        $('#reason-text-area').text($(this).attr('data-content'));
    });
    $('.add_branch').on('click',function(){
        var route = $(this).closest('.route').val();
            if($(this).closest('.side-line').find('.question_answer_div'))
                var len = 1 + $(this).closest('.side-line').find('.question_answer_div').length;
            else
                var len = 1;
            if(len > 4)
            {
                alert('Can only give 4 answers');
            }
            else
            $(this).closest('.side-line').append('<div class="question_answer_div"><a href="#" class="delete-btn-item"><i class="fa fa-trash" onclick="del_item(this)"></i></a> <input class="form-control question_answer" onfocusout="focus_out(this,id)" name="question" value="" /> </div>');


    })

    function focus_out(elem, id)
    {
        var answer = $(elem).val();
        var route = $(elem).parent('.question_answer_div').parent('.side-line').children('.route').val();
        var data = {};
        data = {
            'record_id' : $('#record_id').val(),
            'answer' : answer,
        }
        if($(elem).parent('.question_answer_div').children('.question_answer_id').length > 0)
        {
            var answer_id = $(elem).parent('.question_answer_div').children('.question_answer_id').val();
            data = {
                'answer_id' : answer_id,
                'record_id' : $('#record_id').val(),
                'answer' : answer,
            }
        }
        
        $.post(route,data)
        .then(function(response){
            if(response.result);
            {
                $(elem).parent('.question_answer_div').children('a').children('.fa-trash').attr('data-content', response.answer.id);
                $("#text_answer").val(response.answer);
            }
        })
        .catch(function(response){
            console.log(response);
        });
    }

    function del_item(elem)
    {
        var value = $(elem).data('content');
        var d = $(elem).parents('.question_answer_div');
        var route = $("#delete_answer").val();
        var data = {
            'id' : value
        }
        d.remove();
            
        
            $.post(route,data)
            .then(function(response)
            {
                if(response.result)
                {
                    console.log(d);
                    //d.remove();
                }
            })
            .catch(function(response){
                console.log(response);
            })

        
        
        
    }
    // $(".fa-trash").on('click',function(){
        
    // });

    </script>
@endsection
@endsection