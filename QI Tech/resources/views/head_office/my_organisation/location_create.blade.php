@extends('layouts.head_office_app')
@section('title', 'Assign Setting to Organisation')
@section('content')
<div id="content">
    <style>
         #content{
            overflow-x: unset !important;
            margin: 0;
            padding: 0;
        }
    </style>

<iframe src="{{ env('APP_URL') }}app.html#!/signup/location?loc={{$location_name}}" frameborder="0" width="100%" height="100%"></iframe>

</div>
<input type="hidden" id="route_search_locattion">
@endsection