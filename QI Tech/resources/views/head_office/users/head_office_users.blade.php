@extends('layouts.head_office_app')
@section('title', 'Head Office Users')
@section('content')

<style>
    .new-info-wrapper{
        right: 100% !important;
        left: unset !important;
    }
</style>
<div id="content" class="">
    
    <div style="display: flex; justify-content: center; align-items: center;">
        <div class="content-page-heading">
            Team
        </div>

        <div style="position: absolute;right: 40px;" class="search">
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add_new_user" class="btn btn-info plus-btn" style="height: 30px; width:30px;" title="Add New Alert">
                <i class="fa fa-plus"></i>
            </a>
        </div>
    </div>
    
    <div class="profile-center-area">
        <a href="#" style="display: none;" data-bs-toggle="modal" data-bs-target="#create_profile" class="primary-btn float-right create_profile"><i
            class="fa fa-plus"></i> Add Profile</a>
        <nav class='page-menu bordered'>
            <ul class="nav nav-tab">
                <li><a onclick="changeTabUrl('MemberTeam')" id="MemberTeam" href="javascript:void(0)" class="active" data-bs-toggle="tab" data-bs-target="#members">Members<span></span></a></li>
                <li><a onclick="changeTabUrl('UserProfileTeam')" id="UserProfileTeam" class="profiles" href="javascript:void(0)" data-bs-toggle="tab" data-bs-target="#profiles">User Profile<span></span></a></li>
                <li><a onclick="changeTabUrl('UserProfileInvite')" id="UserProfileInvite" class="invites" href="javascript:void(0)" data-bs-toggle="tab" data-bs-target="#invites">Invites<div class="badge text-bg-primary mx-1">{{count($head_office_invites->where('expires_at','>',now()))}}</div><span></span></a></li>
            </ul>
        </nav>
        <hr class="hrBeneathMenu">
        @include('layouts.error')
        <div id="alert-placeholder"></div>
        <div class="tab-content" id="myTabContent">
            <div id="members" class="members tab-pane active show">
                <table id="tableTeams" class="table new-table mt-2">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Assigned Profile</th>
                            <th>Contact Info</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($headOffice->users as $headOfficeUsers)
                        <tr>
                            <td><input type="checkbox" class="form-check-input selected_users_checkbox" name="selected_users_main[]" value="{{$headOfficeUsers->id}}"
                                data-super_user="
                                    @if ($headOfficeUsers->access_right)
                                        {{$headOfficeUsers->access_right->super_access}}
                                    @elseif (isset($headOfficeUsers->user_profile_assign->profile))
                                    {{$headOfficeUsers->user_profile_assign->profile->super_access}}
                                    @endif
                                "
                                >
                            </td>
                            <td>{{ \Illuminate\Support\Str::title($headOfficeUsers->user->name) }}</td>
                            <td>{{optional($headOfficeUsers)->position}}</td>
                            <td>
                                @if($headOfficeUsers->access_right)
                                custom
                                @elseif($headOfficeUsers->user_profile_assign)
                                {{-- @dd($headOfficeUsers->user_profile_assign->profile) --}}
                                {{ isset($headOfficeUsers->user_profile_assign->profile) ? $headOfficeUsers->user_profile_assign->profile->profile_name : 'Not assigned' }}
                                @else
                                <span class="badge bg-danger">Unassigned</span>
                                @endif
                            </td>
                            <td>{{$headOfficeUsers->user->email}}</td>
                            <td> 
                                <a href="#collapseCard_{{$headOfficeUsers->id}}" class="btn btn-outline-cirlce bg-white" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">
                                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                        <tr> 
                            <td colspan="5" style="padding: 0 !important;">
                                <div class="collapse rounded pb-2" id="collapseCard_{{$headOfficeUsers->id}}" style="background-color: #abd7d400">
                                    <div class="card-body">
                                        <nav class="page-menu bordered">
                                            <ul class="nav nav-tab main_header">
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link active" id="user_details-tab_{{$headOfficeUsers->id}}"
                                                        data-bs-toggle="tab" data-bs-target="#user_details_{{$headOfficeUsers->id}}" type="button"
                                                        role="tab" aria-controls="user_details" aria-selected="true">User Details <span></span></a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" id="case_managers-tab_{{$headOfficeUsers->id}}"
                                                        data-bs-toggle="tab" data-bs-target="#case_managers_{{$headOfficeUsers->id}}"
                                                        type="button" role="tab" aria-controls="case_managers" aria-selected="false">Case
                                                        Manager <span></span></a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" id="access_rights_tab_{{$headOfficeUsers->id}}"
                                                        data-bs-toggle="tab" data-bs-target="#acess_rights_{{$headOfficeUsers->id}}" type="button"
                                                        role="tab" aria-controls="access_rights" aria-selected="false">Access
                                                        Rights <span></span></a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" id="access_assigned_locations_tab_{{$headOfficeUsers->id}}"
                                                        data-bs-toggle="tab" data-bs-target="#assigned_locations_{{$headOfficeUsers->id}}" type="button"
                                                        role="tab" aria-controls="access_assigned_locations" aria-selected="false">Assigned Locations<span></span></a>
                                                </li>
                                            </ul>
                                        </nav>
                                        <hr class="hrBeneathMenu" style="margin-right: -16px;margin-left: -16px;">
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="user_details_{{$headOfficeUsers->id}}"
                                                role="tabpanel" aria-labelledby="user_details-tab">
                                                <div class="profile-page-contents hide-placeholder-parent my-3 rounded w-75 px-3 " style="background: #00000000;max-width:inherit;">
                                                            <form action="{{route('head_office.head_office_user_update',$headOfficeUsers->id)}}" class="form " method="post">
                                                                @if ($logged_ho_user->user_profile_assign->profile->super_access == true || $logged_ho_user->id == $headOfficeUsers->id)
                                                                    @csrf
                                                                @endif
                                                                <label class="inputGroup fw-semibold" for="assigned_profile">Profile Type:
                                                                    @if($headOfficeUsers->access_right)
                                                                    <select class="form-contro bg-transparent  border-0 unset-form-control" disabled name="assigned_profile" id="assigned_profile">
                                                                        <option>Custom</option>
                                                                    </select>

                                                                    @if ($logged_ho_user->user_profile_assign->profile->super_access)
                                                                        
                                                                        <a data-msg="Are you sure to remove custom access right for this user?" class="delete_button text-danger"
                                                                        href="{{route('head_office.head_office_access_right_delete',['id' => $headOfficeUsers->id, '_token' => csrf_token()])}}"><i class="fa-solid fa-trash"></i></a> 
                                                                    @endif
                                                                    @else
                                                                    <select @if($logged_ho_user->user_profile_assign->profile->super_access == false) 
                                                                        onchange="return false;" 
                                                                        style="pointer-events: none;" 
                                                                    @endif 
                                                                    class="form-control unset-form-control bg-transparent mx-4 border-0" 
                                                                    name="assigned_profile" id="assigned_profile"
                                                                    style="font-size: 16px; color: black; font-weight: normal;">
                                                                    <option style="font-size: 16px; color: black; font-weight: normal;">Select a Profile</option>
                                                                    @foreach($user_profiles->where('custom_access_rights_id',null) as $profile)
                                                                        <option 
                                                                            @if($headOfficeUsers->user_profile_assign &&
                                                                                $headOfficeUsers->user_profile_assign->user_profile_id == $profile->id) selected @endif
                                                                            value="{{$profile->id}}" 
                                                                            style="font-size: 16px; color: black; font-weight: normal;">
                                                                            {{$profile->profile_name}}
                                                                            @if($profile->super_access) 
                                                                                <span style="color: palevioletred; font-size: 16px;">-- All System Permissions</span> 
                                                                            @endif
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                
                                                                    @endif
                                                                </label>
                                                                <label class="inputGroup fw-semibold">Position:
                                                                    <input {{$logged_ho_user->user_profile_assign->profile->super_access == true || $logged_ho_user->id == $headOfficeUsers->id ? '' : 'disabled'}} 
                                                                    type="text" class="form-contro unset-form-control bg-transparent mx-4" 
                                                                    name="position" value="{{old('position',optional($headOfficeUsers)->position)}}" 
                                                                    title="Position in Organization" placeholder="Enter Position in Organization"
                                                                    style="font-weight: normal !important;">
                                                                </label>
                                                                
                                                                
                                                                
                                                                {{-- <label class="inputGroup fw-semibold" for="user_account_type">User Account type:
                                                                    <input class="form-contro unset-form-control mx-4 " style="width: 50% !important;" type="text" id="user_account_type" name="user_account_type"
                                                                    value="{{$headOfficeUsers->user->position->name}}"
                                                                    placeholder="Enter user account type" disabled>
                                                                </label> --}}
                                                                
                                                                <div class="d-flex align-items-center mt-2">
                                                                    <p class="text-secondary fw-bold m-0" style="font-size: 14px;">Email Address</p>
                                                                    @if ($logged_ho_user->user_profile_assign->profile->super_access == true || $logged_ho_user->id == $headOfficeUsers->id)
                                                                        <button type="button" class="btn border-0 py-0 text-secondary" id="dropdownMenuButton_x" data-bs-toggle="dropdown">
                                                                            <i class="fa-solid fa-plus text-secondary" style="font-size: 14px;"></i>
                                                                        </button>
                                                                        <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton_x" style="">
                                                                            <a href="javascript:void(0)" class="dropdown-item" onclick="add_phone(this,'{{$headOfficeUsers->id}}')">Add Phone</a>
                                                                            <a href="javascript:void(0)" class="dropdown-item" onclick="add_email(this,'{{$headOfficeUsers->id}}')">Add Email</a>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                {{-- <label class="inputGroup fw-semibold mt-0" for="user_email">User Account Email Address:
                                                                    <input type="text" style="width: 50% !important;" id="user_email" name="user_email"
                                                                    value="{{$headOfficeUsers->user->email}}"
                                                                    placeholder="user email" class="form-contro mx-4 unset-form-control" disabled>
                                                                </label> --}}
                                                                <div id="email_div" class="email_div">
                                                                    @foreach ($headOfficeUsers->head_office_user_contact_details->where('type',1) as $k => $contact)
                                                                    <label class="inputGroup popup fw-semibold w-50 mt-0">Email :
                                                                        <input {{$logged_ho_user->user_profile_assign->profile->super_access == true || $logged_ho_user->id == $headOfficeUsers->id ? '' : 'disabled'}} 
                                                                        type="text" style="margin-left: 10px; background:transparent; font-weight:normal !important;" 
                                                                        placeholder="Add email" id="email_{{$contact->id}}" value="{{$contact->contact}}" 
                                                                        onfocusout="updateEmail({{$headOfficeUsers->id}},this,{{$contact->id}})">
                                                                        
                                                                        <div class="custom_overlay">
                                                                            <span class="custom_overlay_inner">
                                                                                @if ($logged_ho_user->user_profile_assign->profile->super_access == true || $logged_ho_user->id == $headOfficeUsers->id)
                                                                                    <a href="{{route('head_office.delete_contact_user_settings',['id' => $contact->id, 'hou_id' => $headOfficeUsers->id])}}" 
                                                                                       class="delete_button" data-msg="Are you sure you want to delete this contact?">
                                                                                        <svg width="20" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                            <path d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5" 
                                                                                                stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                                        </svg>
                                                                                    </a>
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                    </label>
                                                                    
                                                                    @endforeach
                                                                </div>
                                                                @foreach ($headOfficeUsers->head_office_user_contact_details->where('type',0) as $k  => $contact)
                                                                <label class="inputGroup popup" style="width: 50%;">Phone :
                                                                    <input type="text" class="phone bg-transparent" placeholder="Add a phone number" value="{{ $contact->contact }}"
                                                                        onfocusout="updatePhone({{ $headOfficeUsers->id }},this,{{ $contact->id }})"
                                                                        style="background: {{ $contact->is_phone_hidden ? 'rgb(239 239 239 / 78%)' : '' }}">
                                                                    <div class="custom_overlay">
                                                                        <span class="custom_overlay_inner">
                                                                            <div class="d-flex align-items-center gap-2">
                                                                                <a href="{{ route('head_office.delete_contact_user_settings', ['id' => $contact->id, 'hou_id' => $headOfficeUsers->id]) }}"
                                                                                    class="delete_button"
                                                                                    data-msg="Are you sure you want to delete this contact?">
                                                                                    <svg width="20" height="24" viewBox="0 0 24 24" fill="none"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                            d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5"
                                                                                            stroke="#888" stroke-width="2" stroke-linecap="round"
                                                                                            stroke-linejoin="round"></path>
                                                                                    </svg>
                                                                                </a>
                                                                                
                                                                            </div>
                                                                        </span>
                                                                    </div>
                                                                </label>                                                            @endforeach
                                                            <div id="phone_div" class="phone_div">

                                                            </div>

                                                                <div class="d-flex align-items-center mt-2">
                                                                    <p class="text-secondary fw-bold m-0" style="font-size: 14px;">Expertise</p>
                                                                    @if ($logged_ho_user->user_profile_assign->profile->super_access == true || $logged_ho_user->id == $headOfficeUsers->id)
                                                                        <button type="button" class="btn border-0 py-0 text-secondary"  onclick="add_area('{{$headOfficeUsers->id}}',this)" >
                                                                            <i class="fa-solid fa-plus text-secondary" style="font-size: 14px;"></i>
                                                                        </button>
                                                                        
                                                                    @endif
                                                                    
                                                                </div>
                                                                <div id="area_values" class="area_values">
                                                                    @if ($headOfficeUsers->user->selected_head_office_user && $headOfficeUsers->user->selected_head_office_user->head_office_user_area)
                                                                    @foreach ($headOfficeUsers->user->selected_head_office_user->head_office_user_area as $area)
                                                                    <label class="inputGroup w-75 mt-0" id="{{$area->id}}">Area:
                                                                        <input {{$logged_ho_user->user_profile_assign->profile->super_access == true || $logged_ho_user->id == $headOfficeUsers->id ? '' : 'disabled'}} style="width:40%;background:transparent; font-weight: normal;" type="text" placeholder="Add area" value="{{$area->area}}">
                                                                        <input {{$logged_ho_user->user_profile_assign->profile->super_access == true || $logged_ho_user->id == $headOfficeUsers->id ? '' : 'disabled'}} style="width:40%;background:transparent; font-weight: normal;" type="text" placeholder="Add Level" value="{{$area->level}}">
                                                                               <div class="custom_overlay">
                                                                            <span class="custom_overlay_inner">
                                                                                @if ($logged_ho_user->user_profile_assign->profile->super_access == true || $logged_ho_user->id == $headOfficeUsers->id)
                                                                                    <a href="{{route('head_office.delete_area_user_settings',['id'=>$area->id,'hou_id'=>$headOfficeUsers->id])}}" class="delete_button"
                                                                                        data-msg="Are you sure you want to delete this area?">
                                                                                        <svg width="20" height="24" viewBox="0 0 24 24" fill="none"
                                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                                            <path
                                                                                                d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5"
                                                                                                stroke="#888" stroke-width="2" stroke-linecap="round"
                                                                                                stroke-linejoin="round"></path>
                                                                                        </svg>
                                                                                    </a>
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                    </label>
                                                                    @endforeach
                                                                    @endif
                      
                                                                </div>
                                                                <div id="area_div" class="area_div">
                                            
                                                                </div>
                                                                <div >
                                                                    <div class="inputSection">About Me</div>
                                                                    <style>
                                                                        #about_me:focus {
                                                                            box-shadow: none;
                                                                        }
                                                                    </style>
                                                                    <textarea spellcheck="true"  {{$logged_ho_user->user_profile_assign->profile->super_access == true || $logged_ho_user->id == $headOfficeUsers->id ? '' : 'disabled'}} class="form-control fw-semibold bg-transparent inputGroup w-75" name="about_me" id="about_me" cols="3" rows="1"
                                                                        >{{ $headOfficeUsers->about_me }}</textarea>
                                                                </div>
                                                                        <input type="hidden" value="{{route('head_office.update_email_user_settings')}}" id="update_email_route">
                                                                        <input type="hidden" id="_token" value="{{ csrf_token() }}">
                                                                        <input type="hidden" value="{{route('head_office.update_area_user_settings')}}" id="update_area_route">
                                                                        @if ($logged_ho_user->user_profile_assign->profile->super_access == true || $logged_ho_user->id == $headOfficeUsers->id)
                                                                            
                                                                        <button class="primary-btn my-3" type="submit" id="form_{{$headOfficeUsers->id}}" name="submit">Update</button>
                                                                        @endif
                                                                        @if($logged_ho_user->id != $headOfficeUsers->id && $logged_ho_user->user_profile_assign->profile->super_access == true)
                                                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#p_delete_model_user_{{$headOfficeUsers->id}}" >Delete User</button>
                                                                            
                                                                            @endif
                                                                            
                                                                        </form>
                                                                        <form method="post" action="{{ route('head_office_user_delete', ['id' => $headOfficeUsers->id]) }}">
                                                                        <div class="modal fade select_2_modal" id="p_delete_model_user_{{$headOfficeUsers->id}}" >
                                                                            <div class="modal-dialog modal-lg" >
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header text-center">
                                                                                        <h4 class="modal-title text-info w-100">
                                                                                            Confirm Deletion!
                                                                                        </h4>
                                                                                        <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true"></span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                            
                                                                                            <div class="new_link_wrapper">
                                                                                                @csrf
                                                                                                <p class="fw-bold mb-0">{{$headOfficeUsers->user->name}} has</p>
                                                                                                <li class="mb-0">Total Open Cases: {{count($headOfficeUsers->head_office_user_cases->filter(function($case){
                                                                                                    return $case->case->status == 'open';
                                                                                                }))}}</li>
                                                                                                <li>Total Tasks: {{count($headOfficeUsers->stage_task_assigns())}}</>
                                                                                            </div>
                                                                                            <br>
                                                                                            <div class="action-container">
            
                                                                                                <h5>Select Action <span class="text-secondary " style="font-size: 14px;">assign the cases/tasks to</span></h5>
                                                                                                <select name="delete_action" class="form-select delete_action">
                                                                                                    <option value="1">Assign cases specific user(s)</option>
                                                                                                    <option value="2">Auto Assign Based on profiles</option>
                                                                                                </select>
            
                                                                                                <div class="specific_user my-2">
                                                                                                    <h6>Select user(s)</h6>
                                                                                                    <select name="specific_users[]"  multiple class="select-modal" >
                                                                                                        @foreach ($headOffice->users as $u)
                                                                                                        @if ($u->id != $headOfficeUsers->id)
                                                                                                            <option value="{{ $u->id }}" >
                                                                                                                {{ $u->user->name }}
                                                                                                            </option>
                                                                                                            
                                                                                                        @endif
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="specific_profile mb-2" style="display: none;">
                                                                                                    <h6>Select profile(s)</h6>
                                                                                                    <select name="specific_profiles[]"  multiple class="select-modal" >
                                                                                                        @foreach ($headOffice->head_office_user_profiles as $p)
                                                                                                            <option value="{{ $p->id }}" >
                                                                                                                {{ $p->profile_name }}
                                                                                                            </option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                            <button type="button"  class="btn btn-info btn-submit-del inline-block mb-0"><i
                                                                                                    class="fa fa-location-arrow"></i> </button>
                                                                                                </div>
                                                                                                
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                            
                                                        </div>
                                            </div>
                                            <div class="tab-pane fade" id="case_managers_{{$headOfficeUsers->id}}" role="tabpanel"
                                                aria-labelledby="case_managers-tab">
                                                    <form class="card-body px-3" method="post" action="{{route('head_office.update_user_settings',$headOfficeUsers->user)}}">
                                                        @csrf
                                                        <h4 class="t" style="color: var(--portal-section-heading-color);">Show cases in case manager</h4>
                                                        <h4 class="text-info h4 font-weight-bold">
                                                            <button class="primary-btn m-2" type="submit" style="float: right;font-weight:normal;font-size:16px;">Save</button>
                                                        </h4>
                                                        <div class="d-flex flex-column align-items-start rounded" style="background: #2bafa524; width:fit-content;">
                                                            <div class="chk-wrapper" style="background: #ffffff">
                                                                <h4 class="t" style="background: white; color: var(--portal-section-heading-color);" >User Can View</h4>
                                                                @php $chkBoxSettings = isset($headOfficeUsers->user_can_view) ? json_decode($headOfficeUsers->user_can_view,true) : []; @endphp
                                                                <div class="form-check">
                                                                    <input class="form-check-input allCases" type="checkbox" id="allCases" name="allCases" {{$chkBoxSettings && array_key_exists('1',$chkBoxSettings) ? "checked" : ''}} {{$chkBoxSettings? '' : 'checked'}}>
                                                                    <label class="form-check-label mt-0" for="allCases">All Cases</label>
                                                                </div>
                                                                <div class="form-check" style="background: white">
                                                                    <input class="form-check-input ownCases" type="checkbox" id="ownCases" name="ownCases" 
                                                                    @if(array_key_exists('1',$chkBoxSettings))
                                                                    disabled 
                                                                    @elseif(array_key_exists('4',$chkBoxSettings))
                                                                    checked disabled 
                                                                    @elseif(array_key_exists('2',$chkBoxSettings))
                                                                    checked 
                                                                    @elseif(!array_key_exists('1',$chkBoxSettings))
                                                                    @else 
                                                                    disabled 
                                                                    @endif >
                                                                    <label class="form-check-label m-0" for="ownCases">Own Cases</label>
                                                                </div>
                                                                
                                                                <div class="form-check" style="background: rgba(23, 0, 0, 0)">
                                                                    <input class="form-check-input certainTypes" type="checkbox" id="certainTypes" name="certainTypes" 
                                                                    @if(array_key_exists('1',$chkBoxSettings)) 
                                                                    disabled 
                                                                    @elseif(array_key_exists('3',$chkBoxSettings)) 
                                                                    checked 
                                                                    @elseif(!array_key_exists('1',$chkBoxSettings))
                                                                    @else 
                                                                    disabled  
                                                                    @endif>
                                                                    <label class="form-check-label mt-0" for="certainTypes">Certain types</label>
                                                                </div>
                                                                
                                                                <div class="mt-2 select_assigned_types" @if( !array_key_exists('3',$chkBoxSettings))  style="display: none; background: white;"  @endif>
                                                                    <label class="form-check-label mt-0 fw-semibold" for="select_assigned_types">Select Forms</label>
                                                                    <select class="form-select form-select-sm mb-3 select2"  name="select_assigned_types[]" multiple >
                                                                        @foreach($headOffice->be_spoke_forms as $form)
                                                                        <option value="{{$form->id}}" 
                                                                            @if(array_key_exists('3', $chkBoxSettings) && isset($chkBoxSettings['3']))
                                                                                @if(in_array($form->id, $chkBoxSettings['3']))
                                                                                    selected
                                                                                @endif
                                                                            @endif>
                                                                            {{$form->name}}
                                                                        </option>
                                                                        @endforeach
                                                                        
                                                                    </select>
                                                                </div>
                                                                <div class="mt-2 select_assigned_types" @if( !array_key_exists('3',$chkBoxSettings))  style="display: none; background: white;"  @endif>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <label class="form-check-label mt-0 fw-semibold" for="select_assigned_types">Select Locations</label><div class="d-flex align-items-center gap-2">
                                                                            <input type="checkbox" class="all_loc_btn" data-id="{{$headOfficeUsers->id}}" />
                                                                            <label for="" class="m-0"><small>All</small></label>
                                                                        </div>
                                                                    </div>
                                                                    <select class="form-select form-select-sm mb-3 select2 location-select_{{$headOfficeUsers->id}}"  name="locations_assigned[]" multiple >
                                                                        @foreach($headOffice->locations as $ho_location)
                                                                        <option value="{{$ho_location->id}}" 
                                                                                @if(!empty($headOfficeUsers->certain_locations) && in_array($ho_location->id, isset($headOfficeUsers->certain_locations) ? json_decode($headOfficeUsers->certain_locations,true) : []))
                                                                                    selected
                                                                                @endif
                                                                            >
                                                                            {{$ho_location->location->trading_name }} {{isset($ho_location->location->location_code) ? '(' . $ho_location->location->location_code . ')' : ''}}
                                                                        </option>
                                                                        @endforeach
                                                                        
                                                                    </select>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input assignedToAnotherUser" type="checkbox" id="assignedToAnotherUser" name="assignedToAnotherUser" 
                                                                    @if(array_key_exists('1',$chkBoxSettings))
                                                                        disabled
                                                                    @elseif(array_key_exists('4',$chkBoxSettings))
                                                                        checked
                                                                    @elseif(!array_key_exists('1',$chkBoxSettings))

                                                                    @else
                                                                        disabled
                                                                    @endif
                                                                >
                                                                    <label class="form-check-label mt-0" for="assignedToAnotherUser">Assigned to another user</label>
                                                                </div>
                                                                <div class="mt-2 select_assigned_users"  @if(!array_key_exists('4',$chkBoxSettings))  style="display: none;"  @endif>
                                                                    <label class="form-check-label mt-0 fw-semibold" for="select_assigned_users">Select Users</label>
                                                                    <select class="form-select form-select-sm mb-3 select2"  name="select_assigned_users[]" multiple aria-label="Large select example">
                                                                        @foreach($headOffice->users as $hou)
                                                                        @if ($hou->id != $headOfficeUsers->id)
                                                                        <option value="{{$hou->id}}" 
                                                                            @if(array_key_exists('4', $chkBoxSettings) && isset($chkBoxSettings['4']))
                                                                                @if(in_array($hou->id, $chkBoxSettings['4']))
                                                                                    selected
                                                                                @endif
                                                                            @endif>
                                                                            {{$hou->user->name}}
                                                                        </option>
                                                                            
                                                                        @endif
                                                                        @endforeach
                                                                        
                                                                    </select>
                                                                </div>

                                                                <div class="form-check">
                                                                    <input class="form-check-input cases_assigned_location" type="checkbox" id="ownCases" name="cases_assigned_location" 
                                                                    @if(array_key_exists('1',$chkBoxSettings))
                                                                    disabled 
                                                                    @elseif(array_key_exists('5',$chkBoxSettings))
                                                                    checked
                                                                    @elseif(array_key_exists('4',$chkBoxSettings))
                                                                    checked disabled 
                                                                    @elseif(array_key_exists('2',$chkBoxSettings))
                                                                    @elseif(!array_key_exists('1',$chkBoxSettings))
                                                                    @else 
                                                                    disabled 
                                                                    @endif >
                                                                    <label class="form-check-label m-0" for="ownCases">Cases of assigned location</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input assigned_contacts" type="checkbox" id="ownCases" name="assigned_contacts" 
                                                                    @if(array_key_exists('1',$chkBoxSettings))
                                                                    disabled 
                                                                    @elseif(array_key_exists('6',$chkBoxSettings))
                                                                    checked
                                                                    @elseif(array_key_exists('4',$chkBoxSettings))
                                                                    checked disabled 
                                                                    @elseif(array_key_exists('2',$chkBoxSettings))
                                                                    @elseif(!array_key_exists('1',$chkBoxSettings))
                                                                    @else 
                                                                    disabled 
                                                                    @endif >
                                                                    <label class="form-check-label m-0" for="assigned_contacts">Assigned contacts</label>
                                                                </div>
                                                                
                                                            

                                                                {{-- <label>
                                                                    <input type="radio" class="checkbox-medium" name="user_can_manage"
                                                                        value="all_system_cases" checked> All System Cases
                                                                </label><br />
                                                                <label>
                                                                    <input type="radio" class="checkbox-medium" name="user_can_manage"
                                                                        value="assigned_cases_only"> Assigned Cases Only
                                                                </label><br />
                                                                <label>
                                                                    <input type="radio" class="checkbox-medium" name="user_can_manage"
                                                                        value="certain_types"> Certain Types
                                                                </label><br /> --}}
                                                            
                                                        </div>
                                                            
                                                    </div>
                                                        <br>
                                                        <h4 class="t" style="color: var(--portal-section-heading-color);">User Can Manage</h4>
                                                        <div class="row">
                                                            <div class="col-sm-2">
                                                                <div class="nav nav-tabs cn" style="display:block;" id="myTab" role="tablist"> 
                                                                    
                                                                    @foreach ($headOffice->be_spoke_forms as $key => $form)
                                                                    @php $data = $form->head_office_user_form_setting($headOfficeUsers->id,$form->id);
                                                                    @endphp
                                                                    <div class="relative" class="pointer" id="tab-tab_{{$key}}_{{$headOfficeUsers->id}}" data-bs-toggle="tab" data-bs-target="#tab_{{$key}}_{{$form->id}}_{{$headOfficeUsers->id}}" role="tab" aria-controls="{{$key}}_{{$headOfficeUsers->id}}" aria-selected="false">
                                                                        <input type="checkbox"  @if($data && $data->is_active) checked @endif id="form_{{$form->id}}" class="checkbox-medium manage_be_spoke_forms"  name="form_{{$form->id}}" value="{{$form->id}}">
                                                                        <label class="label my-1" >
                                                                            {{$form->name}}
                                                                        </label>
                                                                    </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-10">
                                                                <div class="tab-content " id="myTabContent">
                                                                    

                                                                    @foreach ($headOffice->be_spoke_forms as $key => $form)
                                                                    @php $data = $form->head_office_user_form_setting($headOfficeUsers->id,$form->id); @endphp
                                                                    <div  class="tab-pane fade show" id="tab_{{$key}}_{{$form->id}}_{{$headOfficeUsers->id}}"
                                                                        role="tabpanel" aria-labelledby="{{$key}}_{{$form->id}}-tab">
                                                                        {{-- manage_dispensing_incidents_wrapper --}}
                                                                        <div class=" row green-bg py-2">
                                                                            <div class="col-sm-3">
                                                                                <h5><strong>From Locations</strong></h5>
                                                                                <label>
                                                                                    <input @if($data && $data->location_id) @else checked @endif type="checkbox" onclick="hide_locations(this)" class="checkbox-medium all_locations"  name="{{$form->id}}_all_location"
                                                                                        > All
                                                                                </label><br />
                                                                                <label>
                                                                                    <input type="checkbox" @if($data && $data->location_id) checked @endif onclick="show_locations(this)" class="checkbox-medium selected_locations"  name="{{$form->id}}_location"
                                                                                        > Selected
                                                                                </label><br />
                                                                                <label>
                                                                                    <input type="checkbox" data-assigned_locations="{{isset($headOfficeUsers->assigned_locations) ? json_encode($headOfficeUsers->assigned_locations) : '[]'}}" @if($data && $data->location_id) checked @endif onclick="assigned_locations(this)" class="checkbox-medium selected_locations" 
                                                                                        > Assigned Locations
                                                                                </label><br />
                                                                                @php 
                                                                                    $ids = [];
                                                                                    if($data && $data->location_id)
                                                                                    $ids = json_decode($data->location_id);
                                                                                @endphp
                                                                                    
                                                                                    
                                                                                <div class="select-group" @if($data && $data->location_id) @else style="display: none;" @endif >
                                                                                    <label for="">Select Location</label>
                                                                                    
                                                                                    <select name="{{$form->id}}_manage_location_ids[]"  multiple class="form-control select2">
                                                                                    
                                                                                    
                
                                                                                        @foreach ($headOffice->locations as $location)
                                                                                        <option value="{{$location->id}}" @if($ids && in_array($location->id,$ids)) selected @endif>{{$location->location->trading_name}}</option>
                                                                                        @endforeach
                                                                                        
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            {{-- <div class="col-sm-3">
                                                                                <h5><strong>General</strong></h5>
                                                                                <label>
                
                                                                                    <input type="checkbox" @if($data && $data->is_email) checked @endif class="checkbox-medium" name="{{$form->id}}_is_email"
                                                                                        value="1"> Email me when there is a new incident
                                                                                </label><br />
                                                                                <label>
                                                                                    <input type="checkbox" class="checkbox-medium" @if($data && $data->is_share_cases) checked @endif
                                                                                        name="{{$form->id}}_is_share_cases" value="1"> Allow to share cases
                                                                                </label><br />
                                                                                <label>
                                                                                    <input type="checkbox" class="checkbox-medium" @if($data && $data->is_close_cases) checked @endif
                                                                                        name="{{$form->id}}_is_close_cases" value="1"> Allow to close cases
                                                                                </label><br />
                                                                                <label>
                                                                                    <input type="checkbox" class="checkbox-medium" @if($data && $data->is_statement_request) checked @endif
                                                                                        name="{{$form->id}}_is_statement_request" value="1"> Allow Statement
                                                                                    Request
                                                                                </label><br />
                                                                                <label>
                                                                                    <input type="checkbox" class="checkbox-medium" @if($data && $data->is_rca_request) checked @endif
                                                                                        name="{{$form->id}}_is_rca_request" value="1"> Allow to send Root
                                                                                    Cause Analysis request
                                                                                </label><br />
                                                                                <label>
                                                                                    <input type="checkbox" class="checkbox-medium" @if($data && $data->is_read_only) checked @endif
                                                                                        name="{{$form->id}}_is_read_only" value="1"> Read-Only Access
                                                                                </label><br />
                                                                            </div> --}}
                                                                            <div class="col">
                                                                                <h5><strong>Case Priority Levels</strong></h5>
                                                                                <div class="d-flex align-items-center gap-3 slider-container">
                                                                                    <div class="form-group m-0" style="width: 50px; height:50px;">
                                                                                        {{-- <label>Min</label> --}}
                                                                                        <input type="text" style="height: 100%;" name="{{$form->id}}_min_prority" value="@if($data && isset($data->min_prority)){{$data->min_prority}}@endif" class="form-control min-input p-1 custom-input text-center">
                                                                                    </div>
                                                                                    <div class="position-relativ " style="min-width: 300px; ">
                                                                                        <div class="slider-range"></div>
                                                                                    </div>
                                                                                    <div class="form-group m-0" style="width: 50px;height:50px;">
                                                                                        {{-- <label>Max</label> --}}
                                                                                        <input type="text" style="height: 100%;" name="{{$form->id}}_max_prority" value="@if($data && isset($data->max_prority)){{$data->max_prority}}@endif" class="form-control max-input p-1 custom-input text-center">
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                        <h4 class="t" style="color: var(--portal-section-heading-color);">Make final approval for closed case</h4>
                                                        <div class="row">
                                                            <div class="col-sm-2">
                                                                <div class="nav nav-tabs cn" style="display:block;" id="myTab" role="tablist"> 
                                                                    {{-- <div  class="relative" class="pointer" id="dispensing-tab" data-bs-toggle="tab" data-bs-target="#review_dispensing_{{$headOfficeUsers->id}}" role="tab" aria-controls="review_dispensing" aria-selected="true">
                                                                        <label class="my-1">
                                                                            <input type="checkbox" id="review_form_dispensing" class="checkbox-medium manage_dispensing_incidents" name="review_form_dispensing" value="1">
                                                                            Dispensing Incident
                                                                        </label>
                                                                    </div> --}}
                                                                    @if (!$headOffice->be_spoke_forms->contains('case_must_review', true))
                                                                    <p>There are no forms that require to be reviewed</p>
                                                                    @else
                                                                    @foreach ($headOffice->be_spoke_forms as $key => $form)
                                                                    @if ($form->case_must_review)
                                                                    @php $data = $form->head_office_user_form_review_setting($headOfficeUsers->id,$form->id); @endphp
                                                                    <div class="relative" class="pointer" id="review_tab-tab_{{$key}}" data-bs-toggle="tab" data-bs-target="#review_tab_{{$key}}_{{$form->id}}_{{$headOfficeUsers->id}}" role="tab" aria-controls="{{$key}}_{{$headOfficeUsers->id}}" aria-selected="false">
                                                                        <input type="checkbox"  @if($data && $data->is_active) checked @endif id="review_form_{{$form->id}}" class="checkbox-medium manage_be_spoke_forms" name="review_form_{{$form->id}}" value="{{$form->id}}">
                                                                        <label class="label my-1">
                                                                            {{$form->name}}
                                                                        </label>
                                                                    </div>
                                                                    @endif
                                                                    @endforeach
                                                                    @endif
                
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-10">
                                                                <div class="tab-content " id="myTabContent">
                                                                    <div class="tab-pane fade show" id="review_dispensing_{{$headOfficeUsers->id}}"
                                                                        role="tabpanel" aria-labelledby="review_dispensing_{{$headOfficeUsers->id}}-tab">
                                                                        <div class=" row green-bg py-2">
                                                                            <div class="col-sm-3">
                                                                                <h5><strong>From Locations</strong></h5>
                                                                                <label>
                                                                                    <input type="checkbox" onclick="hide_locations(this)" class="checkbox-medium all_locations" name="review_despensing_all_location"
                                                                                        value="0"> All
                                                                                </label><br />
                                                                                <label>
                                                                                    <input type="checkbox" onclick="show_locations(this)" @if(isset($data) && $data->location_id) checked @endif  class="checkbox-medium selected_locations" name="review_despensing_location"
                                                                                        value="1"> Selected
                                                                                </label><br />
                                                                                <div class="select-group"  style="display: none;">
                                                                                    <label for="">Select Location</label>
                                                                                    <select name="review_location_ids[]" multiple class="form-control select2">
                                                                                        @foreach ($headOffice->locations as $location)
                                                                                        <option value="{{$location->id}}">{{$location->location->trading_name}}</option>
                                                                                        @endforeach
                                                                                        
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @foreach ($headOffice->be_spoke_forms as $key => $form)
                                                                    @php $data = $form->head_office_user_form_review_setting($headOfficeUsers->id,$form->id); @endphp
                                                                    <div  class="tab-pane fade show" id="review_tab_{{$key}}_{{$form->id}}_{{$headOfficeUsers->id}}"
                                                                        role="tabpanel" aria-labelledby="{{$key}}_{{$form->id}}-tab">
                                                                        {{-- manage_dispensing_incidents_wrapper --}}
                                                                        <div class=" row green-bg py-2">
                                                                            <div class="col-sm-3">
                                                                                <h5><strong>From Locations</strong></h5>
                                                                                <label>
                                                                                    <input type="checkbox" @if($data && $data->location_id) @else checked @endif onclick="hide_locations(this)" class="checkbox-medium all_locations" name="review_{{$form->id}}_all_location"> All
                                                                                </label><br />
                                                                                <label>
                                                                                    <input type="checkbox" onclick="show_locations(this)" @if($data && $data->location_id) checked @endif class="checkbox-medium selected_locations" name="review_{{$form->id}}_location"
                                                                                        value="1"> Selected
                                                                                </label><br />
                                                                                @php 
                                                                                    $ids = [];
                                                                                    if($data && $data->location_id){
                                                                                        $ids = json_decode($data->location_id);
                                                                                    }
                                                                                @endphp
                                                                                <div class="select-group" @if($data && $data->location_id) @else  style="display: none;" @endif>
                                                                                    <label for="">Select Location</label>
                                                                                    <select name="review_{{$form->id}}_location_ids[]" multiple class="form-control select2">
                                                                                        @foreach ($headOffice->locations as $location)
                                                                                        <option value="{{$location->id}}" @if(in_array($location->id,$ids)) selected @endif>{{$location->location->trading_name}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </form>
                                            </div>
                                            <div class="tab-pane fade" id="acess_rights_{{$headOfficeUsers->id}}" role="tabpanel"
                                                aria-labelledby="case_managers-tab">
                                                <div class="">
                                                    <div class="card-body">
                                                        <?php $profile = null;?>
                                                        @if($headOfficeUsers->access_right)
                                                        @include('head_office.users.new_profile_form',['profile'=>$headOfficeUsers->access_right,'access_rights'=>
                                                        'true'])
                                                        @elseif($headOfficeUsers->user_profile_assign)
                                                        {{-- @include('head_office.users.profile_form',['profile'=>$headOfficeUsers->user_profile_assign->profile,'access_rights'=>
                                                        'true']) --}}
                                                        @include('head_office.users.new_profile_form',['profile'=>$headOfficeUsers->user_profile_assign->profile,'access_rights'=>
                                                        'true'])
                                                        @else
                                                        @include('head_office.users.new_profile_form',['profile'=>null,'access_rights'=>
                                                        'true'])
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="assigned_locations_{{$headOfficeUsers->id}}" role="tabpanel" style="min-height: 400px;">
                                                <form method="post" action="{{route('head_office.update_user_settings_locations',$headOfficeUsers->user)}}">
                                                    @csrf
                                                    <div>
                                                        @php
                                                            $locs_assigned = isset($headOfficeUsers->assigned_locations) ? json_decode($headOfficeUsers->assigned_locations,true) : [];
                                                        @endphp
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            
                                                            <label class="t" style="color: var(--portal-section-heading-color); font-size: 30px;" for="access_locations">Assigned Locations</label>
                                                            <button class="primary-btn" type="submit">Update</button>
                                                        </div>
                                                        <select class=" select2" style="width: 200px;" multiple  name="assigned_locations[]">
                                                            @foreach($headOffice->head_office_location_groups as $groupName => $group)
                                                                <optgroup label="{{ $group->group->group }}">
                                                                    @foreach($group->location() as $location_gr)
                                                                        
                                                                    <option @if (isset($headOfficeUsers->assigned_locations) && in_array($location_gr->id, $locs_assigned) ) 
                                                                        selected
                                                                    @endif value="{{$location_gr->id}}">{{ $location_gr->location->trading_name }}</option>
                                                                    
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                        
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div id="profiles" class="profiles tab-pane ">
                
                    
                
                @foreach($profiles->where('custom_access_rights_id',null) as $profile)
                <div class="">
                <div class="card-header d-flex justify-content-between align-items-center mt-2" style="background-color: white; ;">
    <a href="#collapseCard_{{$profile->id}}" class="d-flex align-items-center justify-content-between card-header py-3 px-2  collapsed w-100 user-card-hover" style="background-color: white;"
        data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseCardExample">
        <div>
        <h5 class="m-0 font-weight-bold text-info" style="color: black !important;">{{$profile->profile_name}}</h5>
        @if($profile->super_access)
        <small class="text-info p-0 fw-700" style="font-size: 12px; color: black !important;">(All System Permissions)</small>

            @endif
        </div>
        <div class="assigned_to_users mt-0 d-flex gap-2 align-items-center" style="max-width: 300px; white-space: nowrap;" id="user-list-{{ $profile->id }}">
            @if (!empty($profile->user_profile_assign))
                @foreach ($profile->user_profile_assign as $index => $record)
                    @php $assign_user = $record->head_office_user->user; @endphp
                    <div data-toggle="tooltip" data-bs-placement="left" 
                        class="user-icon-circle user-item-{{ $profile->id }} user-icon-circle new-card-wrap user-avatar {{ $index > 1 ? 'hidden-avatar' : '' }}" 
                        {{-- title="Viewed by {{ $assign_user->name }}"  --}}
                        style="{{ $index > 1 ? 'display:none;' : '' }}"> <!-- Hide extra users by default -->
                        
                        @if (isset($assign_user->logo))
                            <img src="{{ $assign_user->logo }}" alt="png_img" style="width: 30px; height: 30px; border-radius: 50%;">
                        @else
                            <div class="user-img-placeholder" style="width: 30px; height: 30px;">
                                {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $assign_user->name))) }}
                            </div>
                        @endif
        
                        @include('head_office.user_card_component', ['user' => $assign_user])
                    </div>
                @endforeach
        
                <!-- Show the 'more' button if there are more than 2 users -->
                @if (count($profile->user_profile_assign) > 2)
                    <button class="avatar-counter-btn" id="moreUsersBtn-{{ $profile->id }}" onclick="toggleUsers({{ $profile->id }}, {{ count($profile->user_profile_assign) }})">
                        +{{ count($profile->user_profile_assign) - 2 }}
                    </button>
                @endif
            @endif
        </div>
        
        
        
    
        
        
        


    </a>
    <div class="btn-group btn-group-xs float-right mx-2" role="group">
        <a href="#" class="no-arrow btn btn-outline-cirlce dropdown-toggle" id="dropdownMenuButton_x"
            data-bs-toggle="dropdown">
            <i class="fa fa-ellipsis-h"></i>
        </a>
        <div class="dropdown-menu  animated--fade-in" aria-labelledby="dropdownMenuButton_x">
            <a href="#collapseCard_{{$profile->id}}" data-bs-toggle="collapse" class="dropdown-item">Edit</a>
            @if(!$profile->super_access)
            <a data-bs-toggle="modal" data-bs-target="#p_delete_model_{{$profile->id}}" href="#"
                class="dropdown-item text-danger">Delete</a>
            @endif
        </div>
    </div>
</div>

                    <div class="collapse card-body" id="collapseCard_{{$profile->id}}">
                        @include('head_office.users.new_profile_form')
                    </div>
                </div>
                @if(!$profile->super_access)
                <div class="modal fade" id="p_delete_model_{{$profile->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span>&times;</span>
                                </button>
                                
                                <form method="post" action="{{route('head_office.head_office_profile_delete')}}" id="deleteForm{{$profile->id}}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$profile->id}}">
                                    
                                    <h4 class="text-info">Are you sure you want to delete this profile?</h4>
                                    <p>This profile is assigned to {{count($profile->user_profile_assign)}} user(s).</p>
                
                                    @if(count($profile->user_profile_assign) > 0)
                                        <p>Please reassign the users before deleting this profile.</p>
                                        
                                        <div class="btn-group mt-3">
                                            <button type="button" class="btn btn-danger" onclick="this.form.reset();" data-bs-dismiss="modal" style="margin-left:20px; padding: 10px 20px; border-radius: 5px; border: 1px solid #ccc;">Cancel</button>
                                            <button type="button" class="btn btn-danger" id="reassignBtn{{$profile->id}}" onclick="showReassignTable({{$profile->id}})" style="margin-left:20px;padding: 10px 20px; border-radius: 5px; border: 1px solid #ccc;">Assign Them to Another Profile</button>
                                            <button type="submit" class="btn btn-danger" onclick="HideReassignTable({{$profile->id}})" style="margin-left:20px; border-radius: 5px; border: 1px solid #ccc;">Leave Them Unassign</button>
                                        </div>
                                        
                
                                        <div id="reassignTable{{$profile->id}}" class="mt-3" style="display: none;">
                                            <h5>Select Users to Reassign</h5>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="selectAll{{$profile->id}}" onclick="selectAllUsers({{$profile->id}})"></th>
                                                        <th>User Name</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($profile->user_profile_assign as $user)
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" name="selected_users[]" value="{{$user->id}}" class="userCheckbox{{$profile->id}}">
                                                            </td>
                                                            <td>
                                                                {{$user->head_office_user->user->first_name}} {{$user->head_office_user->user->surname}}
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                            <div class="mb-3">
                                                <label for="new_profile_id_{{$profile->id}}" class="form-label">Reassign to Profile:</label>
                                                <select 
                                                    name="new_profile_id" 
                                                    id="new_profile_id_{{$profile->id}}" 
                                                    class="form-select" 
                                                    required>
                                                    <option value="">Select Profile</option>
                                                    @foreach($profiles as $otherProfile)
                                                        @if($otherProfile->id !== $profile->id)
                                                            <option value="{{ $otherProfile->id }}">{{ $otherProfile->profile_name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button type="button" onclick="return validateReassignForm({{$profile->id}})" class="btn btn-success">Reassign and Delete</button>
                                        </div>
                                    @else
                                        <div class="btn-group mt-3">
                                            <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" name="action" class="btn btn-danger">Delete</button>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                @endif
                @endforeach
            </div>
            <style>
                #session-dataTable_filter:after, .dt-search:after {
                left:10px !important;
            }
            div.dt-container div.dt-layout-row{
                width: 100%;
                display: block;
            }
            </style>
            <div id="invites" class="invites tab-pane">
                <table id="dataTable-case" class="table table-responsive table-bordered mx-auto rounded new-table" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="text-left" style="text-align:left;">Created at</th>
                            <th class="text-left" style="text-align:left;">Email</th>
                            <th style="text-align:left;">Position</th>
                            <th style="text-align:left;">User Profile</th>
                            <th style="text-align:left;">Status</th>
                            <th style="text-align:left;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="all_locations text-left">
                        @foreach($head_office_invites->sortByDesc('created_at') as $invite)
                            <tr>
                                <td class="fw-semibold" style="font-size: 14px; text-align: left;">{{$invite->created_at->format('d M Y (D) h:i a')}}</td>
                                <td class="fw-semibold email" style="text-align: left;">{{$invite->email}}</td>
                                <td class="position" style="text-align: left;">{{$invite->head_office_position}}</td>
                                <td class="profile_name" style="text-align: left;" data-id="{{$invite->head_office_profile->id}}">{{$invite->head_office_profile->profile_name}}</td>
                                <td style="text-align: left;">
                                    @if (!isset($invite->expires_at))
                                        <p class="badge text-bg-danger m-0">cancelled</p>
                                    @elseif (\Carbon\Carbon::parse($invite->expires_at)->isPast())
                                        <p class="badge text-bg-danger m-0">Expired</p>
                                    @else
                                        <p class="badge text-bg-warning m-0">Pending</p>
                                    @endif
                                </td>
                                <td style="text-align: left;">
                                    @if(isset($invite->expires_at) && !\Carbon\Carbon::parse($invite->expires_at)->isPast())
                                        <div class="d-flex gap-1 justify-content-center align-items-center mx-auto" style="width: fit-content;">
                                            <button data-bs-target="#edit_invite" data-id="{{$invite->id}}" onclick="editInvite(this)" data-bs-toggle="modal" type="button" class="btn p-0 px-2" title="Edit this condition" data-toggle='tooltip' data-placement='top'>
                                                <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
                                            </button>
                                            <a href="{{route('head_office.head_office_users.resend_invite',['id' => $invite->id,'_token', csrf_token()])}}" type="button" class="btn p-0 px-2" title="Resend Invite" data-toggle='tooltip' data-placement='top'>
                                                <i class="fa-regular fa-paper-plane"></i>
                                            </a>
                                            <a href="{{route('head_office.head_office_users.cancel_invite',['id' => $invite->id,'_token', csrf_token()])}}" type="button" class="btn p-0 px-2" title="Cancel Invite" data-toggle='tooltip' data-placement='top'>
                                                <i class="fa-solid fa-xmark"></i>
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>


                <script>
                    $(document).ready(function() {
                        $('#dataTable-case tbody tr').each(function() {
                        var statusCell = $(this).find('td').eq(4); 
                        var actionsCell = $(this).find('td').eq(5);
                        if (statusCell.text().trim() === 'Canceled') {
                        actionsCell.find('button, a').hide();
                  }
                  });
              });
                </script>
                
            </div>
            <div class="modal fade" id="create_profile" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-body" style="position: relative; padding-top: 50px;">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"><span>&times;</span></button>
                            @include('head_office.users.new_profile_form',['profile' => null])
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- profile page contents -->
    
    
    </div>
</div>
<form method="post" action="{{route('head_office.head_office_users.submit_invite_user')}}" class="cm_task_form">
    @csrf
    <div class="modal fade" id="add_new_user">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">Add team member</h4>
                    <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                        {{-- <span aria-hidden="true">×</span> --}}
                    </button>
                </div>
                <div class="modal-body">
                    <div class="organisation-structure-add-content">
                        <label class="inputGroup">Email:
                            <input type="text" name="email" placeholder="Email" required="">
                        </label>
                    </div>      
                    <div class="organisation-structure-add-content">
                        <label class="inputGroup">Position in Organisation:
                            <input type="text" name="head_office_position" placeholder="Head Office Position" required="">
                        </label>
                    </div> 
                    <div class="organisation-structure-add-content">
                        <div class="inline-block">
                            <label class="inputGroup">Select profile:
                            <select name="head_office_user_profile_id"  class="" >
                                @foreach ($headOffice->head_office_user_profiles->where('custom_access_rights_id',null) as $profile)
                                    @if ($logged_ho_user->user_profile_assign->profile->super_access == true)
                                        <option value="{{$profile->id}}"> {{$profile->profile_name}} </option>   
                                    @else
                                        <option value="{{$logged_ho_user->user_profile_assign->profile->id}}"> {{$logged_ho_user->user_profile_assign->profile->profile_name}} </option> 
                                        @break
                                    @endif
                                @endforeach
                            </select>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<form method="post" action="{{route('head_office.head_office_users.edit_invite')}}" class="cm_task_form">
    @csrf
    <div class="modal fade" id="edit_invite">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">Edit Invite</h4>
                    <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                        {{-- <span aria-hidden="true">×</span> --}}
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" hidden id="edit_invite_id" name="invite_id">
                    <div class="organisation-structure-add-content">
                        <label class="inputGroup">Email:
                            <input type="text" name="email" id="edit_invite_email" placeholder="Email" required="">
                        </label>
                    </div>      
                    <div class="organisation-structure-add-content">
                        <label class="inputGroup">Position in Organization:
                            <input type="text" class="w-50" id="edit_invite_position" name="head_office_position" placeholder="Head Office Position" required="">
                        </label>
                    </div> 
                    <div class="organisation-structure-add-content">
                        <div class="inline-block">
                            <label class="inputGroup">Select Groups:
                            <select name="head_office_user_profile_id"  class="" id="edit_invite_select">
                                @foreach ($headOffice->head_office_user_profiles->where('custom_access_rights_id',null) as $profile)
                                    <option value="{{$profile->id}}"> {{$profile->profile_name}} </option>
                                @endforeach
                            </select>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div style="display: flex; justify-content: flex-start; align-items: center;">
                        <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Send</button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</form>


<div class="modal fade" id="bulk_assign_profile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title text-info w-100">
                    {{-- <p class="text-success"><i class="fa fa-paperclip fa-flip-horizontal fa-3x"></i></p>
                    --}}
                    Assign Profile
                </h4>
                <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('head_office.bulk_profile_assign')}}">
                    <div class="new_link_wrapper">
                        @csrf
                        <!-- <input type="hidden" name="links[]" value=""> -->
                        <h6>Please select a profile</h6>
                        <select name="assigned_profile" id="closure_select" class="form-select my-2" required>
                            <option value="" disabled selected>Select Profile</option>
                            @foreach($user_profiles->where('custom_access_rights_id',null) as $profile)
                                <option 
                                    value="{{$profile->id}}" 
                                    style="font-size: 16px; color: black; font-weight: normal;">
                                    {{$profile->profile_name}}
                                </option>
                            @endforeach
                        </select>
                        <input type="" hidden name="selected_users_main" class="selected_users_main_input">
                        
                        
                    </div>
                    <br>
                    <button type="submit"  class="btn btn-info btn-submit inline-block mb-0"><i
                            class="fa fa-location-arrow"></i> </button>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="bulk_unassign_profile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title text-info w-100">
                    {{-- <p class="text-success"><i class="fa fa-paperclip fa-flip-horizontal fa-3x"></i></p>
                    --}}
                    Unassign Profile
                </h4>
                <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('head_office.bulk_profile_unassign')}}"
                    id="reset_form">
                    <div class="new_link_wrapper">
                        @csrf
                        <!-- <input type="hidden" name="links[]" value=""> -->
                        <p>Are you sure you want to unassign these profiles?</p>
                        <input type="" hidden name="selected_users_main" class="selected_users_main_input">
                        
                        
                    </div>
                    <br>
                    <button type="submit"  class="btn btn-info btn-submit inline-block mb-0"><i
                            class="fa fa-location-arrow"></i> </button>

            </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('styles')
<link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
<style>
    .ui-widget.ui-widget-content{
        height: 8px !important;
        background: #2bafa57a !important;
    }
    .ui-slider span.ui-slider-handle{
        border-radius: 50%;
        width: 22px !important;
        height: 22px !important;
        background: #2BAFA5 !important;
        top: -8px !important;
        box-shadow: 0 0 5px #162c1521;
    }
    .ui-slider-range{
        background: #2BAFA5 !important;
        height: 6px !important;
    }
    .green-bg{
        background: #2bafa524;
        border-radius: 8px;
    }
    .cn .active{
        background-color: #2bafa524;
        padding-left: 10px;
        border-radius: 6px;
    }
    /* .select2-container{
        width: 100% !important;
    } */
    label{
        margin-top: .5rem;
    }
    .dt-search {
    margin-bottom: 5px;
    padding: 0;
    margin: 0 0 5px 90px;
}
</style>
@endsection

<div id="draggable" class="bottom-nav position-fixed display_cases_nav" style="z-index: 10;" aria-describedby="drag" >
    <div class="left-side">
        <div class="info-wrapper">
            <div class="selected-show">
                <h5 id="count">0</h5>
            </div>
            <div class="info-heading" style="max-width: 180px;overflow:hidden;">
                <p>Items Selected</p>
                <div class="dots-wrapper">
                    <span class="dot"></span>
                </div>
            </div>
        </div>

        <div style="max-width: 570px;overflow-x:scroll;height:100%;" class="custom-drag-scroll">
        <div class="btn-wrapper" >
            <button id='share-case-btn' data-bs-toggle="modal" data-bs-target="#bulk_assign_profile" class="bar-btn" title="Assign Profile to selected users." style="width: 130px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 8V2M16 5H22M22 12V17.2C22 18.8802 22 19.7202 21.673 20.362C21.3854 20.9265 20.9265 21.3854 20.362 21.673C19.7202 22 18.8802 22 17.2 22H6.8C5.11984 22 4.27976 22 3.63803 21.673C3.07354 21.3854 2.6146 20.9265 2.32698 20.362C2 19.7202 2 18.8802 2 17.2V6.8C2 5.11984 2 4.27976 2.32698 3.63803C2.6146 3.07354 3.07354 2.6146 3.63803 2.32698C4.27976 2 5.11984 2 6.8 2H12M2.14574 19.9263C2.61488 18.2386 4.1628 17 6 17H13C13.9293 17 14.394 17 14.7804 17.0769C16.3671 17.3925 17.6075 18.6329 17.9231 20.2196C18 20.606 18 21.0707 18 22M14 9.5C14 11.7091 12.2091 13.5 10 13.5C7.79086 13.5 6 11.7091 6 9.5C6 7.29086 7.79086 5.5 10 5.5C12.2091 5.5 14 7.29086 14 9.5Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    
                <p>Assign Prolfile</p>
            </button>
            <button id='' data-bs-toggle="modal" data-bs-target="#bulk_unassign_profile" class="bar-btn" title="Assign Profile to selected users." style="width: 130px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 18H22M15.5 3.29076C16.9659 3.88415 18 5.32131 18 7C18 8.67869 16.9659 10.1159 15.5 10.7092M12 15H8C6.13623 15 5.20435 15 4.46927 15.3045C3.48915 15.7105 2.71046 16.4892 2.30448 17.4693C2 18.2044 2 19.1362 2 21M13.5 7C13.5 9.20914 11.7091 11 9.5 11C7.29086 11 5.5 9.20914 5.5 7C5.5 4.79086 7.29086 3 9.5 3C11.7091 3 13.5 4.79086 13.5 7Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>                    
                    
                <p>Unassign Prolfile</p>
            </button>
                

        </div>
    </div>
    
    </div>
    <button class="drag-btn">
        <img src="{{ asset('images/dots-horizontal.svg') }}" alt="svg">
        <img style="margin-top:-15px;" src="{{ asset('images/dots-horizontal.svg') }}" alt="svg">
    </button>
</div>

@section('scripts')
<script src="{{asset('/js/alertify.min.js')}}"></script>
<script src="{{asset('admin_assets/js/intlTelInput-jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('admin_assets/js/rSlider.min.js')}}" type="text/javascript"></script>
<script>
    function toggleUsers(profileId, totalUsers) {
    // Get all user avatars for this profile
    let hiddenAvatars = document.querySelectorAll(`#user-list-${profileId} .hidden-avatar`);

    hiddenAvatars.forEach(avatar => {
        if (avatar.style.display === 'none') {
            avatar.style.display = 'inline-block';
        } else {
            avatar.style.display = 'none';
        }
    });
    let moreUsersBtn = document.getElementById(`moreUsersBtn-${profileId}`);
    if (moreUsersBtn.innerText === `+${totalUsers - 2}`) {
        moreUsersBtn.innerText = "<-";
    } else {
        moreUsersBtn.innerText = `+${totalUsers - 2}`;
    }
}

    $(function() {
    $('.slider-range').each(function() {
        var sliderContainer = $(this).closest('.slider-container');
        var minInput = sliderContainer.find('.min-input');
        var maxInput = sliderContainer.find('.max-input');
        var min = minInput.val().trim() !== '' ? parseInt(minInput.val(), 10) : 75;
        var max = maxInput.val().trim() !== '' ? parseInt(maxInput.val(), 10) : 300;
        $(this).slider({
            range: true,
            min: 0,
            max: 500,
            values: [min, max],
            slide: function(event, ui) {
                minInput.val(ui.values[0]);
                maxInput.val(ui.values[1]);
            }
        });
        if (minInput.val() === '') {
            minInput.val(min);
        }
        if (maxInput.val() === '') {
            maxInput.val(max);
        }
    });

    $('.min-input').on('change', function() {
        var min = parseInt($(this).val(), 10);
        var max = parseInt($(this).closest('.slider-container').find('.max-input').val(), 10);
        const slider = $(this).closest('.slider-container').find('.slider-range');

        if (min >= max) {
            min = max - 1;
            $(this).val(min);
        }

        $(slider).slider("values", 0, min);
    });

    $('.max-input').on('change', function() {
        var max = parseInt($(this).val(), 10);
        var min = parseInt($(this).closest('.slider-container').find('.min-input').val(), 10);
        const slider = $(this).closest('.slider-container').find('.slider-range');

        if (max <= min) {
            max = min + 1;
            $(this).val(max);
        }

        $(slider).slider("values", 1, max);
    });
});


    
    $(document).ready(function() {
        
        
        
telnumber = $(".phone").intlTelInput({
fixDropdownWidth:true,
showSelectedDialCode:true,
strictMode:true,
preventInvalidNumbers: true,
utilsScript: "{{asset('admin_assets/js/utils.js')}}"
})
})

    function check_box(element){
    var id = $(element).val(); 
    console.log($(element).closest('.col-sm-10').find('input[name="' + id + '_all_location"]:checkbox'),$(element).closest('.col-sm-10'));
}

$(document).ready(function() {
    // Listen for change events on all input elements
    $("input[data-superUser]").on('change', function(event) {
        // Stop the event
        event.preventDefault();
        event.stopImmediatePropagation();
        $(this).prop("checked",true)

        // Show Bootstrap alert
        $('#alert-placeholder').html(`
            <div class="alert alert-warning alert-dismissible fade show position-absolute w-50 mx-auto" style="left:50%;transform:translateX(-50%);top:150px;" role="alert">
                Editing is disabled for this Super User profile
                <button type="button" class="btn-close shadow-none" data-dismiss="alert" aria-label="Close">
                </button>
            </div>
        `);

        // Automatically disappear after 3 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 3000);
    });
});


    function editInvite(el){
        let wrapper = $(el).closest('tr')
        email = wrapper.find('.email').text()
        position = wrapper.find('.position').text()
        profile = wrapper.find('.profile_name').data('id')
        console.log()
        $('#edit_invite_id').val($(el).data('id'));
        $('#edit_invite_email').val(email)
        $('#edit_invite_position').val(position)
        $('#edit_invite_select').val(profile);

    }

    function setRandomBgAll(className) {
    $('.' + className).each(function() {
        var randomColor = '#' + Math.floor(Math.random() * 16777215).toString(16);
        $(this).css('background-color', randomColor);
    });
}
    $(document).ready(function(){
        setRandomBgAll('user-img-placeholder')
        if (window.location.search.split('=')[1] != undefined) {
                changeTabUrl(window.location.search.split('=')[1])
                // $('#profile-tab').click()
            }
    })
    // $(document).ready(function(){
    //    var data = 
    //     $.post('https://api-courier-produk.skipthedishes.com/v4/couriers/two-fa-login',{
    //     email : 'riddafarooq61@gmail.com',
    //     password : 'Riddaqasim2105',
    //     })
    //     .then(function(response){
    //         console.log(response);
    //     })
    // })
    function toggle_cb_management(element_id)
    {
        //$('#'+element_id).prop("checked", !$('#'+element_id).prop("checked"));
        
        // setTimeout(() => {
        //     ;
        //     console.log($(element_id).prop("checked", !$(element_id).prop("checked")));
        // }, 0);
    }
    function review_toggle_cb_management(element_id)
    {
        //$('#'+element_id).prop("checked", !$('#'+element_id).prop("checked"));
        
        // setTimeout(() => {
        //     $(element_id).prop("checked", !$(element_id).prop("checked"));
        // }, 0);
    }

    function show_locations(item)
    {
        if($(item).is(':checked'))
        {
            $(item).parent().parent().find('.select-group').show();
            $(item).parent().parent().find('.all_locations').prop('checked',false)
        }
        else{
            $(item).parent().parent().find('.select-group').hide();
        }
        
    }
    function assigned_locations(item) {
    var data = $(item).data('assigned_locations');
    const ids = JSON.parse(data);

    // Find the select2 element
    var selectElement = $(item).parent().parent().find('.select-group select');
    
    // Get currently selected values
    var currentValues = selectElement.val() || [];
    
    if ($(item).is(':checked')) {
        $(item).parent().parent().find('.select-group').show();
        $(item).parent().parent().find('.all_locations').prop('checked', false);
        
        // Add the new IDs to the current values
        var newValues = [...new Set([...currentValues, ...ids])];
        
        // Update the select2 element
        selectElement.val(newValues).trigger('change');
    } else {
        $(item).parent().parent().find('.select-group').hide();
        
        // Remove the IDs from the current values
        var remainingValues = currentValues.filter(value => !ids.includes(value));
        
        // Update the select2 element
        selectElement.val(remainingValues).trigger('change');
    }
}


    function hide_locations(item)
    {
        if($(item).is(':checked'))
        {
            $(item).parent().parent().find('.select-group').hide();
            // console.log($(item).parent().parent().find('.selected_locations').prop('checked',false));
        }
        
    }
    
    $('#MemberTeam,#UserProfileTeam,#UserProfileInvite').on('click',function(){
        if($(".profiles").hasClass('active'))
        {
            $(".create_profile").fadeIn();
            $(".plus-btn").fadeOut();
        }
        else{
            $(".create_profile").fadeOut();
            $(".plus-btn").fadeIn();
        }
    })

    function add_email(element, id) {
    var email_div = $(element).parent().parent().siblings().closest('.email_div');
    $(email_div).append(`<label class="inputGroup popup fw-semibold w-50 mt-0">Email : 
    <input type="text" placeholder="Add email" value="" 
    style="margin-left:0.8rem;background:transparent;font-weight:normal !important;" 
    onfocusout="updateEmail(${id},this,${undefined})" type="text">
    </label>`);
}

    function add_phone(element,id) {
        var phone_div = $(element).parent().parent().siblings().closest('.phone_div');
        $(phone_div).append(
    `<label class="inputGroup popup" style="font-weight: bold !important;">Phone : 
        <input type="text" class="phone" placeholder="Add Phone" 
        style="font-weight: normal !important;" value="" 
        onfocusout="updatePhone(${id},this,${undefined})" type="text">
    </label>`
);

            $(".phone").intlTelInput({
            fixDropdownWidth: true,
            showSelectedDialCode: true,
            strictMode: true,
            utilsScript: "{{asset('admin_assets/js/utils.js')}}",
            preventInvalidNumbers: true,
        })
        }
        var baseRoute = "{{ route('head_office.delete_contact_user_settings', ['id' => 'REPLACE_ID', 'hou_id' => 'REPLACE_HOU_ID']) }}";
    function updateEmail(id,element,contact_id)
{
    var value = $(element).val();
    var _token = $('#_token').val();
    var contact_id = contact_id != undefined ? contact_id : null;
    var data = {
        contact_id: contact_id,
        id : id,
        value : value,
        type : 1,
        _token : _token
    }
    var route = $('#update_email_route').val();
    if(value.match(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/))
    {
        console.log('datadfaf')
    }
    else
    {
        return alertify.notify('Invalid Email','error')
    }
    const email_div2 = $(element).parent().parent();
    console.log(data, "data")
    $.post(route,data)
    .then(function(response)
    {
        $(element).parent().remove(); 
        if(response.result)
        {
            alertify.notify(response.msg,'success');
            var id = response.data.id;
            var hou_id = response.data.head_office_user_id;
            var r = baseRoute.replace('REPLACE_ID', id).replace('REPLACE_HOU_ID', hou_id);
            $(email_div2).append('<label class="inputGroup popup fw-semibold w-50 mt-0" style="font-weight: normal !important;">Email : ' +'<input type="text" placeholder="Add email" style="margin-left:0.8rem; background:transparent; font-weight: normal !important;" ' +'value="'+response.data.contact+'" id="email_'+response.data.id+'" onfocusout="updateEmail('+response.data.id+',this)" type="text">' +'<div class="custom_overlay"><span class="custom_overlay_inner">' +'<a href="'+r+'" class="delete_button" data-msg="Are you sure you want to delete this contact?">' +'<svg width="20" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">' +'<path d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>' +'</a></span></div></label>');}

    })
    .catch(function(error){
        alertify.notify('Error occurred','error');
        console.log(error);
    })
}

function updatePhone(id, element,contact_id) {
            var value = $(element).val();
            var _token = $('#_token').val();
            var contact_id = contact_id != undefined ? contact_id : null;
            var data = {
                contact_id: contact_id,
                id: id,
                value: value,
                type: 0,
                _token: _token
            }
            var route = $('#update_email_route').val();
            console.log($(element).intlTelInput("isValidNumber"),data)
            if($(element).intlTelInput("isValidNumber") == false || $(element).intlTelInput("isValidNumber") == undefined){
                alertify.notify('Invalid Phone','error')
            }else{
                console.log('datadfaf')
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        alertify.notify(response.msg,'success');
                        window.location.reload();
                        var r = "{{route('head_office.delete_contact_user_settings',['id' => "+response.data.id+", 'hou_id' => "+response.data.head_office_user_id+"])}}"
                        // $("#phone_div").append(
                        //     '<label class="inputGroup popup">Phone : <input type="text" placeholder="Add phone" value="' +
                        //     response.data.contact + '" id="phone_' + response.data.id +
                        //     '" onfocusout="updatePhone(' + response.data.id +
                        //     ',this)" type="text"><div class="custom_overlay"><span class="custom_overlay_inner"><a href="' +
                        //     r +
                        //     '" class="delete_button" data-msg="Are you sure you want to delete this contact?"><svg width="20" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></a></span></div></label>'
                        // );
                    }
                })
                .catch(function(error) {
                    alertify.notify('Error Occured','error')
                    console.log(error);
                })

            }


}

    function add_area(id,element){
        const area_div = $(element).parent().siblings().closest('.area_div');

        $(area_div).append(`
    <label class="inputGroup popup fw-semibold w-50" style="font-weight: normal !important;">
        Area : 
        <input type="text" style="background:transparent; font-weight: normal !important;" 
        placeholder="Add Area" onfocusout="focusOnLevel()" id="area" value="" type="text">
    </label>
    <label class="inputGroup popup fw-semibold w-50" style="font-weight: normal !important;"> Level :<input type="text" id="level" style="background:transparent; font-weight: normal !important;" placeholder="Add Level" value="" onfocusout="updateArea(${id},this)" type="text"> </label>`);
}
    function updateArea(id,element,area_id)
    {
        var area = $(element).parent().parent().find('#area').val();
        var level = $(element).val();
        
        const area_div = $(element).parents().closest('.area_div').siblings().closest('.area_values');
        console.log(element,area_div)
        
        var route = $('#update_area_route').val();
        if(area)
        {
            var data = {
                id : id,
                area_id : area_id,
                area : area,
                level : level,
                _token : $('#_token').val()
            };
            $.post(route,data)
            .then(function(response){
                if(response.result)
                {
                    $(area_div).append('<label class="inputGroup w-75" style="font-weight: normal;" id="'+data.id+'">Area:<input style="width:40%;background:transparent; font-weight: normal;" type="text" placeholder="Add area" value="'+data.area+'"><input style="width:40%;background:transparent; font-weight: normal;" type="text" placeholder="Add Level" value="'+data.level+'"></label>');
                    console.log(response);
                    $(element).parent().parent().remove();
                }
            })
            .catch(function(error){
                console.log(error);
            })
        }
        else{
            $(element).parent().parent().find('#area').focus();
        }
    }
    function focusOnLevel()
    {
        $('#level').focus();
    }

    $(document).ready(function() {
    $('.allCases').change(function() {
        var checkboxes = $(this).closest('.chk-wrapper').find('.form-check-input');
        var user_select = $(this).closest('.wrapper').find('.select_assigned_users');
        var user_select_types = $(this).closest('.wrapper').find('.select_assigned_types');
        if ($(this).is(':checked')) {
            checkboxes.filter('#certainTypes, #assignedToAnotherUser, #ownCases')
              .prop('checked', false)
              .prop('disabled', true);
              user_select.fadeOut();
              user_select_types.fadeOut();
        }else{
            checkboxes.filter('#certainTypes, #assignedToAnotherUser, #ownCases')
              .prop('disabled', false);
        } 
    });


    $('.assignedToAnotherUser').change(function() {
        var checkboxes = $(this).closest('.wrapper').find('.form-check-input');
        var user_select = $(this).closest('.wrapper').find('.select_assigned_users');
        if ($(this).is(':checked')) {
            user_select.fadeIn();
            checkboxes.filter('#ownCases').prop('checked', true).prop('disabled',true);
        }else{
            user_select.fadeOut();
            checkboxes.filter('#ownCases').prop('disabled',false);
        }
    });
    $('.certainTypes').change(function() {
        var user_select_types = $(this).closest('.wrapper').find('.select_assigned_types');
        if ($(this).is(':checked')) {
            user_select_types.fadeIn();
        }else{
            user_select_types.fadeOut();
        }
    });

    table = new DataTable('#dataTable-case', {
                paging: false,
                info: false,
                language: {
                    search: ""
                },
                
            });
});

function selectAllLocations(element) {
        var allValues = [];

        $(element + ' option').each(function() {
            allValues.push($(this).val());
        });

        $(element).val(allValues).trigger('change');
    }
    function clearAllSelections(element) {
        $(element).val([]).trigger('change');
    }

    $('.all_loc_btn').on('change', function() {
        let el = '.location-select_'+$(this).data('id');
        console.log(el)
        if ($(this).is(':checked')) {
            selectAllLocations(el);
        } else {
            clearAllSelections(el);
        }
    })
    function showReassignTable(profileId) {
    const reassignTable = document.getElementById(`reassignTable${profileId}`);
    const reassignButton = document.getElementById(`reassignBtn${profileId}`);
    
    if (reassignTable && reassignButton) {
        reassignTable.style.display = 'block';
        reassignButton.style.display = 'none';
        $(`#reassignTable${profileId} select`).attr('required');

    } else {
        console.error("Reassign table or button not found for profile ID:", profileId);
    }
}

function HideReassignTable(profileId) {
    const reassignTable = document.getElementById(`reassignTable${profileId}`);
    const reassignButton = document.getElementById(`reassignBtn${profileId}`);
    
    if (reassignTable && reassignButton) {
        reassignTable.style.display = 'none';
        reassignButton.style.display = 'block';
        $(`#reassignTable${profileId} select`).removeAttr('required');
    } else {
        console.error("Reassign table or button not found for profile ID:", profileId);
    }
}
function selectAllUsers(profileId) {
    const checkboxes = document.querySelectorAll(`.userCheckbox${profileId}`);
    const selectAll = document.getElementById(`selectAll${profileId}`).checked;
    checkboxes.forEach((checkbox) => {
        checkbox.checked = selectAll;
    });
}

function validateReassignForm(profileId) {
    const selectedUsers = document.querySelectorAll(`.userCheckbox${profileId}:checked`);
    const newProfileSelect = document.getElementById(`new_profile_id_${profileId}`);

    if (selectedUsers.length === 0) {
        alert("Please select at least one user to reassign.");
        return false;
    }

    if (!newProfileSelect || !newProfileSelect.value) {
        alert("Please select a profile to reassign the users.");
        return false;
    }

    // Submit the form only if validations pass
    document.getElementById(`deleteForm${profileId}`).submit();
}


let selectedUsers = []; 

    // Handle checkbox changes
    $('.selected_users_checkbox').on('change', function () {
        const userId = $(this).val(); // Get the user ID from the checkbox value
        const isSuperUser = $(this).data('super_user').trim() == 1 ? true : false;

        if ($(this).is(':checked')) {
            selectedUsers.push(userId);

            // if (isSuperUser) {
            //     $('body').append(`
            //     <div class="alert alert-danger d-flex align-items-center justify-content-between mx-auto" role="alert" style="position:absolute;top:0;width:fit-content;height:60px;left:50%;transform:translateX(-50%);">
            //         <div>
            //             <strong>Super User Selected!</strong> You cannot batch reassign a super user.
            //         </div>
            //         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            //     </div>
            //     `);
            // }
        } else {
            selectedUsers = selectedUsers.filter(id => id !== userId);
        }

        $('.selected_users_main_input').val(selectedUsers);

        // Update animations based on selected users count
        if (selectedUsers.length > 0) {
            $('#draggable').addClass('anim').removeClass('reverse-anim');
        } else {
            $('#draggable').addClass('reverse-anim').removeClass('anim');
        }

        // Log the selected users and their count
        $('#count').text(selectedUsers.length);
        console.log('Selected Users:', selectedUsers);
        console.log('Number of Selected Users:', selectedUsers.length);
    });



const draggable = document.getElementById('draggable');

    var posX = 0,
        posY = 0,
        mouseX = 0,
        mouseY = 0;

    dragBtn.addEventListener('mousedown', mouseDown, false);
    window.addEventListener('mouseup', mouseUp, false);

    function mouseDown(e) {
        e.preventDefault();
        posX = e.clientX - draggable.offsetLeft;
        posY = e.clientY - draggable.offsetTop;
        window.addEventListener('mousemove', moveElement, false);
    }

    function mouseUp() {
        window.removeEventListener('mousemove', moveElement, false);
    }

    function moveElement(e) {
        mouseX = e.clientX - posX;
        mouseY = e.clientY - posY;

        const maxX = 1000 ;
        const maxY = window.innerHeight - draggable.offsetHeight;
        console.log(maxX)

        mouseX = Math.min(Math.max(mouseX, 0), maxX);
        mouseY = Math.min(Math.max(mouseY, 0), maxY);
        draggable.style.left = mouseX + 'px';
        draggable.style.top = mouseY + 'px';
    }

    $(document).ready(function () {
    $('select.select-modal').each(function () {
        let $select = $(this); // Cache the current select element
        let $modalContent = $select.closest('.modal-content'); // Find the closest modal-content

        $select.select2({
            dropdownParent: $modalContent // Use the specific modal-content as the parent
        });
    });
});





$('.delete_action').on('change', function () {
    let container = $(this).closest('.action-container'); // Traverse up to the parent container
    let value = $(this).val();
console.log(value)
    // Show/Hide Divs Based on Value
    if (value == "1") {
        container.find('.specific_user').show();
        container.find('.specific_profile').hide();
    } else if (value == "2") {
        container.find('.specific_user').hide();
        container.find('.specific_profile').show();
    }
});

$('.btn-submit-del').on('click', function () {
    const form = $(this).closest('form');
    form.submit();
})

</script>



@endsection
