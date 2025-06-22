@extends('emails.master')
@section('content')
    <h3>A new patient safety alert <b>"{{$alert->title}}"</b> requires approval!</h3>
    <div>
        You have a new patient safety alert in holding area(s) of head office(s). Please check you holding area(s) for furthur details.
    </div>
@endsection