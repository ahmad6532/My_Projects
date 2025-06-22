@extends('layouts.head_office_app')
@section('title', 'Assign Setting to Organisation')
@section('sidebar')
    @include('layouts.company.sidebar')
@endsection
@section('content')
    <div id="content">
        <style>
            .no-shadow {
                box-shadow: none !important;
                font-size: 12px !important;
                opacity: 0.5;
                transition: 0.2s ease !important;
            }

            .no-shadow:hover,
            .no-shadow:focus {
                transition: 0.2s ease-in-out !important;
                opacity: 1;
            }
            .select2-container{
            width: 100% !important;
        }
        div.dt-container div.dt-layout-row{
            display: block;
        }
        </style>
        <div class="content-page-heading">
            Location Users
        </div>

        <nav class='page-menu bordered'>
            <ul class="nav nav-tab main_header">

                <li>
                    <a data-bs-toggle="tab" onclick="changeTabUrl('ApprovedUser')" id="ApprovedUser" data-bs-target="#approved"
                        class="approved active" href="javascript:void(0)" {{--
                    href="{{route('head_office.company_info')}}" --}}>
                        Approved
                        <span></span>
                    </a>
                </li>
                <li> <a data-toggle="tooltip" data-placement="top" title="Coming Soon"
                    href="#">User Types<span></span></a></li>
                <li>
                    <a data-bs-toggle="tab" onclick="changeTabUrl('UserTypeBlocked')" id="UserTypeBlocked"
                        data-bs-target="#user_blocked" class="user_type" href="javascript:void(0)" {{-- href="{{route('head_office.my_organisation')}}" --}}>
                        Blocked
                        <span></span>
                    </a>
                </li>
            </ul>
        </nav>
        <hr class="hrBeneathMenu">
        <div class="tab-content" id="myTabContent">
            <div id="approved" class="approved relative tab-pane active show">
                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#add_approved_location"
                    class="primary-btn right float-right float-top"><i class="fa fa-plus"></i> Add Location</a>
                @include('layouts.error')
                @if (isset($array) && count($array) > 0)
                <table class="table table-striped new-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Access</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($array as $key => $arr)
                            <tr>
                                <td>{{ $head_office->findUser($key)->name }}</td>
                                <td>
                                    @php
                                        $group_ids = [];
                                        $location_ids = [];
                                    @endphp
                                    @foreach ($arr as $k => $item)
                                        @if ($item->is_location())
                                            @php $location_ids[] = $item->location->id @endphp
                                            <span class="borderedTest">{{ $item->location->registered_company_name }}
                                            </span>
                                        @else
                                            <span class="borderedTest">{{ $item->group->group }} </span>
                                            @php $group_ids[] = $item->head_office_organisation_group_id @endphp
                                        @endif
                                    @endforeach

                                </td>
                                <td>
                                    <div class="dropdown mb-4">
                                        <button class="no-arrow btn btn-outline-cirlce dropdown-toggle" type="button"
                                            id="dropdownMenuButton11" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                        </button>
                                        <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton11"
                                            style="">
                                            <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#edit_approved_location_{{ $key }}"
                                                class="dropdown-item" title="Edit Details">
                                                Edit &nbsp;
                                                <svg width="20" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M11 3.99998H6.8C5.11984 3.99998 4.27976 3.99998 3.63803 4.32696C3.07354 4.61458 2.6146 5.07353 2.32698 5.63801C2 6.27975 2 7.11983 2 8.79998V17.2C2 18.8801 2 19.7202 2.32698 20.362C2.6146 20.9264 3.07354 21.3854 3.63803 21.673C4.27976 22 5.11984 22 6.8 22H15.2C16.8802 22 17.7202 22 18.362 21.673C18.9265 21.3854 19.3854 20.9264 19.673 20.362C20 19.7202 20 18.8801 20 17.2V13M7.99997 16H9.67452C10.1637 16 10.4083 16 10.6385 15.9447C10.8425 15.8957 11.0376 15.8149 11.2166 15.7053C11.4184 15.5816 11.5914 15.4086 11.9373 15.0627L21.5 5.49998C22.3284 4.67156 22.3284 3.32841 21.5 2.49998C20.6716 1.67156 19.3284 1.67155 18.5 2.49998L8.93723 12.0627C8.59133 12.4086 8.41838 12.5816 8.29469 12.7834C8.18504 12.9624 8.10423 13.1574 8.05523 13.3615C7.99997 13.5917 7.99997 13.8363 7.99997 14.3255V16Z"
                                                        stroke="black" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('head_office.approved_location.delete', ['id' => $key,'_token' => csrf_token()]) }}"
                                                data-msg="Are you sure you want to delete this?"
                                                class="dropdown-item delete_button" title="Personalise Account">
                                                Delete &nbsp;
                                                <svg width="20" style="color: white" height="24" viewBox="0 0 24 24"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5"
                                                        stroke="black" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    @include('head_office.my_organisation.add_location_user', [
                                        'key' => $key,
                                        'location' => $arr,
                                        'groups' => $groups,
                                        'locations' => $locations,
                                        'location_ids' => $location_ids,
                                        'group_ids' => $group_ids,
                                    ])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else 
                <p>You have not added any approved users</p>
                @endif
            </div>
            <div id="user_type" class="user_type relative tab-pane">
                Comming soon
            </div>
            <div id="user_blocked" class="user_type relative tab-pane">
                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#add_block_users"
                    class="primary-btn right float-right float-top"><i class="fa fa-plus"></i> Add Users</a>
                    @php 
                    $logedUser = Auth::guard('web')->user();
                    $logged_headOfficeUser = $logedUser->getHeadOfficeUser();
                    $logged_profile = $logged_headOfficeUser->get_permissions();
                @endphp
                
                @php
                    $suspended_users = [];
                    foreach ($head_office->users as $hou) {
                        $profile = $hou->get_permissions();
                
                        // Only collect suspended users if the logged-in user has super access or matching profiles
                        if ($hou->is_blocked && ($logged_profile->super_access || $logged_profile->id == $profile->id)) {
                            $suspended_users[] = $hou;
                        }
                    }
                @endphp
                
                @if (isset($suspended_users) && count($suspended_users) > 0)
                    <table id="blockUser-table"
                        class="table table-responsive table-bordered mx-auto dataTable w-100 new-table" style="width: 100% !important;">
                        <thead>
                            <tr>
                                <th class="text-center"><input type="checkbox" name="select_all" value="1"
                                        id="dataTable-select-all"></th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Comment</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="all_locations text-center">
                            @foreach ($suspended_users as $hou)
                                <tr>
                                    <td></td>
                                    <td class="fw-semibold email">{{ $hou->user->email }}</td>
                                    <td class="fw-semibold "><input type="text" class="form-control no-shadow"
                                            value="{{ $hou->block_comment ?? 'add comment' }}"
                                            onfocusout="updateLink({{ $hou->id }},this)"></td>
                                    <td class="position">{{ $hou->user->name }}</td>
                                    <td>
                                        <p class="badge text-bg-{{ $hou->is_blocked ? 'danger' : 'success' }} m-0">
                                            {{ $hou->is_blocked ? 'blocked' : 'live' }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 justify-content-center align-items-center mx-auto"
                                            style="width: fit-content;">

                                            <a href="{{ isset($logedUser) && $logedUser->id == $hou->user->id ? '#' : route('head_office.head_office_users.block_user', ['id' => $hou->id, '_token' => csrf_token()]) }}"
                                                {{ isset($logedUser) && $logedUser->id == $hou->user->id ? 'disabled' : '' }}
                                                type="button"
                                                class="btn p-0 px-2 {{ $hou->is_blocked ? 'text-success' : 'text-danger' }}"
                                                title="{{ $hou->is_blocked ? 'Un-Block User' : 'Block User' }}">
                                                <i
                                                    class="fa-solid fa-user{{ $hou->is_blocked ? '' : '-lock' }}"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="">You do not have any blocked users</p>
                @endif
            </div>
        </div>
    </div>
    <input type="hidden" value="{{ route('head_office.search_email') }}" id="search_email">
    <input type="hidden" value="{{ csrf_token() }}" id="_token">
    <form method="post" action="{{ route('head_office.head_office_users.block_user_save') }}" class="cm_task_form">
        @csrf
        <div class="modal fade" id="add_block_users">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title text-info w-100">Add Users</h4>
                        <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="groups organisation-structure-add-content">
                            <div class="inline-block">
                                <label class="inputGroup">Select Users
                                    <select name="users[]" multiple class="select2">
                                        @foreach ($head_office->users->where('is_blocked', false) as $hou)
                                        @php
                                            $profile = $hou->get_permissions();
                                        @endphp
                                        @if ($hou->user->id != $logedUser->id)
                                            @if ($logged_profile->super_access || $logged_profile->id == $profile->id)
                                                <option value="{{ $hou->id }}"> {{ $hou->user->name }} | {{ $hou->user->email }} </option>
                                            @endif
                                        @endif
                                    @endforeach                                    
                                    </select>
                                </label>
                            </div>
                            <div class="mb-3 w-100">
                                <label for="comment" class="form-label">Comment (optional)</label>
                                <input type="text" class="form-control border w-100" name="comment">
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
    @include('head_office.my_organisation.add_location_user', [
        'key' => null,
        'location' => null,
        'groups' => $groups,
        'locations' => $locations,
        'location_ids' => null,
        'group_ids' => null,
    ])
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/alertify.min.css') }}">
    <style>
        .select2-container {
            z-index: 10000 !important;

        }

        .select2-dropdown {
            top: -21px !important;
        }

        #session-dataTable_filter:after,
        .dt-search:after {
            left: 10px !important;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('js/alertify.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            loadActiveTab();
            changeTabUrl('ApprovedUser')

            const table = document.querySelector('#blockUser-table');
            const dataTable = new DataTable(table, {
                paging: false,
                info: false,
                language: {
                    search: ""
                },
                columnDefs: [{
                    select: 'multi',
                    targets: 0,
                    searchable: false,
                    orderable: false,
                    className: '',
                    render: function(data, type, full, meta) {
                        return '<input type="checkbox" name="id[]" value="' + escapeHtml(data) +
                            '">';
                    }
                }]
            });


            $('#dataTable-select-all').on('click', function() {
                // Get all rows with search applied
                var rows = dataTable.rows({
                    'search': 'applied'
                }).nodes();
                // Check/uncheck checkboxes for all rows in the table
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });


            // Function to escape HTML characters
            function escapeHtml(html) {
                var div = document.createElement('div');
                div.appendChild(document.createTextNode(html));
                return div.innerHTML;
            }

        });

        function loadActiveTab(tab = null) {
            if (tab == null) {
                tab = window.location.hash;
            }
            console.log($('.nav > li > a[data-bs-target="' + tab + '"]'));
            $('.nav > li > a[data-bs-target="' + tab + '"]').tab('show');
        }
        window.addEventListener('DOMContentLoaded', (event) => {
            $(document).ready(function() {
                $('.select2').select2();

            });
        });

        var data = ["data1", "data2", "data3"];

        // Transform array into { id, text }
        var select2data = $.map(data, function(obj) {
            return {
                id: obj.substr(4),
                text: obj
            };
        });
        console.log(select2data);
        var data = [{
            id: 1,
            text: "test 123345"
        }];

        console.log(data);
        // Initalise select2 with updated data
        $('.group_1007').select2({
            data: data,
        });

        function searchEmail(element) {
            val = $(element).parent().find('input[type=text]').val().trim();
            if (val) {
                var route = $("#search_email").val();
                var _token = $("#_token").val();
                data = {
                    email: val,
                    _token: _token
                };

                $.post(route, data)
                    .then(function(response) {
                        if (response.result) {
                            if (response.locations.length === 0 && response.groups.length === 0)
                                $(element).parent().parent().find('.invalid-feedback').show();
                            $('.general_location_select').val(response.locations).select2();
                            $('.general_group_select').val(response.groups).select2();
                        }
                    })
            }
        }

        function updateLink(id, element) {
            var value = $(element).val();
            var _token = "{{ csrf_token() }}";
            var data = {
                id: id,
                value: value,
                _token: _token
            }
            var route = "{{ route('headoffice.user_block.update_comment') }}";
            $.post(route, data)
                .then(function(response) {
                    if (response) {
                        alertify.success("Link Updated!");
                    }
                    s
                })
                .catch(function(error) {
                    console.log(error);
                })
        }
    </script>
@endsection
