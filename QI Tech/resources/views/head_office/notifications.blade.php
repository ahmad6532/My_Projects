@extends('layouts.head_office_app')
@section('title', 'Notifications')
@section('content')


<div class="card card-qi mt-1">
    <div class="card-body">
        <h3 class="text-info h3 font-weight-bold">Notifications  @if($unread_count > 0) <span class="badge badge-danger badge-counter">{{$unread_count}}</span> @endif</h3>
        @foreach($notifications as $alert)
        <a class="dropdown-item d-flex align-items-center" href="{{!empty($alert->url)?route('location.process_notifcation_url',$alert->id):'#'}}">
            <div class="mr-3">
                <div class="icon-circle {{\App\Models\LocationUserNotification::alertColoring($alert->type)['background_class']}}">
                    <i class="{{\App\Models\LocationUserNotification::alertColoring($alert->type)['icon']}} text-white"></i>
                </div>
            </div>
            <div>
                <div class="small text-gray-500">{{$alert->created_at->format('d F, Y h:i a')}}</div>
                <span class="@if($alert->status == \App\Models\LocationUserNotification::$statusUnread)font-weight-bold @endif">{{$alert->title}}</span>
            </div>
        </a>
        @endforeach
        @if(!count($notifications))
            <p class="font-italic">No notification found.</p>
        @endif
        <br>
        {!! $notifications->render('pagination::bootstrap-5') !!}
    </div>
</div>
@endsection