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
<div class="card card-qi content_widthout_sidebar">
    <div class="card-body">
        <div class="row justify-content-center ">
            <div class="col-md-12 mb-1">
                <div class="card vh-75 ">
                    <div class="card-body">
                        <h3 class="text-info h3 font-weight-bold">Statement</h3>
                        <h4>Hello</h4>
                        <p>We need your account of events regarding an incident that was reported</p>
                        @if ($case_request_information->note)
                            <br>
                            <b>{{$case_request_information->note}}</b><br>
                        @endif
                        <div class="table-responsive">
                            @if ($case_request_information->status == 0)
                                <form method="post" action="{{route('user.statement.single_statement_update',$case_request_information->id)}}">
                                    @csrf
                                    @foreach ($case_request_information->questions as $question)
                                        <div class="form-group">
                                            <label>{{$question->question}}</label>
                                            <textarea spellcheck="true"  class="form-control" name="answer_{{$question->id}}"></textarea>
                                            <br>
                                        </div>
                                    @endforeach
                                    <div class="uploaded_files mt-2 mb-2">
                                        <input type="file" name="file" multiple value="" class="form-control commentMultipleFiles">
                                    </div>
                                    <div class="form-group">
                                        <label for="note">Note</label>
                                        <textarea spellcheck="true"  class="form-control" name="note"></textarea>
                                    </div>
                                    <div class="from-group">
                                        <button type="submit" class="btn btn-info" >Submit</button>
                                    </div>
                                </form>
                            @else
                                @foreach ($case_request_information->questions as $question)
                                   <b>{{$question->question}}</b>
                                   <p>{{$question->answer}}</p> 
                                @endforeach        
                                @foreach($case_request_information->documents as $doc)
                                    <li>
                                        <input type='hidden' name='documents[]' class='file document' value='{{$doc->document->document->unique_id}}'>
                                        <span class="fa fa-file"></span>&nbsp;{{$doc->document->document->original_file_name()}}
                                        <a href="{{route('user.view.attachment', $doc->document->document->unique_id).$doc->document->document->extension()}}" target="_blank" title='Preview' class="preview_btn"> <span class="fa fa-eye"></span></a>
                                        {{-- <a href="#" title='Delete File' class="remove_btn"> <span class="fa fa-times"></span></a> --}}
                                    </li>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')

<link rel="stylesheet" href="{{asset('tribute/tribute.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection

@section('scripts')
<script src="{{asset('admin_assets/js/view_case.js')}}"></script>
<script src="{{asset('tribute/tribute.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('admin_assets/speech-to-text.js')}}"></script>

@endsection