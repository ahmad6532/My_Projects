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
        <div class="row">
            <div class="col-xl-12 mt-4">
                <div class="card-box border-1">

                    <div class="row mb-3">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <h4 class="header-title m-0 font-weight-bold pt-2">Location Listing
                            </h4>
                        </div>
                        {{-- <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pt-xs-15">
                            <div class=" d-flex justify-content-end flex-direction">
                                <div class="mr-2 pt-xs-15">
                                    @if ($user->haspermission(['branch-management-all','branch-management-write']))
                                    <a href="{{ route('add.branch') }}" class="page-btn">Add Branch</a>
                                    @endif
                                </div>
                                <div class="position-relative search-icon_vt">
                                    <input type="text" class="form-control" onkeyup="searchData()" name="search_input"
                                        id="searchID" value="" placeholder="Search Branch">
                                    <a> <i class="fontello icon-search"></i></a>
                                    <!-- <button type='button' class='close' onclick=' $("#searchID").clearSearch();'><img src="{{ asset('assets/images/cancel.png') }}"></button> -->
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <div class="table-responsive">
                        <table id="table1" class="table table-bordered table-nowrap table-hover table-centered m-0">

                            <thead class="table-head border-top border-bottom">
                                <tr>
                                    <th>Sr</th>
                                    <th>Location ID</th>
                                    <th>Office Location</th>
                                    <th>Company</th>
                                    <th>Country</th>
                                    <th>City</th>
                                    <th>No. of Employee</th>
                                    @if ($user->haspermission(['branch-management-all','branch-management-write','branch-management-delete']))
                                    <th></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="Listing_vt">
                                @foreach($branches as $key => $item)
                                    <tr>
                                        <td> {{  $key +1 }} </td>
                                        <td>{{ $item->branch_id }}</td>
                                        <td> {{ ucwords($item->branch_name) }} </td>
                                        <td>{{ $item->company_name }}</td>
                                        <td>{{ $item->country_name }}</td>
                                        <td>{{ $item->city_name }}</td>
                                        <td>{{ $item->total_employees }}</td>
                                        @if ($user->haspermission(['branch-management-all','branch-management-write','branch-management-delete']))
                                        <td>
                                            <div class="btn-group dropdown-btn-group pull-right">
                                                <button type="button" class="active-link_vt dropdown-toggle"
                                                    data-toggle="dropdown" aria-expanded="false">Action <i class="fontello icon-down-dir icon-color" style="color:#ffffff;"></i></button>
                                                <ul class="dropdown-menu form-action-menu" style="">
                                                    @if ($user->haspermission(['branch-management-all','branch-management-write']))
                                                    <li class="checkbox-row  hover-option_vt">
                                                        <a href="{{ url('/branch-management/edit/' . base64_encode($item->id)) }}"
                                                            class="action-content_vt"><label
                                                                for="toggle-tech-companies-1-col-1"
                                                                class="action_option">Edit</label></a>
                                                    </li>
                                                    @endif
                                                    @if ($user->haspermission(['branch-management-all','branch-management-delete']))
                                                    <li class="checkbox-row hover-option_vt">
                                                        <a onclick="delBranch('{{ url('deleteBranch/' . base64_encode($item->id)) }}')"
                                                            id="del_employee" class="action-content_vt"><label
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
                    {{-- <div class="row mt-2 text-center">
                        {{ $branches->links() }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    </div>
    <script type="text/javascript">
      $(document).ready(function() {
                var customButton =`
                <div class="col-lg-2 col-md-2 px-1 mb-2">
                <div class="d-flex justify-content-center">
                    <div class="mr-2 pt-xs-15">
                        @if ($user->haspermission(['branch-management-all','branch-management-write']))
                        <a href="{{ route('add.branch') }}" class="page-btn">Add Location</a>
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
        function delBranch(url) {
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

        function delbranch(id) {
            var new_url = '{{ url('deleteBranch/' . 'id') }}';
            new_url = new_url.replace('id', id);
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
                    window.location.href = new_url;
                }
            })
        }

        // function searchData() {
        //     var input = document.getElementById('searchID').value;
        //     let type = '';
        //     $.ajax({
        //         url: '{{ route('branch.search') }}',
        //         type: 'get',
        //         data: {
        //             'search_value': input,
        //         },
        //         dataType: 'json',
        //         success: function(response) {
        //             console.log(response);
        //             if (response['success'] == true) {
        //                 $("#table1 tbody").empty();
        //                 var result = response.data;
        //                 var id = 0;
        //                 for (var i = 0; i < result.length; i++) {
        //                     var url = '{{ url('Branch/edit/' . 'id') }}';
        //                     url = url.replace('id', result[i].id);
        //                     var input = '';
        //                     input += '<tr>';
        //                     input += ' <td>' + (i + 1) + '</td>';
        //                     input += ' <td>' + result[i].branch_name + '</td>';
        //                     input += ' <td>' + result[i].branch_id + '</td>';
        //                     input += ' <td>' + result[i].company_name + '</td>';
        //                     input += ' <td>' + result[i].country_name + '</td>';
        //                     input += ' <td>' + result[i].city_name + '</td>';
        //                     input += ' <td>' + result[i].total_employees + '</td>';
        //                     input +=
        //                         '<td> <div class="btn-group dropdown-btn-group pull-right"> <button type="button" class="active-link_vt dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action  </button> <ul class="dropdown-menu form-action-menu" style=""> <li class="checkbox-row  hover-option_vt"> <a href="' +
        //                         url +
        //                         '" class="action-content_vt"><label for="toggle-tech-companies-1-col-1" class="action_option">Edit</label></a>  </li> <li class="checkbox-row hover-option_vt"> <a onclick="delbranch(' +
        //                         result[i].id +
        //                         ')" class="action-content_vt" ><label for="toggle-tech-companies-1-col-2" class="action_option">Delete</label></a> </li> </ul> </div> </td></tr>';
        //                     input += '</tr>';
        //                     $("#table1 tbody").append(input);
        //                 }
        //             } else {
        //                 $('#table1 tbody').empty()
        //                 let message = response.data;
        //                 let input = '<tr class="text-center">';
        //                 input += '<td colspan="7">' + [message] + '</td>';
        //                 input += '</tr>';
        //                 $('#table1 tbody').append(input);
        //             }
        //         }

        //     });
        // }
    </script>
@endsection
