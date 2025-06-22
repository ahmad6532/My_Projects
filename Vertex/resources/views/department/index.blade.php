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
    @if (session('success'))
        <div class="alert alert_vt" id="alertID">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <div class="alert alert-success small " style="max-width:100%;">{{ session('success') }}</div>
        </div>
    @endif
    <div class="container-fluid">
        <div class='row pt-2'>
            <div class="col-lg-7 col-md-4 mb-2"
                style="padding-left:2px !important;padding-right:2px !important;">
            </div>
            {{-- <div class="col-lg-2 col-md-2 px-1 mb-2">
                <div class="d-flex justify-content-end">
                    <div>
                        @if ($user->haspermission(['department-all','department-write']))
                        <a href="javascript:void(0);" data-toggle="modal" data-target="#exampleModal" >
                            <button class="page-btn" >Add Department</button>
                        </a>
                        @endif
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="table-responsive">
            <table id="table1" class="table table-bordered table-striped table-nowrap table-centered table-atten-sheet m-0">
                <thead>
                    <tr>
                        <th>Sr</th>
                        <th>Department</th>
                        <th>Updated at</th>
                        @if ($user->haspermission(['department-all','department-write']))
                        <th></th>
                        @endif
                    </tr>
                </thead>
                <tbody class="Listing_vt">
                    @foreach ($departments as $key => $department)
                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td>{{$department->name}}</td>
                            <td>{{date('d-m-Y, h:i A',strtotime($department->updated_at))}}</td>
                            @if ($user->haspermission(['department-all','department-write']))
                                <td>
                                    <div class="btn-group dropdown-btn-group dropdown">
                                        <button type="button" class="active-link_vt dropdown-toggle" data-toggle="dropdown"
                                            aria-expanded="false">Action <i class="fontello icon-down-dir icon-color" style="color:#ffffff;"></i></button>
                                        <ul class="dropdown-menu form-action-menu" style="">
                                            @if ($user->haspermission(['department-all','department-write']))
                                                <li class="checkbox-row pt-1 pl-1 hover-option_vt">
                                                    <a href="javascript:void(0);" onclick="editDepartment({{$department->id}}, '{{$department->name}}')" class="action-content_vt">
                                                        <label for="toggle-tech-companies-1-col-1"
                                                            class="action_option">Edit</label>
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
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Department</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="font-size:25px;">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{route('save.department')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nameInput" class="form-label">Department<span class="red"
                                    style="font-size:14px;">*</span></label>
                            <input type="text" class="form-control" name="department_name" id="department_name" placeholder="Enter Department Name">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="button" class="page-btn page-btn-outline hover-btn sm-page-btn" data-dismiss="modal">Close</button>
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
            <h5 class="modal-title" id="exampleModalLabel">Edit Department</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="font-size:25px;">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{route('update.department')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nameInput" class="form-label">Department<span class="red"
                                    style="font-size:14px;">*</span></label>
                            <input type="hidden" class="form-control" name="dept_id" id="dept_id" placeholder="Enter Department Name">
                            <input type="text" class="form-control" name="dept_name" id="dept_name" placeholder="Enter Department Name">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="button" class="page-btn page-btn-outline hover-btn sm-page-btn" data-dismiss="modal">Close</button>
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
                var customButton =`
                <div class="col-lg-2 col-md-2 px-1 mb-2">
                <div class="d-flex justify-content-center">
                    <div>
                        @if ($user->haspermission(['department-all','department-write']))
                        <a href="javascript:void(0);" data-toggle="modal" data-target="#exampleModal" >
                            <button class="page-btn" >Add Department</button>
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
    function editDepartment(id,name){
       $('#dept_id').val(id);
       $('#dept_name').val(name);
       $('#editModal').modal('show');
    }

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

    function searchData() {
                var input = document.getElementById('searchID').value;
                let type = '';
                $.ajax({
                    url: '{{ route('departmentSearch') }}',
                    type: 'get',
                    data: {
                        'searchValue': input,
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response['success'] == true) {
                            $("#table1 tbody").empty();
                            var result = response.data;
                            console.log(result);
                            for (var i = 0; i < result.length; i++) {
                                var updatedAtDate = new Date(result[i].updated_at);
                                var formattedDate = formatDate(updatedAtDate);
                                var input = '<tr>';
                                input += ' <td>' + (i + 1) + '</td>';
                                input += ' <td>' + result[i].name + '</td>';
                                input += ' <td>' + formattedDate + '</td>';
                                input += `
                                    <td>
                                        <div class="btn-group dropdown-btn-group dropdown">
                                            <button type="button" class="active-link_vt dropdown-toggle"
                                                data-toggle="dropdown" aria-expanded="false">Action <i class="fontello icon-down-dir icon-color" style="color:#ffffff;"></i></button>
                                            <ul class="dropdown-menu form-action-menu" style="">
                                                <li class="checkbox-row pt-1 pl-1 hover-option_vt">
                                                    <a href="javascript:void(0);"
                                                    onclick="editDepartment('${result[i].id}', '${result[i].name}')"
                                                            class="action-content_vt">
                                                            <label for="toggle-tech-companies-1-col-1"
                                                                class="action_option">Edit</label>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                `;
                                input += '</tr>';

                                $("#table1 tbody").append(input);
                            }
                        } else {
                            $('#table1 tbody').empty()
                            let message = response.data;
                            let input = '<tr class="text-center">';
                            input += '<td colspan="9">' + [message] + '</td>';
                            input += '</tr>';
                            $('#table1 tbody').append(input);
                        }
                    }

                });
            }
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
</script>
@endsection
