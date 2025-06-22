@extends('layouts.admin.master')
@section('content')
    <style>
        .pagination {
            display: flex;
            list-style-type: none;
            padding: 0;
            justify-content: center;
        }
    </style>

    <div class="Datatable-content-area mt-2 dataTable-section">
        @if (session('error'))
            <div class="alert alert_vt" id="alertID">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div class="alert alert-danger small " style="max-width:100%;">{{ session('error') }}</div>
            </div>
        @endif
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert_vt" id="alertID">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <div class="alert alert-success small " style="max-width:100%;">{{ session('success') }}</div>
                </div>
            @endif
            <div class='row pt-2'>
                <div class="col-lg-4 col-md-4 mb-2"
                    style="padding-left:2px !important;padding-right:2px !important;">
                </div>
                {{-- <div class="col-lg-2 col-md-2 px-1 mb-2">
                    <div class="d-flex justify-content-end">
                        <div>
                            @if ($user->haspermission(['leave-request-all','leave-request-write']))
                            <a href="{{route('add.leave.request')}}">
                                <button class="page-btn">Add Leave Request</button>
                            </a>
                            @endif
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="col-lg-3 col-md-3 mb-2"
                    style="padding-left:2px !important;padding-right:2px !important;">
                    <form method="get" action="{{route('leave.request')}}" id="dateSubmit">
                        <div class="form-group position-relative caret-holder px-1">
                            <select name="selectBranch" id="selectBranch"
                            required class="form-control" style="appearance: none;">
                            <option disable>Select Branch</option>
                            <option value="all" {{$selectedBranch == 'all'?'selected':''}}>All</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{$selectedBranch == $branch->id?'selected':''}}>{{ ucwords($branch->branch_name) }}
                                </option>
                            @endforeach
                        </select>
                            <i class="awesom-icon icon-down-dir icon-color"></i>
                        </div>
                    </form>
                </div> --}}
            </div>
            <div class="table-responsive">
                <table id="table1" class="table table-bordered table-striped table-nowrap table-centered table-atten-sheet m-0">
                    <thead>
                        <tr>
                            <th>Sr</th>
                            <th>Emp ID</th>
                            <th>Name</th>
                            <th>Leave Type</th>
                            {{-- <th>Remaining <br>Leaves</th> --}}
                            <th>From</th>
                            <th>To</th>
                            <th>Requested <br>Days</th>
                            <th>Approved <br>Days</th>
                            <th>Office <br>Location</th>
                            <th>Approved <br>By</th>
                            <th>Status</th>
                            @if ($user->haspermission(['leave-request-all','leave-request-write','leave-request-delete']))
                            <th></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="Listing_vt">
                        @foreach ($leaves as $key=> $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    @php
                                        if ($item->emp_image) {
                                            $imagePath = public_path($item->emp_image);

                                            if (File::exists($imagePath)) {
                                                $item->emp_image = asset($item->emp_image);
                                            } else {
                                                // If the image file doesn't exist, set a default image based on gender
                                                if ($item->emp_gender == 'F') {
                                                    $item->emp_image = asset('assets/images/female.png');
                                                } else {
                                                    $item->emp_image = asset('assets/images/male.png');
                                                }
                                            }
                                        } else {
                                            // If emp_image is empty, set a default image based on gender
                                            if ($item->emp_gender == 'F') {
                                                $item->emp_image = asset('assets/images/female.png');
                                            } else {
                                                $item->emp_image = asset('assets/images/male.png');
                                            }
                                        }
                                    @endphp
                                    <table>
                                        <tr style="background:transparent;">
                                            <td class="border-0 p-0" style="border: 0 !important;"><img class='table-img_vt' src="{{ asset($item->emp_image) }}"></td>
                                            <td class="border-0 p-0" style="border: 0 !important;">{{ $item->emp_id }}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td><a
                                    href="{{ url('/employee/directory/employee-profile/' . base64_encode($item->employee_id)) }}">{{ mb_convert_case($item->emp_name, MB_CASE_TITLE, 'UTF-8') }}
                                </a></td>
                                <td>{{ ucwords($item->types) }}</td>
                                {{-- <td>{{ $item->remaining }}</td> --}}
                                <td>{{ date('d-m-Y',strtotime($item->from_date)) }}</td>
                                <td>{{ date('d-m-Y',strtotime($item->to_date)) }}</td>
                                <td>
                                    @if($item->requested_days > 1)
                                        {{$item->requested_days}} Days
                                    @elseif($item->requested_days == 1)
                                        {{$item->requested_days}} Day
                                    @elseif($item->requested_days == null)
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($item->approved_days > 1)
                                        {{$item->approved_days}} Days
                                    @elseif($item->approved_days == 1)
                                        {{$item->approved_days}} Day
                                    @elseif($item->approved_days == null)
                                        -
                                    @endif
                                </td>
                                <td>{{ $item->branch_name }}</td>
                                <td>{{ $item->roles ? $item->roles->role_name : ''}}</td>
                                <td>
                                    @php $role_id = auth()->user()->role_id; @endphp
                                    @if ($role_id == '1' || $user->haspermission(['status-update-all']))
                                        <div class="dropdown dropdown-btn-group btn-group action-label">
                                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle btn-dropdown-fs" href="#" data-toggle="dropdown" aria-expanded="false">
                                                @if($item->is_approved == '1')
                                                    <i class="fa-solid fa-circle-dot text-success"></i> Approved
                                                @elseif($item->is_approved == '0')
                                                    <i class="fa-solid fa-circle-dot text-danger"></i> Declined
                                                @elseif($item->is_approved == null)
                                                    <i class="fa-solid fa-circle-dot text-primary"></i> Pending
                                                @endif
                                                <i class="fontello icon-down-dir icon-color"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right" style="position: absolute; margin: 0px; transform: translate(3px, -33px);">
                                                @if($item->is_approved == '0')
                                                <a href="{{ url('update-leave-status/'.$item->id.'/1') }}" class="dropdown-item btn-dropdown-fs" data-bs-toggle="modal" data-bs-target="#approve_leave">
                                                    <i class="fa-solid fa-circle-dot text-success"></i> Approved
                                                </a>
                                                @elseif($item->is_approved == '1')
                                                <a href="{{ url('update-leave-status/'.$item->id.'/0') }}" class="dropdown-item btn-dropdown-fs"><i class="fa-solid fa-circle-dot text-danger"></i> Declined</a>
                                                @elseif($item->is_approved == null)
                                                <a href="{{ url('update-leave-status/'.$item->id.'/1') }}" class="dropdown-item btn-dropdown-fs" data-bs-toggle="modal" data-bs-target="#approve_leave">
                                                    <i class="fa-solid fa-circle-dot text-success"></i> Approved
                                                </a>
                                                <a href="{{ url('update-leave-status/'.$item->id.'/0') }}" class="dropdown-item btn-dropdown-fs"><i class="fa-solid fa-circle-dot text-danger"></i> Declined</a>
                                                @endif
                                            </ul>
                                        </div>
                                    @else
                                        <a style="pointer-events: none;" class="btn btn-white btn-sm btn-rounded dropdown-toggle btn-dropdown-fs" href="#" data-toggle="dropdown" aria-expanded="false">
                                            @if($item->is_approved == '1')
                                                <i class="fa-solid fa-circle-dot text-success"></i> Approved
                                            @else
                                                <i class="fa-solid fa-circle-dot text-danger"></i> Declined
                                            @endif
                                        </a>
                                    @endif
                                </td>
                                @if ($user->haspermission(['leave-request-all','leave-request-write','leave-request-delete']))
                                    <td>
                                        <div class="btn-group dropdown-btn-group dropdown">
                                            <button type="button" class="active-link_vt dropdown-toggle" data-toggle="dropdown"
                                                aria-expanded="false">Action  <i class="fontello icon-down-dir icon-color" style="color:#ffffff;"></i></button>
                                            <ul class="dropdown-menu form-action-menu" style="">
                                                @if ($user->haspermission(['leave-request-all','leave-request-write']))
                                                <li class="checkbox-row pt-1 pl-1 hover-option_vt">
                                                    <a href="{{ url('/leave-request/edit-leave/'.base64_encode($item->id)) }}" class="action-content_vt">
                                                        <label for="toggle-tech-companies-1-col-1"
                                                            class="action_option">Edit</label>
                                                    </a>
                                                </li>
                                                @endif
                                                @if ($user->haspermission(['leave-request-all','leave-request-delete']))
                                                <li class="checkbox-row pt-1 pl-1 hover-option_vt">
                                                    <a onclick="delEmp('{{ route('delete.leave.request', [$item->id]) }}')" class="action-content_vt">
                                                        <label for="toggle-tech-companies-1-col-1"
                                                            class="action_option">Delete</label>
                                                    </a>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
<script>
          $(document).ready(function() {
            var customButton = `
            <div class="col-lg-2 col-md-2 px-1 mb-2">
                    <div class="d-flex justify-content-center">
                        <div>
                            @if ($user->haspermission(['leave-request-all','leave-request-write']))
                            <a href="{{route('add.leave.request')}}">
                                <button class="page-btn">Add Leave Request</button>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            `;

            // DataTable initialization
            var table = $('#table1').DataTable({
                dom: '<"d-flex justify-content-between"lBf>rtip',
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search..."
                },
                buttons: [
                    {
                        extend: 'csvHtml5',
                        text: '<img src="{{ asset('assets/images/csv.png') }}" />',
                        exportOptions: {
                            columns: ':not(:last-child):not(:last-child-1)'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<img src="{{ asset('assets/images/print.png') }}" />',
                        exportOptions: {
                            columns: ':not(:last-child):not(:last-child-1)'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<img src="{{ asset('assets/images/pdf.png') }}" />',
                        exportOptions: {
                            columns: ':not(:last-child):not(:last-child-1)'
                        }
                    },
                ]
            });
            // Append custom select and form outside the DataTable
            var selectFormHTML = `
            <div class="col-lg-3 col-md-3 mb-2"
                    style="padding-left:2px !important;padding-right:2px !important;">
                    <form method="get" action="{{route('leave.request')}}" id="dateSubmit">
                        <div class="form-group position-relative caret-holder px-1">
                            <select name="selectBranch" id="selectBranch"
                            required class="form-control" style="appearance: none;">
                            <option disable>Select Location</option>
                            <option value="all" {{$selectedBranch == 'all'?'selected':''}}>All</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{$selectedBranch == $branch->id?'selected':''}}>{{ ucwords($branch->branch_name) }}
                                </option>
                            @endforeach
                        </select>
                            <i class="awesom-icon icon-down-dir icon-color"></i>
                        </div>
                    </form>
                </div>
            `;

            $('#table1_filter').before(selectFormHTML);
            $('.dt-buttons').after(customButton);
        });
    function delEmp(url) {
                Swal.fire({
                    title: 'Delete',
                    text: 'Are you sure you want to delete? ',
                    iconHtml: '<img src="{{ asset('assets/images/delete-alert.png') }}">',
                    // showDenyButton: true,
                    showCancelButton: true,
                    // denyButtonText: `Cancelss`,
                    reverseButtons: true,
                    confirmButtonText: 'Delete',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                })
            }
    $(document).ready(function() {
        $('#selectBranch').change(function() {
            $('#dateSubmit').submit();
        });

        $('#selectBranch').change(function() {
            $('#myForm').submit();
        });

        // Get the current month and year
        var currentDate = new Date();

        // Set the maximum date for the datepicker
        $('#dateInput2').datepicker({
            format: "dd-mm-yyyy",
            maxDate: new Date()
        });

        // Set the maximum date for the datepicker
        $('#dateInput1').datepicker({
            format: "dd-mm-yyyy",
            maxDate: new Date()
        });

    
    });

    function getRemaingLeaves(approvedDays, annualDays) {
        console.log(approvedDays);
    }


    setTimeout(function() {
        $('#alertID').hide('slow')
    }, 3000);

    // function searchData() {
    //     var input = document.getElementById('searchID').value;
    //     var selectBranch = document.getElementById('selectBranch').value;
    //     let type = '';
    //     $.ajax({
    //         url: '{{ route('leave.search') }}',
    //         type: 'get',
    //         data: {
    //             'searchValue': input,
    //             'selectBranch': selectBranch,
    //         },
    //         dataType: 'json',
    //         success: function(response) {
    //             if (response['success'] == true) {
    //                 $("#table1 tbody").empty();
    //                 var result = response.data;
    //                 var id = 0;
    //                 for (var i = 0; i < result.length; i++) {
    //                     var url = '{{ url('approve-leave/' . 'id') }}';
    //                     approve = url.replace('id', result[i].id);
    //                     var url = '{{ url('decline-leave/' . 'id') }}';
    //                     decline = url.replace('id', result[i].id);
    //                     var input = '';
    //                     input += '<tr>';
    //                     input += ' <td>' + (i + 1) + '</td>';
    //                     input += ' <td>' + result[i].emp_id + '</td>';
    //                     input += ' <td>' + result[i].emp_name + '</td>';
    //                     input += ' <td>' + result[i].leave_type + '</td>';
    //                     input += ' <td>' + result[i].from_date + '</td>';
    //                     input += ' <td>' + result[i].to_date + '</td>';
    //                     input += ' <td>' + result[i].branch_name + '</td>';
    //                     input +=
    //                         '<td> <div class="btn-group dropdown-btn-group pull-right"> <button type="button" class="active-link_vt dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button> <ul class="dropdown-menu form-action-menu" style=""> <li class="checkbox-row hover-option_vt"> <a href="' +
    //                     approve +
    //                     '" class="action-content_vt"><label for="toggle-tech-companies-1-col-1" class="action_option">Approve</label></a></li> <li class="checkbox-row hover-option_vt"> <a href="' +
    //                     decline +
    //                     '" class="action-content_vt" ><label for="toggle-tech-companies-1-col-2" class="action_option">Decline</label></a></li> </ul> </div> </td></tr>';
    //                         input += '</tr>';


    //                     $("#table1 tbody").append(input);
    //                 }
    //             } else {
    //                 $('#table1 tbody').empty()
    //                 let message = response.data;
    //                 let input = '<tr class="text-center">';
    //                 input += '<td colspan="12">' + [message] + '</td>';
    //                 input += '</tr>';
    //                 $('#table1 tbody').append(input);
    //             }
    //         }
    //     });
    // }
</script>
@endsection
