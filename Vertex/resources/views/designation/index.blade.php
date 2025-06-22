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
                <div class="col-lg-7 col-md-4 mb-2" style="padding-left:2px !important;padding-right:2px !important;">
                </div>
                {{-- <div class="col-lg-2 col-md-2 px-1 mb-2">
                    <div class="d-flex justify-content-end">
                        <div>
                            @if ($user->haspermission(['designation-all', 'designation-write']))
                                <a href="javascript:void(0);" data-toggle="modal" data-target="#exampleModal">
                                    <button class="page-btn">Add Designation</button>
                                </a>
                            @endif
                        </div>
                    </div>
                </div> --}}
            </div>
            <div class="table-responsive">
                <table id="table1"
                class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th>Sr</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Updated at</th>
                            @if ($user->haspermission(['designation-all', 'designation-write']))
                                <th></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="Listing_vt">
                        @foreach ($designations as $key => $designation)
                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td>{{ $designation->name }}</td>
                            <td>{{ $designation->department->name }}</td>
                            <td>{{ date('d-m-Y, h:i A', strtotime($designation->updated_at)) }}</td>
                            @if ($user->haspermission(['designation-all', 'designation-write']))
                            <td>
                                <div class="btn-group dropdown-btn-group dropdown">
                                    <button type="button" class="active-link_vt dropdown-toggle"
                                    data-toggle="dropdown" aria-expanded="false">Action <i class="fontello icon-down-dir icon-color" style="color:#ffffff;"></i></button>
                                    <ul class="dropdown-menu form-action-menu" style="">
                                        <li class="checkbox-row pt-1 pl-1 hover-option_vt">
                                            @if ($user->haspermission(['designation-all', 'designation-write']))
                                            <a href="javascript:void(0);"
                                            onclick="editDesignation({{ $designation->id }}, '{{ $designation->name }}',{{ $designation->department->id }}, '{{ $designation->department->name }}')"
                                            class="action-content_vt">
                                            <label for="toggle-tech-companies-1-col-1"
                                            class="action_option">Edit</label>
                                        </a>
                                        @endif
                                    </li>
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

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Designation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size:25px;">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('save.designation') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="nameInput" class="form-label">Designation<span class="red"
                                                style="font-size:14px;">*</span></label>
                                        <input type="text" class="form-control" name="designation_name"
                                            id="designation_name" placeholder="Enter Designation Name">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group position-relative arrow_vt">
                                        <label for="nameInput" class="form-label">Department<span class="red"
                                                style="font-size:14px;">*</span></label>
                                        <select title="Branch" id="department_id" name="department_id" required
                                            class="form-control" style="appearance: none;">
                                            <option disable selected value="">Select Department</option>
                                            @forelse ($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @empty
                                                <option>No date found</option>
                                            @endforelse
                                        </select>
                                        <i class="fontello icon-down-dir icon-color"></i>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="button" class="page-btn page-btn-outline hover-btn sm-page-btn"
                                        data-dismiss="modal">Close</button>
                                    <Button type="submit" class="page-btn sm-page-btn">Save</Button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Designation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size:25px;">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('update.designation') }}" method="POST">
                            @csrf
                            <input type="hidden" class="form-control" name="desg_id" id="desg_id">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="nameInput" class="form-label">Designation<span class="red"
                                                style="font-size:14px;">*</span></label>
                                        <input type="text" class="form-control" name="desg_name" id="desg_name"
                                            placeholder="Enter Designation Name">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group position-relative arrow_vt">
                                        <label for="nameInput" class="form-label">Department<span class="red"
                                                style="font-size:14px;">*</span></label>
                                        <select title="Department" id="dept_id" name="dept_id" required
                                            class="form-control" style="appearance: none;">
                                            <option disable selected value="">Select Department</option>
                                        </select>
                                        <i class="fontello icon-down-dir icon-color"></i>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="button" class="page-btn page-btn-outline hover-btn sm-page-btn"
                                        data-dismiss="modal">Close</button>
                                    <Button type="submit" class="page-btn sm-page-btn">Update</Button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                var customButton = `
                <div class="col-lg-2 col-md-2 px-1 mb-2">
                    <div class="d-flex justify-content-center">
                        <div>
                            @if ($user->haspermission(['designation-all', 'designation-write']))
                                <a href="javascript:void(0);" data-toggle="modal" data-target="#exampleModal">
                                    <button class="page-btn">Add Designation</button>
                                </a>
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

            function editDesignation(id, desg_name, dept_id, dept_name) {
                $('#desg_id').val(id);
                $('#desg_name').val(desg_name);
                $('#dept_id').val(dept_id);
                $('#dept_name').val(dept_name);
                $('#editModal').modal('show');
                $.ajax({
                    method: 'get',
                    dataType: 'json',
                    url: '{{ route('getDepartments') }}',
                    data: {},
                    success: function(response) {
                        var data = response.data;
                        $('#dept_id').html('');
                        var html = '<option selected disabled>Select Department</option>';
                        for (var i = 0; i < data.length; ++i) {
                            var selected = data[i].id == dept_id ? 'selected' : '';
                            html += `<option value="${data[i].id}" ${selected}>${data[i].name}</option>`;
                        }
                        $('#dept_id').html(html);
                    }
                });
            }
            $(document).ready(function() {
                // Find the DataTables search input field
                var searchInput = $('.dataTables_filter input');

                // Add a placeholder to the input field
                searchInput.attr('placeholder', 'Search...');
                
                $('#dateInput').change(function() {
                    $('#dateSubmit').submit();
                });
            });

            $(document).ready(function() {
                // Get the current month and year
                var currentDate = new Date();

                // Set the maximum date for the datepicker
                $('#dateInput2').datepicker({
                    format: "dd-mm-yyyy",
                    maxDate: new Date()
                });
            });

            $(document).ready(function() {
                // Get the current month and year
                var currentDate = new Date();

                // Set the maximum date for the datepicker
                $('#dateInput1').datepicker({
                    format: "dd-mm-yyyy",
                    maxDate: new Date()
                });
            });


            setTimeout(function() {
                $('#alertID').hide('slow')
            }, 3000);
            function formatDate(date) {
            var day = String(date.getDate()).padStart(2, '0');
            var month = String(date.getMonth() + 1).padStart(2, '0');
            var year = date.getFullYear();
            var hours = String(date.getHours()).padStart(2, '0');
            var minutes = String(date.getMinutes()).padStart(2, '0');
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight

            return `${day}-${month}-${year}, ${hours}:${minutes} ${ampm}`;
        }
            $(document).ready(function() {
                $('#selectBranch').change(function() {
                    $('#myForm').submit();
                });
            });
        </script>
    @endsection
