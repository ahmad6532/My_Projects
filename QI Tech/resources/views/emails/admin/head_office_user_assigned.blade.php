@extends('emails.master')
@section('content')
    <h3>You have been added as a Super User to head office "{{$head_office->name()}}".</h3>
@endsection