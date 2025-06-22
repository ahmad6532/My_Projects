{{-- @extends('layouts.users_app')
@section('title', 'user Profile')
@section('content')

<div class="container-fluid">

    <div class="row justify-content-center ">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-header-gradient-primary card-header-height">
                </div>
                <div class="card-body text-center">
                    <img src="{{asset('admin_assets/img/profile-pic.png')}}" alt="profile picture"
                        class="img-fluid rounded-circle img-profile profile-picture-top" width="100">
                    <h2 class="h3 font-weight-bold text-info">{{ $user->name }}</h2>
                    <a class="text-info border-bottom-info font-weight-bold no-ul" href="#">Dashboard</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h3 class="h4 text-info font-weight-bold">My Information</h3>
                    <div class="text-gray-500 font-weight-bold">
                        <p>First Name: {{ $user->first_name }}</p>
                        <p>Surname: {{ $user->surname }}</p>
                        <p class="d-inline-block">Telephone No: {{ $user->mobile_no }}</p>
                        <p class="d-inline-block margin-email">Email Address: {{ $user->email }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h3 class="h4 text-info font-weight-bold">User Type</h3>
                    <div class="text-gray-500 font-weight-bold">
                        <p>My Position: {{ $user->position->name }}</p>
                        <p class="d-inline-block">Registered Body : {{optional($user->locationRegulatoryBody)->name}}
                        </p>
                        <p class="d-inline-block margin-email">Regd. No: {{ $user->registration_no }}</p>
                        <p>Role(s)</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h3 class="h4 text-info font-weight-bold">Password & Security</h3>
                    <div class="row text-dark font-weight-bold">
                        <div class="col-md-6 col-12">
                            <small class="text-gray-500 font-weight-bold">Last Changes Date</small>
                            <p>19 July 2022, 10:58am</p>
                            <button type="button" class="btn btn-info btn-md w-100" data-toggle="modal"
                                data-target="#change_password">Change Password</button>

                        </div>
                        <div class="col-md-6 col-12 ">
                            <small class="text-gray-500 font-weight-bold">Two Factor Authentication</small>
                            <p>19 July 2022, 10:58am</p>
                            <button class="btn btn-info btn-md w-100">Active</button>
                        </div>
                        @include('layouts.error')
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body ">
                    <h3 class="h4 text-info font-weight-bold">Overview</h3>
                    <div class="row  text-center">

                        <div class="col-sm-3">
                            <img src="{{asset('admin_assets/img/user.svg')}}" alt="icon" class="w-75">
                            <p class="p-width"><small class="text-gray-500 font-weight-bold">Shared Cases</small>
                            </p>
                            <p class="font-weight-bold align-text-top-margin">{{count($user->share_cases)}}</p>
                        </div>
                        <div class="col-sm-3">
                            <img src="{{asset('admin_assets/img/user.svg')}}" alt="icon" class="w-75">
                            <p class="p-width" style="white-space: nowrap"><small
                                    class="text-gray-500 font-weight-bold">Statement Requests</small>
                            </p>
                            <p class="font-weight-bold align-text-top-margin">
                                {{count($user->case_request_informations)}}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="change_password" tabindex="-1" role="dialog" aria-labelledby="change_password_ModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="change_password_ModalLabel">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route(" user.update_password")}}" method="post">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="old">Old Password</label>
                        <input type="password" id="old" name="old_password" placeholder="Old Password" minlength="8"
                            class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="new">New Password</label>
                        <input type="password" id="new" name="new_password" placeholder="New Password" minlength="8"
                            class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm">Confirm Password</label>
                        <input type="password" id="confirm" name="confirm_password" placeholder="Confirm Password"
                            minlength="8" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info" name="update">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@php
use App\Helpers\Helper;
$UserServiceMessage=Helper::ServiceMessage('User','web');
$UserServiceMessageLength=count($UserServiceMessage);
@endphp
<input type="hidden" id="service_message_length" data-mdb-toggle="modal" value="{{$UserServiceMessageLength}}"
    role="button">

@if($UserServiceMessageLength)
@foreach($UserServiceMessage as $key=> $service_message)
<div class="modal fade" id="ServiceMessageModalToggle{{$key}}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel1" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content  modal-fullscreen">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel1">{{$service_message->title}}</h5>
            </div>
            <div class="modal-body">
                {{$service_message->message}}
            </div>

            <div class="modal-footer">
                @if($UserServiceMessageLength==$key+1)
                <button class="btn btn-info" id="dismiss" data-dismiss="modal">
                    Dismiss
                </button>
                @endif
                @if($UserServiceMessageLength>1 && $UserServiceMessageLength!=$key+1)
                <button class="btn btn-info" onclick="nextModal({{$key + 1}})" data-dismiss="modal">
                    Next
                </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
@endsection

@section('scripts')
<script>
    function nextModal(i)
        {
            $('#ServiceMessageModalToggle' + i).modal({backdrop: 'static', keyboard: false},'show');
        }
        $(document).ready(function(){
            var sml=$("#service_message_length").val();
            if(sml>0){
                $('#ServiceMessageModalToggle0').modal({backdrop: 'static', keyboard: false},'show');
                setTimeout(function () {
                    $('#dismiss').attr('disabled',false);
                },5000);
                // $('#ServiceMessageModalToggle0').modal({
                //     backdrop: 'static'
                // });
//                 for(var i=0;i<sml;i++)
//                 {
//                     $('#ServiceMessageModalToggle'+i).modal({backdrop: 'static', keyboard: false}, 'show');
//                     for(var j=0;j<i;j++)
//                     {
//                         $('#ServiceMessageModalToggle'+j).modal('hide');
//                     }
//
//                     for(var k=sml;k>i;k--)
//                     {
//                         $('#ServiceMessageModalToggle'+k).modal('hide');
//                     }
// //not working
//                 }
            }
        });
</script>
@endsection --}}



@extends('layouts.users_app')
@section('styles')
<link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
@endsection

@section('content')
<div class="profile-center-area">

    @include('layouts.user.sub-header')
    <nav class='page-menu bordered'>
        <ul class="nav nav-tab main_header">
            <li><a data-bs-toggle="tab" data-bs-target="#my_info" class="active" href="#">My Info<span></span></a></li>
            <li><a data-bs-toggle="tab" data-bs-target="#password_and_security" href="#">Password &
                    Security<span></span></a></li>
        </ul>
    </nav>

    <div class="tab-content" id="myTabContent">
        <div id="my_info" class="relative tab-pane active show">
            <div class="profile-center-area">


                <!-- company page contents -->

                <div class="user-page-contents hide-placeholder-parent">
                    <div class="inputSection activeColor activeColor">Personal</div>
                    <label class="inputGroup">First Name:
                        <input type="text" placeholder="Add First Name" onfocusout="update_first_name(this)" value="{{$user->first_name}}" id="first_name"/>
                    </label>
                    {{-- <label class="inputGroup">Middle Name:
                        <input type="text" placeholder="Add Middle Name" />
                    </label> --}}
                    <label class="inputGroup">Surname:
                        <input type="text" placeholder="Add Surname" id="sur_name" onfocus="show_save_button(this)" value="{{$user->surname}}" />
                        <span style="cursor: pointer;display:none;"  onclick="update_sur_name(this)"><img  src="{{ asset('v2/images/icons/check.svg') }}" alt=""></span>
                    </label>
                    <div class="inputSection activeColor">Contact Info
                        {{-- <span style="float: right;cursor:pointer" id="dropdownMenuButton_x" data-bs-toggle="dropdown">
                            <svg width="15" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 5V19M5 12H19" stroke="#888" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </span> --}}
                        {{-- <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton_x" style="">
                            <a href="javascript:void(0)" class="dropdown-item" onclick="add_phone()">Add Phone</a>
                            <a href="javascript:void(0)" class="dropdown-item" onclick="add_email()">Add Email</a>
                        </div> --}}
                    </div>
                    <label class="inputGroup" >Email:
                        <input type="text" placeholder="Add an Email" @if(!$user->is_email_hidden) style="color:green;"@endif onfocusout="update_email(this)" value="{{$user->email}}" /> 
                        <div class="custom_overlay">
                            <span class="custom_overlay_inner">
                                &nbsp;
                                @if(!$user->is_email_hidden)
                                <a href="{{route('user.hide.email',['type' => 1,'is_sub_contact' => false])}}" title="hide this contact?" data-msg="Are you sure you want to hide this contact?" class="hide_email">
                                    <svg width="20" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.7429 5.09232C11.1494 5.03223 11.5686 5 12.0004 5C17.1054 5 20.4553 9.50484 21.5807 11.2868C21.7169 11.5025 21.785 11.6103 21.8231 11.7767C21.8518 11.9016 21.8517 12.0987 21.8231 12.2236C21.7849 12.3899 21.7164 12.4985 21.5792 12.7156C21.2793 13.1901 20.8222 13.8571 20.2165 14.5805M6.72432 6.71504C4.56225 8.1817 3.09445 10.2194 2.42111 11.2853C2.28428 11.5019 2.21587 11.6102 2.17774 11.7765C2.1491 11.9014 2.14909 12.0984 2.17771 12.2234C2.21583 12.3897 2.28393 12.4975 2.42013 12.7132C3.54554 14.4952 6.89541 19 12.0004 19C14.0588 19 15.8319 18.2676 17.2888 17.2766M3.00042 3L21.0004 21M9.8791 9.87868C9.3362 10.4216 9.00042 11.1716 9.00042 12C9.00042 13.6569 10.3436 15 12.0004 15C12.8288 15 13.5788 14.6642 14.1217 14.1213" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                                @else
                                <a href="{{route('user.hide.email',['type' => 0,'is_sub_contact' => false])}}" title="show this contact?" data-msg="Are you sure you want to show this contact?" class="hide_email">
                                    <svg width="20" height="16" viewBox="0 0 22 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1.42012 8.71318C1.28394 8.49754 1.21584 8.38972 1.17772 8.22342C1.14909 8.0985 1.14909 7.9015 1.17772 7.77658C1.21584 7.61028 1.28394 7.50246 1.42012 7.28682C2.54553 5.50484 5.8954 1 11.0004 1C16.1054 1 19.4553 5.50484 20.5807 7.28682C20.7169 7.50246 20.785 7.61028 20.8231 7.77658C20.8517 7.9015 20.8517 8.0985 20.8231 8.22342C20.785 8.38972 20.7169 8.49754 20.5807 8.71318C19.4553 10.4952 16.1054 15 11.0004 15C5.8954 15 2.54553 10.4952 1.42012 8.71318Z" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M11.0004 11C12.6573 11 14.0004 9.65685 14.0004 8C14.0004 6.34315 12.6573 5 11.0004 5C9.34355 5 8.0004 6.34315 8.0004 8C8.0004 9.65685 9.34355 11 11.0004 11Z" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg> 
                                </a>                                   
                                @endif
                            </span>
                        </div>
                    </label>
                    {{-- <div id="email_div">
                        @foreach ($user->contacts->where('type',1) as $k => $contact)
                        <label class="inputGroup popup" >Email :
                            <input @if($user->is_email_hidden && $contact->is_contact_show) style="color:green;" @endif type="text"  placeholder="Add email" id="email_{{$contact->id}}" value="{{$contact->contact}}" type="text"
                                onfocusout="updateContact({{$contact->id}},this,1)">
                                &nbsp;
                            <div class="custom_overlay">
                                <span class="custom_overlay_inner">
                                    &nbsp;
                                    @if(!$contact->is_contact_show)
                                    <a href="{{route('user.delete.contact',$contact->id)}}" class="delete_button"
                                        data-msg="Are you sure you want to delete this contact?">
                                        <svg width="20" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5"
                                                stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </a>
                                    @endif
                                    @if($user->is_email_hidden && !$contact->is_contact_show)
                                    <a href="{{route('user.hide.email',['id'=> $contact->id,'type' => 0,'is_sub_contact' => true])}}"
                                        title="show this contact?" data-msg="Are you sure you want to show this contact?"
                                        class="hide_email">
                                        <svg width="20" height="16" viewBox="0 0 22 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M1.42012 8.71318C1.28394 8.49754 1.21584 8.38972 1.17772 8.22342C1.14909 8.0985 1.14909 7.9015 1.17772 7.77658C1.21584 7.61028 1.28394 7.50246 1.42012 7.28682C2.54553 5.50484 5.8954 1 11.0004 1C16.1054 1 19.4553 5.50484 20.5807 7.28682C20.7169 7.50246 20.785 7.61028 20.8231 7.77658C20.8517 7.9015 20.8517 8.0985 20.8231 8.22342C20.785 8.38972 20.7169 8.49754 20.5807 8.71318C19.4553 10.4952 16.1054 15 11.0004 15C5.8954 15 2.54553 10.4952 1.42012 8.71318Z"
                                                stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path
                                                d="M11.0004 11C12.6573 11 14.0004 9.65685 14.0004 8C14.0004 6.34315 12.6573 5 11.0004 5C9.34355 5 8.0004 6.34315 8.0004 8C8.0004 9.65685 9.34355 11 11.0004 11Z"
                                                stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                    @endif
                                </span>
                            </div>
                        </label>
                        @endforeach
                    </div> --}}
                    <label class="inputGroup">Phone:
                        <input @if(!$user->is_phone_hidden) style="color:green;" @endif  type="text" placeholder="Add a phone number" onfocusout="update_phone(this)" value="{{$user->mobile_no}}" />
                        <div class="custom_overlay">
                            <span class="custom_overlay_inner">
                                &nbsp;
                                @if(!$user->is_phone_hidden)
                                <a href="{{route('user.hide.phone',['type' => 1,'is_sub_contact' => false])}}" title="hide this contact?" data-msg="Are you sure you want to hide this contact?" class="hide_email">
                                    <svg width="20" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.7429 5.09232C11.1494 5.03223 11.5686 5 12.0004 5C17.1054 5 20.4553 9.50484 21.5807 11.2868C21.7169 11.5025 21.785 11.6103 21.8231 11.7767C21.8518 11.9016 21.8517 12.0987 21.8231 12.2236C21.7849 12.3899 21.7164 12.4985 21.5792 12.7156C21.2793 13.1901 20.8222 13.8571 20.2165 14.5805M6.72432 6.71504C4.56225 8.1817 3.09445 10.2194 2.42111 11.2853C2.28428 11.5019 2.21587 11.6102 2.17774 11.7765C2.1491 11.9014 2.14909 12.0984 2.17771 12.2234C2.21583 12.3897 2.28393 12.4975 2.42013 12.7132C3.54554 14.4952 6.89541 19 12.0004 19C14.0588 19 15.8319 18.2676 17.2888 17.2766M3.00042 3L21.0004 21M9.8791 9.87868C9.3362 10.4216 9.00042 11.1716 9.00042 12C9.00042 13.6569 10.3436 15 12.0004 15C12.8288 15 13.5788 14.6642 14.1217 14.1213" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                                @else
                                <a href="{{route('user.hide.phone',['type' => 0,'is_sub_contact' => false])}}" title="show this contact?" data-msg="Are you sure you want to show this contact?" class="hide_email">
                                    <svg width="20" height="16" viewBox="0 0 22 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1.42012 8.71318C1.28394 8.49754 1.21584 8.38972 1.17772 8.22342C1.14909 8.0985 1.14909 7.9015 1.17772 7.77658C1.21584 7.61028 1.28394 7.50246 1.42012 7.28682C2.54553 5.50484 5.8954 1 11.0004 1C16.1054 1 19.4553 5.50484 20.5807 7.28682C20.7169 7.50246 20.785 7.61028 20.8231 7.77658C20.8517 7.9015 20.8517 8.0985 20.8231 8.22342C20.785 8.38972 20.7169 8.49754 20.5807 8.71318C19.4553 10.4952 16.1054 15 11.0004 15C5.8954 15 2.54553 10.4952 1.42012 8.71318Z" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M11.0004 11C12.6573 11 14.0004 9.65685 14.0004 8C14.0004 6.34315 12.6573 5 11.0004 5C9.34355 5 8.0004 6.34315 8.0004 8C8.0004 9.65685 9.34355 11 11.0004 11Z" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg> 
                                </a>                                   
                                @endif
                            </span>
                        </div>
                    </label>
                    
                    {{-- <div id="phone_div">
                        @foreach ($user->contacts->where('type',0) as $key => $contact)
                        <label class="inputGroup popup">Phone :
                            <input @if($user->is_phone_hidden && $contact->is_contact_show) style="color:green;" @endif type="number" placeholder="Add a phone number" value="{{$contact->contact}}"
                                type="number" onfocusout="updateContact({{$contact->id}},this,0)">
                                &nbsp;
                                <div class="custom_overlay">
                                    <span class="custom_overlay_inner">
                                        &nbsp;
                                        @if(!$contact->is_contact_show)
                                        <a href="{{route('user.delete.contact',$contact->id)}}" class="delete_button"
                                            data-msg="Are you sure you want to delete this contact?">
                                            <svg width="20" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5"
                                                    stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </a>
                                        @endif
                                        @if($user->is_phone_hidden && !$contact->is_contact_show)
                                        <a href="{{route('user.hide.phone',['id'=> $contact->id,'type' => 0,'is_sub_contact' => true])}}"
                                            title="show this contact?" data-msg="Are you sure you want to show this contact?"
                                            class="hide_email">
                                            <svg width="20" height="16" viewBox="0 0 22 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M1.42012 8.71318C1.28394 8.49754 1.21584 8.38972 1.17772 8.22342C1.14909 8.0985 1.14909 7.9015 1.17772 7.77658C1.21584 7.61028 1.28394 7.50246 1.42012 7.28682C2.54553 5.50484 5.8954 1 11.0004 1C16.1054 1 19.4553 5.50484 20.5807 7.28682C20.7169 7.50246 20.785 7.61028 20.8231 7.77658C20.8517 7.9015 20.8517 8.0985 20.8231 8.22342C20.785 8.38972 20.7169 8.49754 20.5807 8.71318C19.4553 10.4952 16.1054 15 11.0004 15C5.8954 15 2.54553 10.4952 1.42012 8.71318Z"
                                                    stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M11.0004 11C12.6573 11 14.0004 9.65685 14.0004 8C14.0004 6.34315 12.6573 5 11.0004 5C9.34355 5 8.0004 6.34315 8.0004 8C8.0004 9.65685 9.34355 11 11.0004 11Z"
                                                    stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                        @endif
                                    </span>
                                </div>
                        </label>
                        @endforeach

                    </div> --}}
                    <div class="inputSection activeColor activeColor">Account</div>
                    <label class="inputGroup"><strong>My Position:</strong>
                        <input type="" value="{{optional($user->postiton)->name}}" placeholder="No position assigned" />
                    </label>
                    <label class="inputGroup"><strong>Registered Body:</strong>
                        <input type="" value="{{optional($user->locationRegulatoryBody)->name}}"
                            placeholder="No registered body assigned" />
                    </label>
                    <label class="inputGroup"><strong>Regd. No:</strong>
                        <input type="" value="{{$user->registration_no}}"
                            placeholder="No registration number assigned" />
                    </label>
                    <label class="inputGroup"><strong>Role(s):</strong>
                        <input type="" value="" placeholder="No role assigned" />
                    </label>
                </div>
            </div>
        </div>

        <div class="tab-pane" id="password_and_security">

        </div>
    </div>
</div>
<input type="hidden" value="{{route('user.update.first_name')}}" id="update_first_name_route">
<input type="hidden" value="{{route('user.update.sur_name')}}" id="update_sur_name_route">
<input type="hidden" value="{{route('user.update.email')}}" id="update_email_route">
<input type="hidden" value="{{route('user.update.phone')}}" id="update_phone_route">
<input type="hidden" value="{{route('user.create.contact')}}" id="update_contact">
<input type="hidden" value="{{csrf_token()}}" id="_token">
<!-- profile page contents -->



@section('scripts')
<script src="{{asset('js/alertify.min.js')}}"></script>

<script>
    function show_save_button(ele)
    {
        $(ele).parent().find('span').show();
    }
    $(document).ready(function (){
        loadActiveTab();
    });
    function loadActiveTab(tab = null){
        if(tab == null){
            tab = window.location.hash;
        } 
        console.log($('.nav-tab li > a[data-bs-target="' + tab + '"]'));
        
        $('.nav-tab li > a[data-bs-target="' + tab + '"]').tab('show');
    }
    $(document).on( "click", ".delete_extension", function(e) {
        e.preventDefault();
        let href= $(this).attr('href');
        
        let msg = $(this).data('msg');
        alertify.confirm("Are you sure?", msg,
        function(){
            window.location.href= href;
        },function(i){
            console.log(i);
        });
    });
    function update_first_name(element)
    {
        $("#pinder").show();
        var first_name = $(element).val();
        $('#sur_name').focus();
        
        
       $("#pinder").hide();
        // var update_first_name_route = $('#update_first_name_route').val();
        // var _token = $('#_token').val();
        // var data = {
        //     first_name : first_name,
        //     _token : _token
        // };
        // $.post(update_first_name_route,data)
        // .then(function(response){
        //     if(response.result)
        //     {
        //         alertify.confirm( response.msg)
        //     }
        //     $("#pinder").hide();
        // })
        // .catch(function(error){
        //     alertify.confirm( error.responseJSON.message);
        //     $("#pinder").hide();
        // })
    }
    function update_sur_name(element)
    {
        $("#pinder").show();
        var sur_name = $('#sur_name').val();
        var first_name = $("#first_name").val();
        var update_sur_name_route = $('#update_sur_name_route').val();
        var _token = $('#_token').val();
        var data = {
            first_name : first_name,
            sur_name : sur_name,
            _token : _token
        };
        $.post(update_sur_name_route,data)
        .then(function(response){
            if(response.result)
            {
                console.log($(element).css('display','none'));;
                alertify.confirm( response.msg);
            }
            $("#pinder").hide();
        })
        .catch(function(error){
            
            $(element).hide();
            $("#pinder").hide();
            alertify.confirm( error.responseJSON.message);
        })
    }
    function update_email(element)
    {
        $("#pinder").show();
        var email = $(element).val();
        var update_email_route = $('#update_email_route').val();
        var _token = $('#_token').val();
        var data = {
            email : email,
            _token : _token
        };
        $.post(update_email_route,data)
        .then(function(response){
            if(response.result)
            {
                alertify.confirm( response.msg)
            }
            $("#pinder").hide();
        })
        .catch(function(error){
            alertify.confirm( error.responseJSON.message);
            $("#pinder").hide();
        })
    }
    function update_phone(element)
    {
        $("#pinder").show();
        var phone = $(element).val();
        var update_phone_route = $('#update_phone_route').val();
        var _token = $('#_token').val();
        var data = {
            phone : phone,
            _token : _token
        };
        $.post(update_phone_route,data)
        .then(function(response){
            alertify.confirm( response.msg)
            $("#pinder").hide();
        })
        .catch(function(error){
            alertify.confirm( error.responseJSON.message);
            $("#pinder").hide();
        })
    }
    function add_email(){
        $("#email_div").append('<label class="inputGroup popup">Email : <input type="text" placeholder="Add email" value="" onfocusout="updateContact(0,this,1)" type="text"></label>');
        $("#email_div").find('label').last().find('input').focus();
    }
    function add_phone(){
        $("#phone_div").append('<label class="inputGroup popup">Phone : <input type="text" placeholder="Add Phone" value="" onfocusout="updateContact(0,this,0)" type="text"></label>');
        $("#phone_div").find('label').last().find('input').focus();
    }
    function updateContact(id,element,type)
    {
        $("#pinder").show();
        var value = $(element).val();
        if(!$.trim(value).length)
        {
            $("#pinder").hide();
            return;
        } 
        var route = $('#update_contact').val();
        var _token = $('#_token').val();
        data = {
            id : id,
            value : value,
            _token : _token,
            type : type
        }
        
        $.post(route,data)
        .then(function(response){
            $(element).parent().remove(); 
            if(response.result)
            {
                if(parseInt(response.data.type))
                {
                    var r = "{{route('user.delete.contact',"+response.data.id+")}}";
                    
                    $("#email_div").append('<label class="inputGroup popup">Email : <input type="text" placeholder="Add email" value="'+response.data.contact+'" id="email_'+response.data.id+'" onfocusout="updateContact('+response.data.id+',this,1)" type="text"><div class="custom_overlay"><span class="custom_overlay_inner"><a href="'+r+'" class="delete_button" data-msg="Are you sure you want to delete this contact?"><svg width="20" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></a></span></div></label>');
                }
                else
                {
                    var show_icon;
                    var r = "{{route('user.delete.contact',"+response.data.id+")}}";
                    if(response.is_phone_hidden)
                    {
                        var id = response.data.id;
                        show_icon_r = "{{route('user.hide.phone',['id' =>"+id+",'type' => 0,'is_sub_contact' => true])}}";
                        show_icon = ' <a href="'+show_icon_r+'" title="show this contact?" data-msg="Are you sure you want to show this contact?" class="hide_email"><svg width="20" height="16" viewBox="0 0 22 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.42012 8.71318C1.28394 8.49754 1.21584 8.38972 1.17772 8.22342C1.14909 8.0985 1.14909 7.9015 1.17772 7.77658C1.21584 7.61028 1.28394 7.50246 1.42012 7.28682C2.54553 5.50484 5.8954 1 11.0004 1C16.1054 1 19.4553 5.50484 20.5807 7.28682C20.7169 7.50246 20.785 7.61028 20.8231 7.77658C20.8517 7.9015 20.8517 8.0985 20.8231 8.22342C20.785 8.38972 20.7169 8.49754 20.5807 8.71318C19.4553 10.4952 16.1054 15 11.0004 15C5.8954 15 2.54553 10.4952 1.42012 8.71318Z" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M11.0004 11C12.6573 11 14.0004 9.65685 14.0004 8C14.0004 6.34315 12.6573 5 11.0004 5C9.34355 5 8.0004 6.34315 8.0004 8C8.0004 9.65685 9.34355 11 11.0004 11Z" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></a>'
                        $("#phone_div").append('<label class="inputGroup popup">Phone : <input type="text" placeholder="Add phone" value="'+response.data.contact+'" id="phone_'+response.data.id+'" onfocusout="updateContact('+response.data.id+',this,0)" type="text"><div class="custom_overlay"><span class="custom_overlay_inner"><a href="'+r+'" class="delete_button" data-msg="Are you sure you want to delete this contact?"><svg width="20" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></a>'+show_icon+'</span></div></label>');
                        $("#pinder").hide();
                        return;
                    }
                    $("#phone_div").append('<label class="inputGroup popup">Phone : <input type="text" placeholder="Add phone" value="'+response.data.contact+'" id="phone_'+response.data.id+'" onfocusout="updateContact('+response.data.id+',this,0)" type="text"><div class="custom_overlay"><span class="custom_overlay_inner"><a href="'+r+'" class="delete_button" data-msg="Are you sure you want to delete this contact?"><svg width="20" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></a></span></div></label>');
                }
            }
            $("#pinder").hide();
        })
        .catch(function(error){
            console.log(error);
            
            $("#pinder").hide();
        })
    }
    $(document).on( "click", ".hide_email", function(e) {
        e.preventDefault();
        let href= $(this).attr('href');
        let msg = $(this).data('msg');
        alertify.confirm("Are you sure?", msg,
        function(){
            window.location.href= href;
        },function(i){
        });
    });
    $(document).on( "click", ".hide_email", function(e) {
        e.preventDefault();
        let href= $(this).attr('href');
        let msg = $(this).data('msg');
        alertify.confirm("Are you sure?", msg,
        function(){
            window.location.href= href;
        },function(i){
        });
    });
    $(document).on( "click", ".hide_phone", function(e) {
        e.preventDefault();
        let href= $(this).attr('href');
        let msg = $(this).data('msg');
        alertify.confirm("Are you sure?", msg,
        function(){
            window.location.href= href;
        },function(i){
        });
    });
</script>

@endsection
@endsection



@section('sidebar')
@include('layouts.user.sidebar-header')
@endsection