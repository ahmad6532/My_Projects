@extends('layouts.admin_app')
@section('title','View National Alert')
@section('content')

<div class="card">

    <div class="card-body">
    <div class="mb-3">
        <span class="float-left">
            <h4 class="text-info font-weight-bold">{{ isset($nationalAlert->title) ? $nationalAlert->title : 'National Alert' }}</h4>
        </span>

        <div class="float-right">

            <form method="POST" action="{!! route('national_alerts.national_alert.destroy', $nationalAlert->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('national_alerts.national_alert.index') }}" class="btn btn-success" title="Show All National Alert">
                        <span class="fa fa-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('national_alerts.national_alert.create') }}" class="btn btn-info" title="Create New National Alert">
                        <span class="fas fa-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('national_alerts.national_alert.create', $nationalAlert->id ) }}" class="btn btn-primary" title="Edit National Alert">
                        <span class="fas fa-edit" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete National Alert" onclick="return confirm(&quot;Click Ok to delete National Alert.?&quot;)">
                        <span class="fa fa-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
</div>
<div class="table-responsive">
    <table class="table">
            <tr><th>Date</th><td> {{ date('d/m/Y',strtotime($nationalAlert->start_time))}} <br>
                {{ date('h:i a',strtotime($nationalAlert->start_time))}}</td></tr>
            <tr><th>Title</th><td>{{ $nationalAlert->title }}</td></tr>
            <tr>
                <th>Type</th>
                <td>@if($nationalAlert->type  == 'Custom')
                    {{ $nationalAlert->type }} ({{$nationalAlert->custom_type }})
                    @else
                        {{ $nationalAlert->type }}
                    @endif
                </td>
            </tr>
            <tr><th>Originator</th>
                <td>
                @foreach($nationalAlert->originators as $o) 
                    @if($o->originator == 'Custom')
                        {{$o->originator}} ({{$nationalAlert->custom_originator}})@if(!$loop->last), @endif
                    @else
                    {{$o->originator}}@if(!$loop->last), @endif
                    @endif
                @endforeach
                </td>
            </tr>
            <tr><th>Class</th>
                <td>
                    {{$nationalAlert->class}} {{\App\Models\NationalAlert::showClassTitle($nationalAlert->class)}}
                </td>
            </tr>
            <tr><th>Action Within</th>
                <td>
                    {{$nationalAlert->showActionWithinTitle()}}
                </td>
            </tr>
            <tr>
                <th>Summary</th>
                <td>{{ $nationalAlert->summary }}</td>
            </tr>
            <tr>
                <th>Suggested Actions</th>
                <td>{{ $nationalAlert->suggested_actions }}</td>
            </tr>
            <tr><th>Send to Countries</th>
                <td>
                @if($nationalAlert->send_to_all_countries) All @endif {{$nationalAlert->countries->implode('country', ', ')}}
                </td>
            </tr>
            <tr><th>Send to Designation</th>
                <td>
                @if($nationalAlert->send_to_all_designations) All @endif
                    @foreach($nationalAlert->designations as $d) 
                        {{$d->position->name}}@if(!$loop->last), @endif 
                    @endforeach
                </td>
            </tr>
            <tr><th> Send to Head Office / Locations</th>
                <td>
                    @if($nationalAlert->send_to_head_offices_or_location == 'all')
                        <b>Head Offices: </b> All<br>
                        <b>Locations: </b> All<br>
                    @elseif($nationalAlert->send_to_head_offices_or_location == 'head_offices')
                        <b>Head Offices: </b><br>
                    @foreach($nationalAlert->head_offices as $d) 
                            {{$d->head_office->name()}} @if(!$loop->last), @endif 
                        @endforeach
                    @elseif($nationalAlert->send_to_head_offices_or_location == 'locations')
                        <b>Locations: </b><br>
                        @foreach($nationalAlert->locations as $d) 
                            {{$d->location->short_name()}}@if(!$loop->last), @endif 
                        @endforeach
                    @endif
                </td>
            </tr>
            <tr><th>Patient Level Recall</th>
                <td>
                    {{$nationalAlert->patient_level_recall?'Yes':'No'}}
                </td>
            </tr>
            <tr><th>Scheduled</th>
                <td>
                @if($nationalAlert->schedule_later == 'no')
                    No
                @else
                    {{ date('d/m/Y',strtotime($nationalAlert->start_time))}} <br>
                    {{ date('h:i a',strtotime($nationalAlert->start_time))}}
                    @if(time() < strtotime($nationalAlert->start_time))<div class="badge badge-success">Scheduled</div>@endif
                @endif
                </td>
            </tr>
            <tr>
                <th>Uploaded Docs</th>
                <td>
                    @foreach($nationalAlert->documents as $doc)
                        <a href="{{route('document.get', $doc->document->unique_id)}}" target="_blank">{{$doc->document->original_file_name()}}</a><br>
                    @endforeach
                </td>
            </tr>

    </table>
</div>

    </div>
</div>

@endsection