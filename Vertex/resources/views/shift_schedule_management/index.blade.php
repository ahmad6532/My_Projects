@extends('layouts.admin.master')
@section('content')
    <style>
        .btn2 span {
            color: #1e85ff;
            border: 1px solid;
            border-block-end-width: 1px;
            border-radius: 3px;
            padding: 5px 6px;
            margin: 1px 1px;
        }
    </style>
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
            <div class='row align-items-center'>
                <div class='col-lg-3 p-3'>
                    <h1 class='subtitle_vt' style="font-size:16px;">Shift And Schedule</h1>
                </div>
                {{-- <div class='col-lg-9 p-3'>
                    <div class="row justify-content-end">
                        <div class="col-lg-5 col-md-5 px-1 mb-2">
                            <div class="d-flex">
                                @if ($user->haspermission(['holidays-all', 'holidays-write']))
                                    <div class="w-50 mx-1">
                                        <button type="button" class="page-btn page-btn-outline hover-btn"
                                            style="float:right;" data-toggle="modal" data-target="#assignShiftModal">
                                            Assign Shift
                                        </button>
                                    </div>
                                    <div class="w-50 mx-1">
                                        <a href="{{ route('add.shift') }}">
                                            <button type="button" class="page-btn mn-width-auto" style="min-width:100%;"
                                                data-toggle="modal" data-target="#addShiftModal">
                                                Add Shift
                                            </button>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>

            <div class="table-responsive">
                <table id="table1"
                    class="table table-striped table-hover table-bordered table-nowrap table-centered table-atten-sheet m-0">
                    <thead>
                        <tr>
                            <th>Sr</th>
                            <th>Shift Name</th>
                            <th>Shift Time</th>
                            <th>Break Time</th>
                            <th>Working Hours</th>
                            <th>Late Time</th>
                            <th>Half Day</th>
                            <th>Updated At</th>
                            @if ($user->haspermission(['holidays-all', 'holidays-write', 'holidays-delete']))
                                <th></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="Listing_vt">
                        @foreach ($shiftListing as $key => $shifts)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{ $shifts->shift_name }}</td>
                                <td>{{ date('h:i A', strtotime($shifts->start_time)) }} - {{ date('h:i A', strtotime($shifts->end_time)) }}</td>
                                <td>{{ date('h:i A', strtotime($shifts->break_start_time))}} - {{date('h:i A', strtotime($shifts->break_end_time))}} </td>
                                <td>
                                    @php
                                        $start_time = strtotime($shifts->start_time);
                                        $end_time = strtotime($shifts->end_time);
                                        $duration = $end_time - $start_time;
                                        $hours = floor($duration / 3600);
                                        $minutes = floor(($duration % 3600) / 60);
                                        echo $hours.'h '.$minutes.'m';
                                    @endphp
                                </td>
                                <td>{{ date('h:i A', strtotime($shifts->late_time)) }} </td>
                                <td>{{ $shifts->half_day }} hours </td>
                                <td>{{ $shifts->updated_at->format('d-m-Y, h:i A') }} </td>
                                @if ($user->haspermission(['holidays-all', 'holidays-write', 'holidays-delete']))
                                    <td>
                                        <div class="btn-group dropdown-btn-group pull-right">
                                            <button type="button" class="active-link_vt dropdown-toggle"
                                                data-toggle="dropdown" aria-expanded="false">Action <i
                                                    class="fontello icon-down-dir icon-color"
                                                    style="color:#ffffff;"></i></button>
                                            <ul class="dropdown-menu form-action-menu" style="">
                                                {{-- @if ($user->haspermission(['holidays-all', 'holidays-write'])) --}}
                                                    <li class="checkbox-row pt-1 pl-1 hover-option_vt">
                                                        <a href="{{ route('edit.shift', ['id' => $shifts->id]) }}"
                                                            class="action-content_vt">
                                                            <label for="toggle-tech-companies-1-col-1"
                                                                class="action_option">Edit</label>
                                                        </a>
                                                    </li>
                                                {{-- @endif --}}
                                                {{-- @if ($user->haspermission(['holidays-all', 'holidays-delete'])) --}}
                                                    <li class="checkbox-row pt-1 pl-1 hover-option_vt">
                                                        <a onclick="delshift('{{ url('/employee/shift-management/delete/' . $shifts->id) }}')"
                                                            id="del_holiday" class="action-content_vt"><label
                                                                for="toggle-tech-companies-1-col-2"
                                                                class="action_option">Delete</label></a>
                                                    </li>
                                                {{-- @endif --}}
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

        <div class="modal fade" id="assignShiftModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="modal-title" id="exampleModalLabel">Add Schedule</span>
                        <button type="button" class="btn-close close" data-dismiss="modal" aria-label="Close"><i
                                class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{route('save.schedule')}}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <div class="form-group position-relative arrow_vt">
                                        <label for="nameInput" class="form-label">Company<span class="red"
                                                style="font-size:14px;">*</span></label>
                                        <select name="company_id" id="company_id" onchange="getBranches(this.value)"
                                            required class="form-control" style="appearance: none;">
                                            <option disabled selected>Select Company</option>
                                            @forelse ($companies as $company)
                                                <option value="{{ $company->id }}">{{ ucwords($company->company_name) }}
                                                </option>
                                            @empty
                                                <option>No Record Found</option>
                                            @endforelse
                                        </select>

                                        <i class="fontello icon-down-dir icon-color"></i>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group position-relative arrow_vt">
                                        <label for="nameInput" class="form-label">Office Location<span class="red"
                                                style="font-size:14px;">*</span></label>
                                        <select name="branch_id" id="branch_id" onchange="getEmployees()" required class="form-control"
                                            style="appearance: none;">
                                            <option disabled selected>Select Location</option>
                                        </select>
                                        <i class="fontello icon-down-dir icon-color"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <div class="form-group position-relative arrow_vt">
                                        <label for="nameInput" class="form-label">Employee<span class="red"
                                                style="font-size:14px;">*</span></label>
                                        <select id="emp_id" multiple name="emp_id" required class="form-control selectpicker"
                                            style="appearance: none;" aria-placeholder="Select Employee">
                                            <option disabled >Select Employee</option>
                                        </select>
                                        {{-- <i class="fontello icon-down-dir icon-color"></i> --}}
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group position-relative arrow_vt">
                                        <label for="nameInput" class="form-label">Department<span class="red"
                                                style="font-size:14px;">*</span></label>
                                        <select id="dept_id" name="dept_id" required class="form-control"
                                            style="appearance: none;" aria-placeholder="Select Department">
                                            <option disabled selected>Select Department</option>
                                            @foreach ($departments as $department)
                                                <option value="{{$department->id}}" {{old('dept_id')?'selected':''}}>{{ucwords($department->name)}}</option>
                                            @endforeach
                                        </select>
                                        <i class="fontello icon-down-dir icon-color"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <div class="form-group position-relative arrow_vt">
                                        <label for="nameInput" class="form-label">Shift Type<span class="red"
                                                style="font-size:14px;">*</span></label>
                                        <select id="shift_id" name="shift_id" required class="form-control"
                                            style="appearance: none;" aria-placeholder="Select Shift">
                                            <option disabled selected>Select Shift</option>
                                            @foreach ($shiftListing as $shift)
                                                <option value="{{$shift->id}}" {{old('shift_id')?'selected':''}}>{{ucwords($shift->shift_name)}}</option>
                                            @endforeach
                                        </select>
                                        <i class="fontello icon-down-dir icon-color"></i>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="dated" class="form-label">Date</label>
                                    <div class=" mb-1 position-relative month-field_vt">
                                        <input type="text" name="dated" id="dateInput1" min="{{ date('Y-m-d') }}"
                                            value="{{ date('d-m-Y') }}" class="form-control" placeholder="Select Date">
                                        <i class="fontello icon-calander1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    <label for="is_extra_hours" class="form-label">Extra Hours:&nbsp;</label><br>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value="1" id="is_extra_hours" name="is_extra_hours"
                                            class="custom-control-input" checked="">
                                        <label class="custom-control-label" for="is_extra_hours">Yes</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value="0" id="is_extra_hours2" name="is_extra_hours"
                                            class="custom-control-input" checked="">
                                        <label class="custom-control-label" for="is_extra_hours2">No</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="page-btn mn-width-auto p-2">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="holidayEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Holiday</h5>
                        <button type="button" class="btn-close close" data-dismiss="modal" aria-label="Close"><i
                                class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{ route('update.holiday') }}">
                            @csrf
                            <input type="hidden" id="edit_holiday" name="id">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group position-relative arrow_vt">
                                        <label for="nameInput" class="form-label">Company<span class="red"
                                                style="font-size:14px;">*</span></label>
                                        <select name="company_id" id="company_edit_id" onchange="getEditBranches()"
                                            required class="form-control" style="appearance: none;">
                                            <option disable selected>Select Company</option>
                                            @forelse ($companies as $company)
                                                <option value="{{ $company->id }}">{{ ucwords($company->company_name) }}
                                                </option>
                                            @empty
                                                <option>No Record Found</option>
                                            @endforelse
                                        </select>
                                        <i class="fontello icon-down-dir icon-color"></i>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group position-relative arrow_vt">
                                        <label for="nameInput" class="form-label">Branch<span class="red"
                                                style="font-size:14px;">*</span></label>
                                        <select name="branch_id" id="branch_edit_id" required class="form-control"
                                            style="appearance: none;">
                                            <option disable selected>Select Branch</option>
                                        </select>
                                        <i class="fontello icon-down-dir icon-color"></i>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name='brancch_edit_id' id="brancch_edit_id">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <label for="nameInput" class="form-label">Name<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <input type="text" class="form-control" name="event_name" id="holiday_event_name"
                                        placeholder="Enter event name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="emailInput" class="form-label">From<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <div class=" mb-1 position-relative month-field_vt">
                                        <input type="text" name="holiday_start_date" id="holiday_start_date" required
                                            class="form-control" placeholder="Select Date">
                                        <i class="fontello icon-calander1"></i>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="emailInput" class="form-label">To</label>
                                    <div class=" mb-1 position-relative month-field_vt">
                                        <input type="text" name="holiday_end_date" id="holiday_end_date" required
                                            class="form-control" placeholder="Select Date">
                                        <i class="fontello icon-calander1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="pb-2">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" value="1" id="status_id1" name="is_active"
                                        class="custom-control-input" checked="">
                                    <label class="custom-control-label" for="status_id1">Active</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" value="0" id="status_id2" name="is_active"
                                        class="custom-control-input" checked="">
                                    <label class="custom-control-label" for="status_id2">Inactive</label>
                                </div>
                            </div>
                            <button type="submit" class="page-btn mn-width-auto p-2">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script> --}}
        <script>
            
            $(document).ready(function() {
                    var customButton = `
                    <div class="col-lg-2 col-md-2 px-1 mb-2">
                        <div class="d-flex justify-content-center">
                            <div>
                                @if ($user->haspermission(['holidays-all', 'holidays-write']))
                                <a href="{{ route('add.shift') }}">
                                    <button name="submit" type="submit" class="page-btn">Add Shift</button>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    `;
                    var customButton2 = `
                    <div class="col-lg-2 col-md-2 px-1 mb-2">
                        <div class="d-flex justify-content-center">
                            <div>
                                @if ($user->haspermission(['holidays-all', 'holidays-write']))
                                <div class="w-50 mx-1">
                                        <button type="button" class="page-btn page-btn-outline hover-btn"
                                            style="float:right;" data-toggle="modal" data-target="#assignShiftModal">
                                            Assign Shift
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    `;
                    $('#table1').DataTable({
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
                                    text: '<img src="' + "{{ asset('assets/images/pdf.png') }}" + '" />',
                                    exportOptions: {
                                        columns: ':not(:last-child):not(:last-child-1)'
                                    }
                                },
                            ]
                    });
                    $('.dt-buttons').after(customButton);
                    $('#table1_filter').before(customButton2);
                // Get the current month and year
                var currentDate = new Date();

                // Set the maximum date for the datepicker
                $('#dateInput').datepicker({
                    format: "dd-mm-yyyy",
                    defaultDate: currentDate,
                });

                // Set the maximum date for the datepicker
                $('#dateInput1').datepicker({
                    format: "dd-mm-yyyy",
                    defaultDate: currentDate,
                });

                // Set the maximum date for the datepicker
                $('#holiday_start_date').datepicker({
                    format: "dd-mm-yyyy",
                    defaultDate: currentDate,
                });

                // Set the maximum date for the datepicker
                $('#holiday_end_date').datepicker({
                    format: "dd-mm-yyyy",
                    defaultDate: currentDate,
                });

                $('#selectBranch').change(function() {
                    $('#myForm').submit();
                });
            });
            function formatDate(dateString) {
                const dateObj = new Date(dateString);
                const formattedDate = ('0' + dateObj.getDate()).slice(-2) + '-' + ('0' + (dateObj.getMonth() + 1)).slice(-2) +
                    '-' + dateObj.getFullYear();
                const hours = dateObj.getHours();
                const minutes = ('0' + dateObj.getMinutes()).slice(-2);
                const period = hours >= 12 ? 'PM' : 'AM';
                const formattedTime = ('0' + ((hours + 11) % 12 + 1)).slice(-2) + ':' + minutes + ' ' + period;
                return formattedDate + ', ' + formattedTime;
            }

            function editHoliday(id) {

                $.ajax({
                    url: "{{ route('edit.holiday') }}",
                    type: "get",
                    data: {
                        holiday_id: id,
                    },
                    success: function(response) {
                        if (response.success) {
                            var data = response.data;
                            var model = new bootstrap.Modal(document.getElementById("holidayEdit"));
                            model.show();
                            document.getElementById('edit_holiday').value = response.data.id;
                            document.getElementById('holiday_event_name').value = data.event_name;
                            document.getElementById('holiday_start_date').value = data.start_date;
                            document.getElementById('holiday_end_date').value = data.end_date;
                            document.getElementById('company_edit_id').value = data.company_id;
                            document.getElementById('brancch_edit_id').value = data.branch_id;
                            if (data.is_active === "1") {
                                $('#status_id1').prop('checked', true);
                                $('#status_id2').prop('checked', false);
                            } else if (data.is_active === "0") {
                                $('#status_id1').prop('checked', false);
                                $('#status_id2').prop('checked', true);
                            }
                            getEditBranches(data.company_id, data.branch_id);
                        }
                    }
                })
            }

            function delHoliday(url) {
                Swal.fire({
                    title: 'Delete',
                    text: 'Are you sure you want to delete? ',
                    iconHtml: '<img src="{{ asset('assets/images/delete-alert.png') }}">',
                    showCancelButton: true,
                    reverseButtons: true,
                    confirmButtonText: 'Delete',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                })
            }

            function delHolida(id) {
                var new_url = '{{ url('deleteholiday') }}/' + id;
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
                });
            }

            function getBranches() {
                var company_id = $('#company_id').val();
                $.ajax({
                    method: 'get',
                    dataType: 'json',
                    url: '{{ route('get-branch') }}',
                    data: {
                        company_id: company_id
                    },
                    success: function(response) {
                        var data = response.data;
                        console.log(data);
                        $('#branch_id').html('');
                        var html =
                            '<option selected disabled>Select Location</option>';
                        for (var i = 0; i < data.length; ++i) {
                            html += `<option value="${data[i].id}">${data[i].branch_name}</option>`;
                        }
                        $('#branch_id').html(html);
                    }
                });
            }

            function getEmployees() {
                var branch_id = $('#branch_id').val();
                $.ajax({
                    method: 'get',
                    dataType: 'json',
                    url: '{{ route('get.branch.employees') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function(response) {
                        var data = response.data;
                        $('#emp_id').html('');
                        var html = '<option disabled>Select Employee</option>';
                        for (var i = 0; i < data.length; ++i) {
                            html += `<option value="${data[i].id}">${data[i].emp_name}</option>`;
                        }
                        $('#emp_id').html(html);
                        $('#emp_id').selectpicker('refresh');
                    }
                });
            }

            function getEditBranches() {
                var company_id = $('#company_edit_id').val();
                var branch_id = $('#brancch_edit_id').val();
                $.ajax({
                    method: 'get',
                    dataType: 'json',
                    url: '{{ route('get-branch') }}',
                    data: {
                        company_id: company_id
                    },
                    success: function(response) {
                        var data = response.data;
                        console.log(data);
                        $('#branch_edit_id').html('');
                        var html = '<option selected disabled>Select Branch</option>';
                        for (var i = 0; i < data.length; ++i) {
                            var selected = (data[i].id == branch_id ? 'selected' : '');
                            html += `<option value="${data[i].id}" ${selected}>${data[i].branch_name}</option>`;
                        }
                        $('#branch_edit_id').html(html);
                    }
                });
            }

            setTimeout(function() {
                $('#alertID').hide('slow')
            }, 3000);

        </script>
    @endsection
