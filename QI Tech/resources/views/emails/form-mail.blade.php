@extends('emails.master')
@section('content')

@if(is_array( $messageContent ))
    @foreach($messageContent as $m)
    {!! $m !!}
    <br><hr>
    @endforeach

@else
    {!! $messageContent !!}
@endif
@endsection