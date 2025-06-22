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
                <div class="col-lg-4 col-md-4 mb-2" style="padding-left:2px !important;padding-right:2px !important;">
                </div>
            </div>
            <div class="table-responsive">
                <table id="table1"
                    class="table table-bordered table-striped table-nowrap table-centered table-atten-sheet m-0">
                    <thead>
                        <tr>
                            <th>Sr</th>
                            <th>Month</th>
                            <th>Number Of Employees</th>
                            <th>Increment</th>
                            <th>Total Salary</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="Listing_vt">
                        {{-- @foreach ($employees as $key => $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>
                                    @php
                                        if ($item->emp_name ? $item->emp_image : '') {
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
                                            <td class="border-0 p-0" style="border: 0 !important;"><img class='table-img_vt'
                                                    src="{{ asset($item->emp_image) }}"></td>
                                            <td class="border-0 p-0" style="border: 0 !important;">
                                                {{ $item->emp_name ? $item->emp_id : '' }}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td><a
                                    href="{{ url('/employee/directory/employee-profile/' . base64_encode($item->emp_name ? $item->id : '')) }}">{{ ($item->emp_name ? $item->emp_name : '') }}
                                </a></td>
                                <td>{{$item->approval ? $item->approval->joining_date : ''}}</td>
                                <td>{{$item->branch->branch_name}}</td>
                                <td>{{$item->designation_name ? $item->designation_name->name : ''}}</td>
                                <td>{{ $item->salary ? number_format($item->salary->basic_salary) : '' }}</td>
                                <td style="position: relative;text-align: center;" onclick="openConvanceModal('{{$item->id}}','{{$item->company_id}}','{{$item->branch_id}}','{{$item->approval->joining_date ?? ''}}','{{$item->approval->designation_id ?? ''}}','{{$item->salary->basic_salary ?? ''}}','{{$item->Monthly_payroll->conveince_allowance ?? ''}}','{{$item->salary->net_salary ?? '0'}}','{{$item->salary->mobile_allowance ?? '0'}}','{{$item->get_user_monthly_attendance->late_comings ?? '0'}}','{{ $current_month_year }}')"><span>{{ $item->Monthly_payroll ? number_format($item->Monthly_payroll->conveince_allowance) : '' }}</span>
                                    <i class="fas fa-pencil-alt" style="position: absolute; top: 0; right: 0;"></i></td>
                                <td></td>
                                <td></td>
                                <td>{{ $item->get_user_monthly_attendance ? $item->get_user_monthly_attendance->late_comings : '0' }}</td>
                                <td></td>
                                <td>{{ $item->get_user_monthly_attendance ? $item->get_user_monthly_attendance->absent : '0' }}</td>
                                <td></td>
                                <td>{{ $item->salary ? number_format($item->salary->mobile_allowance) : '' }}</td>
                                <td></td>
                                <td>{{ $item->salary ? number_format($item->salary->net_salary) : '' }}</td>
                                <td style="position: relative;text-align: center;" onclick="openRemarksModal('{{$item->id}}','{{$item->company_id}}','{{$item->branch_id}}','{{$item->approval->joining_date ?? ''}}','{{$item->approval->designation_id ?? ''}}','{{$item->salary->basic_salary ?? ''}}','{{$item->Monthly_payroll->conveince_allowance ?? ''}}','{{$item->salary->net_salary ?? '0'}}','{{$item->salary->mobile_allowance ?? '0'}}','{{$item->get_user_monthly_attendance->late_comings ?? '0'}}','{{ $current_month_year }}','{{$item->Monthly_payroll->remarks ?? ''}}')"><span>{{ $item->Monthly_payroll ? $item->Monthly_payroll->remarks : '' }}</span>
                                    <i class="fas fa-pencil-alt" style="position: absolute; top: 0; right: 0;"></i></td>
                            </tr>
                        @endforeach --}}
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
                            <form method="get" id="searchByDate" action="{{ route('monthly.payroll.emp_salary') }}">
                            <div class="">
                                <input type="hidden" name="branch_id" value="{{ $selected }}">
                                <div class=" mb-1 position-relative month-field_vt">
                                    <input type="text" name="year_month" id="datepicker"
                                        value="{{ $current_month_year }}" class="form-control" placeholder="Select Month">
                                    <i class="fontello icon-calander1"></i>
                                </div>
                                <!-- <div class="col-lg-4">
                                                        <button type="submit" class="searchBtn page-btn mn-width-auto w-100">Search</button>
                                                    </div> -->
                            </div>
                        </form>
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
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<img src="{{ asset('assets/images/print.png') }}" />',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<img src="{{ asset('assets/images/pdf.png') }}" />',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                ]
            });
            var selectFormHTML = `
                <div class="col-lg-3 col-md-3 mb-3">
                    <form method="GET" id="myForm" action="{{ route('monthly.payroll') }}">
                            <input type="hidden" name="year_month"
                                value="{{ date('Y-m', strtotime($current_month_year)) }}" class="form-control">
                            <div class="form-group position-relative caret-holder px-1 mb-1">
                                <select class="form-control m-b" id="selectCompany" name="company_id" required=""
                                    style="appearance: none;">
                                    <option value="" disabled="">Select Branch</option>
                                    <option value="all" {{ $selected == 'all' ? 'selected' : '' }}>All</option>
                                    @forelse ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ $selected == $company->id ? 'selected' : '' }}>
                                            {{ ucwords($company->company_name) }}</option>
                                    @empty
                                        <option>No Record Found</option>
                                    @endforelse
                                </select>
                                <i class="awesom-icon icon-down-dir purple_vt ca"></i>
                            </div>
                        </form>
                </div>
            `;

            $('#table1_filter').before(selectFormHTML);
            $('.dt-buttons').after(customButton);
            $('#selectCompany').change(function() {
                $('#myForm').submit();
            });
            $('#datepicker').change(function() {
                $('#searchByDate').submit();
            });
            var currentDate = new Date();
            var currentMonth = currentDate.getMonth() + 1;
            var currentYear = currentDate.getFullYear();
            $('#datepicker').datepicker({
                format: "MM yyyy",
                minViewMode: "months",
                endDate: currentMonth + "-" + currentYear
            });
        });
        </script>
    @endsection
