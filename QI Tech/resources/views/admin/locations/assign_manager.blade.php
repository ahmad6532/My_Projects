@extends('layouts.admin_app')
@section('title', 'Add New Manager')
@section('content')
    <div class="card mb-2">
        <div class="card-body">
            <div class="mb-3 clearfix">
            <span class="float-left">
                <h4 class="text-info font-weight-bold">Assign Manager</h4>
            </span>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif


                    <form method="POST" action="{{route('locations.location.assign_manager',$location->id)}}" accept-charset="UTF-8" id="assign_super_admin_form" name="assign_super_admin_form" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="user">Assign from existing User</label>
                            <select name="user_id" id="user" class="form-control w-100">
                                <option value="">Select a user</option>
                                @foreach($users as $user)
                                    @if(\App\Models\LocationManager::where('user_id',$user->id)->where('location_id',$location->id)->count() == 0)
                                        <option value="{{$user->id}}">{{$user->name}} , {{$user->email}}, {{$user->position->name}}</option>
                                    @endif
                                    @endforeach
                            </select>
                            <p class="info"><i class="fa fa-info text-info"></i> Users already assigned as manager to this location are not shown here.</p>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10">
                                <input class="btn btn-info" type="submit" value="Assign">
                            </div>
                        </div>

                    </form>
                </div>
            </div>


        </div>
    </div>

    <div class="card mb-2">
        <div class="card-body">
            <div class="mb-3 clearfix">
            <span class="float-left">
                <h4 class="text-info font-weight-bold">Create New User and make Manager</h4>
            </span>
                <div class="btn-group btn-group-sm float-right" role="group">
                    <a href="{{ route('users.user.index') }}" class="btn btn-info" title="Show All User">
                        <span class="fas fa-th-list" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif


                    <form method="POST" action="{{ route('users.user.store') }}?lid={{$location->id}}" accept-charset="UTF-8" id="create_user_form" name="create_user_form" class="form-horizontal">
                        {{ csrf_field() }}

                        @include ('admin.users.form', [
                                        'user' => null,
                                      ])

                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10">
                                <input class="btn btn-info" type="submit" value="Add">
                            </div>
                        </div>

                    </form>
                </div>
            </div>


        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
        $(document).ready(function() {
            $('select[name="user_id"]').select2();
        });
        });
    </script>

@endsection


