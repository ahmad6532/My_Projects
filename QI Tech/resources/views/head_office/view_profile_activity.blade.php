@extends('layouts.head_office_app')
@section('title', 'Head office Profile')
@section('content')
@section('styles')
    <link href="https://qitech.kiebit.com/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.css" rel="stylesheet">
    <style>
        /* Popup container - can be anything you want */
        .popup {
            position: relative;
            display: inline-block;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* The actual popup */
        .popup .popuptext {
            visibility: hidden;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px 0;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -80px;
        }

        /* Popup arrow */
        .popup .popuptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent transparent;
        }

        /* Toggle this class - hide and show the popup */
        .popup .show {
            visibility: visible;
            -webkit-animation: fadeIn 1s;
            animation: fadeIn 1s;
        }

        /* Add animation (fade in the popup) */
        @-webkit-keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }


        .profile-pic {
            color: transparent;
            transition: all .3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            transition: all .3s ease;

            input {
                display: none;
            }

            img {
                position: absolute;
                object-fit: cover;
                width: 120px;
                height: 120px;
                box-shadow: 0 0 10px 0 rgba(255, 255, 255, .35);
                border-radius: 100px;
                z-index: 0;
            }

            .-label {
                cursor: pointer;
                height: 120px;
                width: 120px;
            }

            &:hover {
                .-label {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    background-color: rgba(0, 0, 0, .8);
                    z-index: 10000;
                    color: rgb(250, 250, 250);
                    ;
                    transition: background-color .2s ease-in-out;
                    border-radius: 100px;
                    margin-bottom: 0;
                }
            }

            span {
                display: inline-flex;
                padding: .2em;
                height: 2em;
            }
            
            
        }
        #logs-table_wrapper{
            width: 80%;
            margin: 0 auto;
        }
        div.dt-container div.dt-layout-row div.dt-layout-cell{
            width: 80% !important;
            display: flex !important;
            margin: 0 auto;
        }
        #logs-table_wrapper .dt-start, #logs-table_wrapper .dt-end {
            width: fit-content !important;
        }
        #session-dataTable_wrapper .dt-start, #session-dataTable_wrapper .dt-end {
            width: fit-content !important;
        }
        #logs-table_wrapper .dt-search input{
            padding-left: 28px !important;
        }
        #logs-table_wrapper .dt-search input{
            padding-left: 28px !important;
        }
        #session-dataTable_wrapper .dt-search input{
            padding-left: 20px !important;
        }
    </style>
@endsection

<input type="hidden" id="route" value="{{ route('head_office.update_status') }}">
<input type="hidden" id="route_active" value="{{ route('head_office.update_active') }}">
<input type="hidden" id="_token" value="{{ csrf_token() }}">
<div id="content">

    <!-- Profile page heading -->
    <div class="content-page-heading">
        My Profile
    </div>

    <div class="profile-center-area">

        <nav class="page-menu bordered">
            <ul class="nav nav-tab main_header">
                <li><a href="{{route('head_office.view_profile',['tab'=>'aboutMeProfile'])}}"  
                        id="aboutMeProfile" onclick="changeTabUrl('aboutMeProfile')">About Me<span></span></a></li>
                <li><a href="{{route('head_office.view_profile',['tab'=>'workingProfile'])}}" 
                        id="workingProfile" onclick="changeTabUrl('workingProfile')">Working Status<span></span></a>
                </li>
                <li><a href="{{route('head_office.view_profile',['tab'=>'sessionProfile'])}}" 
                        id="sessionProfile" onclick="changeTabUrl('sessionProfile')">Session History<span></span></a>
                </li>
                <li><a href="{{route('head_office.view_profile_logs')}}"  class="active" id="activtyLog"
                        onclick="changeTabUrl('activtyLog')">My Activity<span></span></a></li>
            </ul>
        </nav>
        <hr class="hrBeneathMenu">
        <!-- profile page contents -->
        <div class="tab-content" id="myTabContent">
            
            <div id="activty_log" class="activty_log tab-pane fade show active">
                <div>
                    @php
                        $profile = $head_office_user->get_permissions();
                    @endphp
                    @if (isset($profile)  &&
                            $head_office_user->get_permissions()->is_access_company_activity_log == true)
                        @php $logs = App\Models\ActivityLog::where('head_office_id',$head_office->id)->get(); @endphp
                        <table id="logs-table" class="table  new-table" style="width: 100%;margin-inline:auto;">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Date</th>
                                    <th>Type</th>
                                    <th style="text-align: center;">Detail</th>
                                    <!-- x<th>Entry By</th> -->
                                </tr>
                            </thead>
                            <tbody class="all_locations text-center">
                                @foreach ($logs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('d/m/y (D) h:i') }}</td>
                                        <td>{{ $log->type }}</td>
                                        <td style="text-align: center;">
                                            @if ($log->type == 'Information Request' && isset($log->comment_id))
                                                {{$log->action}}
                                                <br>
                                                <a href="{{route('headOffice.requestInformation.comment.view',['id' => $log->comment_id])}}" target="_blank">View Response</a>
                                            @elseif($log->type == 'Comment' || $log->type == 'Approve Close' && isset($log->comment_id))
                                                <div>
                                                    <div class="cm_comment_comment">
                                                        {!! $log->comment?->comment !!}
                                                    </div>
                                                    @if(count($log->comment?->documents ?? []))
                                                    <div class="cm_comment_attachments mt-1">
                                                        <ul class="list-style-none p-0">
                                                            @foreach($log->comment->documents as $doc)
                                                                <li class="relative ">
                                                                    @if ($doc->type == 'audio')
                                                                    <div class="mt-2">
                                                                        <audio class="m-0" controls src="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}"></audio>
                                                                    </div>
                                                                    @else
                                                                        <a class="relative 
                                                                        @if($doc->type == 'image') cm_image_link @endif " href="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}" target="_blank"><i class="fa fa-link"></i> {{$doc->document->original_file_name()}}
                                                                        @if($doc->type == 'image')
                                                                            <div class="cm_image_hover">
                                                                                <div class="card shadow">
                                                                                    <div class="card-body">
                                                                                        <img class="image-responsive" width="300" src="{{route('headoffice.view.attachment', $doc->document->unique_id).$doc->document->extension()}}">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                        </a>

                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    @endif
                                                    
                                                    
                                                </div>
                                            @else
                                            {!! nl2br(e($log->action)) !!}
                                            @endif
                                        </td>
                                       <!-- <td>{{ isset($log->user->name) ? $log->user->name : 'unknown' }}</td> -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <h4 class="h6 mt-2 mx-auto">You don't have permission to see the Activity Logs</h4>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal fade file_upload_model" id="add_new_holiday"
    @if (isset($remove_backdrop)) data-backdrop="false" @endif tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('head_office.update_head_office_user_holidays') }}" method="post">
                @csrf
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        Add New Holiday
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="text-info">Start Date</h6>
                    <input id="away_from" required="" onchange="disable_return_on()" type="date"
                        name="away_from" class="form-control ">
                    <h6 class="text-info">End Date</h6>
                    <input id="return_on" onchange="calculate_date()" required="" type="date"
                        name="return_on" class="form-control">
                    <h6 class="text-info">Holiday Type</h6>
                    <input id=""  required type="text"
                        name="type" class="form-control">


                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="submit" class="btn btn-light">Save <i class="fa fa-arrow-right"></i></button>
                        <button type="button" class="btn  btn-primary" data-bs-dismiss="modal">Close </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="add_contact_detail" @if (isset($remove_backdrop)) data-backdrop="false" @endif
    tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('head_office.add_contact_detail') }}" method="post">
                @csrf
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        Add New Contact
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="text-info">Type</h6>
                    <select name="type" class="form-control" onchange="changeType(this)">
                        <option value="0">Phone Number</option>
                        <option value="1">Email</option>
                    </select>
                    <h6 class="text-info">Contact</h6>
                    <input required type="text" id="type_contact" name="contact" class="form-control">
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="submit" class="btn btn-white">Save <i class="fa fa-arrow-right"></i></button>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Close <i
                                class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="head_office_add_area" @if (isset($remove_backdrop)) data-backdrop="false" @endif
    tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('head_office.head_office_add_area') }}" method="post">
                @csrf
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        Add New Area
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="text-info">Area</h6>
                    <input required type="text" name="contact" class="form-control">
                    <h6 class="text-info">Level</h6>
                    <input required type="text" name="contact" class="form-control">
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="submit" class="btn btn-white">Save <i class="fa fa-arrow-right"></i></button>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Close <i
                                class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="draggable" class="bottom-nav position-fixed " style="z-index: 9999;" aria-describedby="drag" >
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

        <div class="btn-wrapper">
            <button id='delete-btn' class="bar-btn" title="Remove selected records">
                <img src="{{ asset('images/trash-01.svg') }}" alt="icon">
                <p>Remove</p>
            </button>
            <button id="log-btn" class="bar-btn" style="display: none;width:100px;" title="Logout all devices">
                <img src="{{ asset('images/log-out-04.svg') }}" alt="icon">
                <p>Log-out All</p>
            </button>

        </div>
    </div>
    <button class="drag-btn">
        <img src="{{ asset('images/dots-horizontal.svg') }}" alt="svg">
        <img style="margin-top:-15px;" src="{{ asset('images/dots-horizontal.svg') }}" alt="svg">
    </button>
</div>

<div class="d-none" hidden>
    <form id="delete-form" hidden action="{{route('head_office.remove_session_records')}}" method="POST">
        @csrf
        <input type="text" name="sessionIds[]" value="" id="sessionid-input">
    </form>
    <form id="log-form" hidden action="{{route('head_office.log_session_records')}}" method="POST">
        @csrf
        <input type="text" name="sessionIds[]" value="" id="sessionid-log">
    </form>
</div>
<input type="hidden" value="{{ route('head_office.update_email') }}" id="update_email_route">
<input type="hidden" value="{{ route('head_office.update_position') }}" id="update_position_route">
<input type="hidden" value="{{ route('head_office.update_phone') }}" id="update_phone_route">
<input type="hidden" value="{{ route('head_office.update_about') }}" id="update_about_route">
<input type="hidden" value="{{ route('head_office.update_location') }}" id="update_location_route">
<input type="hidden" value="{{ route('head_office.update_area') }}" id="update_area_route">
<input type="hidden" value="{{ route('user.update_picture') }}" id="update_profile">
@section('scripts')
@if(Session::has('success'))
    <script>
        alertify.success("{{ Session::get('success') }}");
    </script>
@elseif(Session::has('error'))
<script>
    alertify.success("{{ Session::get('error') }}");
</script>
@endif
    <script src="{{ asset('admin_assets/js/ho_view_profile.js') }}"></script>
    <script>
        
        $(document).ready(function() {

            telnumber = $("#telephone").intlTelInput({
        fixDropdownWidth:true,
        showSelectedDialCode:true,
        strictMode:true,
        preventInvalidNumbers: true,
        initialCountry:'gb'
    })

        $(".phone").intlTelInput({
                fixDropdownWidth: true,
                showSelectedDialCode: true,
                strictMode: true,
                utilsScript: "{{asset('admin_assets/js/utils.js')}}",
                preventInvalidNumbers: true,
            })


            loadActiveTab();
            // changeTabUrl('aboutMeProfile')
            if(window.location.search.split('=')[1] != undefined){
                changeTabUrl(window.location.search.split('=')[1])
            }
            const dataTable = new DataTable('#logs-table', {
                autoWidth:false,
                paging: true,
                info: false,
                language: {
                    search: ""
                },
            });
            let table = new DataTable('#session-dataTable', {
                paging: true,
                info: false,
                language: {
                    search: ""
                },
                'columnDefs': [{
                    "select": 'multi',
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'className': '',
                    'render': function(data, type, full, meta) {
                        return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(
                            data).html() + '">';
                    }
                }],
            });

            $('#dataTable-select-all').on('click', function() {
                var rows = table.rows({
                    'search': 'applied'
                }).nodes();
                // Check/uncheck checkboxes for all rows in the table
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            let sessionIds = []; // Array to store data-session-id values
            table.on('change', 'input', function() {

                let rowData = table.column(0).nodes().filter(function(value, index) {
                    let inputElement = $(value).find('input');
                    return inputElement.prop('checked');
                });

                sessionIds = [];
                $.each(rowData, function(index, obj) {
                    let sessionId = $(obj).data('session-id');
                    if (sessionId) {
                        sessionIds.push(sessionId);
                    }
                });


                if (rowData.length > 0) {
                    $('#draggable').addClass('anim').removeClass('reverse-anim');
                } else {
                    $('#draggable').addClass('reverse-anim').removeClass('anim');
                }


                const dotsWrapper = $('.dots-wrapper');
                dotsWrapper.empty();

                for (let i = 0; i < rowData.length; i++) {
                    dotsWrapper.append('<span class="dot" style="width:8px;height:8px;"></span>')
                }
                if (rowData.length > 1) {
                    $('#log-btn').fadeIn()
                    $('#delete-btn').fadeOut()
                } else {
                    $('#log-btn').fadeOut('fast')
                    $('#delete-btn').fadeIn()
                }
                $('#count').text(rowData.length);
            });


            $('#delete-btn').on('click',function(){
                $('#sessionid-input').val(sessionIds);
                $('#delete-form').submit();
            })
            $('#log-btn').on('click',function(){
                $('#sessionid-log').val(sessionIds);
                $('#log-form').submit();
            })

        });


        function loadActiveTab(tab = null) {
            if (tab == null) {
                tab = window.location.hash;
            }
            console.log(tab);
            $('.nav-tabs a[data-bs-target="' + tab + '"]').tab('show');
        }

        function myFunction() {
            var popup = document.getElementById("myPopup");
            $('#myPopup').addClass('show').fadeIn();
        }
        // removes the pop-up in email
        $(".email-pop-wrapper").on("mouseout", function(event) {
            if (!$(event.relatedTarget).closest('.inputSection').length && !$(event.relatedTarget).is('#myPopup') && !$(event.relatedTarget).is('#myPopup *')) {
                $('#myPopup').fadeOut();
            }
        });

        function byPhone() {
            var popup = document.getElementById("myPopupPhone");
            $('#myPopupPhone').addClass('show').fadeIn();
        }
        // removes the pop-up in phone
        $(".popup").on("mouseout", function(event) {
            event.preventDefault();
            if (!$(event.relatedTarget).closest('.inputSection').length && !$(event.relatedTarget).is('#myPopupPhone') && !$(event.relatedTarget).is('#myPopupPhone *')) {
                $('#myPopupPhone').fadeOut();
            }
        });

        function changeType(element) {
            val = $(element).val();
            if (val == 0) {
                $('#type_contact').prop("type", "number");
            } else {

                $('#type_contact').prop("type", "text");
            }
        }

        function updateEmail(id, element) {
            var value = $(element).val();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                alertify.notify('Invalid Email', 'error');
                return;
            }
            var _token = $('#_token').val();
            var data = {
                id: id,
                value: value,
                type: 1,
                _token: _token
            }
            var route = $('#update_email_route').val();
            $.post(route, data)
                .then(function(response) {
                    $(element).parent().remove();
                    if (response.result) {
                        window.location.reload();
                        var r = "{{ route('head_office.delete_contact', '+response.data.id+') }}";
                        var r2 =
                            "{{ route('contact.hide.email', ['type' => 1, 'contact_id' => '+response.data.id+']) }}"
                        $("#email_div").append(
                            '<label class="inputGroup popup">Email : <input type="text" placeholder="Add email" value="' +
                            response.data.contact + '" id="email_' + response.data.id +
                            '" onfocusout="updateEmail(' + response.data.id +
                            ',this)" type="text"><div class="custom_overlay"><span class="custom_overlay_inner "><div class="d-flex align-items-center gap-2"><a href="' +
                            r +
                            '" class="delete_button" data-msg="Are you sure you want to delete this contact?"><svg width="20" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></a><div><a href="http://127.0.0.1:8000/head_office/hide_email/1?contact_id=2" title="hide this contact?" data-msg="Are you sure you want to hide this contact?" class="hide_email"><svg width="20" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.7429 5.09232C11.1494 5.03223 11.5686 5 12.0004 5C17.1054 5 20.4553 9.50484 21.5807 11.2868C21.7169 11.5025 21.785 11.6103 21.8231 11.7767C21.8518 11.9016 21.8517 12.0987 21.8231 12.2236C21.7849 12.3899 21.7164 12.4985 21.5792 12.7156C21.2793 13.1901 20.8222 13.8571 20.2165 14.5805M6.72432 6.71504C4.56225 8.1817 3.09445 10.2194 2.42111 11.2853C2.28428 11.5019 2.21587 11.6102 2.17774 11.7765C2.1491 11.9014 2.14909 12.0984 2.17771 12.2234C2.21583 12.3897 2.28393 12.4975 2.42013 12.7132C3.54554 14.4952 6.89541 19 12.0004 19C14.0588 19 15.8319 18.2676 17.2888 17.2766M3.00042 3L21.0004 21M9.8791 9.87868C9.3362 10.4216 9.00042 11.1716 9.00042 12C9.00042 13.6569 10.3436 15 12.0004 15C12.8288 15 13.5788 14.6642 14.1217 14.1213" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></a></div></div></span></div></label>'
                        );
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
        }

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

        function updateAbout(id, element) {
            var value = $(element).val();
            var _token = $('#_token').val();
            var data = {
                id: id,
                value: value,
                type: 1,
                _token: _token
            }
            var route = $('#update_about_route').val();
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        alertify.notify('User about Updated!', 'success', 5)
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
        }

        function updatePhone(id, element) {
            var value = $(element).val();
            var _token = $('#_token').val();
            var data = {
                id: id,
                value: value,
                type: 0,
                _token: _token
            }
            var route = $('#update_email_route').val();

            if($(element).intlTelInput("isValidNumber") == false || $(element).intlTelInput("isValidNumber") == undefined){
                alertify.notify('Invalid Phone','error')
            }else{
                console.log('datadfaf')
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        window.location.reload();
                        var r = "{{ route('head_office.delete_contact', '+response.data.id+') }}";
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
                    console.log(error);
                })

            }


        }

        function add_email() {
            $("#email_div").append(
                '<label class="inputGroup popup">Email : <input type="text" placeholder="Add email" value="" onfocusout="updateEmail(0,this)" type="text"></label>'
            );
        }

        function add_phone() {
            $("#phone_div").append(
                '<label class="inputGroup popup">Phone : <input type="text" class="phone" placeholder="Add Phone" value="" onfocusout="updatePhone(0,this)" type="text"></label>'
            );
            $(".phone").intlTelInput({
            fixDropdownWidth: true,
            showSelectedDialCode: true,
            strictMode: true,
            utilsScript: "{{asset('admin_assets/js/utils.js')}}",
            preventInvalidNumbers: true,
        })
        }

        function updateLocation(element) {
            var value = $(element).val();
            var route = $('#update_location_route').val();
            var data = {
                location: value,
                _token: $('#_token').val()
            }
            $.post(route, data)
                .then(function(response) {
                    console.log(response)
                })
                .catch(function(error) {
                    console.log(error);
                })
        }

        function add_area() {
            $("#area_div").empty();
            $("#area_div").append(
                '<label class="inputGroup popup">Area : <input type="text" placeholder="Add Area" onfocusout="focusOnLevel()" id="area" value="" type="text"></label><label class="inputGroup popup">Level : <input type="text" id="level" placeholder="Add Level" value="" onfocusout="updateArea(this)" type="text"></label>'
            );
        }

        function updateArea(element) {
            var area = $(element).parent().parent().find('#area').val();
            var level = $(element).val();

            var route = $('#update_area_route').val();
            if (area) {
                var data = {
                    area: area,
                    level: level,
                    _token: $('#_token').val()
                };
                $.post(route, data)
                    .then(function(response) {
                        if (response.result) {
                            window.location.reload();
                            $('#area_values').append('<label class="inputGroup" id="' + data.id +
                                '">Area:<input style="width:40%" type="text" placeholder="Add area" value="' + data
                                .area + '"><input style="width:40%" type="text" placeholder="Add Level" value="' +
                                data.level + '"></label>');
                            console.log(response);
                            $(element).parent().parent().remove();
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    })
            } else {
                $(element).parent().parent().find('#area').focus();
            }
        }

        function focusOnLevel() {
            $('#level').focus();
        }
        var loadFile = function(event) {
            var image = document.getElementById("output");
            image.src = URL.createObjectURL(event.target.files[0]);
            var route = $('#update_profile').val();
            var token = $('#_token').val();
            let file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = (evt) => {
                console.log(evt.target.result);
                result = evt.target.result;
                var data = {
                    file: result,
                    _token: token
                }
                $.post(route, data).then(function(response) {
                    console.log(response);
                });
            };
            reader.readAsDataURL(file);
        };
        $(document).on("click", ".hide_email", function(e) {
            e.preventDefault();
            let href = $(this).attr('href');
            let msg = $(this).data('msg');
            alertify.confirm("Are you sure?", msg,
                function() {
                    window.location.href = href;
                },
                function(i) {}).set('labels', {ok:'Yes', cancel:'No'});
        });
        $(document).on("click", ".hide_phone", function(e) {
            e.preventDefault();
            let href = $(this).attr('href');
            let msg = $(this).data('msg');
            alertify.confirm("Are you sure?", msg,
                function() {
                    window.location.href = href;
                },
                function(i) {}).set('labels', {ok:'Yes', cancel:'No'});
        });

        $(document).ready(function(){
            const dragBtn = document.querySelector('.drag-btn');
        })
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
    </script>
@endsection
@endsection
