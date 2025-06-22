@extends('layouts.head_office_app')
@section('title', 'Head Office Invite New User')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-info">Invite New Head Office User</h1>
    </div>
    @include('layouts.error')

    <!-- Content Row -->
    <form action="{{route('head_office.head_office_users.submit_invite_user')}}" class="form" method="post">
    <div class="row">
        
           
                @csrf
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{old('email')}}"  title="User Email" required>
                    </div>
                    <div class="form-group">
                        <label for="head_office_position">Position in Organization</label>
                        <input type="text" id="head_office_position" name="head_office_position"
                            class="form-control" value="{{old('head_office_position')}}" title="Position in Organization"
                            placeholder="Enter Position in Organization" required>
                    </div>
                    <div class="form-group">
                        <label for="head_office_user_profile_id">Assign Profile</label>
                        <select name="head_office_user_profile_id" id="head_office_user_profile_id" class="form-control">
                            <option>Select a Profile</option>
                            @foreach($profiles as $profile)
                            <option value="{{$profile->id}}" {{old('head_office_user_profile_id') == $profile->id ? 'selected' : ''}}>{{$profile->profile_name}}
                                @if($profile->super_access) -- All System Permissions @endif
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
    </div>
    <button class="btn btn-info" type="submit" name="submit">Invite</button>

</form>
</div>
@endsection
@section('styles')
<link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('/js/alertify.min.js')}}"></script>

@endsection