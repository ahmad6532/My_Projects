@extends('layouts.admin.master')
@section('title', 'Notification Management')
@section('content')
    <style>
        .checkbox_check {
            display: flex;
            width: auto;
            justify-content: space-between;
            margin: 0 !important;
        }

        .notification_vt .hum_tum_vt .table .thead-light th {
            text-align: left !important;
        }

        .notification_vt .hum_tum_vt td {
            text-align: left !important;
        }

        .notification_vt .checkbox-primary input[type=checkbox]:checked+label::before {
            background-color: #063c6e;
            border-color: #063c6e;
        }

        .notification_vt .modal-title {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            font-size: 14px;
        }

        .modal-header {
            background: lightgrey;
        }

        .notification_vt .checkbox label {
            cursor: pointer;
        }

        .btn_pin_vt {
            background: none !important;
            border: none !important;
            color: #063C6E !important;
        }

        .table .thead-light th {
            text-align: left !important;
        }

        textarea.form-control {
            height: auto !important;
        }

        .pagination {
            float: right;
            margin-bottom: 20px;
        }

        .table td {
            padding: 7px 0.85rem !important;
            border-bottom: 1px solid #dee2e6 !important;
        }

        .table th {
            padding: 7px 0.85rem !important;
            border-bottom: 1px solid #dee2e6 !important;
        }

        .bs_noti_vt .btn_add {
            width: 130px;
            float: right;
            color: #fff;
            background: var(--btn-bg) !important;
            line-height: 40px;
            border: none;
            margin-left: 0;
            border-radius: 2px;
        }

        .edit_btn_vt {
            background: none;
            border: 1px solid #6c757d !important;
            font-size: 14px;
            color: #6c757d;
            padding: 2px 10px;
            position: relative;
        }

        /* Sweep To Left */
        .hvr-sweep-to-left {
            display: inline-block;
            vertical-align: middle;
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
            box-shadow: 0 0 1px rgba(0, 0, 0, 0);
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            -moz-osx-font-smoothing: grayscale;
            position: relative;
            -webkit-transition-property: color;
            transition-property: color;
            -webkit-transition-duration: 0.3s;
            transition-duration: 0.3s;
        }

        .hvr-sweep-to-left:before {
            content: "";
            position: absolute;
            z-index: -1;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--btn-bg) !important;
            -webkit-transform: scaleX(0);
            transform: scaleX(0);
            -webkit-transform-origin: 100% 50%;
            transform-origin: 100% 50%;
            -webkit-transition-property: transform;
            transition-property: transform;
            -webkit-transition-duration: 0.3s;
            transition-duration: 0.3s;
            -webkit-transition-timing-function: ease-out;
            transition-timing-function: ease-out;
        }

        .hvr-sweep-to-left:hover,
        .hvr-sweep-to-left:focus,
        .hvr-sweep-to-left:active {
            color: #fff;
        }

        .hvr-sweep-to-left:hover:before,
        .hvr-sweep-to-left:focus:before,
        .hvr-sweep-to-left:active:before {
            -webkit-transform: scaleX(1);
            transform: scaleX(1);
        }

        .modal-body p {
            font-size: 13px;
        }

        /* Switch 2 Specific Style Start */





        /*------ ADDED CSS ---------*/
        .on_vt {
            position: absolute;
            left: 5px;
            top: 6px;
            font-size: 8px !important;
            font-weight: bold;
            color: #fff;
            z-index: 5;
        }

        .off_vt {
            position: absolute;
            right: 4px;
            top: 6px;
            font-size: 8px !important;
            font-weight: bold;
            color: #000;
            z-index: 5;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 20px;
            transform: translateY(4px);
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 15px;
            width: 15px;
            left: 4px;
            bottom: 3px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
            z-index: 6;
        }

        input:checked+.slider {
            background-color: var(--btn-bg) !important;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px var(--btn-bg) !important;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(21px);
            -ms-transform: translateX(1px);
            transform: translateX(21px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }


        .left-side-menu {
            background: #293846;
        }

        .ibox-title h5 {
            font-weight: 600 !important;
            padding: 0 !important;
        }

        #sidebar-menu>ul>li>a {
            color: #cccccc;
            font-size: 12px;
        }

        .arrow-link {
            position: relative;
        }

        .arrow-link::before {
            content: "";
            position: absolute;
            top: 60%;
            right: -14px;
            transform: translateY(-50%);
            border-width: 5px 5px 0;
            border-style: solid;
            border-color: #000 transparent transparent transparent;
            display: inline-block;
            width: 0;
            height: 0;
        }
    </style>



    <div class="col-md-12 mt-3">
        <div class="card">
            <div class="card-header">
                <h3>Notification</h3>
            </div>

            <div class="pb-3">
                <div class="table-responsive">
                    <table id="table1" class="table table-borderless table-centered table-nowrap">
                        <thead class="thead-light vt_head_td">
                            <tr>
                                <th>Sr #</th>
                                <!-- <th>Name</th> -->
                                <th>Name</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>SMS</th>
                                <th>Mobile App</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody class="btn_a_vt">
                            {{-- {{$i=0}} --}}
                            <?php $i = 0; ?>

                            @foreach ($noti_types as $key => $notifi_type)
                                <?php $i++; ?>
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        {{ $notifi_type->type }}
                                    </td>
                                    <td>
                                        <a class="arrow-link" style="cursor: pointer"
                                            onclick="openModal({{ $notifi_type->id }})">{{ $notifi_type->role_id != null ? 'View Role' : 'Select Role' }}</a>
                                    </td>
                                    <td>
                                        <div class="switch_box box_2">
                                            <label class="switch">
                                                <input type="checkbox" name="notify_by[]" value="sms" class="switch_2"
                                                    @if ($notifi_type->send_email == 'Y') checked @endif
                                                    id="checkbox{{ $i }}"
                                                    onclick="handleClick('email',{{ $notifi_type->id }},'checkbox{{ $i }}',this.checked)">
                                                <span class="slider round"></span>
                                                <span class="on_vt">ON</span>
                                                <span class="off_vt">OFF</span>
                                            </label>
                                            <!-- <span>Email</span> -->
                                        </div>
                                    </td>
                                    <td>
                                        <div class="switch_box box_2">
                                            <label class="switch">
                                                <input type="checkbox" name="notify_by[]" value="sms" class="switch_2"
                                                    @if ($notifi_type->send_sms == 'Y') checked @endif
                                                    id="checkbox{{ $i }}"
                                                    onclick="handleClick('sms',{{ $notifi_type->id }},'checkbox{{ $i }}',this.checked)">
                                                <span class="slider round"></span>
                                                <span class="on_vt">ON</span>
                                                <span class="off_vt">OFF</span>
                                            </label>
                                            <!-- <span>SMS</span> -->
                                        </div>
                                    </td>
                                    <td>
                                        <div class="switch_box box_2">
                                            <label class="switch">
                                                <input type="checkbox" name="notify_by[]" value="sms" class="switch_2"
                                                    @if ($notifi_type->send_app_noti == 'Y') checked @endif
                                                    id="checkbox{{ $i }}"
                                                    onclick="handleClick('app',{{ $notifi_type->id }},'checkbox{{ $i }}',this.checked)">
                                                <span class="slider round"></span>
                                                <span class="on_vt">ON</span>
                                                <span class="off_vt">OFF</span>
                                            </label>
                                            <!-- <span>Mobile APP</span> -->
                                        </div>
                                    </td>
                                    <!-- <td class="checkbox_check">
                                    <div class="checkbox checkbox-primary">
                                        <input id="checkbox{{ $i }}" name="notify_by[]" class="checkboxData" value="email" type="checkbox" @if ($notifi_type->send_email == 'Y') checked @endif>
                                        <label for="checkbox{{ $i }}" onclick="handleClick('email',{{ $notifi_type->id }},'checkbox{{ $i }}')">
                                            Email
                                        </label>
                                    </div>
                                    <?php $i++; ?>

                                    <div class="checkbox checkbox-primary">
                                        <input id="checkbox{{ $i }}" name="notify_by[]" class="checkboxData" value="sms" type="checkbox" @if ($notifi_type->send_sms == 'Y') checked @endif>
                                        <label for="checkbox{{ $i }}" onclick="handleClick('sms',{{ $notifi_type->id }},'checkbox{{ $i }}')">
                                            SMS
                                        </label>
                                    </div>
                                    <?php $i++; ?>

                                    <div class="checkbox checkbox-primary">
                                        <input id="checkbox{{ $i }}" name="notify_by[]" class="checkboxData" value="mobile_app" type="checkbox" @if ($notifi_type->send_app_noti == 'Y') checked @endif>
                                        <label for="checkbox{{ $i }}" onclick="handleClick('app',{{ $notifi_type->id }},'checkbox{{ $i }}')">
                                            Mobile APP
                                        </label>
                                    </div>
                                </td> -->
                                    @if ($notifi_type->type != 'Custom Notification')
                                        <td>
                                            <button
                                                onclick="NotiManagId({{ $notifi_type->id }},{{ json_encode($notifi_type->mail_subject) }}, {{ json_encode($notifi_type->mail) }},{{ json_encode($notifi_type->sms) }},{{ json_encode($notifi_type->mobile_app_title) }},{{ json_encode($notifi_type->mobile_app_description) }},{{ json_encode($notifi_type->variable_list) }},
                                {{ json_encode($notifi_type->send_sms) }},{{ json_encode($notifi_type->send_email) }},{{ json_encode($notifi_type->send_app_noti) }},{{ json_encode($notifi_type->to_email) }})"
                                                class="edit_btn_vt hvr-sweep-to-left" data-toggle="modal"
                                                data-animation="fadein" data-target=".bs-example-modal-center"><i
                                                    class="fa fa-edit"></i> Edit </button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Add Roles</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('save.notification.roles') }}" method="POST">
                                <div class="modal-body">

                                    @csrf()
                                    <input type="hidden" name="notification_id" value="" id="notification_id">
                                    <select class="selectpicker mb-0" data-live-search="true" multiple name="role_id[]"
                                        id="send-Email" style="appearance: none;">
                                    </select>
                                    <br>
                                    <br>
                                    <div class="d-flex align-items-center">
                                        <input id="email-checkBox" type="checkbox">
                                        <span class="checkbox-text_vt">Select All Roles</span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
                    aria-labelledby="myCenterModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title text-center" id="myCenterModalLabel">Template</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <ul class="nav nav-tabs nav-bordered">
                                    <li class="nav-item">
                                        <a href="#home-b1" data-toggle="tab" id="email_tab" aria-expanded="false"
                                            class="nav-link">
                                            Email
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#profile-b1" data-toggle="tab" id="sms_tab" aria-expanded="true"
                                            class="nav-link active">
                                            SMS
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#messages-b1" data-toggle="tab" aria-expanded="false" id="mobile_tab"
                                            class="nav-link">
                                            Mobile App
                                        </a>
                                    </li>
                                </ul>
                                <form method="post" action="{{ route('notifi.setting.store') }}">
                                    @csrf
                                    <input type="hidden" id="send_noti_id" name="id" value="">

                                    <div class="tab-content">
                                        <div class="tab-pane" id="home-b1">
                                            <div class="form-group" id="toEmail">
                                                <label for="exampleFormControlInput1">To Email</label>
                                                <input type="text" class="form-control" id="to_email"
                                                    name="to_email" value="" placeholder="Enter Email">
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="exampleFormControlInput1">You can used these variable</label>
                                                {{--                                            <input type="text" class="form-control" id="variable_List_sms" name="variable_list" value="" disabled> --}}
                                                <select class="form-control" id="variable_List_mail">
                                                    <option value="">Available Tags</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleFormControlInput1">Subject</label>
                                                <input type="text" class="form-control" id="email_subject"
                                                    name="subject" value="" placeholder="Enter Mail Subject">
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="exampleFormControlInput1">Body</label>

                                                <textarea name="email_body" id="summernote-editor" value="">
                                                </textarea>
                                            </div>
                                            <div class="btn_vt">
                                                <button type="submit" class="btn_add">Save</button>
                                            </div>
                                        </div>
                                        <div class="tab-pane show active" id="profile-b1">
                                            <div class="row">
                                                <div class="col-md-6 mt-3">
                                                    <div class="form-group">
                                                        <label for="exampleFormControlInput1">You can used these
                                                            variable</label>
                                                        {{--                                                    <input type="text" class="form-control" id="variable_List_mail" name="variable_list" value="" disabled> --}}
                                                        <select class="form-control" id="variable_List_sms">
                                                            <option value="">Available Tags</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleFormControlTextarea1">Enter your SMS</label>
                                                        <textarea class="form-control" name="smsDescription" id="msm1" value="" rows="3"></textarea>
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <h4>Example of how to use the available tags:</h4>
                                                    <p>

                                                        Hello {user_name},<br>

                                                        Your plant id is {plant_id} . We have notified an error in your
                                                        plant and sent an email on {user_email}.<br>

                                                        You can track your plant Details with this link: {TrackingLink}
                                                        .<br>

                                                        Kind Regards,<br>
                                                        Your Company Name<br>
                                                    </p>
                                                </div>
                                                <div class="col-md-12">
                                                    <h4>140 SMS characters remaining</h4>
                                                    <p>You can send text messages greater than 140 characters, but you will
                                                        be
                                                        charged more than once. Each text message is counted as 140
                                                        characters! The
                                                        max character limit for SMS is 240 characters!
                                                    </p>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="btn_vt">
                                                        <button type="submit" class="btn_add">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="messages-b1">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 mt-3">
                                                        <label for="exampleFormControlInput1">Title</label>
                                                        <input type="text" class="form-control" id="appTitlevt"
                                                            name="app_title" value="" placeholder="Enter">
                                                    </div>
                                                    <div class="col-md-6 mt-3">
                                                        <label for="exampleFormControlInput1">You can used these
                                                            variable</label>
                                                        {{--                                            <input type="text" class="form-control" id="variable_List_app" name="variable_list" value="" disabled> --}}
                                                        <select class="form-control" id="variable_List_app">
                                                            <option value="">Available Tags</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="exampleFormControlTextarea1">Enter your Text</label>
                                                <textarea class="form-control" placeholder="Enter" name="app_noti_Descr" id="appDescription" value=""
                                                    rows="3"></textarea>
                                            </div>

                                            <div class="btn_vt">
                                                <button type="submit" value="Submit" class="btn_add">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <h1 style="width: 100%;text-align: center;font-size: 20px;  color: #ccc;padding: 2rem 0"
                                    id = "emptyBlock" hidden>Please enable any option</h1>

                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

            </div>
            @if ($noti_types)
        {!! $noti_types->render() !!}
        @endif

        </div>
    </div>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link href="{{ asset('assets/css/summernote.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/js/summernote.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/jquery-3.5.1.min.js')}}" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script> --}}
    <script>
        // $(document).ready(function() {
        //     $('#table1').DataTable();
        // });

        function openModal(id) {
            $('#send-Email').empty();
            document.getElementById("notification_id").value = id;
            $.ajax({
                url: "{{ route('fetch.role.notification') }}",
                method: "POST",
                data: {
                    'id': id,
                    '_token': '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(response) {
                    var data = response.role;
                    var selectedRoles = response.data.roleName
                    $('#send-Email').empty();
                    var html;
                    // var html = '<option selected disabled>Select Branch</option>';
                    for (var i = 0; i < data.length; ++i) {
                        var isSelected = selectedRoles.includes(data[i].id) ? 'selected' : '';
                        html += `<option value="${data[i].id}" ${isSelected}>${data[i].role_name}</option>`;
                    }
                    $('#send-Email').html(html);
                    $('#send-Email').selectpicker('refresh');
                },
                error: function(data) {
                    console.log(data);
                    // window.location.reload();
                    // alert('Some Error Occured!');
                }
            });
            $('#exampleModalCenter').modal('show');
        }
        $(document).on("click", function(event) {
            if (!$(event.target).closest("#send-Email").length) {
                $("#save-button").hide();
            }
        });
        $("#send-Email").on("click", function() {
            $("#save-button").show();
        });
        $(document).ready(function() {
            document.getElementById('toEmail').setAttribute('class', 'd-none');

            $("#summernote-editor").summernote({
                height: 250,
                minHeight: null,
                maxHeight: null,
                focus: !1
            });
        });
    </script>
    <script>
        $("#email-checkBox").click(function() {
            if ($(this).is(':checked')) {
                $("#send-Email > option").prop("selected", true);
                $("#send-Email").trigger("change");
            } else {
                $("#send-Email > option").prop("selected", false);
                $("#send-Email").trigger("change");
            }
        });

        var id;
        var ischecked;

        function handleClick(type, id, checkBox, status) {

            console.log(checkBox, type, id, status);

            // $('#' + checkBox).click(function() {
            var ischecked = $(this).is(':checked');
            console.log(ischecked);

            $.ajax({
                url: "{{ route('notifi.setting.store') }}",
                method: "POST",
                data: {
                    'id': id,
                    'Notify_by': type,
                    'is_check': status,
                    '_token': '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    window.location.reload();
                },
                error: function(data) {
                    console.log(data);
                    window.location.reload();
                    // alert('Some Error Occured!');
                }
            });
            // });

        }

        function NotiManagId(id, email_subject, mail, sms, mobile_app_title, mobile_app_description, variables_list,
            send_sms, send_email, send_app_noti, to_email) {
            // console.log([id, mail, sms, mobile_app_title, mobile_app_description, variables_list,send_sms,send_email,send_app_noti]);
            document.getElementById('sms_tab').setAttribute('class', 'nav-link');
            document.getElementById('profile-b1').setAttribute('class', 'tab-pane');
            document.getElementById('email_tab').setAttribute('class', 'nav-link');
            document.getElementById('home-b1').setAttribute('class', 'tab-pane');
            document.getElementById('mobile_tab').setAttribute('class', 'nav-link');
            document.getElementById('messages-b1').setAttribute('class', 'tab-pane');
            // alert(id);
            if (send_sms == 'Y') {
                var sms_tab = document.getElementById('sms_tab');
                var sms_content = document.getElementById('profile-b1');
                sms_tab.classList.add("active");
                sms_content.classList.add("show", "active");
            } else {
                document.getElementById('sms_tab').setAttribute('class', 'd-none');
            }
            if (send_email == 'Y') {
                if (send_sms != 'Y') {
                    var email_tab = document.getElementById('email_tab');
                    var email_content = document.getElementById('home-b1');
                    email_tab.classList.add("active");
                    email_content.classList.add("show", "active");
                }

                if (id == 21) {
                    document.getElementById('toEmail').setAttribute('class', 'd-block');
                } else {
                    document.getElementById('toEmail').setAttribute('class', 'd-none');
                }

            } else {
                document.getElementById('email_tab').setAttribute('class', 'd-none');
            }
            if (send_app_noti == 'Y') {
                if (send_sms != 'Y' && send_email != 'Y') {
                    var mobile_tab = document.getElementById('mobile_tab');
                    var mobile_content = document.getElementById('messages-b1');
                    mobile_tab.classList.add("active");
                    mobile_content.classList.add("show", "active");
                }
            } else {
                document.getElementById('mobile_tab').setAttribute('class', 'd-none');
            }
            if (send_app_noti == 'N' && send_email == 'N' && send_sms == 'N') {
                document.getElementById('emptyBlock').removeAttribute('hidden');

            }
            $('#variable_List_sms').find('option').not(':first').remove();
            $('#variable_List_mail').find('option').not(':first').remove();
            $('#variable_List_app').find('option').not(':first').remove();

            document.getElementById("send_noti_id").setAttribute("value", id);
            $("#summernote-editor").summernote("code", mail);
            document.getElementById("msm1").innerHTML = sms;
            $.each(variables_list.split(','), function(key, value) {
                $('#variable_List_sms').append('<option value="' + value + '" >' + value + '</option>');
            });
            $.each(variables_list.split(','), function(key, value) {
                $('#variable_List_mail').append('<option value="' + value + '">' + value + '</option>');
            });
            $.each(variables_list.split(','), function(key, value) {
                $('#variable_List_app').append('<option value="' + value + '">' + value + '</option>');
            });
            document.getElementById("appTitlevt").setAttribute('value', mobile_app_title);
            document.getElementById("appDescription").innerHTML = mobile_app_description;
            document.getElementById("email_subject").setAttribute('value', email_subject);
            document.getElementById("to_email").setAttribute('value', to_email);
            // }

            var nameValue = document.getElementById("send_noti_id").setAttribute("value", id);
            $("#summernote-editor").summernote("code", mail);
            var nameValue = document.getElementById("msm1").innerHTML = sms;
            var nameValue = document.getElementById("variable_List_sms").setAttribute('value', variables_list);
            var nameValue = document.getElementById("variable_List_mail").setAttribute('value', variables_list);
            var nameValue = document.getElementById("variable_List_app").setAttribute('value', variables_list);
            var nameValue = document.getElementById("appTitlevt").setAttribute('value', mobile_app_title);
            var nameValue = document.getElementById("appDescription").innerHTML = mobile_app_description;
        }

        $(document).ready(function() {
            // alert(123);
            $("#variable_List_sms").change(function() {
                var dropdown = document.getElementById("variable_List_sms");
                var cursorPos = $('#msm1').prop('selectionStart');
                var v = $('#msm1').val();
                var textBefore = v.substring(0, cursorPos);
                var textAfter = v.substring(cursorPos, v.length);
                console.log(dropdown, cursorPos, v, textBefore, textAfter);
                $('#msm1').val(textBefore + dropdown.value + textAfter);
            });
            $("#variable_List_mail").change(function() {
                // console.log("in dowen condition");
                var dropdown = document.getElementById("variable_List_mail").value;
                $('#summernote-editor').summernote('editor.saveRange');
                // Editor loses selected range (e.g after blur)
                $('#summernote-editor').summernote('editor.restoreRange');
                $('#summernote-editor').summernote('editor.focus');
                $('#summernote-editor').summernote('editor.insertText', dropdown);
            });
            $("#variable_List_app").change(function() {
                var dropdown = document.getElementById("variable_List_app");
                var cursorPos = $('#appDescription').prop('selectionStart');
                var v = $('#appDescription').val();
                var textBefore = v.substring(0, cursorPos);
                var textAfter = v.substring(cursorPos, v.length);
                console.log(dropdown, cursorPos, v, textBefore, textAfter);
                $('#appDescription').val(textBefore + dropdown.value + textAfter);
            });
        });
    </script>
@endsection
