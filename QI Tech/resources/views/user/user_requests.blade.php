@extends('layouts.users_app')
@section('title', 'user requests')
@section('content')

<div class="profile-center-area">
    @include('layouts.user.sub-header')
    <div style="display: flex; justify-content: center; align-items: center;">
        <div class="content-page-heading">
            Information Requests
        </div>
        <div style="position: absolute;left: 40px; margin-top:-88px;" class="search">
            <input type="search" placeholder="Search" />
            <i style="margin-left: -25px; color: #777;" class="fa fa-search icon"></i>
        </div>

    </div>
    <table class="table table-borderless" id="dataTable">
        <thead>
            <tr>
                <th>Case ID</th>
                <th>Requested By</th>
                <th>Note</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="contacts_table">
            @foreach ($user->requests as $request)
                <tr>
                    <td>
                        {{$request->case->id}}
                    </td>
                    <td>
                        {{$request->requested_by_user->first_name}} {{$request->requested_by_user->surname}}
                    </td>
                    <td>
                        {{$request->note}}
                    </td>
                    <td>
                        @if ($request->status)
                            Submitted
                        @else
                            Pending
                        @endif
                    </td>
                    <td> 
                        <a href="{{route('user.request.view',$request->id)}}" target="_blank">
                            <svg width="15" height="16" viewBox="0 0 22 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1.42012 8.71318C1.28394 8.49754 1.21584 8.38972 1.17772 8.22342C1.14909 8.0985 1.14909 7.9015 1.17772 7.77658C1.21584 7.61028 1.28394 7.50246 1.42012 7.28682C2.54553 5.50484 5.8954 1 11.0004 1C16.1054 1 19.4553 5.50484 20.5807 7.28682C20.7169 7.50246 20.785 7.61028 20.8231 7.77658C20.8517 7.9015 20.8517 8.0985 20.8231 8.22342C20.785 8.38972 20.7169 8.49754 20.5807 8.71318C19.4553 10.4952 16.1054 15 11.0004 15C5.8954 15 2.54553 10.4952 1.42012 8.71318Z" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M11.0004 11C12.6573 11 14.0004 9.65685 14.0004 8C14.0004 6.34315 12.6573 5 11.0004 5C9.34355 5 8.0004 6.34315 8.0004 8C8.0004 9.65685 9.34355 11 11.0004 11Z" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                {{-- <tr>
                    <td colspan="4">
                        <div style="background-color: #e8e8e8" class="collapse" id="reqeust_data_{{$request->id}}">
                            <input type="hidden" id="request_{{$request->id}}" value="{{$request->id}}">
                            <table class="table table-borderless">
                                <thead>
                                    <th>Question</th>
                                    <th>Answer</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @foreach ($request->questions as $question)
                                        <tr>
                                            <td>{{$question->question}}</td>
                                            <td>
                                                <input type="hidden" name="" class="question_id" value="{{$question->id}}">
                                                <textarea spellcheck="true"  class="form-control" id="{{$question->id}}" @readonly($request->status)>{{$question->answer}}</textarea>
                                            </td>
                                            <td>
                                                @if(!$request->status)
                                                <button class="btn btn-info" onclick="save_answer(this)" >Save</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="3">
                                            <button class="btn btn-info" onclick="submit_report(this)">Submit</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>     
                    </td>
                </tr> --}}
                
            @endforeach
            <tr class="line-reloading" style="display:none">
                <td colspan="4">
                    <div class="line line-date  print-display-none">
                        <div class="timeline-label"><i
                                class="spinning_icon fa-spin fa fa-spinner"></i>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>   
</div>
<input type="hidden" value="{{csrf_token()}}" id="_token">
<input type="hidden" value="{{route('user.save_answer')}}" id="route">
<input type="hidden" value="{{route('user.submit.request')}}" id="submit_request">
@endsection

@section('styles')
<link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
@endsection
@section('scripts')
<script src="{{asset('/js/alertify.min.js')}}"></script>
<script>
    function save_answer(element)
    {
        var text = $(element).parent().parent().find("textarea").val();
        var question_id = $(element).parent().parent().find("input").val();
        var _token = $("#_token").val();
        var route = $("#route").val();
        var data = {
            answer : text,
            question_id : question_id,
            _token : _token
        }
        if(!text)
        {
            var textarea = $(element).parent().parent().find("textarea");
            alertify.alert("Alert", 'Answer cannot be empty');
            $(textarea).focus();
            return;
        }
        $.get(route,data)
        .then(function(response){
            console.log(response);
        })
        .catch(function(error)
        {
            console.log(error);
        })
    }
    function submit_report(element)
    {
        textares = $(element).parent().parent().parent().find('textarea');
        var _token = $("#_token").val();
        var route = $("#submit_request").val();
        var data = {};
        textares.each(
            function(i){
            if(!$(this).val())
            {
                $(this).focus();
                alertify.alert("Alert", 'Please enter answer to continue.');
                return;
            }
            data['question_'+$(this).prop('id')] = $(this).prop('id');
            data['answer_'+$(this).prop('id')] = $(this).val();
        });
        data['_token'] = _token;
        var value = $(element).parent().parent().parent().parent().parent().find('input')[0];
        data['request_id'] = $(value).val()
        console.log(data);
        $.post(route,data)
        .then(function(response){
            if(response.result)
            {
                var buttons = $(element).parent().parent().parent().parent().parent().find('button');
                var textareas = $(element).parent().parent().parent().parent().parent().find('textarea');
                $(buttons).hide();
                $(textareas).prop('readonly',true);
                var tr = $(element).parent().parent().parent().parent().parent().parent().parent().prev().find('td:nth-child(4)');
                $(tr).empty()
                $(tr).text('Submitted');
            }
            alertify.alert("Alert", response.msg);
        })
        .catch(function(error){
            console.log(error);
        })
    }
</script>
@endsection
@section('sidebar')
@include('layouts.user.sidebar-header')
@endsection


