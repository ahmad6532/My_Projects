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
        @if($errors->has("start_date"))
        <div class="alert alert_vt" id="alertID">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <div class="alert alert-danger small " style="max-width:100%;">{{ $errors->first('start_date') }}</div>
        </div>
        @endif
        @if($errors->has("end_date"))
        <div class="alert alert_vt" id="alertID">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <div class="alert alert-danger small " style="max-width:100%;">{{ $errors->first('end_date') }}</div>
        </div>
        @endif
        @if($errors->has("holiday_start_date"))
        <div class="alert alert_vt" id="alertID">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <div class="alert alert-danger small " style="max-width:100%;">{{ $errors->first('holiday_start_date') }}</div>
        </div>
        @endif
        @if($errors->has("holiday_end_date"))
        <div class="alert alert_vt" id="alertID">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <div class="alert alert-danger small " style="max-width:100%;">{{ $errors->first('holiday_end_date') }}</div>
        </div>
        @endif
        <div class="container-fluid">
            <div class='row align-items-center'>
                <div class='col-lg-3 p-3'>
                    <h1 class='subtitle_vt' style="font-size:16px;">Holidays Sheet</h1>
                    {{-- <p class='head-para_vt'>{{ $current_date->format('d F Y') }}</p> --}}
                </div>
                <div class='col-lg-12 p-3'>
                    <div class="row buttons justify-content-end">
                        <div class="col-lg-4 col-md-3 px-1 mb-2">
                            <div class="d-flex justify-content-end">
                                {{-- <div class="w-50 mx-1">
                                    @if ($user->haspermission(['holidays-all', 'holidays-write']))
                                        <button type="button" class="page-btn mn-width-auto" style="min-width:100%;"
                                            data-toggle="modal" data-target="#myModal">
                                            Add
                                        </button>
                                    @endif
                                </div> --}}
                                {{-- <div class="w-50">
                                    @if ($user->haspermission(['holidays-all', 'holidays-write']))
                                        <button name="submit" type="submit"
                                            class="page-btn page-btn-outline mn-width-auto" style="min-width:100%;">Upload
                                            CSV</button>
                                    @endif
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="table1"
                class="table table-striped table-hover table-bordered table-nowrap table-centered table-atten-sheet m-0">
                <thead>
                    <tr>
                        <th>Sr</th>
                        <th>Event Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Office Location</th>
                        <th>Updated At</th>
                        <th>Action</th>
                        @if ($user->haspermission(['holidays-all', 'holidays-write', 'holidays-delete']))
                            <th></th>
                        @endif
                    </tr>
                </thead>
                <tbody class="Listing_vt">
                    @foreach ($holidays as $key=>$holiday)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ ucwords($holiday->event_name) }}</td>
                            <td>{{ date('d-m-Y', strtotime($holiday->start_date)) }} </td>
                            <td>{{ date('d-m-Y', strtotime($holiday->end_date)) }} </td>
                            <td>{{ $holiday->is_active ? 'Active' : 'Inactive' }} </td>
                            <td>
                                @php
                                    $branches = explode(',', $holiday->branch_id);
                                    $totalBranches = count($branches);
                                @endphp
                            
                                @foreach ($branches as $index => $branchId)
                                    @php
                                        $branch = \App\Models\Location::find($branchId);
                                    @endphp
                            
                                    @if ($branch)
                                        @if ($index === 2 && $totalBranches > 2)
                                            <a href="#" onclick="openModal('{{$holiday->event_name}}','{{implode(",", $branches)}}')">
                                                <i class="fa-solid fa-circle-info" style="font-size:12px;" title="Show More"></i>
                                            </a>
                                            @break
                                        @else
                                            {{ $branch->branch_name }}
                                            @if ($index < $totalBranches - 1)
                                                ,
                                            @endif
                                        @endif
                                    @endif
                                @endforeach
                            </td>
                            {{-- not workink --}}
                            {{-- <td>{{ $holiday->updated_at->format('d-m-Y, h:i A') }} </td> --}}
                           <td>{{date('d-m-Y',strtotime($holiday->updated_at))}}</td>
                            @if ($user->haspermission(['holidays-all', 'holidays-write', 'holidays-delete']))
                                <td>
                                    <div class="btn-group dropdown-btn-group pull-right">
                                        <button type="button" class="active-link_vt dropdown-toggle" data-toggle="dropdown"
                                            aria-expanded="false">Action <i class="fontello icon-down-dir icon-color"
                                                style="color:#ffffff;"></i></button>
                                        <ul class="dropdown-menu form-action-menu" style="">
                                            @if ($user->haspermission(['holidays-all', 'holidays-write']))
                                                <li class="checkbox-row pt-1 pl-1 hover-option_vt">
                                                    <a href="#" class="action-content_vt" data-toggle="modal"
                                                        data-target="#editHolidayModal"
                                                        onclick="editHoliday('{{ $holiday->id }}')">
                                                        <label for="toggle-tech-companies-1-col-1"
                                                            class="action_option">Edit</label>
                                                    </a>
                                                </li>
                                            @endif
                                            @if ($user->haspermission(['holidays-all', 'holidays-delete']))
                                                <li class="checkbox-row pt-1 pl-1 hover-option_vt">
                                                    <a onclick="delHoliday('{{ url('deleteholiday/' . $holiday->id) }}')"
                                                        id="del_holiday" class="action-content_vt"><label
                                                            for="toggle-tech-companies-1-col-2"
                                                            class="action_option">Delete</label></a>
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
    <div class="modal fade" id="allBranch" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">All Locations</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="table1"  class="table table-striped table-hover table-bordered table-nowrap table-centered table-sheets m-0">
                            <thead class="table-head border-top border-bottom">
                                <tr>
                                    <th>Sr</th>
                                    <th>Office Location</th>
                                </tr>
                            </thead>
                            <tbody class="Listing_vt">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="exampleModalLabel">Add Holiday</span>
                    <button type="button" class="btn-close close" data-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('save.holiday') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="billing-first-name">Assign Company<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <select name="company_id[]" id="company_id" required
                                        onchange="getMultipleBranchesForAdd()" class="form-control selectpicker" multiple
                                        style="appearance: none;">
                                        <option value="all" selected>All</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}">{{ ucwords($company->company_name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- <i class="fontello icon-down-dir icon-color"></i> --}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="billing-first-name">Office Location<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <select name="branch_id[]" id="branch_id" multiple required
                                        class="form-control selectpicker" style="appearance: none;">
                                        <option value="all" selected>All</option>
                                    </select>
                                    {{-- <i class="fontello icon-down-dir icon-color"></i> --}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="nameInput" class="form-label">Name<span class="red"
                                        style="font-size:14px;">*</span></label>
                                <input type="text" class="form-control" name="event_name" id="event_name"
                                    placeholder="Enter event name">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="emailInput" class="form-label">From<span class="red"
                                        style="font-size:14px;">*</span></label>
                                <div class=" mb-1 position-relative month-field_vt">
                                    <input type="text" name="start_date" min="{{ date('Y-m-d') }}" id="dateInput"
                                        value="{{ date('d-m-Y') }}" required class="form-control"
                                        placeholder="Select Date">
                                    <i class="fontello icon-calander1"></i>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="emailInput" class="form-label">To</label>
                                <div class=" mb-1 position-relative month-field_vt">
                                    <input type="text" name="end_date" id="dateInput1" min="{{ date('Y-m-d') }}"
                                        value="{{ date('d-m-Y') }}" class="form-control" placeholder="Select Date">
                                    <i class="fontello icon-calander1"></i>
                                </div>
                            </div>
                        </div>
                        <div class="pb-2">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" value="1" id="status_id3" name="is_active"
                                    class="custom-control-input" checked="">
                                <label class="custom-control-label" for="status_id3">Active</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" value="0" id="status_id4" name="is_active"
                                    class="custom-control-input">
                                <label class="custom-control-label" for="status_id4">Inactive</label>
                            </div>
                        </div>
                        <button type="submit" class="page-btn mn-width-auto p-2">Submit</button>
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
                                    <label for="billing-first-name">Assign Company<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <select name="company_id[]" id="company_idd" multiple
                                        onchange="handleCompanyChange()" required class="form-control selectpicker"
                                        style="appearance: none;">
                                        <option value="all">All</option>
                                        @forelse ($companies as $company)
                                            <option value="{{ $company->id }}">{{ ucwords($company->company_name) }}
                                            </option>
                                        @empty
                                            <option>No Record Found</option>
                                        @endforelse
                                    </select>
                                    {{-- <i class="fontello icon-down-dir icon-color"></i> --}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative arrow_vt">
                                    <label for="billing-first-name">Office Location<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <select name="branch_id[]" id="branch_idd" multiple required
                                        class="form-control selectpicker" style="appearance: none;">
                                        <option Select:disabled>Select Location</option>
                                    </select>
                                    {{-- <i class="fontello icon-down-dir icon-color"></i> --}}
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
                                    <input type="text" name="holiday_start_date" min="{{ date('Y-m-d') }}"
                                        id="holiday_start_date" required value="{{ date('d-m-Y') }}"
                                        class="form-control" placeholder="Select Date">
                                    <i class="fontello icon-calander1"></i>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="emailInput" class="form-label">To</label>
                                <div class=" mb-1 position-relative month-field_vt">
                                    <input type="text" name="holiday_end_date" min="{{ date('Y-m-d') }}"
                                        id="holiday_end_date" required value="{{ date('d-m-Y') }}" class="form-control"
                                        placeholder="Select Date">
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
    <script>
    function openModal(event_name, branches) {
        $('#allBranch .modal-title').text('All Locations of "' + event_name+'"');
        var branchesArray = branches;
        var modalBody = $('#allBranch .modal-body .Listing_vt');
        modalBody.empty();

        $.ajax({
            url: "{{ route('holiday.branch') }}",
            type: "get",
            data: {
                holiday_branch_id: branchesArray,
            },
            success: function (data) {
                for (var i = 0; i < data.data.length; i++) {
                    modalBody.append('<tr><td>' + (i + 1) + '</td><td style="padding: 0.6rem 0.85rem !important;">' + data.data[i].branch_name + '</td></tr>');
                }
            },
            error: function () {
                console.error('Error fetching Location');
            }
        });
        $('#allBranch').modal('show');
    }

              $(document).ready(function() {
                var customButton =`
                <div class="col-lg-2 col-md-2 px-1 mb-2">
                <div class="d-flex justify-content-center">
                    <div>
                        @if ($user->haspermission(['holidays-all', 'holidays-write']))
                            <button type="button" class="page-btn mn-width-auto" style="min-width:100%;"
                                data-toggle="modal" data-target="#myModal">
                                Add Holiday
                            </button>
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
                    buttons: [{
                            extend: 'csvHtml5',
                            text: '<img src="' + "{{ asset('assets/images/csv.png') }}" + '" />',
                            exportOptions: {
                                columns: ':not(:last-child):not(:last-child-1)'
                            }
                        },
                        {
                            extend: 'print',
                            text: '<img src="' + "{{ asset('assets/images/print.png') }}" + '" />',
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
                $('#table1_filter').before(customButton);
            });
        $(document).ready(function() {
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
        setTimeout(function() {
            $('#alertID').hide('slow')
        }, 3000);

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
                        if (data.company_id.includes(',')) {
                            var companyIds = data.company_id.split(',');
                            var branchIds = data.branch_id.split(',');
                            getMultipleBBranches(companyIds, branchIds);
                        } else {
                            getMultipleBBranches(data.company_id, data.branch_id);
                        }
                        var model = new bootstrap.Modal(document.getElementById("holidayEdit"));
                        model.show();
                        document.getElementById('edit_holiday').value = response.data.id;
                        document.getElementById('holiday_event_name').value = data.event_name;
                        document.getElementById('holiday_start_date').value = data.start_date;
                        document.getElementById('holiday_end_date').value = data.end_date;
                        if (data.company_id.includes(',')) {
                            var selectedCompanyIds = data.company_id.split(',');
                        } else {
                            var selectedCompanyIds = data.company_id;
                        }
                        if (selectedCompanyIds) {
                            $('#company_idd').val(selectedCompanyIds);
                        }
                        $('#company_idd').selectpicker('refresh');
                        if (data.is_active === "1") {
                            $('#status_id1').prop('checked', true);
                            $('#status_id2').prop('checked', false);
                        } else if (data.is_active === "0") {
                            $('#status_id1').prop('checked', false);
                            $('#status_id2').prop('checked', true);
                        }
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

        function handleCompanyChange(id) {
            var id = id;
            var selectedCompanyIds = $('#company_idd').val();
            getMultipleBBranches(selectedCompanyIds, '');
        }

        function getMultipleBBranches(company_edit, branch_id) {
            if (!Array.isArray(company_edit)) {
                company_edit = [company_edit];
            }
            var company_ids = [];
            $('#company_idd option:selected').each(function() {
                company_ids.push($(this).val());
            });
            $.ajax({
                method: 'get',
                dataType: 'json',
                url: '{{ route('get.multi.branches') }}',
                data: {
                    company_id: company_ids,
                    company_edit: company_edit
                },
                success: function(response) {
                    var data = response.data;
                    var html = '<option disabled>Select Location</option><option value="all">All</option>';
                    for (var i = 0; i < data.length; ++i) {
                        var selected = '';
                        if (branch_id.includes(data[i].id.toString())) {
                            selected = 'selected';
                        }
                        html += `<option value="${data[i].id}" ${selected}>${data[i].branch_name}</option>`;
                    }
                    $('#branch_idd').find('option:selected').each(function() {
                        var existingBranchId = $(this).val();
                        if (!branch_id.includes(existingBranchId)) {
                            html +=
                                `<option value="${existingBranchId}" selected>${$(this).text()}</option>`;
                        }
                    });

                    $('#branch_idd').html(html);
                    $('#branch_idd').selectpicker('refresh');
                }
            });
        }

        function getMultipleBranchesForAdd() {
            var company_ids = [];
            $('#company_id option:selected').each(function() {
                company_ids.push($(this).val());
            });
            $.ajax({
                method: 'get',
                dataType: 'json',
                url: '{{ route('get.multiple.branches') }}',
                data: {
                    company_id: company_ids
                },
                success: function(response) {
                    var data = response.data;
                    if (data.length > 0) {
                        $('#branch_id').html('');
                        var html = '<option disabled>Select Location</option><option value="all">All</option>';
                        for (var i = 0; i < data.length; ++i) {
                            html += `<option value="${data[i].id}">${data[i].branch_name}</option>`;
                        }
                        $('#branch_id').html(html);
                        $('#branch_id').selectpicker('refresh');
                    } else {
                        // Handle the case when there is no data to display
                        $('#branch_id').html('<option value="all">All</option>');
                        $('#branch_id').selectpicker('refresh');
                    }
                }
            });
        }
    </script>
@endsection
