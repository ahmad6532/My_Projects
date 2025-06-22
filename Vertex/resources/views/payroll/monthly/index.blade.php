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
                    class="table table-bordered table-striped table-nowrap table-centered table-atten-sheet m-0 ">
                    <thead>
                        <tr>
                            <th>Sr</th>
                            <th>Emp ID</th>
                            <th>Name</th>
                            <th>Join Date</th>
                            <th>Office Location</th>
                            <th>Designation</th>
                            {{-- <th>Salary</th> --}}
                            <th title="Editable">CONV/B/A</th>
                            {{-- <th>Increment</th>
                            <th>Arrears</th>
                            <th>Late</th>
                            <th>Absent(E.L.A)</th>
                            <th>Absent(L.ADJ)</th> --}}
                            <th>Mobile Allowance</th>
                            {{-- <th>Late Deduction</th> --}}
                            <th>Net Salary</th>
                            <th title="Editale">Remarks</th>
                            <th>Process</th>
                        </tr>
                    </thead>
                    <tbody class="Listing_vt">
                        @foreach ($employees as $key => $item)
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
                                    href="{{ url('/employee/directory/employee-profile/' . base64_encode($item->emp_name ? $item->id : '')) }}">{{ ($item->emp_name ? mb_convert_case($item->emp_name, MB_CASE_TITLE, 'UTF-8') : '') }}
                                </a></td>
                                <td>{{$item->approval ? $item->approval->joining_date : ''}}</td>
                                <td>{{$item->branch->branch_name}}</td>
                                <td>{{$item->designation_name ? $item->designation_name->name : ''}}</td>
                                {{-- <td>{{ $item->salary ? number_format($item->salary->basic_salary) : '' }}</td> --}}
                                @if ($item->Monthly_payroll && $item->Monthly_payroll->status == '1')
                                <td style="position: relative;text-align: center;" onclick="openConvanceModalApprove('{{$item->id}}','{{$item->company_id}}','{{$item->branch_id}}','{{$item->approval->joining_date ?? ''}}','{{$item->approval->designation_id ?? ''}}','{{$item->salary->basic_salary ?? ''}}','{{$item->Monthly_payroll->conveince_allowance ?? ''}}','{{$item->salary->net_salary ?? '0'}}','{{$item->salary->mobile_allowance ?? '0'}}','{{$item->get_user_monthly_attendance->late_comings ?? '0'}}','{{ $current_month_year }}','{{$item->Monthly_payroll->remarks ?? ''}}')"><span>{{ $item->Monthly_payroll ? number_format($item->Monthly_payroll->conveince_allowance) : '' }}</span>
                                    <i class="fas fa-pencil-alt" style="position: absolute; top: 0; right: 0;"></i></td>
                                @else
                                <td style="position: relative;text-align: center;" onclick="openConvanceModal('{{$item->id}}','{{$item->company_id}}','{{$item->branch_id}}','{{$item->approval->joining_date ?? ''}}','{{$item->approval->designation_id ?? ''}}','{{$item->salary->basic_salary ?? ''}}','{{$item->Monthly_payroll->conveince_allowance ?? ''}}','{{$item->salary->net_salary ?? '0'}}','{{$item->salary->mobile_allowance ?? '0'}}','{{$item->get_user_monthly_attendance->late_comings ?? '0'}}','{{ $current_month_year }}','{{$item->Monthly_payroll->remarks ?? ''}}')"><span>{{ $item->Monthly_payroll ? number_format($item->Monthly_payroll->conveince_allowance) : '' }}</span>
                                    <i class="fas fa-pencil-alt" style="position: absolute; top: 0; right: 0;"></i></td>
                                @endif
                                {{-- <td></td>
                                <td></td>
                                <td>{{ $item->get_user_monthly_attendance ? $item->get_user_monthly_attendance->late_comings : '0' }}</td>
                                <td></td> --}}
                                {{-- <td>{{ $item->get_user_monthly_attendance ? $item->get_user_monthly_attendance->absent : '0' }}</td> --}}
                                {{-- <td></td> --}}
                                <td>{{ $item->salary ? number_format($item->salary->mobile_allowance) : '' }}</td>
                                {{-- <td></td> --}}
                                <td>{{ $item->salary ? number_format($item->salary->net_salary) : '' }}</td>
                                @if ($item->Monthly_payroll && $item->Monthly_payroll->status == '1')
                                <td onclick="openConvanceModalApprove('{{$item->id}}','{{$item->company_id}}','{{$item->branch_id}}','{{$item->approval->joining_date ?? ''}}','{{$item->approval->designation_id ?? ''}}','{{$item->salary->basic_salary ?? ''}}','{{$item->Monthly_payroll->conveince_allowance ?? ''}}','{{$item->salary->net_salary ?? '0'}}','{{$item->salary->mobile_allowance ?? '0'}}','{{$item->get_user_monthly_attendance->late_comings ?? '0'}}','{{ $current_month_year }}','{{$item->Monthly_payroll->remarks ?? ''}}')" style="position: relative;text-align: center;"><span>{{ $item->Monthly_payroll ? $item->Monthly_payroll->remarks : '' }}</span>
                                    <i class="fas fa-pencil-alt" style="position: absolute; top: 0; right: 0;"></i></td>
                                @else
                                <td onclick="openConvanceModal('{{$item->id}}','{{$item->company_id}}','{{$item->branch_id}}','{{$item->approval->joining_date ?? ''}}','{{$item->approval->designation_id ?? ''}}','{{$item->salary->basic_salary ?? ''}}','{{$item->Monthly_payroll->conveince_allowance ?? ''}}','{{$item->salary->net_salary ?? '0'}}','{{$item->salary->mobile_allowance ?? '0'}}','{{$item->get_user_monthly_attendance->late_comings ?? '0'}}','{{ $current_month_year }}','{{$item->Monthly_payroll->remarks ?? ''}}')" style="position: relative;text-align: center;"><span>{{ $item->Monthly_payroll ? $item->Monthly_payroll->remarks : '' }}</span>
                                    <i class="fas fa-pencil-alt" style="position: absolute; top: 0; right: 0;"></i></td>
                                @endif
                                    <td>
                                        @php $role_id = auth()->user()->role_id; @endphp
                                        @if ($role_id == '1' || $user->haspermission(['status-update-all']))
                                            <div class="dropdown dropdown-btn-group btn-group action-label">
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle btn-dropdown-fs" href="#" data-toggle="dropdown" aria-expanded="false">
                                                    @if($item->Monthly_payroll)
                                                        @if($item->Monthly_payroll->status == '1')
                                                            <i class="fa-solid fa-circle-dot text-success"></i> Approved
                                                        @elseif($item->Monthly_payroll->status == '2')
                                                            <i class="fa-solid fa-circle-dot text-danger"></i> Declined
                                                        @else
                                                            <i class="fa-solid fa-circle-dot text-primary"></i> Pending
                                                        @endif
                                                    @else
                                                        <i class="fa-solid fa-circle-dot text-primary"></i> Pending
                                                    @endif
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-right" style="position: absolute; margin: 0px; transform: translate(3px, -33px);">
                                                    @if($item->Monthly_payroll)
                                                        @if($item->Monthly_payroll->status == '2')
                                                            <a href="{{ route('monthly.payroll.process.approve', ['id' => $item->id]) }}" class="dropdown-item btn-dropdown-fs" data-bs-toggle="modal" data-bs-target="#approve_leave">
                                                                <i class="fa-solid fa-circle-dot text-success"></i> Approved
                                                            </a>
                                                        @elseif($item->Monthly_payroll->status == '1')
                                                            <a href="{{ route('monthly.payroll.process.decline', ['id' => $item->id]) }}" class="dropdown-item btn-dropdown-fs">
                                                                <i class="fa-solid fa-circle-dot text-danger"></i> Declined
                                                            </a>
                                                        @elseif($item->Monthly_payroll->status == '0')
                                                            <a href="{{ route('monthly.payroll.process.decline', ['id' => $item->id]) }}" class="dropdown-item btn-dropdown-fs">
                                                                <i class="fa-solid fa-circle-dot text-danger"></i> Declined
                                                            </a>
                                                            <a href="{{ route('monthly.payroll.process.approve', ['id' => $item->id]) }}" class="dropdown-item btn-dropdown-fs" data-bs-toggle="modal" data-bs-target="#approve_leave">
                                                                <i class="fa-solid fa-circle-dot text-success"></i> Approved
                                                            </a>
                                                        @endif
                                                    @else
                                                    <form action="{{ route('save.monthly.payroll.emp_salary') }}" method="post" id="statusFormId">
                                                        @csrf
                                                                <input type="hidden" value="{{$item->id}}" name="emp_id" id="emp_id_app">
                                                                <input type="hidden" name="company_id" value="{{$item->company_id}}" id="company_id_app">
                                                                <input type="hidden" name="branch_id" value="{{$item->branch_id}}" id="branch_id_app">
                                                                <input type="hidden" name="joining_date" value="{{$item->approval->joining_date ?? ''}}" id="joining_date">
                                                                <input type="hidden" name="designation_id" value="{{$item->approval->designation_id ?? ''}}" id="designation_id">
                                                               
                                                                    <input type="hidden" class="form-control" name="current_salary" value="{{$item->salary->basic_salary ?? ''}}" readonly id="current_salary_app">
                                                                
                                                                    <input type="hidden" class="form-control" value="{{$item->salary->net_salary ?? '0'}}" name="net_salary" readonly id="net_salary_app">
                                                              
                                                                <input type="hidden" class="form-control" value="{{$item->salary->mobile_allowance ?? '0'}}" readonly name="mobile_allowance" id="mobile_allowance_app">
                                                                
                                                                <input type="hidden" class="form-control" value="{{$item->get_user_monthly_attendance->late_comings ?? '0'}}" readonly name="late_comings" id="late_comings_app">
                                                                
                                                                <input type="hidden" class="form-control" name="increment" readonly id="increment">
                                                               
                                                                <input type="hidden" class="form-control" name="arrears" readonly id="arrears">
                                                                
                                                                <input type="hidden" class="form-control" name="absent_ELA" readonly id="absent_ELA">
                                                                
                                                                <input type="hidden" class="form-control" name="absent_L_adj" readonly id="absent_L_adj">
                                                              
                                                                <input type="hidden" class="form-control" name="late_deduction" readonly id="late_deduction">
                                                                <input type="hidden" name="year_month" value="{{ $current_month_year }}" id="year_month">
                                                            
                                                                <input type="hidden" class="form-control" value="{{$item->Monthly_payroll->conveince_allowance ?? ''}}" name="conveince_allowance" id="conveince_allowance_app" readonly required placeholder="Enter Conveince">
                                                                
                                                                <input type="hidden" class="form-control" value="{{$item->Monthly_payroll->remarks ?? ''}}" name="remark" id="remark_remark_app" readonly required placeholder="Enter Remarks">
                                                                <input type="hidden" name="approval" id="approvalValue" value="1">
                                                            
                                                                <a href="#" class="dropdown-item btn-dropdown-fs" onclick="setApprovalValue(1)">
                                                                    <i class="fa-solid fa-circle-dot text-success"></i> Approved
                                                                </a>
                                                                <a href="#" class="dropdown-item btn-dropdown-fs" onclick="setApprovalValue(2)">
                                                                    <i class="fa-solid fa-circle-dot text-danger"></i> Declined
                                                                </a>
                                                    </form>
                                                    @endif
                                                </ul>
                                            </div>
                                        @else
                                            <a style="pointer-events: none;" class="btn btn-white btn-sm btn-rounded dropdown-toggle btn-dropdown-fs" href="#" data-toggle="dropdown" aria-expanded="false">
                                                @if($item->Monthly_payroll && $item->Monthly_payroll->status == '1')
                                                    <i class="fa-solid fa-circle-dot text-success"></i> Approved
                                                @else
                                                    <i class="fa-solid fa-circle-dot text-danger"></i> Declined
                                                @endif
                                            </a>
                                        @endif
                                    </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal fade" id="convance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Conveince</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="{{route('save.monthly.payroll.emp_salary')}}" method="post">
                    @csrf
                <div class="modal-body">
                        <input type="hidden" name="emp_id" id="emp_id">
                        <input type="hidden" name="company_id" id="company_id">
                        <input type="hidden" name="branch_id" id="branch_id">
                        <input type="hidden" name="joining_date" id="joining_date">
                        <input type="hidden" name="designation_id" id="designation_id">
                        <label for="nameInput" class="form-label">Current Salary<span class="red"
                            style="font-size:14px;">*</span></label>
                            <input type="number" class="form-control" name="current_salary" readonly id="current_salary">
                        <label for="nameInput" class="form-label">Net Salary<span class="red"
                            style="font-size:14px;">*</span></label>
                            <input type="number" class="form-control" name="net_salary" readonly id="net_salary">
                        <label for="nameInput" class="form-label">Mobile Allowance<span class="red"
                            style="font-size:14px;">*</span></label>
                        <input type="number" class="form-control" value="{{old('mobile_allowance')}}" readonly name="mobile_allowance" id="mobile_allowance">
                        <label for="nameInput" class="form-label">Late<span class="red"
                            style="font-size:14px;">*</span></label>
                        <input type="number" class="form-control" value="{{old('late_comings')}}" readonly name="late_comings" id="late_comings">
                        <label for="nameInput" class="form-label">Increment<span class="red"
                            style="font-size:14px;">*</span></label>
                        <input type="number" class="form-control" name="increment" readonly id="increment">
                        <label for="nameInput" class="form-label">Arrears<span class="red"
                            style="font-size:14px;">*</span></label>
                        <input type="number" class="form-control" name="arrears" readonly id="arrears">
                        <label for="nameInput" class="form-label">Absent ELA<span class="red"
                            style="font-size:14px;">*</span></label>
                        <input type="number" class="form-control" name="absent_ELA" readonly id="absent_ELA">
                        <label for="nameInput" class="form-label">Absent Late Adj<span class="red"
                            style="font-size:14px;">*</span></label>
                        <input type="number" class="form-control" name="absent_L_adj" readonly id="absent_L_adj">
                        <label for="nameInput" class="form-label">Late Deduction<span class="red"
                            style="font-size:14px;">*</span></label>
                        <input type="number" class="form-control" name="late_deduction" readonly id="late_deduction">
                        <input type="hidden" name="year_month" id="year_month">
                        <label for="nameInput" class="form-label">Conveince<span class="red"
                            style="font-size:14px;">*</span></label>
                        <input type="number" class="form-control" value="{{old('conveince_allowance')}}" name="conveince_allowance" id="conveince_allowance" required placeholder="Enter Conveince">
                        <label for="nameInput" class="form-label">Remarks<span class="red"
                            style="font-size:14px;">*</span></label>
                        <textarea type="text" class="form-control" value="{{old('remark')}}" name="remark" id="remark_remark" required placeholder="Enter Remarks"></textarea>
                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
        <div class="modal fade" id="convanceapprove" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Conveince</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="{{route('save.monthly.payroll.emp_salary')}}" method="post">
                    @csrf
                <div class="modal-body">
                        <input type="hidden" name="emp_id" id="emp_id_app">
                        <input type="hidden" name="company_id" id="company_id_app">
                        <input type="hidden" name="branch_id" id="branch_id_app">
                        <input type="hidden" name="joining_date" id="joining_date">
                        <input type="hidden" name="designation_id" id="designation_id">
                        <label for="nameInput" class="form-label">Current Salary<span class="red"
                            style="font-size:14px;">*</span></label>
                            <input type="number" class="form-control" name="current_salary" readonly id="current_salary_app">
                        <label for="nameInput" class="form-label">Net Salary<span class="red"
                            style="font-size:14px;">*</span></label>
                            <input type="number" class="form-control" name="net_salary" readonly id="net_salary_app">
                        <label for="nameInput" class="form-label">Mobile Allowance<span class="red"
                            style="font-size:14px;">*</span></label>
                        <input type="number" class="form-control" value="{{old('mobile_allowance')}}" readonly name="mobile_allowance" id="mobile_allowance_app">
                        <label for="nameInput" class="form-label">Late<span class="red"
                            style="font-size:14px;">*</span></label>
                        <input type="number" class="form-control" value="{{old('late_comings')}}" readonly name="late_comings" id="late_comings_app">
                        <label for="nameInput" class="form-label">Increment<span class="red"
                            style="font-size:14px;">*</span></label>
                        <input type="number" class="form-control" name="increment" readonly id="increment">
                        <label for="nameInput" class="form-label">Arrears<span class="red"
                            style="font-size:14px;">*</span></label>
                        <input type="number" class="form-control" name="arrears" readonly id="arrears">
                        <label for="nameInput" class="form-label">Absent ELA<span class="red"
                            style="font-size:14px;">*</span></label>
                        <input type="number" class="form-control" name="absent_ELA" readonly id="absent_ELA">
                        <label for="nameInput" class="form-label">Absent Late Adj<span class="red"
                            style="font-size:14px;">*</span></label>
                        <input type="number" class="form-control" name="absent_L_adj" readonly id="absent_L_adj">
                        <label for="nameInput" class="form-label">Late Deduction<span class="red"
                            style="font-size:14px;">*</span></label>
                        <input type="number" class="form-control" name="late_deduction" readonly id="late_deduction">
                        <input type="hidden" name="year_month" id="year_month">
                        <label for="nameInput" class="form-label">Conveince<span class="red"
                            style="font-size:14px;">*</span></label>
                        <input type="number" class="form-control" value="{{old('conveince_allowance')}}" name="conveince_allowance" id="conveince_allowance_app" readonly required placeholder="Enter Conveince">
                        <label for="nameInput" class="form-label">Remarks<span class="red"
                            style="font-size:14px;">*</span></label>
                        <textarea type="text" class="form-control" value="{{old('remark')}}" name="remark" id="remark_remark_app" readonly required placeholder="Enter Remarks"></textarea>
                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                        {{-- <button type="submit" class="btn btn-primary">Save</button> --}}
                    </div>
                </form>
              </div>
            </div>
          </div>
        {{-- <div class="modal fade" id="remarks_payroll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Remarks</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="{{route('save.monthly.payroll.emp_salary')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="emp_id" id="emp_id_remark">
                        <input type="hidden" name="company_id" id="company_id_remark">
                        <input type="hidden" name="branch_id" id="branch_id_remark">
                        <input type="hidden" name="joining_date" id="joining_date_remark">
                        <input type="hidden" name="designation_id" id="designation_id_remark">
                        <input type="hidden" name="current_salary" id="current_salary_remark">
                        <input type="hidden" name="conveince_allowanced" id="conveince_allowanced_remark">
                        <input type="hidden" name="net_salary" id="net_salary_remark">
                        <input type="hidden" name="mobile_allowance" id="mobile_allowance_remark">
                        <input type="hidden" name="late_comings" id="late_comings_remark">
                        <input type="hidden" name="increment" id="increment_remark">
                        <input type="hidden" name="arrears" id="arrears_remark">
                        <input type="hidden" name="absent_ELA" id="absent_ELA_remark">
                        <input type="hidden" name="absent_L_adj" id="absent_L_adj_remark">
                        <input type="hidden" name="late_deduction" id="late_deduction_remark">
                        <input type="hidden" name="year_month" id="year_month_remark">
                        <input type="hidden" name="conveince_allowance" id="conveince_allowance_remark">
                        <label for="nameInput" class="form-label">Remarks<span class="red"
                            style="font-size:14px;">*</span></label>
                        <textarea type="text" class="form-control" value="{{old('remark')}}" name="remark" id="remark_remark" required placeholder="Enter Remarks"></textarea>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
              </div>
            </div>
          </div> --}}
        <script type="text/javascript">
            function setApprovalValue(value) {
                document.getElementById('approvalValue').value = value;
                document.getElementById('statusFormId').submit(); // Replace 'yourFormId' with the actual ID of your form
            }
         function openConvanceModal(emp_id,company_id,branch_id,joining_date,designation_id,basic_salary,conveince_allowanced,net_salary,mobile_allowance,late_comings,year_month,remarks) {
                $('#convance').modal('show');
                $('#emp_id').val(emp_id);
                $('#company_id').val(company_id);
                $('#branch_id').val(branch_id);
                $('#joining_date').val(joining_date);
                $('#designation_id').val(designation_id);
                $('#current_salary').val(basic_salary);
                $('#conveince_allowance').val(conveince_allowanced);
                $('#net_salary').val(net_salary);
                $('#mobile_allowance').val(mobile_allowance);
                $('#late_comings').val(late_comings);
                $('#remark_remark').val(remarks);
                $('#year_month').val(year_month);
            }
         function openConvanceModalApprove(emp_id,company_id,branch_id,joining_date,designation_id,basic_salary,conveince_allowanced,net_salary,mobile_allowance,late_comings,year_month,remarks) {
                $('#convanceapprove').modal('show');
                $('#emp_id_app').val(emp_id);
                $('#company_id_app').val(company_id);
                $('#branch_id_app').val(branch_id);
                $('#joining_date_app').val(joining_date);
                $('#designation_id_app').val(designation_id);
                $('#current_salary_app').val(basic_salary);
                $('#conveince_allowance_app').val(conveince_allowanced);
                $('#net_salary_app').val(net_salary);
                $('#mobile_allowance_app').val(mobile_allowance);
                $('#late_comings_app').val(late_comings);
                $('#remark_remark_app').val(remarks);
                $('#year_month_app').val(year_month);
            }
        //  function openRemarksModal(emp_id,company_id,branch_id,joining_date,designation_id,basic_salary,conveince_allowanced,net_salary,mobile_allowance,late_comings,year_month,remarks) {
        //         $('#remarks_payroll').modal('show');
        //         $('#emp_id_remark').val(emp_id);
        //         $('#company_id_remark').val(company_id);
        //         $('#branch_id_remark').val(branch_id);
        //         $('#joining_date_remark').val(joining_date);
        //         $('#designation_id_remark').val(designation_id);
        //         $('#current_salary_remark').val(basic_salary);
        //         $('#net_salary_remark').val(net_salary);
        //         $('#mobile_allowance_remark').val(mobile_allowance);
        //         $('#late_comings_remark').val(late_comings);
        //         $('#year_month_remark').val(year_month);
        //         $('#conveince_allowanced_remark').val(conveince_allowanced);
        //         $('#remark_remark').val(remarks);
        //     }
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
                        text: '<img src="{{ asset('assets/images/pdf.png') }}" />',
                        exportOptions: {
                            columns: ':not(:last-child):not(:last-child-1)'
                        }
                    },
                ]
            });
            var selectFormHTML = `
                <div class="col-lg-3 col-md-3 mb-3">
                    <form method="GET" id="myForm" action="{{ route('monthly.payroll.emp_salary') }}">
                            <input type="hidden" name="year_month"
                                value="{{ date('Y-m', strtotime($current_month_year)) }}" class="form-control">
                            <div class="form-group position-relative caret-holder px-1 mb-1">
                                <select class="form-control m-b" id="selectBranch" name="branch_id" required=""
                                    style="appearance: none;">
                                    <option value="" disabled="">Select Location</option>
                                    <option value="all" {{ $selected == 'all' ? 'selected' : '' }}>All</option>
                                    @forelse ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ $selected == $branch->id ? 'selected' : '' }}>
                                            {{ ucwords($branch->branch_name) }}</option>
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
            $('#selectBranch').change(function() {
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
