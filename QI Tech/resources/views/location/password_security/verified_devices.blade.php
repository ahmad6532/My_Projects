@extends('layouts.location_app')
@section('title', 'Location verified Devices')
@section('top-nav-title', 'Location verified Devices')
@section('content')


    <div class="container-fluid">

        <div class="row justify-content-center ">
            <div class="col-md-12 mb-1">
                <div class="card vh-75 ">
                    <div class="card-body">
                        <h3 class="text-info h3 font-weight-bold">Verified Devices</h3>
                        <table border="0" id="scheduleTable" class="table table-responsive table_full_width">
                            <thead>
                                <tr>
                                    <th>Device</th>
                                    <th>Location</th>
                                    <th>IP</th>
                                    <th></th>
                                </tr>
                            </thead>
                            @foreach ($sessions as $session)
                                
                            
                                <tr>
                                    <td>
                                        {{$session->browser}}
                                    </td>
                                    <td>
                                        {{$session->city}}, {{$session->country}}
                                    </td>
                                    
                                    <td>
                                        {{$session->ip}}
                                    </td>
                                    <td>
                                        @if(session('user_session') !== $session->user_session)
                                        <a href="{{route('location.end_user_session',$session->user_session)}}">End Session</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection