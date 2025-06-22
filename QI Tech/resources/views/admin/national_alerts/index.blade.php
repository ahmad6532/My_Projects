@extends('layouts.admin_app')
@section('title','View All National Alerts')
@section('content')
    @if(Session::has('success_message'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            {!! session('success_message') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
        <div class="mb-3">
            <div class="float-left">
                <h4 class="text-info font-weight-bold">National Alerts</h4>
            </div>
            <div class="btn-group btn-group-sm float-right" role="group">
                <a href="{{ route('national_alerts.national_alert.create') }}" class="btn btn-info" title="Create New National Alert">
                    <span class="fas fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        <form method="get">
            <div class="input-group form-group mb-3 search-wrapper admin_search">
                <input type="search" class="form-control" name="search" @if(request()->query('search')) value="{{request()->query('search')}}" @endif>
                <button type="submit" class="btn btn-info search_button"><i class="fa fa-search"></i></button>
            </div>
        </form>
        @if(count($nationalAlerts) == 0)
                <h4 class="text-info text-center">No National Alerts Available.</h4>
         @else
            <div class="table-responsive">
                <table class="table table-striped table-bordered data_table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Alert</th>
                            <th>Uploaded Docs</th>
                            <th>Send To:</th>
                            <th>Class</th>

                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($nationalAlerts as $nationalAlert)
                        <tr class="national_alert_{{$nationalAlert->id}}">
                            <td>
                                {{ date('d/m/Y',strtotime($nationalAlert->start_time))}} <br>
                                {{ date('h:i a',strtotime($nationalAlert->start_time))}}
                                @if(time() < strtotime($nationalAlert->start_time))<div class="badge badge-success">Scheduled</div>@endif
                            </td>
                            <td>
                                {{ $nationalAlert->title }} <br>
                                @if($nationalAlert->type  == 'Custom')
                                    {{ $nationalAlert->type }} ({{$nationalAlert->custom_type }})
                                @else
                                    {{ $nationalAlert->type }}
                                @endif
                                <br>
                                @foreach($nationalAlert->originators as $o) 
                                    @if($o->originator == 'Custom')
                                        {{$o->originator}} ({{$nationalAlert->custom_originator}})@if(!$loop->last), @endif
                                    @else
                                    {{$o->originator}}@if(!$loop->last), @endif
                                    @endif
                                @endforeach

                                <br><br>{{ $nationalAlert->summary }}
                            </td>
                            <td>
                                @foreach($nationalAlert->documents as $doc)
                                    <a href="{{route('document.get', $doc->document->unique_id)}}" target="_blank">{{$doc->document->original_file_name()}}</a><br>
                                @endforeach
                            </td>
                            <td>
                                <b>Countries: </b><br>@if($nationalAlert->send_to_all_countries) All @else {{$nationalAlert->countries->implode('country', ', ')}} @endif<br><br>
                                <b>Designations: </b><br>
                                @if($nationalAlert->send_to_all_designations) All @else
                                    @foreach($nationalAlert->designations as $d) 
                                        {{$d->position->name}}@if(!$loop->last), @endif 
                                    @endforeach
                                @endif
                                    <br><br>

                                @if($nationalAlert->send_to_head_offices_or_location == 'all')
                                    <b>Head Offices: </b> All<br>
                                    <b>Locations: </b> All<br>
                                @elseif($nationalAlert->send_to_head_offices_or_location == 'head_offices')
                                    <b>Head Offices: </b><br>
                                    @if($nationalAlert->send_to_all_head_offices) All @else
                                    @foreach($nationalAlert->head_offices as $d) 
                                        {{$d->head_office->name()}} @if(!$loop->last), @endif 
                                    @endforeach
                                    @endif
                                @elseif($nationalAlert->send_to_head_offices_or_location == 'locations')
                                    <b>Locations: </b><br>
                                    @if($nationalAlert->send_to_all_locations) All @else
                                        @foreach($nationalAlert->locations as $d) 
                                            {{$d->location->short_name()}}@if(!$loop->last), @endif 
                                        @endforeach
                                    @endif
                                @endif
                            <br><br>
                            </td>
                            <td>
                                <b>Class: </b>{{$nationalAlert->class}} {{\App\Models\NationalAlert::showClassTitle($nationalAlert->class)}}<br><br>
                                <b>Action Within: </b>{{$nationalAlert->showActionWithinTitle()}}<br><br>
                            </td>

                            <td>


                                <div class="dropdown mb-4">
                                    <button class="btn btn-info dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                    </button>
                                    <div class="dropdown-menu animated--fade-in"
                                         aria-labelledby="dropdownMenuButton">
                                <form method="POST" action="{!! route('national_alerts.national_alert.destroy', $nationalAlert->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                        <a href="{{ route('national_alerts.national_alert.show', $nationalAlert->id ) }}" class="dropdown-item" title="Show National Alert">
                                            View
                                        </a>
                                        <a href="{{ route('national_alerts.national_alert.create', $nationalAlert->id ) }}" class="dropdown-item" title="Edit National Alert">
                                            Edit
                                        </a>

                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this alert. This will also delete its performed actions.')" title="Delete National Alert" onclick="return confirm(&quot;Are you sure you want to delete this National Alert.&quot;)">
                                            Remove
                                        </button>
                                        <a href="#" class="dropdown-item" title="National Alert Analysis">
                                            Analysis
                                        </a>
                                </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                   <div class="laravel_pagination right">  {!! $nationalAlerts->render('pagination::bootstrap-5') !!} </div> 
            </div>
        </div>
        @endif
    
    </div>
@endsection