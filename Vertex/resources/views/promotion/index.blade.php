@extends('layouts.admin.master')
@section('content')
    <div class="Datatable-content-area mt-2 dataTable-section">
        @if (session('error'))
            <div class="alert alert_vt" id="alertID">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div class="alert alert-danger small " style="max-width:100%;">{{ session('error') }}</div>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert_vt" id="alertID">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div class="alert alert-success small " style="max-width:100%;">{{ session('success') }}</div>
            </div>
        @endif
        <div class="container-fluid">
            <div class='row pt-2'>
                {{-- <div class="col-lg-4 col-md-4 mb-2" style="padding-left:2px !important;padding-right:2px !important;">
                </div>
                <div class="col-lg-2 col-md-2 px-1 mb-2">
                    <div class="d-flex justify-content-end">
                        <div>
                            @if ($user->haspermission(['promotion-all','promotion-write']))
                            <a href="{{ url('employee/add-promotion') }}">
                                <button name="submit" type="submit" class="page-btn">Add Promotion</button>
                            </a>
                            @endif
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="col-lg-3 col-md-3 mb-2" style="padding-left:2px !important;padding-right:2px !important;">
                    <form action="{{ route('promotion.search.branch') }}" method="get" id="myForm">
                        <div class="form-group position-relative caret-holder px-1">
                            <select title="Branch" name="selectBranch" id="selectBranch" required class="form-control"
                                style="appearance: none;">
                                <option disabled selected>Select Branch</option>
                                <option value="all" {{ $selectBranch == 'all' ? 'selected' : '' }}>All</option>
                                @forelse ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ $selectBranch == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->branch_name }}</option>
                                @empty
                                    <option>No Record Found</option>
                                @endforelse
                            </select>
                            <i class="awesom-icon icon-down-dir icon-color"></i>
                        </div>
                    </form>
                </div> --}}
            </div>
            <div class="table-responsive">
                <table id="promotionTable"
                    class="table table-bordered table-striped table-nowrap table-centered table-atten-sheet m-0">
                    <thead>
                        <tr>
                            <th>Sr</th>
                            <th>Emp ID</th>
                            <th>Name</th>
                            <th>Promotion From</th>
                            <th>Promotion To</th>
                            <th>Promotion Date</th>
                            <th>Office Location</th>
                            <th>Status</th>
                            @if ($user->haspermission(['promotion-all','promotion-write','promotion-delete']))
                            <th></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="Listing_vt">
                        @foreach ($employees as $key => $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>
                                    @php
                                        if ($item->employee_detail ? $item->employee_detail->emp_image : '') {
                                            $imagePath = public_path($item->employee_detail->emp_image);
                                        
                                            if (File::exists($imagePath)) {
                                                $item->emp_image = asset($item->employee_detail->emp_image);
                                            } else {
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
                                            <td class="border-0 p-0" style="border: 0 !important;"><img class='table-img_vt'
                                                    src="{{ asset($item->emp_image) }}"></td>
                                            <td class="border-0 p-0" style="border: 0 !important;">
                                                {{ $item->employee_detail ? $item->employee_detail->emp_id : '' }}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td><a
                                    href="{{ url('/employee/directory/employee-profile/' . base64_encode($item->employee_detail ? $item->employee_detail->id : '')) }}">{{ ($item->employee_detail ?  mb_convert_case($item->employee_detail->emp_name, MB_CASE_TITLE, 'UTF-8') : '') }}
                                </a></td>
                                @if (isset($item->preDesignation->name))
                                    <td>{{ $item->preDesignation->name }}</td>
                                @else
                                    <td>N/A</td>
                                @endif
                                <td>{{ $item->designation->name }}</td>
                                <td>{{ date('d-m-Y', strtotime($item->from_date)) }}</td>
                                <td>{{ $item->branch ? $item->branch->branch_name : '' }}</td>
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
                                                <a onclick="changeStatus({{ $item->id }},'1')" class="dropdown-item btn-dropdown-fs" href="#" data-bs-toggle="modal" data-bs-target="#approve_leave">
                                                    <i class="fa-solid fa-circle-dot text-success"></i> Approved
                                                </a>
                                                @elseif($item->is_approved == '1')
                                                <a onclick="changeStatus({{ $item->id }},'0')" class="dropdown-item btn-dropdown-fs" href="#"><i class="fa-solid fa-circle-dot text-danger"></i> Declined</a>
                                                @elseif($item->is_approved == null)
                                                <a onclick="changeStatus({{ $item->id }},'1')" class="dropdown-item btn-dropdown-fs" href="#" data-bs-toggle="modal" data-bs-target="#approve_leave">
                                                    <i class="fa-solid fa-circle-dot text-success"></i> Approved
                                                </a>
                                                <a onclick="changeStatus({{ $item->id }},'0')" class="dropdown-item btn-dropdown-fs" href="#"><i class="fa-solid fa-circle-dot text-danger"></i> Declined</a>
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
                            @if ($user->haspermission(['promotion-all','promotion-write','promotion-delete']))
                                <td>
                                    <div class="btn-group dropdown-btn-group dropdown">
                                        <button type="button" class="active-link_vt dropdown-toggle" data-toggle="dropdown"
                                            aria-expanded="false">Action <i class="fontello icon-down-dir icon-color" style="color:#ffffff;"></i></button>
                                            
                                        <ul class="dropdown-menu form-action-menu" style="">
                                            @if ($user->haspermission(['promotion-all','promotion-write']))
                                            <li class="checkbox-row pt-1 pl-1 hover-option_vt">
                                                <a href="{{ route('edit.promotion', ['id' => $item->id]) }}"
                                                    class="action-content_vt">
                                                    <label for="toggle-tech-companies-1-col-2"
                                                        class="action_option">Edit</label>
                                                </a>
                                            </li>
                                            @endif
                                            @if ($user->haspermission(['promotion-all','promotion-delete']))
                                            <li class="checkbox-row pt-1 pl-1 hover-option_vt">
                                                <a onclick="delEmp('{{ route('delete.promotion', [$item->id]) }}')"
                                                    class="action-content_vt">
                                                    <label for="toggle-tech-companies-1-col-2"
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
        <script type="text/javascript">
            $(document).ready(function() {
                var customButton = `
                <div class="col-lg-2 col-md-2 px-1 mb-2">
                    <div class="d-flex justify-content-center">
                        <div>
                            @if ($user->haspermission(['promotion-all','promotion-write']))
                            <a href="{{ url('employee/add-promotion') }}">
                                <button name="submit" type="submit" class="page-btn">Add Promotion</button>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                `;

                $('#promotionTable').DataTable({
                    dom: '<row"d-flex justify-content-between"lBf>rtip',
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
                            text: '<img src="' + "{{ asset('assets/images/pdf.png') }}" + '" />',
                            exportOptions: {
                                columns: ':not(:last-child):not(:last-child-1)'
                            }
                        },
                    ]
                });
                var selectFormHTML = `
                <div class="col-lg-3 col-md-3 mb-2" style="padding-left:2px !important;padding-right:2px !important;">
                    <form action="{{ route('promotion.search.branch') }}" method="get" id="myForm">
                        <div class="form-group position-relative caret-holder px-1">
                            <select title="Branch" name="selectBranch" id="selectBranch" required class="form-control"
                                style="appearance: none;">
                                <option disabled selected>Select Location</option>
                                <option value="all" {{ $selectBranch == 'all' ? 'selected' : '' }}>All</option>
                                @forelse ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ $selectBranch == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->branch_name }}</option>
                                @empty
                                    <option>No Record Found</option>
                                @endforelse
                            </select>
                            <i class="awesom-icon icon-down-dir icon-color"></i>
                        </div>
                    </form>
                </div>
                `;

                $('#promotionTable_filter').before(selectFormHTML);
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
            function changeStatus(id, status) {
                var statusText = '';
                if (status == '0') {
                    statusText = 'decline';
                } else if (status == '1') {
                    statusText = 'approve';
                } else if (status == '2') {
                    statusText = 'disapprove';
                }

                Swal.fire({
                    title: 'Confirm',
                    text: 'Are you sure you want to ' + statusText + ' Promotion? ',
                    iconHtml: '<img src="{{ asset('assets/images/delete-alert.png') }}">',
                    showCancelButton: true,
                    reverseButtons: true,
                    confirmButtonText: 'Yes',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: 'get',
                            dataType: 'json',
                            url: '{{ route('change.promotion.status') }}',
                            data: {
                                id: id,
                                status: status
                            },
                            success: function(response) {
                                console.log(response);
                                window.location.reload();
                            }
                        });
                    }
                })
            }

            $(document).ready(function() {
                $('#dateInput').change(function() {
                    $('#dateSubmit').submit();
                });
                // Get the current month and year
                var currentDate = new Date();

                // Set the maximum date for the datepicker
                $('#dateInput2').datepicker({
                    format: "dd-mm-yyyy",
                    maxDate: new Date()
                });

                $('#selectBranch').change(function() {
                    $('#myForm').submit();
                });

                // Set the maximum date for the datepicker
                $('#dateInput1').datepicker({
                    format: "dd-mm-yyyy",
                    maxDate: new Date()
                });
            });

            setTimeout(function() {
                $('#alertID').hide('slow')
            }, 3000);

            // function searchData() {
            //     var input = document.getElementById('searchID').value;
            //     var selectBranch = document.getElementById('selectBranch').value;
            //     let type = '';
            //     $.ajax({
            //         url: '{{ route('promotion.search') }}',
            //         type: 'get',
            //         data: {
            //             'searchValue': input,
            //             'selectBranch': selectBranch,
            //         },
            //         dataType: 'json',
            //         success: function(response) {
            //             if (response['success'] == true) {
            //                 $("#promotionTable tbody").empty();
            //                 var result = response.data;
            //                 var id = 0;
            //                 for (var i = 0; i < result.length; i++) {
            //                     var url = '{{ url('edit-promotion/' . 'id') }}';
            //                     edit = url.replace('id', result[i].id);
            //                     var url = '{{ url('delete-promotion/' . 'id') }}';
            //                     deleter = url.replace('id', result[i].id);
            //                     var approval_status = result[i].is_approved == '1' ? 'Approved' :
            //                     result[i].is_approved == '0' ? 'Declined' : 'Pending';
            //                     // var designationName = result[i].desgn.name ? result[i].desgn.name : 'N/A'; 
            //                     var input = '';
            //                     input += '<tr>';
            //                     input += ' <td>' + (i + 1) + '</td>';
            //                     input += ' <td>' + result[i].emp_id + '</td>';
            //                     input += ' <td>' + result[i].emp_name + '</td>';
            //                     input += ' <td>' + (result[i].previous_desgnation ? (result[i].previous_desgnation.name ? result[i].previous_desgnation.name : 'N/A') : 'N/A') + '</td>';
            //                     input += ' <td>' + result[i].new_designation.name + '</td>';
            //                     input += ' <td>' + result[i].from_date + '</td>';
            //                     input += ' <td>' + result[i].branch_name + '</td>';
            //                     input += ' <td>' + approval_status + '</td>';
            //                     input += '<td>' +
            //                         '<div class="dropdown dropdown-btn-group btn-group action-label">' +
            //                 '<a class="btn btn-white btn-sm btn-rounded dropdown-toggle btn-dropdown-fs" href="#" data-toggle="dropdown" aria-expanded="false">' +
            //                 (result[i].is_approved == '1' ? '<i class="fa-solid fa-circle-dot text-success"></i> Approved' :
            //                     result[i].is_approved == '0' ? '<i class="fa-solid fa-circle-dot text-danger"></i> Declined' :
            //                     '<i class="fa-solid fa-circle-dot text-primary"></i> Pending') +
            //                 '<i class="fontello icon-down-dir icon-color"></i>' +
            //                 '</a>' +
            //                 '<ul class="dropdown-menu dropdown-menu-right" style="position: absolute; margin: 0px; transform: translate(3px, -33px);">' +
            //                 (result[i].is_approved == '0' ?
            //                     '<li><a onclick="changeStatus(' + result[i].id + ', 1)" class="dropdown-item btn-dropdown-fs" href="#" data-bs-toggle="modal" data-bs-target="#approve_leave"><i class="fa-solid fa-circle-dot text-success"></i> Approved</a></li>' :
            //                     result[i].is_approved == '1' ?
            //                     '<li><a onclick="changeStatus(' + result[i].id + ', 0)" class="dropdown-item btn-dropdown-fs" href="#"><i class="fa-solid fa-circle-dot text-danger"></i> Declined</a></li>' :
            //                     '<li><a onclick="changeStatus(' + result[i].id + ', 1)" class="dropdown-item btn-dropdown-fs" href="#" data-bs-toggle="modal" data-bs-target="#approve_leave"><i class="fa-solid fa-circle-dot text-success"></i> Approved</a></li>' +
            //                     '<li><a onclick="changeStatus(' + result[i].id + ', 0)" class="dropdown-item btn-dropdown-fs" href="#"><i class="fa-solid fa-circle-dot text-danger"></i> Declined</a></li>') +
            //                 '</ul>' +
            //                 '</div>' +
            //                 '</td>';
            //                     input += '<td>' +
            //                         '<div class="btn-group dropdown-btn-group pull-right">' +
            //                         '<button type="button" class="active-link_vt dropdown-toggle" data-toggle="dropdown" aria-expanded="false">' +
            //                         'Action <i class="fontello icon-down-dir icon-color" style="color:#ffffff;"></i>' +
            //                         '</button>' +
            //                         '<ul class="dropdown-menu form-action-menu" style="">' +
            //                         '<li class="checkbox-row hover-option_vt">' +
            //                         '<a href="{{ url('edit-promotion/') }}/' + result[i].id + '" class="action-content_vt">' +
            //                         '<label for="toggle-tech-companies-1-col-3" class="action_option">Edit</label>' +
            //                         '</a>' +
            //                         '</li>' +
            //                         '<li class="checkbox-row hover-option_vt">' +
            //                         '<a onclick="delEmp(\'' + deleter + '\')" class="action-content_vt">' +
            //                         '<label for="toggle-tech-companies-1-col-4" class="action_option">Delete</label>' +
            //                         '</a>' +
            //                         '</li>' +
            //                         '</ul>' +
            //                         '</div>' +
            //                         '</td>'+
            //                         '</tr>';

            //                     $("#promotionTable tbody").append(input);
            //                 }
            //             } else {
            //                 $('#promotionTable tbody').empty()
            //                 let message = response.data;
            //                 let input = '<tr class="text-center">';
            //                 input += '<td colspan="9">' + [message] + '</td>';
            //                 input += '</tr>';
            //                 $('#promotionTable tbody').append(input);
            //             }
            //         }
            //     });
            // }

        </script>
    @endsection
