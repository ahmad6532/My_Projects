@extends('layouts.head_office_app')
@section('title', 'Patient Safety Alerts')
@section('content')
    <div id="content">
        <div class="headingWithSearch">
        
            <div class="heading-center">
                Holding Area
            </div>
        </div>
            @if(!$headOffice->holding_area_on)
                <p class="text-muted font-italic"><i class="fas fa-exclamation-triangle"></i> Holding area is turned off in settings. New alerts will directly go to the locations.</p>
            @endif
            
            @include('layouts.error')
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date of Alert</th>
                            <th>Type</th>
                            <th>Origin</th>
                            <th>Name</th>
                            <th>Class</th>
                            <th>Send To</th>
                            <th>Suggested Actions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!count($alerts))
                        <tr><td colspan="8" class="font-italic">No Unapproved Patient Safety Alerts Found.</td></tr>
                        @else
                        @foreach($alerts as $alert)
                        <tr>
                            <td>{{ date('d/m/Y',strtotime($alert->national_alert->start_time))}}
                            <br>  {{ date('h:i a',strtotime($alert->national_alert->start_time))}}
                            @if(time() < strtotime($alert->national_alert->start_time))<div class="badge badge-success">Scheduled</div>@endif
                            </td>
                            <td>
                            @if($alert->national_alert->type  != 'None')
                                <b>Type:</b> @if($alert->national_alert->type  == 'Custom') {{$alert->national_alert->custom_type }}
                                @else {{ $alert->national_alert->type }}
                                    @if($alert->national_alert->type == 'Company-Led Medicines Recall/Notification' || $alert->national_alert->type == 'Medicines Recall' ) 
                                    @if($alert->national_alert->patient_level_recall) <br><span class="badge badge-danger">Patient Level Recall</span> @endif
                                    @endif 
                                @endif
                                <br><br>
                                @endif
                            <b>Originator(s):</b>
                            @foreach($alert->national_alert->originators as $o) 
                                        @if($o->originator == 'Custom')
                                            {{$alert->national_alert->custom_originator}} @if(!$loop->last), @endif
                                        @else
                                        {{$o->originator}}@if(!$loop->last), @endif
                                        @endif
                                    @endforeach
                            </td>
                            <td>@if($alert->national_alert->created_by == 'CAS') Central Alerting System @else Head Office @endif</td> 
                            <td>{{$alert->national_alert->title}}</td> 
                            <td>
                                @if($alert->national_alert->class !== 'None')
                                    <span class="alert_class {{$alert->national_alert->alertColor()}}" data-toggle="tooltip"  title="{{$alert->national_alert->getClassDescripiton($alert->national_alert->class)}}">
                                    <span class="alert_class_name">{{$alert->national_alert->class}}</span> 
                                    <span class="alert_class_description ">{{$alert->national_alert->showClassTitle($alert->national_alert->class)}}</span>
                                @else 
                                <span class="alert_class_name">Class None</span> 
                                @endif
                            </span>
                            <br><br>
                            <b>Action Within:</b> {{$alert->national_alert->showActionWithinTitle()}}
                        </td>  
                        <td>
                            <b>Countries:</b>
                            @if($alert->national_alert->send_to_all_countries) All @endif {{$alert->national_alert->countries->implode('country', ', ')}}
                            <br><br><b>Positions:</b>
                                @if($alert->national_alert->send_to_all_designations) All @else
                                    @foreach($alert->national_alert->designations as $d) 
                                        {{$d->position->name}}@if(!$loop->last), @endif 
                                    @endforeach
                                @endif
                            @if($alert->national_alert->created_by != 'CAS')
                            <br><br><b>Branch/Pharmacies:</b>
                                @if($alert->national_alert->send_to_all_locations) All @else
                                    @foreach($alert->national_alert->locations as $d) 
                                        {{$d->location->short_name()}}@if(!$loop->last), @endif 
                                    @endforeach
                                @endif
                            @endif
                            @if($alert->national_alert->created_by != 'CAS')
                            <br><br><b>Tier/Groups:</b>
                                @if($alert->national_alert->send_to_groups == 'all') All @else
                                    @foreach($alert->national_alert->groups as $d) 
                                        {{$d->group->group}}@if(!$loop->last), @endif 
                                    @endforeach
                                @endif
                            @endif
                        </td>
                        <td>{{$alert->national_alert->suggested_actions}}</td>
                        <td>
                            <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                            <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton4">
                                <a href="{{route('head_office.psa.view',$alert->id)}}" class="dropdown-item" title="View">View</a>
                                @if($alert->has_child_alert() == 0)
                                    <a href="{{route('head_office.psa.holding_area.record',[$alert->national_alert->id,'cloning'=>($alert->national_alert->created_by == 'CAS'?1:0)])}}" class="dropdown-item" title="Edit">
                                    @if($alert->national_alert->created_by == 'CAS') Edit (before approving) @else Edit @endif</a>
                                @endif
                                <a href="{{route('head_office.psa.approve',$alert->id)}}" class="dropdown-item" title="Approve">Approve</a>
                                <a href="{{route('head_office.psa.reject',['id'=>$alert->id,'_token'=>csrf_token()])}}" class="dropdown-item text-danger delete_button" data-msg="Are you sure you want to reject this error?" title="Reject">Reject</a>
                                <a href="{{route('head_office.psa.archive',['id'=>$alert->id,'_token'=>csrf_token()])}}" class="dropdown-item" title="Archive">Archive</a>
                            </div>
                        </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                <div>
                {!! $alerts->render('pagination::bootstrap-5') !!}
                </div>
                <br><br><br>
            </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
@endsection

@section('scripts')
    <script src="{{asset('js/alertify.min.js')}}"></script>
@endsection