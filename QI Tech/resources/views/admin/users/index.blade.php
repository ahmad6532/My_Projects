@extends('layouts.admin_app')
@section('title', 'List of all Users')


@section('content')

    @include('layouts.error')

    <div class="card">

        <div class="card-body">
        <div class="mb-3">

            <div class="float-left">
                <h4 class="text-info font-weight-bold">Users</h4>
            </div>

            <div class="btn-group btn-group-sm float-right" role="group">
                <a href="{{ route('users.user.create') }}" class="btn btn-info" title="Create New User">
                    <span class="fas fa-plus" aria-hidden="true"></span>
                </a>
            </div>

        </div>

        @if(count($users) == 0)
                <h4 class="text-info text-center">No Users Available.</h4>
         @else
            <div class="table-responsive">

                <table class="table table-striped table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Last Used</th>
                            <th>Notes</th>

                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>

                                {{ $user->name }} <br>
                                {{ optional($user->position)->name }}
                                <br>    {{ $user->registration_no }}
                                <br>{{ $user->email }}
                                <br>{{ $user->mobile_no }}
                            </td>
                            <td class="align-middle text-center">
                                    <span class="badge badge-pill badge-{{$user->status[1]}}">{{$user->status[0]}}</span>

                            </td>
                            <td>
                                Last logged into User Account: <br>
                                Date time <br>
                                Last Logged into Location Account: <br>
                                [date][time] <br>
                                [location account email address]
                            </td>
                            <td></td>

                            <td class="align-middle text-center">

                                <div class="dropdown mb-4">
                                    <button class="btn btn-info dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                    </button>
                                    <div class="dropdown-menu animated--fade-in"
                                         aria-labelledby="dropdownMenuButton">

                                            <a class="dropdown-item" href="{{ route('users.user.show', $user->id ) }}" title="Show User Details">
                                                View
                                            </a>
                                        </a>
                                        <a class="dropdown-item" href="{{route('users.user.toggle_archived',$user->id)}}" title="{{$user->archived_status}} user" onclick="return confirm(&quot;Click Ok to {{$user->archived_status}} user.&quot;)">
                                            {{$user->archived_status}}
                                        </a>
                                        @if(!$user->email_verified_at)
                                        <a class="dropdown-item" href="{{route('users.user.toggle_active',['user'=>$user->id,'_token'=>csrf_token()])}}" title="{{$user->active_status}} Account" onclick="return confirm(&quot;Click Ok to {{$user->active_status}} user.&quot;)">
                                            Activate Account
                                        </a>
                                        <a class="dropdown-item" href="{{route('users.user.activation_email',['user'=>$user->id,'_token'=>csrf_token()])}}" title="Resend Account Activation Email">
                                            Resend Account Activation Email
                                        </a>
                                        @endif

                                        <a href="{{ route('users.user.edit', $user->id ) }}" class="dropdown-item" title="Edit User Account">
                                                Edit Details
                                            </a>

                                        <a class="dropdown-item text-danger" href="{{route('users.user.toggle_suspend',$user->id)}}" title="{{$user->suspend_status}} Account" onclick="return confirm(&quot;Click Ok to {{$user->suspend_status}} user.&quot;)">
                                            {{$user->suspend_status}} user Account
                                        </a>


                                            <a href="#" class="dropdown-item" title="Access Remotely">
                                                Access Remotely
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
{{--            {!! $users->render() !!} --}}
{{--        </div> --}}
        
        @endif
    
    </div>
@endsection