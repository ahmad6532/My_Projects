@extends('layouts.admin.master')
@section('content')
<style>
    .smaller-logo {
        width: 50px; 
        height: auto;
    }
</style>
    <div class="container-fluid">
        <div class="row">
            @if (session('success'))
                <div class="alert alert_vt" id="alertID">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <div class="alert alert-success small " style="max-width:100%;">{{ session('success') }}</div>
                </div>
            @endif
            <div class="col-xl-12 mt-4">
                <div class="card-box border-1">
                    <div class="row mb-3">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <h4 class="header-title m-0 font-weight-bold pt-2">Company Management</h4>
                        </div>
                        {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pt-xs-15">
                            <div class="d-flex justify-content-end flex-direction">
                                <div class="mr-2 pt-xs-15">
                                    <a href="{{ route('add.company.manage') }}"> <button class="page-btn">Add Company
                                        </button> </a>
                                </div>
                                <div class="position-relative search-icon_vt">
                                    <input type="text" class="form-control" onkeyup="searchData1()" name="search_input1"
                                        id="searchID1" value="" placeholder="Search By Name">
                                    <i class=" fontello icon-search"></i>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <div class="table-responsive">
                        <table id="table1"
                            class="table table-bordered table-striped table-nowrap table-hover table-centered table-atten-sheet m-0">

                            <thead class="table-head border-top border-bottom">
                                <tr>
                                    <th>Sr</th>
                                    <th>Company Name</th>
                                    <th>Company Logo</th>
                                    <th>Phone</th>
                                    <th>Tel</th>
                                    <th>Country</th>
                                    <th>City</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($companies as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->company_name }}</td>
                                        <td style="text-align: center;">
                                            @if ($item->logo != null)
                                                <img class="blog-inner-img smaller-logo"
                                                     src="{{ $item->logo != null ? asset($item->logo) : '' }}">
                                            @else 
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $item->phone }}</td>
                                        <td>{{ $item->tel }}</td>
                                        <td>{{ $item->country->country_name ?? 'N/A' }}</td>
                                        <td>{{ $item->city->city_name ?? 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group dropdown-btn-group pull-right">
                                                <button type="button" class="active-link_vt dropdown-toggle" data-toggle="dropdown"
                                                    aria-expanded="false">Action <i class="fontello icon-down-dir icon-color" style="color:#ffffff;"></i></button>
                                                <ul class="dropdown-menu form-action-menu" style="">
                                                        <li class="checkbox-row pt-1 pl-1 hover-option_vt">
                                                            <a href="{{ route('edit.company.manage', ['id' => $item->id]) }}"
                                                                class="action-content_vt"><label for="edit-company-{{ $item->id }}"
                                                                    class="action_option">Edit</label></a>
                                                        </li>
                                                        <li class="checkbox-row pt-1 pl-1 hover-option_vt">
                                                            <a onclick="delEmp('{{ route('delete.company.manage', ['id' => $item->id]) }}')" id="del_user"
                                                                class="action-content_vt"><label for="delete-company-{{ $item->id }}"
                                                                    class="action_option">Delete</label></a>
                                                        </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
       $(document).ready(function() {
                var customButton =`
                <div class="col-lg-2 col-md-2 px-1 mb-2">
                <div class="d-flex justify-content-center">
                    <div class="mr-2 pt-xs-15">
                        <a href="{{ route('add.company.manage') }}"> <button class="page-btn">Add Company
                            </button> </a>
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
        setTimeout(function() {
            $('#alertID').hide('slow')
        }, 3000);
    </script>
@endsection
