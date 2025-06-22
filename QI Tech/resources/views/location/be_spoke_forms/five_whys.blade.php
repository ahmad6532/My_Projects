@extends('layouts.location_app')
@section('title', 'Five whys root cause analysis request')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item" aria-current="page">Root cause analysis Five Why's</li>
    </ol>
</nav>

@include('layouts.error')
@section('styles')
<link href="{{asset('admin_assets/css/five-why-model.css')}}" rel="stylesheet" />
@endsection
<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <div class="">
                <h4 class="text-info font-weight-bold">Five Why's</h4>
            </div>
        </div>
        
        <!-- 5 Whys design model -->
        <form method="post" id="myForm" action="{{route('root_cause_analysis_save',$request->id)}}">
            @csrf
        <div class="five-why-container">
            <div class="problem">
                
                @if($request->is_editable)
                <p>
                    <span class="editable-input input" role="textbox" contenteditable onfocusout="problem(this)">{{$request->name}}</span>
                    <input type="hidden" value="{{$request->name}}" name="problem" id="problem">
                </p>
                @else
                <p>
                    {{$request->name}}
                </p>
                @endif
            </div>
            <div class="five_whys">
                <!-- Q 1 -->
                @foreach ($request->five_whys_questions as $key => $question)
                    
                
                <div class="five-why counter_{{$key}}" @if($key) style="display: none" @endif>
                    <div class="element-shape why">
                        <span class="before"></span>
                        <p>Why?
                        </p>
                        <span class="after"></span>
                    </div>
                    <div class="answer element-shape">
                        <span class="before"></span>
                        <p>
                            @if($request->status == 1 || $request->status == 2)
                                <span>{{optional($question->answers)->answer}}</span>
                            @else
                            <span class="editable-input input" role="textbox" contenteditable onfocus="on_focus(this)"  onfocusout="focus_out(this)">{{optional($question->answers)->answer}}</span>
                            {{-- <input type="hidden" value="{{optional($question->answer)->answer}}" class="form-control" onfocusout="focus_out(this,id)"> --}}
                            <input type="hidden" class="route" value="{{route('route_cause_analysis.request.store_question_answer',['question_id' => $question->id,'root_cause_analysis_id' => $request->id])}}">
                            <input type="hidden" name="answer_id" class="answer_id" value="{{optional($question->answers)->id}}">
                            @endif
                        </p>
                        <span class="after"></span>
                    </div>
                </div>
                @endforeach
                
            </div>
        </div>


        <!-- End of 5 whys -->


        
            <div style="width: 300px; margin:auto">
                @if(!$request->status)
                <button type="submit" title="Submit" class="btn btn-info">Submit</button>
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
<input type="hidden" id="text_answer">

<input type="hidden" id="record_id" value="{{$id}}">

<input type="hidden" id="delete_answer" value="{{route('root_cause_analysis_answer_delete')}}">
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
    
    function on_focus(ele)
    {
        var div = $(ele).parents('.five-why');
        if($(div).next().length > 0)
        {
            console.log($(div).next().show());
        }
    }
    $(document).ready(function(){
        if($('.editable-input').text().length > 0)
        {
            $('.editable-input').parents('.five-why').show();
        }
    })
//     $("#myForm").on("submit", function(event) {
//     event.preventDefault();

//     // Validate form, returning on failure.

//     console.log($(this).children(".editable-input").text());

//     //this.submit();
// });

</script>
<script>
 function problem(elem, id)
    {
        var problem = $(elem).text();
        $("#problem").val(problem);
    }
    function focus_out(elem, id)
    {
        var answer = $(elem).text();
        var route = $(elem).parent('p').children('.route').val();
        var data = {};
        data = {
            'answer' : answer,
        }
        
            var answer_id = $(elem).parent('p').children('.answer_id').val();
            if(answer_id)
            {
                data = {
                    'answer_id' : answer_id,
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

    

</script>
@endsection
@endsection