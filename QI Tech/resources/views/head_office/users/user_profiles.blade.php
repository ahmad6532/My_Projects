@extends('layouts.head_office_app')
@section('title', 'Head Office User Profiles')
@section('content')
<div id="content">
    <a href="#" data-toggle="modal" data-target="#create_profile" class="btn btn-info float-right"><i
            class="fa fa-plus"></i> Add Profile</a>
    @include('layouts.error')
    @foreach($profiles as $profile)
    <div class="card shadow mb-2 mt-3">
        <div class="card-header">
            <div class="float-left">
                <a href="#collapseCard_{{$profile->id}}" class="d-block card-header py-3 collapsed"
                    data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseCardExample">
                    <h6 class="m-0 font-weight-bold text-info">{{$profile->profile_name}}
                        @if($profile->super_access)<br>
                        <span class="badge badge-primary">All System Permissions</span>
                        @endif
                    </h6>
                </a>
            </div>
            <div class="assigned_to_users mt-2">Assigned to User(s): {{count($profile->user_profile_assign)}}</div>
            <div class="btn-group btn-group-xs float-right" role="group">
                <a href="#" class="no-arrow btn btn-outline-cirlce dropdown-toggle" id="dropdownMenuButton_x"
                    data-toggle="dropdown">
                    <i class="fa fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu  animated--fade-in" aria-labelledby="dropdownMenuButton_x">
                    <a href="#collapseCard_{{$profile->id}}" data-toggle="collapse" class="dropdown-item">Edit</a>
                    @if(!$profile->super_access)
                    <a data-toggle="modal" data-target="#p_delete_model_{{$profile->id}}" href="#"
                        class="dropdown-item text-danger">Delete</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="collapse card-body" id="collapseCard_{{$profile->id}}">
            @include('head_office.users.profile_form')
        </div>
    </div>
    @if(!$profile->super_access)
    <!-- Delete Modal -->
    <div class="modal fade" id="p_delete_model_{{$profile->id}}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"><span>&times;</span></button>
                    <form method="post" action="{{route('head_office.head_office_profile_delete')}}">
                        @csrf
                        <input type="hidden" name="id" value="{{$profile->id}}">
                        <h4 class="text-info">Are you sure you want to delete this profile?</h4>
                        <p>This profile is assigned to {{count($profile->user_profile_assign)}} user(s).</p>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endif
    @endforeach
</div>
<div class="modal fade" id="create_profile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"><span>&times;</span></button>
                @include('head_office.users.profile_form',['profile' => null])
            </div>
        </div>
    </div>
</div>


@endsection