@extends('layouts.users_app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/alertify.min.css') }}">
    <link href="{{ asset('admin_assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .main-wrapper {
            max-width: 650px;
            width: 100%;
            margin-left: 32%;
            display: flex;
            flex-direction: column;
            margin-top: 1rem;
        }

        .main-wrapper p {
            margin: 0;
        }

        .sub-title {
            color: #34BFAF;
            font-size: 14px;
        }

        .row-wrapper {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            padding-inline: 0.5rem;
            border-bottom: 1px solid #e3e2e2;
            padding-bottom: 0.5rem;
            font-size: 14px;
        }

        .icon-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .ico-circle {
            height: 20px;
            width: 20px;
            background: #4fb53d;
            border-radius: 50%;
            display: flex;
            color: white;
            padding: 0px;
            justify-content: center;
            align-items: center;
        }

        .ico-circle i {
            font-size: 12px;
        }

        .row-btn {
            border: 1px solid #e2e3e5;
            background: rgba(242, 246, 247, 255);
            font-size: 12px;
            border-radius: 4px;
            padding: 0.475rem 0.95rem;
        }

        .icon {
            width: 22px;
            height: 22px;
        }
#save_first_name {
    background-color: #d3d3d3;
    color: #333; 
    padding: 2px 4px;
    border: 1px solid #ccc;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
}

#save_sur_name {
    background-color: #d3d3d3;
    color: #333;
    padding: 2px 4px;
    border: 1px solid #ccc;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
}

#submit_form {
    background-color: #d3d3d3;
    color: #333; 
    padding: 8px 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

    </style>
@endsection

@section('content')
    <div class="profile-center-area">
        <div class="text-center">
            <img class="circle-img" src="{{ $user->logo }}" />
            <div class="content-page-heading">
                {{$user->name}}
            </div>
        </div>
        {{-- @include('layouts.user.sub-header') --}}
        <nav class='page-menu bordered'>
            <ul class="nav nav-tab main_header">
                <li><a data-bs-toggle="tab" data-bs-target="#my_info" class="active" href="#">My Info<span></span></a>
                </li>
                <li><a data-bs-toggle="tab" data-bs-target="#password_and_security" href="#">Password & Security<span></span></a></li>
            </ul>
        </nav>

        <hr class="hrBeneathMenu">

        <div class="tab-content" id="myTabContent">
            <div id="my_info" class="relative tab-pane active show">
                <div class="profile-center-area">


                    <!-- company page contents -->

                    <div class="user-page-contents hide-placeholder-parent">
                        <div class="inputSection activeColor activeColor">Personal</div>
                        <form method="POST" action="{{ route('user.update.sur_name') }}">
                            @csrf
                            <label class="inputGroup">First Name:
                                <input type="text" placeholder="Add First Name" onfocus="show_save_button(this)"
                                       value="{{ $user->first_name }}" id="first_name" name="first_name" />
                            </label>
                        
                            <button id="save_first_name" style="display:none;">Save First Name</button>
                        
                            <label class="inputGroup">Surname:
                                <input type="text" placeholder="Add Surname" id="sur_name" name="surname" onfocus="show_save_button(this)"
                                       value="{{ $user->surname }}" />
                            </label>
                            <button id="save_sur_name" style="display:none;">Save Surname</button>
                        
                            <button type="submit" style="display:none;" id="submit_form">Save Changes</button>
                        </form>

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
                        <label class="inputGroup">Email:
                            <input type="text" placeholder="Add an Email"
                                @if (!$user->is_email_hidden) style="color:green;" @endif
                                onfocusout="update_email(this)" value="{{ $user->email }}" />
                            {{-- <div class="custom_overlay">
                                <span class="custom_overlay_inner">
                                    &nbsp;
                                    @if (!$user->is_email_hidden)
                                        <a href="{{ route('user.hide.email', ['type' => 1, 'is_sub_contact' => false]) }}"
                                            title="hide this contact?"
                                            data-msg="Are you sure you want to hide this contact?" class="hide_email">
                                            <svg width="20" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M10.7429 5.09232C11.1494 5.03223 11.5686 5 12.0004 5C17.1054 5 20.4553 9.50484 21.5807 11.2868C21.7169 11.5025 21.785 11.6103 21.8231 11.7767C21.8518 11.9016 21.8517 12.0987 21.8231 12.2236C21.7849 12.3899 21.7164 12.4985 21.5792 12.7156C21.2793 13.1901 20.8222 13.8571 20.2165 14.5805M6.72432 6.71504C4.56225 8.1817 3.09445 10.2194 2.42111 11.2853C2.28428 11.5019 2.21587 11.6102 2.17774 11.7765C2.1491 11.9014 2.14909 12.0984 2.17771 12.2234C2.21583 12.3897 2.28393 12.4975 2.42013 12.7132C3.54554 14.4952 6.89541 19 12.0004 19C14.0588 19 15.8319 18.2676 17.2888 17.2766M3.00042 3L21.0004 21M9.8791 9.87868C9.3362 10.4216 9.00042 11.1716 9.00042 12C9.00042 13.6569 10.3436 15 12.0004 15C12.8288 15 13.5788 14.6642 14.1217 14.1213"
                                                    stroke="#888" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    @else
                                        <a href="{{ route('user.hide.email', ['type' => 0, 'is_sub_contact' => false]) }}"
                                            title="show this contact?"
                                            data-msg="Are you sure you want to show this contact?" class="hide_email">
                                            <svg width="20" height="16" viewBox="0 0 22 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M1.42012 8.71318C1.28394 8.49754 1.21584 8.38972 1.17772 8.22342C1.14909 8.0985 1.14909 7.9015 1.17772 7.77658C1.21584 7.61028 1.28394 7.50246 1.42012 7.28682C2.54553 5.50484 5.8954 1 11.0004 1C16.1054 1 19.4553 5.50484 20.5807 7.28682C20.7169 7.50246 20.785 7.61028 20.8231 7.77658C20.8517 7.9015 20.8517 8.0985 20.8231 8.22342C20.785 8.38972 20.7169 8.49754 20.5807 8.71318C19.4553 10.4952 16.1054 15 11.0004 15C5.8954 15 2.54553 10.4952 1.42012 8.71318Z"
                                                    stroke="#888" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M11.0004 11C12.6573 11 14.0004 9.65685 14.0004 8C14.0004 6.34315 12.6573 5 11.0004 5C9.34355 5 8.0004 6.34315 8.0004 8C8.0004 9.65685 9.34355 11 11.0004 11Z"
                                                    stroke="#888" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    @endif
                                </span>
                            </div> --}}
                        </label>

                        <label class="inputGroup">Phone:
                            <input id="telephone" @if (!$user->is_phone_hidden) style="color:green;" @endif
                                type="text" placeholder="Add a phone number" onfocusout="update_phone(this)"
                                value="{{ $user->mobile_no }}" />
                            {{-- <div class="custom_overlay">
                                <span class="custom_overlay_inner">
                                    &nbsp;
                                    @if (!$user->is_phone_hidden)
                                        <a href="{{ route('user.hide.phone', ['type' => 1, 'is_sub_contact' => false]) }}"
                                            title="hide this contact?"
                                            data-msg="Are you sure you want to hide this contact?" class="hide_email">
                                            <svg width="20" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M10.7429 5.09232C11.1494 5.03223 11.5686 5 12.0004 5C17.1054 5 20.4553 9.50484 21.5807 11.2868C21.7169 11.5025 21.785 11.6103 21.8231 11.7767C21.8518 11.9016 21.8517 12.0987 21.8231 12.2236C21.7849 12.3899 21.7164 12.4985 21.5792 12.7156C21.2793 13.1901 20.8222 13.8571 20.2165 14.5805M6.72432 6.71504C4.56225 8.1817 3.09445 10.2194 2.42111 11.2853C2.28428 11.5019 2.21587 11.6102 2.17774 11.7765C2.1491 11.9014 2.14909 12.0984 2.17771 12.2234C2.21583 12.3897 2.28393 12.4975 2.42013 12.7132C3.54554 14.4952 6.89541 19 12.0004 19C14.0588 19 15.8319 18.2676 17.2888 17.2766M3.00042 3L21.0004 21M9.8791 9.87868C9.3362 10.4216 9.00042 11.1716 9.00042 12C9.00042 13.6569 10.3436 15 12.0004 15C12.8288 15 13.5788 14.6642 14.1217 14.1213"
                                                    stroke="#888" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    @else
                                        <a href="{{ route('user.hide.phone', ['type' => 0, 'is_sub_contact' => false]) }}"
                                            title="show this contact?"
                                            data-msg="Are you sure you want to show this contact?" class="hide_email">
                                            <svg width="20" height="16" viewBox="0 0 22 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M1.42012 8.71318C1.28394 8.49754 1.21584 8.38972 1.17772 8.22342C1.14909 8.0985 1.14909 7.9015 1.17772 7.77658C1.21584 7.61028 1.28394 7.50246 1.42012 7.28682C2.54553 5.50484 5.8954 1 11.0004 1C16.1054 1 19.4553 5.50484 20.5807 7.28682C20.7169 7.50246 20.785 7.61028 20.8231 7.77658C20.8517 7.9015 20.8517 8.0985 20.8231 8.22342C20.785 8.38972 20.7169 8.49754 20.5807 8.71318C19.4553 10.4952 16.1054 15 11.0004 15C5.8954 15 2.54553 10.4952 1.42012 8.71318Z"
                                                    stroke="#888" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M11.0004 11C12.6573 11 14.0004 9.65685 14.0004 8C14.0004 6.34315 12.6573 5 11.0004 5C9.34355 5 8.0004 6.34315 8.0004 8C8.0004 9.65685 9.34355 11 11.0004 11Z"
                                                    stroke="#888" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    @endif
                                </span>
                            </div> --}}
                        </label>


                        <div class="inputSection activeColor activeColor">Account</div>
                        <label class="inputGroup"><strong>Job title:</strong>
                            <input type="text" onfocusout="updatePosition({{ $user->id }},this)"
                                placeholder="Add a position" value="{{ $user->selected_head_office_position }}">
                        </label>
                        <label class="inputGroup"><strong>Registered Body:</strong>
                            <input type="" value="{{ optional($user->locationRegulatoryBody)->name }}"
                                placeholder="No registered body assigned" />
                        </label>
                        <label class="inputGroup"><strong>Regd. No:</strong>
                            <input type="" value="{{ $user->registration_no }}"
                                placeholder="No registration number assigned" />
                        </label>
                        <label class="inputGroup"><strong>Role(s):</strong>
                            <input type="" value="" placeholder="No role assigned" />
                        </label>
                    </div>
                </div>
            </div>

            <div class="tab-pane " id="password_and_security">
                <div class="main-wrapper">
                    <h6 class="sub-title">Security</h6>
                    <div class="row-wrapper">
                        <div class="icon-wrapper">
                            <img class="icon" src="{{ asset('images/shield-zap.svg') }}" alt="icon">
                            <p>2-Step Verification</p>
                        </div>
                        <div class="icon-wrapper">
                            <span class="ico-circle"
                                style="background-color:{{ $otp->isEnabled ? '#4fb53d' : '#c73232' }};"><i
                                    class="fa-solid fa-{{ $otp->isEnabled ? 'check' : 'xmark' }}"></i></span>
                            <p>{{ $otp->isEnabled ? 'On' : 'Off' }} since {{ $otp->updated_at->format('d M Y') }}</p>
                        </div>
                        <a class="row-btn" href="{{ route('otp.security') }}"
                            style="place-self:center;color:black;">{{ $otp->isEnabled ? 'Disable' : 'Enable' }}</a>
                    </div>
                    <div class="row-wrapper mt-3">
                        <div class="icon-wrapper">
                            <img class="icon" src="{{ asset('images/key-01.svg') }}" alt="icon">
                            <p>Password</p>
                        </div>
                        <div class="icon-wrapper ">
                            <span class="ico-circle"><i class="fa-solid fa-check"></i></span>
                            <p>Last changed on
                                {{ isset($user->password_updated_at) ? $user->password_updated_at->format('d M Y') : 'unknown' }}
                            </p>
                        </div>
                        <button class="row-btn" style="place-self:center;color:black;" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">Change</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('user.change_password') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Password</h5>
                        <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"></span>
                        </button>

                    </div>
                    <div class="modal-body">
                        <label style="margin: 0;font-size: 12px;" for="current-password">Current Password</label>
                        <input type="password" id="current-password" placeholder="Current Password" class="form-control"
                            style="height:50px" required name="current_password">
                        <label style="margin: 0;font-size: 12px;" for="new-password">New Password</label>
                        <input type="password" id="new-password" placeholder="New Password" class="form-control"
                            style="height:50px" required name="new_password">
                        <label style="margin: 0;font-size: 12px;" for="confirm-password">Confirm Password</label>
                        <input type="password" id="confirm-password" placeholder="Confirm Password" class="form-control"
                            style="height:50px" required>

                        <p class="text-danger" id="warning"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="change-password-btn">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <input type="hidden" value="{{ route('user.update.first_name') }}" id="update_first_name_route">
    <input type="hidden" value="{{ route('user.update.sur_name') }}" id="update_sur_name_route">
    <input type="hidden" value="{{ route('user.update.email') }}" id="update_email_route">
    <input type="hidden" value="{{ route('user.update.phone') }}" id="update_phone_route">
    <input type="hidden" value="{{ route('user.create.contact') }}" id="update_contact">
    <input type="hidden" value="{{ route('head_office.update_position') }}" id="update_position_route">
    <input type="hidden" value="{{ csrf_token() }}" id="_token">
    <!-- profile page contents -->



@section('scripts')
<script src="{{ asset('admin_assets/js/intlTelInput-jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/alertify.min.js') }}"></script>

    <script>
        function show_save_button(ele) {
            $(ele).parent().find('span').show();
        }
        $(document).ready(function() {
            loadActiveTab();
        });

        function loadActiveTab(tab = null) {
            if (tab == null) {
                tab = window.location.hash;
            }
            console.log($('.nav-tab li > a[data-bs-target="' + tab + '"]'));

            $('.nav-tab li > a[data-bs-target="' + tab + '"]').tab('show');
        }
        $(document).on("click", ".delete_extension", function(e) {
            e.preventDefault();
            let href = $(this).attr('href');

            let msg = $(this).data('msg');
            alertify.confirm("Are you sure?", msg,
                function() {
                    window.location.href = href;
                },
                function(i) {
                    console.log(i);
                });
        });

        function update_first_name(element) {
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

        function update_sur_name(element) {
            $("#pinder").show();
            var sur_name = $('#sur_name').val();
            var first_name = $("#first_name").val();
            var update_sur_name_route = $('#update_sur_name_route').val();
            var _token = $('#_token').val();
            var data = {
                first_name: first_name,
                sur_name: sur_name,
                _token: _token
            };
            $.post(update_sur_name_route, data)
                .then(function(response) {
                    if (response.result) {
                        console.log($(element).css('display', 'none'));;
                        alertify.defaults.glossary.title = 'Success';
                        alertify.confirm(response.msg);
                    }
                    $("#pinder").hide();
                })
                .catch(function(error) {

                    $(element).hide();
                    $("#pinder").hide();
                    alertify.defaults.glossary.title = 'Error!';
                    alertify.confirm(error.responseJSON.message);
                })
        }

        function update_email(element) {
            $("#pinder").show();
            var email = $(element).val();
            var update_email_route = $('#update_email_route').val();
            var _token = $('#_token').val();
            var data = {
                email: email,
                _token: _token
            };
            $.post(update_email_route, data)
                .then(function(response) {
                    if (response.result) {
                        alertify.defaults.glossary.title = 'Success';
                        alertify.confirm(response.msg)
                    }
                    $("#pinder").hide();
                })
                .catch(function(error) {
                    alertify.defaults.glossary.title = 'Error!';
                    alertify.confirm(error.responseJSON.message);
                    $("#pinder").hide();
                })
        }

        function update_phone(element) {
            $("#pinder").show();
            const code  = $("#telephone").intlTelInput("getSelectedCountryData").dialCode
            var phone = "+"+code + $(element).val();
            var update_phone_route = $('#update_phone_route').val();
            var _token = $('#_token').val();
            var data = {
                phone: phone,
                _token: _token
            };
            $.post(update_phone_route, data)
                .then(function(response) {
                    alertify.defaults.glossary.title = 'Success';
                    alertify.confirm(response.msg)
                    $("#pinder").hide();
                })
                .catch(function(error) {
                    alertify.defaults.glossary.title = 'Error';
                    alertify.confirm(error.responseJSON.message);
                    $("#pinder").hide();
                })
        }

        function add_email() {
            $("#email_div").append(
                '<label class="inputGroup popup">Email : <input type="text" placeholder="Add email" value="" onfocusout="updateContact(0,this,1)" type="text"></label>'
            );
            $("#email_div").find('label').last().find('input').focus();
        }

        function add_phone() {
            $("#phone_div").append(
                '<label class="inputGroup popup">Phone : <input type="text" placeholder="Add Phone" value="" onfocusout="updateContact(0,this,0)" type="text"></label>'
            );
            $("#phone_div").find('label').last().find('input').focus();
        }

        function updateContact(id, element, type) {
            $("#pinder").show();
            var value = $(element).val();
            if (!$.trim(value).length) {
                $("#pinder").hide();
                return;
            }
            var route = $('#update_contact').val();
            var _token = $('#_token').val();
            data = {
                id: id,
                value: value,
                _token: _token,
                type: type
            }

            $.post(route, data)
                .then(function(response) {
                    $(element).parent().remove();
                    if (response.result) {
                        if (parseInt(response.data.type)) {
                            var r = "{{ route('user.delete.contact', ['id' => '+response.data.id+', '_token' => csrf_token()]) }}";

                            $("#email_div").append(
                                '<label class="inputGroup popup">Email : <input type="text" placeholder="Add email" value="' +
                                response.data.contact + '" id="email_' + response.data.id +
                                '" onfocusout="updateContact(' + response.data.id +
                                ',this,1)" type="text"><div class="custom_overlay"><span class="custom_overlay_inner"><a href="' +
                                r +
                                '" class="delete_button" data-msg="Are you sure you want to delete this contact?"><svg width="20" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></a></span></div></label>'
                            );
                        } else {
                            var show_icon;
                            var r = "{{ route('user.delete.contact', ['id' => '+response.data.id+', '_token' => csrf_token()]) }}";
                            if (response.is_phone_hidden) {
                                var id = response.data.id;
                                show_icon_r =
                                    "{{ route('user.hide.phone', ['id' => '+id+', 'type' => 0, 'is_sub_contact' => true]) }}";
                                show_icon = ' <a href="' + show_icon_r +
                                    '" title="show this contact?" data-msg="Are you sure you want to show this contact?" class="hide_email"><svg width="20" height="16" viewBox="0 0 22 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.42012 8.71318C1.28394 8.49754 1.21584 8.38972 1.17772 8.22342C1.14909 8.0985 1.14909 7.9015 1.17772 7.77658C1.21584 7.61028 1.28394 7.50246 1.42012 7.28682C2.54553 5.50484 5.8954 1 11.0004 1C16.1054 1 19.4553 5.50484 20.5807 7.28682C20.7169 7.50246 20.785 7.61028 20.8231 7.77658C20.8517 7.9015 20.8517 8.0985 20.8231 8.22342C20.785 8.38972 20.7169 8.49754 20.5807 8.71318C19.4553 10.4952 16.1054 15 11.0004 15C5.8954 15 2.54553 10.4952 1.42012 8.71318Z" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M11.0004 11C12.6573 11 14.0004 9.65685 14.0004 8C14.0004 6.34315 12.6573 5 11.0004 5C9.34355 5 8.0004 6.34315 8.0004 8C8.0004 9.65685 9.34355 11 11.0004 11Z" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></a>'
                                $("#phone_div").append(
                                    '<label class="inputGroup popup">Phone : <input type="text" placeholder="Add phone" value="' +
                                    response.data.contact + '" id="phone_' + response.data.id +
                                    '" onfocusout="updateContact(' + response.data.id +
                                    ',this,0)" type="text"><div class="custom_overlay"><span class="custom_overlay_inner"><a href="' +
                                    r +
                                    '" class="delete_button" data-msg="Are you sure you want to delete this contact?"><svg width="20" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></a>' +
                                    show_icon + '</span></div></label>');
                                $("#pinder").hide();
                                return;
                            }
                            $("#phone_div").append(
                                '<label class="inputGroup popup">Phone : <input type="text" placeholder="Add phone" value="' +
                                response.data.contact + '" id="phone_' + response.data.id +
                                '" onfocusout="updateContact(' + response.data.id +
                                ',this,0)" type="text"><div class="custom_overlay"><span class="custom_overlay_inner"><a href="' +
                                r +
                                '" class="delete_button" data-msg="Are you sure you want to delete this contact?"><svg width="20" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></a></span></div></label>'
                            );
                        }
                    }
                    $("#pinder").hide();
                })
                .catch(function(error) {
                    console.log(error);

                    $("#pinder").hide();
                })
        }
        $(document).on("click", ".hide_email", function(e) {
            e.preventDefault();
            let href = $(this).attr('href');
            let msg = $(this).data('msg');
            alertify.defaults.glossary.title = 'Alert!';
            alertify.confirm("Are you sure?", msg,
                function() {
                    window.location.href = href;
                },
                function(i) {});
        });
        $(document).on("click", ".hide_email", function(e) {
            e.preventDefault();
            let href = $(this).attr('href');
            let msg = $(this).data('msg');
            alertify.defaults.glossary.title = 'Alert!';
            alertify.confirm("Are you sure?", msg,
                function() {
                    window.location.href = href;
                },
                function(i) {});
        });
        $(document).on("click", ".hide_phone", function(e) {
            e.preventDefault();
            let href = $(this).attr('href');
            let msg = $(this).data('msg');
            alertify.defaults.glossary.title = 'Alert!';
            alertify.confirm("Are you sure?", msg,
                function() {
                    window.location.href = href;
                },
                function(i) {});
        });

        $(document).ready(function() {
            $("#new-password").keyup(function() {
                if ($("#new-password").val().length < 8) {
                    $("#warning").text("Password should be at least 8 characters");
                    $("#change-password-btn").prop("disabled", true);
                } else {
                    $("#warning").text("");
                    $("#change-password-btn").prop("disabled", false);
                }
            });
            $("#confirm-password").keyup(function() {
                if ($("#new-password").val() == $("#confirm-password").val()) {
                    $("#warning").text("");
                    $("#change-password-btn").prop("disabled", false);
                } else {
                    $("#warning").text("Passwords do not match");
                    $("#change-password-btn").prop("disabled", true);
                }
            });
        });
     

            telnumber = $("#telephone").intlTelInput({
                fixDropdownWidth: true,
                showSelectedDialCode: true,
                strictMode: true,
                preventInvalidNumbers: true,
                initialCountry: 'gb'
            })

        
    </script>
    <script>
        function updatePosition(id, element) {
            var value = $(element).val();
            var _token = $('#_token').val();
            var data = {
                id: id,
                value: value,
                type: 1,
                _token: _token
            }
            var route = $('#update_position_route').val();
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        alertify.notify('User Position Updated!', 'success', 5)
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
        }
        function show_save_button(input) {
            if (input.id === 'first_name') {
                document.getElementById('save_first_name').style.display = 'inline-block'; // Show first name save button
            } else if (input.id === 'sur_name') {
                document.getElementById('save_sur_name').style.display = 'inline-block'; // Show surname save button
            }
        }
    
        document.getElementById('save_first_name').addEventListener('click', function (event) {
            event.preventDefault();
    
            var firstName = document.getElementById('first_name').value;
            var updateFirstNameRoute = document.getElementById('update_first_name_route').value;
            var csrfToken = '{{ csrf_token() }}';
    
            if (firstName.trim() !== '') {
                updateName('first_name', firstName, updateFirstNameRoute, csrfToken);
                document.getElementById('save_first_name').style.display = 'none'; // Hide the button after update
            }
        });
        document.getElementById('save_sur_name').addEventListener('click', function (event) {
            event.preventDefault();

            var surName = document.getElementById('sur_name').value;
            var updateSurNameRoute = document.getElementById('update_sur_name_route').value;
            var csrfToken = '{{ csrf_token() }}';

            if (surName.trim() !== '') {
                updateName('surname', surName, updateSurNameRoute, csrfToken); // Use 'surname' instead of 'sur_name'
                document.getElementById('save_sur_name').style.display = 'none'; // Hide the button after update
        }
    });

    
        function updateName(type, value, url, csrfToken) {
            var data = {};
            data[type] = value;
            data._token = csrfToken;
                
            var xhr = new XMLHttpRequest();
            xhr.open('POST', url, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alertify.notify(type.replace('_', ' ') + ' updated!', 'success', 5); 
                }
            };
        
            xhr.send(serialize(data));
        }

    
        function serialize(obj) {
            var str = [];
            for (var p in obj) {
                if (obj.hasOwnProperty(p)) {
                    str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                }
            }
            return str.join("&");
        }
    </script>
    
@endsection
@endsection


@section('sidebar')
@include('layouts.user.sidebar-header')
@endsection
