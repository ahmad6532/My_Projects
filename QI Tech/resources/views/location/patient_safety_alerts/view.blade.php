@extends('layouts.location_app')
@section('title', 'View Patient Safety Alert')
@section('content')
@if(request()->query('success'))
    <div class="alert mt-5 to_hide_10 alert-success w-50" style="margin:0 auto">
        {{request()->query('success')}} 
        <i class="right to_hide_to_manual fa fa-times" onclick="$('.to_hide_10').hide()"></i>
    </div>
    @endif
    @if(request()->query('error'))
    <div class="alert to_hide_10 alert-danger w-50" style="margin:0 auto">
        {{request()->query('error')}} 
        <i class="right to_hide_to_manual fa fa-times" onclick="$('.to_hide_10').hide()"></i>
    </div>
    @endif
<div class="card vh-75 card-qi container mt-5">
    <div class="card-body">
        <div class="row">
            <div class="col-md-7">
                <p><a href="{{route('location.view_patient_safety_alerts')}}" class="text-info"><i class="fa fa-arrow-left"></i> Back</a></p>
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
                        <a @if(!$alert->action() && !$alert->document_is_read) onclick="makeAttachmentRead({{$alert->id}})" @endif href="{{route('location.view.attachment', $doc->document->unique_id).$doc->document->extension()}}" target="_blank">{{$doc->document->original_file_name()}}</a><br>
                    @endforeach
                </p>

            </div>
            <div class="col-md-5 mt-5">
                    <form>
                        <h5 class="psa_action_title text-center text-black mt-3">What have you done about this alert?</h5>
                        <div class="psa_actions">
                            <button type="button" data-value="read_alert" @if(count($alert->national_alert->documents) > 0 && !$alert->action() && !$alert->document_is_read) onclick="event.stopPropagation();alertify.alert('Please read the attachments first to continue.')" @endif  data-toggle="modal" data-target="#pas_action_modal" class="btn psa_action_btn @if($alert->action() && $alert->action()->action_type == 'read_alert' ) active @endif" name="psa_action_1">I have read this alert @if($alert->action() && $alert->canEditAndDelete() && $alert->action()->action_type == "read_alert" ) <a href="{{route('location.remove_action_patient_safety_alert',['id'=>$alert->action()->id,'_token'=>csrf_token()])}}" title="Remove Action" onclick=" event.stopPropagation(); return confirm('Are you sure you want to delete this action?')" class="remove_action_btn text-danger"><i class="fa fa-trash"></i></a> @endif</button>
                            <button type="button" data-value="read_and_taken_action" @if(count($alert->national_alert->documents) > 0 && !$alert->action() && !$alert->document_is_read) onclick="event.stopPropagation();alertify.alert('Please read the attachments first to continue.')" @endif data-toggle="modal" data-target="#pas_action_modal" class="btn psa_action_btn @if($alert->action() && $alert->action()->action_type == 'read_and_taken_action' ) active @endif" name="psa_action_2">Read & taken action @if($alert->action() && $alert->canEditAndDelete() && $alert->action()->action_type == "read_and_taken_action" ) <a href="{{route('location.remove_action_patient_safety_alert',['id'=>$alert->action()->id,'_token'=>csrf_token()])}}" title="Remove Action" onclick=" event.stopPropagation(); return confirm('Are you sure you want to delete this action?')"  class="remove_action_btn text-danger"><i class="fa fa-trash"></i></a> @endif</button>
                            <button type="button" data-value="not_relevant_to_my_practice" @if(count($alert->national_alert->documents) > 0 && !$alert->action() && !$alert->document_is_read) onclick="event.stopPropagation();alertify.alert('Please read the attachments first to continue.')" @endif data-toggle="modal" data-target="#pas_action_modal" class="btn psa_action_btn @if($alert->action() && $alert->action()->action_type == 'not_relevant_to_my_practice' ) active @endif" name="psa_action_3">Not relevant to my practice @if($alert->action() && $alert->canEditAndDelete() && $alert->action()->action_type == "not_relevant_to_my_practice" ) <a href="{{route('location.remove_action_patient_safety_alert',['id'=>$alert->action()->id,'_token'=>csrf_token()])}}" title="Remove Action" onclick=" event.stopPropagation(); return confirm('Are you sure you want to delete this action?')" class="remove_action_btn text-danger"><i class="fa fa-trash"></i></a> @endif</button>
                        </div>
                    </form>
                    @if(!$alert->action() || ($alert->action() && $alert->canEditAndDelete()))
                    <div class="modal fade" id="pas_action_modal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <button type="button" class="close model_close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                                    <form method="post" id="pas_action_form" action="{{route('location.patient_safety_alert_action.save', $alert->id)}}">
                                        @include('location.patient_safety_alerts.action_form')
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>   
        </div>
        <br>
        <div class="staff_actions">
            <h4 class="text-black bold">Staff Actions</h4>
            <div class="row">
                <div class="col-md-4">
                    <h5>Read Alert</h5>
                    <div class="action_people_wrapper">
                        @foreach(\App\Models\LocationReceivedAlert::actionsBasedOnType($alert->id,'read_alert') as $action)
                            <div class="action_person" title="{{$action->user->nameWithPosition()}}">{{$action->user->initials}}</div>
                        @endforeach
                       
                        @foreach($alert->quickLoginsWhoReadTheAlert() as $quicklogin)
                            <div class="action_person" title="{{$quicklogin->user->nameWithPosition()}}">{{$quicklogin->user->initials}}</div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-4">
                    <h5>Taken Action</h5>
                    <div class="action_people_wrapper action_people_wrapper_with_comments ">
                    @if(count(\App\Models\LocationReceivedAlert::actionsBasedOnType($alert->id,'read_and_taken_action')))
                        @foreach(\App\Models\LocationReceivedAlert::actionsBasedOnType($alert->id,'read_and_taken_action') as $action)
                            <div data-target=".comment_section_{{$action->id}}" class="action_person action_person_comments" title="{{$action->user->nameWithPosition()}}">
                                {{$action->user->initials}}
                            </div>
                            @include('location.patient_safety_alerts.taken_action')
                        @endforeach
                    @endif
                    </div>    
                    <br>
                   
                </div>
                <div class="col-md-4">
                    <h5>Not rel </h5>
                    @if(count(\App\Models\LocationReceivedAlert::actionsBasedOnType($alert->id,'not_relevant_to_my_practice')))
                        @foreach(\App\Models\LocationReceivedAlert::actionsBasedOnType($alert->id,'not_relevant_to_my_practice') as $action)
                            <div class="action_person" title="{{$action->user->nameWithPosition()}}">{{$action->user->initials}}</div>
                        @endforeach
                    @endif
                </div>
            </div>
            <br><br>
            <div class="action_statistics">
                <h4 class="text-black bold">Statistics</h4>
            </div>
        </div>

    </div>
</div>
@endsection

@section('styles')   
    <link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script  src="{{asset('/js/alertify.min.js')}}"></script>
@endsection