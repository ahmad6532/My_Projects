@extends('emails.master')
@section('content')
    <h3>{{$subject}}</h3>
    <p>Please ensure you are recording all near misses that occur.</p>
    <p>Based on your near miss settings, you should report at least {{$toReportNearMisses}} near misses per each working day.</p>
@endsection