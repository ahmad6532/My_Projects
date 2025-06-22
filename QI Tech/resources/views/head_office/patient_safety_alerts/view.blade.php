@extends('layouts.head_office_app')
@section('title', 'View Patient Safety Alert')
@section('content')
<div class="" id="content">
    <div class="card-body">
        <div class="row">
            <div class="col-md-7">
                <p><a href="{{ url()->previous() }}" class="text-info"><i class="fa fa-arrow-left"></i> Back</a></p>
                <p class="text-muted">{{$alert->date_with_year()}}</p>
                <h4 class="psa_title text-black font-weight-bold ">{{$alert->national_alert->title}}</h4>
                <p class="psa_class">
                    <span class="alert_class {{$alert->national_alert->alertColor()}}" data-toggle="tooltip"  title="{{$alert->national_alert->getClassDescripiton($alert->national_alert->class)}}">
                        @if($alert->national_alert->class !== 'None')
                        <span class="alert_class_name">{{$alert->national_alert->class}}</span> 
                        <span class="alert_class_description ">{{$alert->national_alert->showClassTitle($alert->national_alert->class)}}</span>
                        @endif
                    </span>
                </p>
                <p><b>Action Within:</b> {{$alert->national_alert->showActionWithinTitle()}}</p>
                @if($alert->national_alert->type != 'None')
                <p class="psa_type">
                    <b>Type: </b>
                    @if($alert->national_alert->type  == 'Custom') {{$alert->national_alert->custom_type }}
                    @else {{ $alert->national_alert->type }}
                        @if($alert->national_alert->type == 'Company-Led Medicines Recall/Notification' || $alert->national_alert->type == 'Medicines Recall' ) 
                        @if($alert->national_alert->patient_level_recall) <br><span class="badge badge-danger">Patient Level Recall</span> @endif
                        @endif 
                    @endif
                </p>
                @endif
                <p class="psa_originators">
                    <b>Originators: </b>
                    @foreach($alert->national_alert->originators as $o) 
                    @if($o->originator == 'Custom')
                        {{$alert->national_alert->custom_originator}} @if(!$loop->last), @endif
                    @else
                    {{$o->originator}}@if(!$loop->last), @endif
                    @endif
                @endforeach

                </p>
                <p class="psa_summary">
                {{$alert->national_alert->summary}}
                </p>
                @if(!empty($alert->national_alert->suggested_actions))
                <p class="psa_suggested_actions">
                    <b>Suggested Actions</b><br>
                    {{$alert->national_alert->suggested_actions}}
                </p> 
                @endif
                <p class="psa_overdue">
                @if($alert->national_alert->is_overdue() && $alert->status  == App\Models\LocationReceivedAlert::$unactionedStatus)
                 <div class="overdue"> <i class="fas fa-exclamation-triangle overdue-icon"></i>
                  <strong>Overdue</strong> - by {{$alert->national_alert->generateOverDueString()}}!
                  </div>
                @endif
                </p>
                <p class="psa_attachments">
                    <b>Attachments</b><br>
                    @if(count($alert->national_alert->documents) == 0) <span class="font-italic">No attachments are added</span>@endif
                    @foreach($alert->national_alert->documents as $doc)
                        <a href="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}" target="_blank">{{$doc->document->original_file_name()}}</a><br>
                    @endforeach
                </p>

            </div>
            <div class="col-md-5 mt-5">
                <div class="psa_actions">
                    @if($alert->status == 'unapproved' && $alert->has_child_alert() == 0 && $headOffice->holding_area_on )
                        <a href="{{route('head_office.psa.holding_area.record',[$alert->national_alert->id,'cloning'=>($alert->national_alert->created_by == 'CAS'?1:0)])}}" class="btn psa_action_btn btn-info text-white">Edit Alert (before approving)</a>
                    @elseif($alert->national_alert->created_by == 'head_office')
                        <a href="{{route('head_office.psa.holding_area.record',[$alert->national_alert->id,'cloning'=>($alert->national_alert->created_by == 'CAS'?1:0)])}}" class="btn psa_action_btn btn-info text-white">Edit Alert</a>
                    @endif
                    <a href="#" class="btn psa_action_btn">Archive</a>
                </div>
            </div>   
        </div>
        <br>
    </div>
</div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
@endsection

@section('scripts')
    <script src="{{asset('js/alertify.min.js')}}"></script>
@endsection