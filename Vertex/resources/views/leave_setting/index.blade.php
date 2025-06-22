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
                <div class="col-lg-7 col-md-7 mb-2">
                    <h4 class="header-title m-0 pt-2">Leave Settings
                </h4>
                </div>
                {{-- <div class="col-lg-2 col-md-2 px-1 mb-2">
                    <div class="d-flex justify-content-end">
                        <div>
                            @if ($user->haspermission(['leave-request-all','leave-request-write']))
                            <a href="{{route('add.leave.setup')}}">
                                <button class="page-btn">Add Leave Setup</button>
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
                {{-- <div class="col-lg-3 col-md-3 px-1 mb-2 pr-2">
                    <div class="search-icon p-0">
                        <input type="text" class="form-control" style="font-size:0.8rem !important;"
                            onkeyup="searchData()" name="search_input" id="searchID" value=""
                            placeholder="Search">
                        <i class='fontello icon-search' style="font-size:0.8rem !important;"></i>
                    </div>
                </div> --}}

            </div>
            <div class="table-responsive">
                <table id="table1" class="table table-bordered table-striped table-nowrap table-centered table-atten-sheet m-0">
                    <thead>
                        <tr>
                            <th>Sr</th>
                            <th>Company</th>
                            <th>Annual Leaves</th>
                            <th>Casual Leaves</th>
                            <th>Sick Leaves</th>
                            <th>Maternity Leaves</th>
                            @if ($user->haspermission(['leave-request-all','leave-request-write','leave-request-delete']))
                            <th></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="Listing_vt">
                        @foreach ($leave_setting as $key=> $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{$item->company->company_name }}</td>
                                <td>{{ $item->annual_days }} Days</td>
                                <td>{{ $item->casual_days }} Days</td>
                                <td>{{ $item->sick_days }} Days</td>
                                <td>{{ $item->maternity_days }} Days</td>
                                @if ($user->haspermission(['leave-request-all','leave-request-write','leave-request-delete']))
                                    <td>
                                        <div class="btn-group dropdown-btn-group dropdown">
                                            <button type="button" class="active-link_vt dropdown-toggle" data-toggle="dropdown"
                                                aria-expanded="false">Action <i class="fontello icon-down-dir icon-color" style="color:#ffffff;"></i></button>
                                            <ul class="dropdown-menu form-action-menu" style="">
                                                @if ($user->haspermission(['leave-request-all','leave-request-write']))
                                                <li class="checkbox-row pt-1 pl-1 hover-option_vt">
                                                    <a href="{{ route('leave.settings.edit', ['id' => $item->id]) }}" class="action-content_vt">
                                                        <label for="toggle-tech-companies-1-col-1"
                                                            class="action_option">Edit</label>
                                                    </a>
                                                </li>
                                                @endif
                                                @if ($user->haspermission(['leave-request-all','leave-request-delete']))
                                                <li class="checkbox-row pt-1 pl-1 hover-option_vt">
                                                    <a href="{{ route('delete.leave.setup', ['id' => $item->id]) }}" class="action-content_vt">
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
                var customButton =`
                <div class="col-lg-2 col-md-2 px-1 mb-2">
                <div class="d-flex justify-content-center">
                    <div>
                    @if ($user->haspermission(['leave-request-all','leave-request-write']))
                    <a href="{{route('add.leave.setup')}}">
                        <button class="page-btn">Add Leave Setup</button>
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
        $('#selectBranch').change(function() {
            $('#dateSubmit').submit();
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

    setTimeout(function() {
        $('#alertID').hide('slow')
    }, 3000);

    function searchData() {
        var input = document.getElementById('searchID').value;
        var selectBranch = document.getElementById('selectBranch').value;
        let type = '';
        $.ajax({
            url: '{{ route('leave.search') }}',
            type: 'get',
            data: {
                'searchValue': input,
                'selectBranch': selectBranch,
            },
            dataType: 'json',
            success: function(response) {
                if (response['success'] == true) {
                    $("#table1 tbody").empty();
                    var result = response.data;
                    var id = 0;
                    for (var i = 0; i < result.length; i++) {
                        var url = '{{ url('approve-leave/' . 'id') }}';
                        approve = url.replace('id', result[i].id);
                        var url = '{{ url('decline-leave/' . 'id') }}';
                        decline = url.replace('id', result[i].id);
                        var input = '';
                        input += '<tr>';
                        input += ' <td>' + (i + 1) + '</td>';
                        input += ' <td>' + result[i].emp_id + '</td>';
                        input += ' <td>' + result[i].emp_name + '</td>';
                        input += ' <td>' + result[i].leave_type + '</td>';
                        input += ' <td>' + result[i].from_date + '</td>';
                        input += ' <td>' + result[i].to_date + '</td>';
                        input += ' <td>' + result[i].branch_name + '</td>';
                        input +=
                            '<td> <div class="btn-group dropdown-btn-group pull-right"> <button type="button" class="active-link_vt dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button> <ul class="dropdown-menu form-action-menu" style=""> <li class="checkbox-row hover-option_vt"> <a href="' +
                        approve +
                        '" class="action-content_vt"><label for="toggle-tech-companies-1-col-1" class="action_option">Approve</label></a></li> <li class="checkbox-row hover-option_vt"> <a href="' +
                        decline +
                        '" class="action-content_vt" ><label for="toggle-tech-companies-1-col-2" class="action_option">Decline</label></a></li> </ul> </div> </td></tr>';
                            input += '</tr>';


                        $("#table1 tbody").append(input);
                    }
                } else {
                    $('#table1 tbody').empty()
                    let message = response.data;
                    let input = '<tr class="text-center">';
                    input += '<td colspan="12">' + [message] + '</td>';
                    input += '</tr>';
                    $('#table1 tbody').append(input);
                }
            }
        });
    }
        $('#selectBranch').change(function() {
            $('#myForm').submit();
        });
    });
</script>
@endsection
