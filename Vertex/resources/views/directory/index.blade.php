@extends('layouts.admin.master')
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert_vt" id="alertID">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div class="alert alert-success small " style="max-width:100%;">{{ session('success') }}</div>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert_vt" id="alertID">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div class="alert alert-danger small " style="max-width:100%;">{{ session('error') }}</div>
            </div>
        @endif
        <div class="row">
            <div class="col-xl-12 mt-4">
                <div class="card-box border-1">
                    <div class="row mb-2">
                        <div class="col-lg-2">
                            <h4 class="header-title m-0 pt-2">Employee List
                                <span class="light-blue ml-1 p-1 light-blue-bg rounded-5">{{ count($employees) }}</span>

                            </h4>
                        </div>
                        <div class="col-lg-10 pt-xs-15">
                            <div class="row justify-content-end">
                                <div class="col-lg-2 mb-2" style="padding-right:3px !important;padding:left:3px !important;">
                                    @if ($user->haspermission(['directory-all','directory-write']))
                                        <div class="">
                                            <a href="{{ route('add.employee') }}" class="page-btn float-right"> Add Employee</a>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-3" style="padding-right:3px !important;padding:left:3px !important;">
                                    <form method="GET" id="myForm" action="{{ url('/employee/directory') }}">
                                        <input type="hidden" value="{{$selectedStatus}}" name="selectStatus">
                                    <div class="form-group position-relative caret-holder">
                                        <select id="selectBranch" name="branch_id"  required class="form-control m-b" style="appearance: none;">
                                            <option  disable>Select Location</option>
                                            <option value="all" {{($selectedBranch == 'all'?'selected':'')}}>All</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{$branch->id}}" {{old('branch_id',$selectedBranch == $branch->id)?'selected':''}}>{{ucwords($branch->branch_name)}}</option>
                                            @endforeach
                                        </select>
                                        <i class="awesom-icon icon-down-dir icon-color"></i>
                                    </div>
                                </form>
                                </div>
                                <div class="col-lg-2" style="padding-right:3px !important;padding:left:3px !important;">
                                    <form method="get" id="searchByStatus" action="{{  url('/employee/directory') }}">
                                        <input type="hidden" name="branch_id" value="{{$selectedBranch}}">
                                        <div class="form-group position-relative caret-holder">
                                            <select id="selectStatus" name="selectStatus"  required class="form-control m-b" style="appearance: none;">
                                                <option  disable>Select Status</option>
                                                <option value="1" {{($selectedStatus == '1'?'selected':'')}}>Approved</option>
                                                <option value="2" {{($selectedStatus == '2'?'selected':'')}}>Declined</option>
                                                <option value="3" {{($selectedStatus == '3'?'selected':'')}}>Resigned</option>
                                                <option value="4" {{($selectedStatus == '4'?'selected':'')}}>Terminated</option>
                                                <option value="0" {{($selectedStatus == '0'?'selected':'')}}>Pending</option>
                                                <option value="5" {{($selectedStatus == '5'?'selected':'')}}>Deleted</option>
                                            </select>
                                            <i class="awesom-icon icon-down-dir icon-color"></i>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-3" style="padding-right:3px !important;padding:left:3px !important;">
                                    <div class="position-relative search-icon_vt">
                                        <input type="text" class="form-control" onkeyup="searchData()" name="search_input"
                                            id="searchID" value="" placeholder="Search by Name or ID">
                                        <a> <i class="fontello icon-search"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="table1" class="table table-striped table-bordered table-nowrap table-hover table-centered m-0">
                            <thead class="table-head border-top border-bottom">
                                <tr>
                                    <th>Sr</th>
                                    <th>Emp ID</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Join Date</th>
                                    <th>Leaves</th>
                                    <th>Office Location</th>
                                    <th>Status</th>
                                    @if ($user->haspermission(['directory-all','directory-write','directory-delete']))
                                        <th></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="Listing_vt">
                                <?php $iteration = 1; ?>
                                @forelse($employees as $key => $item)
                                    <tr>
                                        <td>{{ $iteration }}</td>
                                        <td>
                                            @php
                                                if ($item->emp_image) {
                                                    $imagePath = public_path($item->emp_image);

                                                    if (File::exists($imagePath)) {
                                                        $item->emp_image = asset($item->emp_image);
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
                                                    <td class="border-0 p-0" style="border:0 !important;"><img class='table-img_vt'
                                                            src="{{ asset($item->emp_image) }}"></td>
                                                    <td class="border-0 p-0" style="border:0 !important;">{{ $item->emp_id }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td> <a
                                                href="{{ url('/employee/directory/employee-profile/' . base64_encode($item->id)) }}">{{ mb_convert_case($item->emp_name, MB_CASE_TITLE, 'UTF-8') }}
                                            </a>
                                        </td>
                                        <td>{{ optional($item->department)->name ?? '-' }}</td>
                                        <td>{{ optional($item->designation_name)->name ?? '-' }}</td>
                                        @if ($item->approval != null)
                                            <td> {{ Date('d-m-Y', strtotime($item->approval->joining_date)) }}</td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        <td>
                                            @if($item->approved_leave_days > 1)
                                                {{ $item->approved_leave_days }} Days
                                            @elseif($item->approved_leave_days == 1)
                                                {{ $item->approved_leave_days }} Day
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td> {{ ($item->branch_name?ucwords($item->branch_name):'-') }} </td>
                                        <td>
                                            @php $role_id = auth()->user()->role_id; @endphp
                                            @if ($role_id == '1' || $user->haspermission(['status-update-all']))
                                                <div class="dropdown dropdown-btn-group btn-group action-label">
                                                    <a class="btn btn-white btn-sm btn-rounded dropdown-toggle btn-dropdown-fs" href="#" data-toggle="dropdown" aria-expanded="false">
                                                            @if ($item->status == '0')
                                                                <i class="fa-solid fa-circle-dot text-primary"></i> Pending
                                                            @elseif($item->status == '1')
                                                                <i class="fa-solid fa-circle-dot text-success"></i> Approved
                                                            @elseif($item->status == '2')
                                                                <i class="fa-solid fa-circle-dot text-danger"></i> Declined
                                                            @elseif($item->status == '3')
                                                                <i class="fa-solid fa-circle-dot text-orange"></i> Resigned
                                                            @elseif($item->status == '4')
                                                                <i class="fa-solid fa-circle-dot text-blue"></i> Terminated
                                                            @endif
                                                        <i class="fontello icon-down-dir icon-color"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-right" style="position: absolute; margin: 0px; transform: translate(3px, -33px);">
                                                        <a onclick="changeStatus('approved',{{$item->id}})" class="dropdown-item btn-dropdown-fs" href="#" data-bs-toggle="modal" data-bs-target="#approve_leave">
                                                            <i class="fa-solid fa-circle-dot text-success"></i> Approved
                                                        </a>
                                                        <a onclick="changeStatus('declined',{{$item->id}})" class="dropdown-item btn-dropdown-fs" href="#"><i class="fa-solid fa-circle-dot text-danger"></i> Declined</a>
                                                        <a onclick="changeStatus('resigned',{{$item->id}})" class="dropdown-item btn-dropdown-fs" href="#"><i class="fa-solid fa-circle-dot text-orange"></i> Resigned</a>
                                                        <a onclick="changeStatus('terminate',{{$item->id}})" class="dropdown-item btn-dropdown-fs" href="#"><i class="fa-solid fa-circle-dot text-blue"></i> Terminated</a>
                                                    </ul>
                                                </div>
                                            @else
                                                <a style="pointer-events: none;" class="btn btn-white btn-sm btn-rounded dropdown-toggle btn-dropdown-fs" href="#" data-toggle="dropdown" aria-expanded="false">
                                                    @if ($item->status == '0')
                                                        <i class="fa-solid fa-circle-dot text-primary"></i> Pending
                                                    @elseif($item->status == '1')
                                                        <i class="fa-solid fa-circle-dot text-success"></i> Approved
                                                    @elseif($item->status == '2')
                                                        <i class="fa-solid fa-circle-dot text-danger"></i> Declined
                                                    @elseif($item->status == '3')
                                                        <i class="fa-solid fa-circle-dot text-orange"></i> Resigned
                                                    @elseif($item->status == '4')
                                                        <i class="fa-solid fa-circle-dot text-blue"></i> Terminated
                                                    @endif
                                                    {{-- <i class="fontello icon-down-dir icon-color"></i> --}}
                                                </a>
                                            @endif
                                        </td>
                                        @if ($user->haspermission(['directory-all','directory-write','directory-delete']))
                                        @if($item->is_deleted == '1')
                                        <td>
                                            <div class="btn-group dropdown-btn-group pull-right">
                                                <button type="button" class="active-link_vt dropdown-toggle"
                                                    data-toggle="dropdown" aria-expanded="false">Action <i class="fontello icon-down-dir icon-color" style="color:#ffffff;"></i></button>
                                                <ul class="dropdown-menu form-action-menu" style="position:absolute;">
                                                    @if ($user->haspermission(['directory-all','directory-write']))
                                                    <li class="checkbox-row  hover-option_vt">
                                                        <a href="{{ url('employee/directory/restore-employee/' . base64_encode($item->id)) }}"
                                                            class="action-content_vt"><label
                                                                for="toggle-tech-companies-1-col-1"
                                                                class="action_option">Restore</label></a>
                                                    </li>
                                                    @endif
                                                    @if ($user->haspermission(['directory-all','directory-delete']))
                                                    <li class="checkbox-row hover-option_vt">
                                                        <a onclick="delEmpPer('{{ url('employee/directory/hard/delete/' . base64_encode($item->id)) }}')"
                                                            id="del_employee" class="action-content_vt"><label
                                                                for="toggle-tech-companies-1-col-2"
                                                                class="action_option">Delete</label></a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                        @else
                                        <td>
                                            <div class="btn-group dropdown-btn-group pull-right">
                                                <button type="button" class="active-link_vt dropdown-toggle"
                                                    data-toggle="dropdown" aria-expanded="false">Action <i class="fontello icon-down-dir icon-color" style="color:#ffffff;"></i></button>
                                                <ul class="dropdown-menu form-action-menu" style="position:absolute;">
                                                    @if ($user->haspermission(['directory-all','directory-write']))
                                                    <li class="checkbox-row  hover-option_vt">
                                                        <a href="{{ url('employee/directory/edit-employee/' . base64_encode($item->id)) }}"
                                                            class="action-content_vt"><label
                                                                for="toggle-tech-companies-1-col-1"
                                                                class="action_option">Edit</label></a>
                                                    </li>
                                                    @endif
                                                    @if ($user->haspermission(['directory-all','directory-delete']))
                                                    <li class="checkbox-row hover-option_vt">
                                                        <a onclick="delEmp('{{ url('employee/directory/delete/' . base64_encode($item->id)) }}')"
                                                            id="del_employee" class="action-content_vt"><label
                                                                for="toggle-tech-companies-1-col-2"
                                                                class="action_option">Delete</label></a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                        @endif
                                        @endif
                                        <?php $iteration++ ?>
                                    </tr>
                                @empty
                                    <tr class="text-center">
                                        <td colspan="10">No Record Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="pagination">
            {{ $employees->links() }}
        </div>
    </div>
    </div>
    <script type="text/javascript">
    //  $(document).ready(function() {
    //         var table = $('#table1').DataTable({
    //             dom: '<"d-flex justify-content-between"l>rtip',
    //         });
    //     });
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
        function delEmpPer(url) {
            Swal.fire({
                title: 'Delete',
                text: 'Are you sure you want to delete? Enter your password to confirm:',
                iconHtml: '<img src="{{ asset('assets/images/delete-alert.png') }}">',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonText: 'Delete',
                input: 'password',
                inputAttributes: {
                    required: 'required',
                    autocapitalize: 'off',
                    autocorrect: 'off'
                },
                inputValidator: (value) => {
                    if (!value) {
                        return 'Password is required';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const password = result.value;
                    // Send the password to the controller
                    window.location.href = url + '?password=' + encodeURIComponent(password);
                }
            });
        }
        $(document).ready(function() {
            $('#selectStatus').change(function() {
                $('#searchByStatus').submit();
            });

            $('#selectBranch').change(function() {
                $('#myForm').submit();
            });
        });

        function delUsers(id) {
            var new_url = '{{ url('/employee/directory/delete/' . 'id') }}';
            new_url = new_url.replace('id', id);
            Swal.fire({
                title: 'Delete',
                text: 'Are you sure you want to delete? ',
                iconHtml: '<img src="{{ asset('assets/images/delete-alert.png') }}">',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = new_url;
                }
            })
        }

        setTimeout(function() {
            $('#alertID').hide('slow')
        }, 3000);

        function searchData() {
            var input = document.getElementById('searchID').value;
            var selectBranch = document.getElementById('selectBranch').value;
            var selectStatus = document.getElementById('selectStatus').value;
            let type = '';
            $.ajax({
                url: '{{ route('employee.search') }}',
                type: 'get',
                data: {
                    'emp_name': input,
                    'branch_id': selectBranch,
                    'selectStatus': selectStatus,
                },
                dataType: 'json',
                success: function(response) {
                    if (response['success'] == true) {
                        $("#table1 tbody").empty();

                        var result = response.data.emp;
                        var role_id = response.data.role_id;
                        var id = 0;
                        for (var i = 0; i < result.length; i++) {
                            var url5 = '{{ url('employee/directory/employee-profile/' . 'id') }}';
                            url5 = url5.replace('id', btoa(result[i].id));
                            var url1 = '{{ url('employee/directory/edit-employee/' . 'id') }}';
                            url1 = url1.replace('id', btoa(result[i].id));
                            var url2 = '{{ url('employee/directory/delete/' . 'id') }}';
                            url2 = url2.replace('id', btoa(result[i].id));
                            var url3 = '{{ url('employee/directory/restore-employee/' . 'id') }}';
                            url3 = url3.replace('id', btoa(result[i].id));
                            var url4 = '{{ url('employee/directory/hard/delete/' . 'id') }}';
                            url4 = url4.replace('id', btoa(result[i].id));
                            var input = '';
                            input += '<tr>';
                            input += ' <td>' + (i + 1) + '</td>';
                            input += ' <td>' + result[i].emp_id + '</td>';
                                                        input += ' <td><a href="' + url5 + '">' + result[i].emp_name + '</a></td>';
                            if (result[i].department == "") {
                                input += '<td>-</td>';
                            } else {
                                input += ' <td>' + result[i].department.name + ' Days</td>';
                            }
                            if (result[i].designation_name == "") {
                                input += '<td>-</td>';
                            } else {
                                input += ' <td>' + result[i].designation_name.name + ' Days</td>';
                            }
                            if (result[i].joining_date == "") {
                                input += '<td>-</td>';
                            } else {
                                const d = new Date(result[i].joining_date);
                                input += ' <td>' + d.toLocaleDateString('pt-PT').split("/").join("-") + '</td>';
                            }

                            if (result[i].approved_leave_days == "") {
                                input += '<td>-</td>';
                            } else {
                                input += ' <td>' + result[i].approved_leave_days + ' Days</td>';
                            }
                            if (result[i].branch_name == "") {
                                input += '<td>-</td>';
                            } else {
                                input += ' <td>' + result[i].branch_name + '</td>';
                            }
                            input += '<td>';
                            if (role_id == 1){
                                input += '<div class="dropdown dropdown-btn-group btn-group action-label">';
                                    input += '<a class="btn btn-white btn-sm btn-rounded dropdown-toggle btn-dropdown-fs" href="#" data-toggle="dropdown" aria-expanded="false">';
                                    if (result[i].status == '0'){
                                        input += '<i class="fa-solid fa-circle-dot text-primary"></i> Pending';
                                    } else if (result[i].status == '1'){
                                        input += '<i class="fa-solid fa-circle-dot text-success"></i> Approved';
                                    } else if (result[i].status == '2'){
                                        input += '<i class="fa-solid fa-circle-dot text-danger"></i> Declined';
                                    } else if (result[i].status == '3'){
                                        input += '<i class="fa-solid fa-circle-dot text-orange"></i> Resigned';
                                    } else if (result[i].status == '4'){
                                        input += '<i class="fa-solid fa-circle-dot text-blue"></i> Terminated';
                                    }
                                    input += '<i class="fontello icon-down-dir icon-color"></i>';
                                    input += '</a>';
                                    input += '<ul class="dropdown-menu dropdown-menu-right" style="position: absolute; margin: 0px; transform: translate(3px, -33px);">';
                                    input += '<a onclick="changeStatus(\'approved\', ' + result[i].id + ')" class="dropdown-item btn-dropdown-fs" href="#" data-bs-toggle="modal" data-bs-target="#approve_leave">';
                                    input += '<i class="fa-solid fa-circle-dot text-success"></i> Approved';
                                    input += '</a>';
                                    input += '<a onclick="changeStatus(\'declined\', ' + result[i].id + ')" class="dropdown-item btn-dropdown-fs" href="#"><i class="fa-solid fa-circle-dot text-danger"></i> Declined</a>';
                                    input += '<a onclick="changeStatus(\'resigned\', ' + result[i].id + ')" class="dropdown-item btn-dropdown-fs" href="#"><i class="fa-solid fa-circle-dot text-orange"></i> Resigned</a>';
                                    input += '<a onclick="changeStatus(\'terminate\', ' + result[i].id + ')" class="dropdown-item btn-dropdown-fs" href="#"><i class="fa-solid fa-circle-dot text-blue"></i> Terminated</a>';
                                    input += '</ul>';
                                    input += '</div>';
                            }else{
                                input += '<a style="pointer-events: none;" class="btn btn-white btn-sm btn-rounded dropdown-toggle btn-dropdown-fs" href="#" data-toggle="dropdown" aria-expanded="false">';
                                if (result[i].status == '0'){
                                input += '<i class="fa-solid fa-circle-dot text-primary"></i> Pending';
                                }else if(result[i].status == '1'){
                                input += '<i class="fa-solid fa-circle-dot text-success"></i> Approved';
                                }else if(result[i].status == '2'){
                                input += '<i class="fa-solid fa-circle-dot text-danger"></i> Declined';
                                }else if(result[i].status == '3'){
                                input += '<i class="fa-solid fa-circle-dot text-orange"></i> Resigned';
                                }else if(result[i].status == '4'){
                                input += '<i class="fa-solid fa-circle-dot text-blue"></i> Terminated';
                                }
                                input += '</a>';
                            }
                            input +='</td>';
                            if(result[i].is_deleted == '1'){
                                input +=
                                    '<td> <div class="btn-group dropdown-btn-group pull-right"> <button type="button" class="active-link_vt dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action  </button> <ul class="dropdown-menu form-action-menu" style=""> <li class="checkbox-row  hover-option_vt"> <a href="' +
                                    url3 +
                                    '" class="action-content_vt"><label for="toggle-tech-companies-1-col-1" class="action_option">Restore</label></a>  </li> <li class="checkbox-row hover-option_vt"> <a onclick="delEmpPer(\'' +
                                    url4 + '\')" class="action-content_vt" ><label for="toggle-tech-companies-1-col-2" class="action_option">Delete</label></a> </li> </ul> </div> </td></tr>';
                                input += '</tr>';
                                $("#table1").append(input);
                            }else{
                                input +=
                                    '<td> <div class="btn-group dropdown-btn-group pull-right"> <button type="button" class="active-link_vt dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action  </button> <ul class="dropdown-menu form-action-menu" style=""> <li class="checkbox-row  hover-option_vt"> <a href="' +
                                    url1 +
                                    '" class="action-content_vt"><label for="toggle-tech-companies-1-col-1" class="action_option">Edit</label></a>  </li> <li class="checkbox-row hover-option_vt"> <a onclick="delEmp(\'' +
                                    url2 + '\')" class="action-content_vt" ><label for="toggle-tech-companies-1-col-2" class="action_option">Delete</label></a> </li> </ul> </div> </td></tr>';
                                input += '</tr>';
                                $("#table1").append(input);
                            }
                        }
                    } else {

                        $('#table1 tbody').empty()
                        let message = response.data;
                        let input = '<tr class="text-center">';
                        input += '<td colspan="10">' + [message] + '</td>';
                        input += '</tr>';
                        $('#table1 tbody').append(input);
                    }
                }
            });
        }

        function changeStatus(status,id){
            Swal.fire({
                title: 'Update',
                text: 'Are you sure you want to '+ status +'? ',
                iconHtml: '<img src="{{ asset('assets/images/delete-alert.png') }}">',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonText: 'OK',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('change.employee.status') }}',
                        type: 'get',
                        data: {
                            'status': status,
                            'emp_id': id,
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success == true) {
                                Swal.fire({
                                    title: 'Success',
                                    text: response.message ,
                                    iconHtml: '<img src="{{ asset('assets/images/success-icon.png') }}">',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    timer: 1000,
                                })
                                window.location.reload();
                            }else{
                                Swal.fire({
                                    title: 'Error',
                                    text: response.message ,
                                    iconHtml: '<img src="{{ asset('assets/images/delete-alert.png') }}">',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    timer: 1000,
                                })
                            }
                        }
                    });
                }
            });
        }
    </script>
@endsection
