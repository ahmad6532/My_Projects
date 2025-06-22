@extends('layouts.admin_app')
@section('title', 'List of All Location')
@section('content')
    @include('layouts.error')
    <div class="card">
        <div class="card-body">
        <div class="mb-3">
            <div class="float-left">
                <h4 class="text-info font-weight-bold">Locations</h4>
            </div>
            <div class="btn-group btn-group-sm float-right" role="group">
                <a href="{{ route('locations.location.create') }}" class="btn btn-info" title="Create New Location">
                    <span class="fas fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>

        @if(count($locations) == 0)
                <h4 class="text-info text-center">No Locations Available.</h4>
         @else
            <div class="table-responsive">

                <table class="table table-striped table-bordered" id="dataTable">
                    <thead>
                        <tr>

                            <th>Name</th>
                            <th>Head Office</th>
                            <th>Status</th>
                            <th>Last Used</th>
                            <th>Notes</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($locations as $location)
                        <tr>
                            <td>
                                <strong>{{ $location->trading_name }}</strong>
                                <br>{{ $location->full_address }}
                                <br><a href="mailto:{{ $location->email }}">{{ $location->email }}</a>
                                <br><a href="tel:{{ $location->telephone_no }}">{{ $location->telephone_no }}</a>
                                <br/>{{$location->regulatory_body->regulatory}} No: {{$location->registration_no}}
                                <br>
                                    <b>Managers</b>
                                    @if(count($location->managers) >  0)
                                    @foreach($location->managers as $manager)
                                        <br>
                                        <p class="mb-0">{{$manager->user->name}} ({{$manager->user->email}})
                                            <a href="{{route('locations.location.assign_manager_remove',['manager_id'=>$manager->id,'_token'=>csrf_token()])}}" class="btn-sm right" title="Remove this Manager" onclick="return confirm(&quot;Click Ok to Remove this Manager.&quot;)"><i class="fas fa-trash"></i></a>
                                        </p>
                                    @endforeach
                                    @else
                                    <br><small>No managers found.</small>
                                    @endif
                            </td>
                            <td>
                                @if($location->head_office()) 
                                    <p class="mb-0"> {{$location->head_office()->name()}}
                                            <a href="{{route('admin.location.remove_head_office',['location_id'=>$location->id,'_token'=>csrf_token()])}}" class="btn-sm right" title="Remove this Head Office" onclick="return confirm(&quot;Click Ok to Remove this Head Office.&quot;)"><i class="fas fa-trash"></i></a>
                                    </p>
                                @endif
                            </td>
                            <td class="align-middle text-center">
                                <span class="badge badge-pill badge-{{$location->status[1]}}">{{$location->status[0]}}</span>
                            </td>
                           
                            <td>
                                Last Login:{{ $location->last_login_time }}<br>
                                Last entry made: N/A<br />
                                Last Login: <a href=#>{{ $location->last_login_user_name }}</a>
                            </td>
                            <td></td>
                            
                            <td class="align-middle text-center">


                                <div class="dropdown mb-4">
                                    <button class="btn btn-info dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">Actions
                                    </button>
                                    <div class="dropdown-menu animated--fade-in"
                                         aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="{{route('locations.location.show', $location->id ) }}" title="Show Location">
                                                View
                                            </a>
                                            <a class="dropdown-item" href="{{route('admin.location.login', $location->id ) }}" title="Login location">
                                                Login
                                            </a>
                                            <a class="dropdown-item" href="{{route('locations.location.toggle_archived',$location->id)}}" title="{{$location->archived_status}} Location" onclick="return confirm(&quot;Click Ok to {{$location->archived_status}} Location.&quot;)">
                                                {{$location->archived_status}}
                                            </a>
                                            @if(!$location->is_active || !$location->email_verified_at)
                                            <a class="dropdown-item" href="{{route('locations.location.toggle_active',$location->id)}}" title="{{$location->active_status}} Account" onclick="return confirm(&quot;Click Ok to {{$location->active_status}} Location.&quot;)">
                                                Activate Account
                                            </a>
                                            @endif
                                            @if(!$location->email_verified_at)
                                            <a class="dropdown-item" href="{{route('locations.location.activation_email',$location->id)}}" title="Resend Account Activation Email">
                                                Resend Account Activation Email
                                            </a>
                                            @endif
                                        
                                            <a href="{{ route('locations.location.edit', $location->id ) }}" class="dropdown-item" title="Edit Location">
                                                Edit Details
                                            </a>
                                            <a class="dropdown-item text-danger" href="{{route('locations.location.toggle_suspend',$location->id)}}" title="{{$location->suspend_status}} Account" onclick="return confirm(&quot;Click Ok to {{$location->suspend_status}} Location.&quot;)">
                                                {{$location->suspend_status}} Location Account
                                            </a>


                                            <a href="#" class="dropdown-item" title="Restart Trial">
                                                Restart Trial
                                            </a>

                                            <a href="#" class="dropdown-item" title="Extend Trial">
                                                Extend Trial
                                            </a>

                                            <a href="#" class="dropdown-item" title="Access Remotely">
                                                Access Remotely
                                            </a>
                                            <a href="{{route('admins.location.view_assign_ho',$location->id)}}" class="dropdown-item" title="Add New Manager">
                                                @if($location->head_office()) Change Head Office @else Assign to Head Office @endif
                                            </a>
                                            <a href="{{route('locations.location.assign_manager',$location->id)}}" class="dropdown-item" title="Add New Manager">
                                                Add New Manager
                                            </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

{{--        <div class="card-footer"> --}}
{{--            {!! $locations->render() !!} --}}
{{--        </div> --}}
        
        @endif
    
    </div>
@endsection