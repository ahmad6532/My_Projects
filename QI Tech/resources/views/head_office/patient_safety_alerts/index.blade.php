@extends('layouts.head_office_app')
@section('title', 'Patient Safety Alerts')
@section('content')
<div id="content">

    <div style="display: flex; justify-content: center; align-items: center;">
        <div class="content-page-heading">
            Alerts
        </div>
        <div class="input-group rounded" style="position: absolute;left: 40px;width:auto;">
            <span class="input-group-text border-0 bg-transparent search-addon" id="search-addon">
                <i class="fas fa-search" style="color: #969697;"></i>
            </span>
            <input type="search" class="form-control rounded shadow-none search-input" placeholder="Search" aria-label="Search" />
        </div>
        <div style="position: absolute;right: 40px;" class="search">
            <a href="{{route('head_office.psa.holding_area.record')}}" class="btn btn-info" title="Add New Alert">
                <i class="fa fa-plus"></i>
            </a>
        </div>
    </div>
    {{-- <form method="get" class="form search-form print-display-none">
        <div class="input-group form-group mb-3 search-wrapper">
            <div class="form-group-search">
                <select name="status" class="form-control mb-1 inline" style="width:auto;"
                    onchange="this.form.submit();">
                    <option value="approved" @if(request()->query('status') == 'approved') selected @endif>Show Approved
                    </option>
                    <option value="edited" @if(request()->query('status') == 'edited') selected @endif>Show Edited
                    </option>
                    <option value="rejected" @if(request()->query('status') == 'rejected') selected @endif>Show Rejected
                    </option>
                </select>
            </div>
            <div class="form-group-search">
                <select name="is_archived" class="form-control mb-1 inline" style="width:auto;"
                    onchange="this.form.submit();">
                    <option value="0" @if(request()->query('is_archived') == 0) selected @endif>Show Unarchived</option>
                    <option value="1" @if(request()->query('is_archived') == 1) selected @endif>Show Archived</option>
                </select>
            </div>
        </div>

    </form> --}}
    {{-- <div class="btn-group btn-group-sm float-right" role="group" style="margin-top: -51px">
        <a href="{{route('head_office.psa.holding_area.record')}}" class="btn btn-info" title="Add New Alert">
            <i class="fa fa-plus"></i>
        </a>

    </div> --}}
    <div class="profile-center-area">
        <nav class="page-menu bordered">
            <ul class="nav nav-tab main_header">
                <li><a data-bs-toggle="tab" onclick="changeTabUrl('AlertsTemp')" id="AlertsTemp" data-bs-target="#templates" class="active templates" href="javascript:void(0)">Templates<span></span></a></li>
                <li><a data-bs-toggle="tab" onclick="changeTabUrl('ReceiveAlerts')" id="ReceiveAlerts" data-bs-target="#alerts" class="alerts" href="javascript:void(0)">Receive Alerts<span></span></a></li>
            </ul>
        </nav>
        <hr class="hrBeneathMenu">
        @include('layouts.error')
        <div class="tab-content" id="myTabContent">
            <div id="templates" class="templates relative tab-pane active show">
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!count($alerts))
                            <tr>
                                <td colspan="7" class="font-italic">No Patient Safety Alerts Found.</td>
                            </tr>
                            @else
                            @foreach($alerts as $alert)
                            <tr>
                                <td>{{ date('d/m/Y',strtotime($alert->national_alert->start_time))}}
                                    <br> {{ date('h:i a',strtotime($alert->national_alert->start_time))}}
                                    @if(time() < strtotime($alert->national_alert->start_time))<div class="badge badge-success">
                                            Scheduled</div>@endif
                                </td>
                                <td>
                                    @if($alert->national_alert->type != 'None')
                                    <b>Type:</b> @if($alert->national_alert->type == 'Custom')
                                    {{$alert->national_alert->custom_type }}
                                    @else {{ $alert->national_alert->type }}
                                    @if($alert->national_alert->type == 'Company-Led Medicines Recall/Notification' ||
                                    $alert->national_alert->type == 'Medicines Recall' )
                                    @if($alert->national_alert->patient_level_recall) <br><span
                                        class="badge badge-danger">Patient Level Recall</span> @endif
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
                                <td>@if($alert->national_alert->created_by == 'CAS') Central Alerting System @else Head Office
                                    @endif</td>
                                <td>{{$alert->national_alert->title}}</td>
                                <td>
                                    @if($alert->national_alert->class !== 'None')
                                    <span class="alert_class {{$alert->national_alert->alertColor()}}" data-toggle="tooltip"
                                        title="{{$alert->national_alert->getClassDescripiton($alert->national_alert->class)}}">
                                        <span class="alert_class_name">{{$alert->national_alert->class}}</span>
                                        <span
                                            class="alert_class_description ">{{$alert->national_alert->showClassTitle($alert->national_alert->class)}}</span>
                                        @else
                                        <span class="alert_class_name">Class None</span>
                                        @endif
                                    </span>
                                    <br><br>
                                    <b>Action Within:</b> {{$alert->national_alert->showActionWithinTitle()}}
                                </td>
                                <td>
                                    <b>Countries:</b>
                                    @if($alert->national_alert->send_to_all_countries) All @endif
                                    {{$alert->national_alert->countries->implode('country', ', ')}}
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
                                    @if($alert->national_alert->created_by == 'CAS')
                                    <br><br><b>Tier/Groups:</b> All <br>
                                    @endif

                                </td>
                                <td>
                                    <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton4"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                    <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton4">
                                        <a href="{{route('head_office.psa.view',$alert->id)}}" class="dropdown-item"
                                            title="View">View</a>
                                        @if($alert->national_alert->created_by =='head_office')
                                        <a href="{{route('head_office.psa.holding_area.record',[$alert->national_alert->id,'cloning'=>($alert->national_alert->created_by == 'CAS'?1:0)])}}"
                                            class="dropdown-item" title="Edit"> Edit</a>
                                        @elseif($alert->national_alert->created_by =='CAS')
                                        <a href="{{route('head_office.psa.holding_area.record',[$alert->national_alert->id,'copying'=>1])}}"
                                            class="dropdown-item" title="Copy"> Copy</a>
                                        @endif
                                        @if($alert->is_archived == 0)
                                        <a href="{{route('head_office.psa.archive',['id'=>$alert->id,'_token'=>csrf_token()])}}" class="dropdown-item"
                                            title="Archive">Archive</a>
                                        @else
                                        <a href="{{route('head_office.psa.archive',[$alert->id,'unarchive'=>true,'_token'=>csrf_token()])}}"
                                            class="dropdown-item" title="Unarchive">Unarchive</a>
                                        @endif
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
            <div id="alerts" class="alerts tab-pane ">
                No alerts found
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
<script>
    $(document).ready(function(){
        changeTabUrl('AlertsTemp')
    })
</script>
@endsection