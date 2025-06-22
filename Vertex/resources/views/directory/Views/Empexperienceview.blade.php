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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="nav nav-pills navtab-bg nav-pills-tab text-center justify-content-center"
                                    id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link  mt-2 py-2" id="custom-v-pills-billing-tab"
                                        href="{{ url('/employee/directory/edit-employee/' . base64_encode($employee_id)) }}"
                                        role="tab" aria-controls="custom-v-pills-billing" aria-selected="true">Personal
                                        Details
                                    </a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-shipping-tab"
                                        href="{{ url('/employee/directory/edit-education/' . base64_encode($employee_id)) }}"
                                        role="tab" aria-controls="custom-v-pills-shipping"
                                        aria-selected="false">Education</a>
                                    <a class="nav-link active show mt-2 py-2" id="custom-v-pills-payment-tab" href="#"
                                        role="tab" aria-controls="custom-v-pills-payment"
                                        aria-selected="false">Employment</a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-payment1-tab"
                                        href="{{ url('/employee/directory/edit-refrences/' . base64_encode($employee_id)) }}"
                                        role="tab" aria-controls="custom-v-pills-payment2"
                                        aria-selected="false">References</a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-payment1-tab"
                                        href="{{ url('/employee/directory/edit-account/' . base64_encode($employee_id)) }}"
                                        role="tab" aria-controls="custom-v-pills-payment2"
                                        aria-selected="false">Account</a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-payment2-tab"
                                        href="{{ url('/employee/directory/edit-approval/' . base64_encode($employee_id)) }}"
                                        role="tab" aria-controls="custom-v-pills-payment2"
                                        aria-selected="false">Approvals</a>
                                </div>
                            </div> <!-- end col-->
                            <div class="col-lg-12">
                                <div class="tab-content main-tabs-content">
                                    <div class="tab-pane fade active show" id="custom-v-pills-billing" role="tabpanel"
                                        aria-labelledby="custom-v-pills-billing-tab">

                                        <div class="tab-pane fade active show" id="custom-v-pills-payment" role="tabpanel"
                                            aria-labelledby="custom-v-pills-payment-tab">
                                            <div class="border p-2 mt-4">
                                                <h1 class="text-heading_vt text-overlap_vt" style="width:115px;">Employment
                                                </h1>
                                                <form
                                                    action="{{ url('update-employee-experience/' . base64_encode($employee_id)) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="edit" value="preview">
                                                    <div class="mt-4 mb-3">
                                                        @if (isset($EmpExperience) && count($EmpExperience) > 0)
                                                            <div class="table-responsive">
                                                                <table id="empTableID"
                                                                    class="table table-bordered mb-0 form-table_vt">
                                                                    <thead class="table-head-bg">
                                                                        <tr>
                                                                            <th>ORGANIZATION</th>
                                                                            <th>POSITION</th>
                                                                            <th>SALARY</th>
                                                                            <th>FROM</th>
                                                                            <th>TO</th>
                                                                            <th>REASON FOR LEAVING</th>
                                                                            <th style="border:0;"></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($EmpExperience as $index => $item)
                                                                            <input name="exp_id[]" class="form-control"
                                                                                type="hidden" value="{{ $item->id }}"
                                                                                id="billing-phone" />
                                                                            <tr>
                                                                                <td><input name="organization[]"
                                                                                        class="form-control"
                                                                                        placeholder="Enter Organization"
                                                                                        value="{{ $item->organization }}"
                                                                                        type="text" /></td>
                                                                                <td><input name="prev_position[]"
                                                                                        class="form-control"
                                                                                        placeholder="Position"
                                                                                        type="text"
                                                                                        value="{{ $item->prev_position }}" />
                                                                                </td>
                                                                                <td><input name="prev_salary[]"
                                                                                        class="form-control"
                                                                                        placeholder="Salary" type="text"
                                                                                        value="{{ $item->prev_salary }}" />
                                                                                </td>
                                                                                <td
                                                                                    style="border-right: 1px solid #dee2e6 !important;">
                                                                                    <input name="exp_from[]"
                                                                                        value="{{ $item->exp_from }}"
                                                                                        class="form-control"
                                                                                        type="date" />
                                                                                </td>
                                                                                <td><input name="exp_to[]"
                                                                                        value="{{ $item->exp_to }}"
                                                                                        class="form-control"
                                                                                        type="date" /> </td>
                                                                                <td><input name="reason_for_leaving[]"
                                                                                        placeholder="Reason for leaving"
                                                                                        value="{{ $item->reason_for_leaving }}"
                                                                                        class="form-control"
                                                                                        type="text" /></td>

                                                                                {{-- @if ($index == 0 && empty(old('organization.0')) && empty(old('prev_position.0')) && empty(old('prev_salary.0')) && empty(old('exp_from.0')) && empty(old('exp_to.0')) && empty(old('reason_for_leaving.0')))
                                                                        <td class="align-middle" style="border: 0;"> --}}
                                                                                {{-- <div id="deleteField" onclick="javascript:deleteField('{{ $item->id }}');" class="input-field-btn  bg-danger" title="Delete">
                                                                                <i class="fontello icon-trash-1 color-white"></i>
                                                                            </div> --}}
                                                                                {{-- </td> --}}

                                                                                {{-- @elseif ($loop->last) --}}
                                                                                <td class="align-middle"
                                                                                    style="border:0;">
                                                                                    <div id="addinputfield"
                                                                                        onclick="javascript:addEmployeeField();"
                                                                                        class="input-field-btn">
                                                                                        <i
                                                                                            class="fontello icon-plus2 color-white"></i>
                                                                                    </div>
                                                                                </td>
                                                                                {{-- @endif --}}
                                                                            </tr>
                                                                        @endforeach

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        @else
                                                            <div class="table-responsive">
                                                                <table id="empTableID"
                                                                    class="table table-bordered mb-0 form-table_vt">
                                                                    <thead class="table-head-bg">
                                                                        <tr>
                                                                            {{-- <th class="p-0"> --}}
                                                                            {{-- <table style="width:100%;" class="inner-table-head">
                                                                            <tr style="border-bottom: 1px solid #dee2e6 !important;"> --}}
                                                                            <th> ORGANIZATION</th>
                                                                            {{-- </tr> --}}
                                                                            {{-- <tr>
                                                                                <th> Name Address & Ph: #</th>
                                                                            </tr>
                                                                        </table> --}}
                                                                            {{-- </th> --}}
                                                                            <th>POSITION</th>
                                                                            <th>SALARY</th>
                                                                            <th>FROM</th>
                                                                            <th>TO</th>
                                                                            <th>REASON FOR LEAVING</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td><input name="organization[]"
                                                                                    placeholder="Enter Organization"
                                                                                    class="form-control"
                                                                                    value="{{ old('organization') }}"
                                                                                    type="text" placeholder=""
                                                                                    id="billing-phone" /></td>
                                                                            <td><input name="prev_position[]"
                                                                                    class="form-control"
                                                                                    placeholder="Position" type="text"
                                                                                    value="{{ old('prev_position') }}"
                                                                                    placeholder="" id="billing-phone" />
                                                                            </td>
                                                                            <td><input name="prev_salary[]"
                                                                                    class="form-control"
                                                                                    placeholder="Salary" type="text"
                                                                                    value="{{ old('prev_salary') }}"
                                                                                    placeholder="" id="billing-phone" />
                                                                            </td>
                                                                            <!-- <td class="p-0">
                                                                                    <table style="width:100%;" class="inner-table-body">
                                                                                        <tr> -->
                                                                            <td
                                                                                style="border-right: 1px solid #dee2e6 !important;">
                                                                                <input name="exp_from[]"
                                                                                    class="form-control" type="date"
                                                                                    placeholder="" id="billing-phone" />
                                                                            </td>
                                                                            <td><input name="exp_to[]"
                                                                                    class="form-control" type="date"
                                                                                    placeholder="" id="billing-phone" />
                                                                            </td>
                                                                            <!-- </tr>
                                                                                    </table>
                                                                                </td> -->
                                                                            <td><input name="reason_for_leaving[]"
                                                                                    placeholder="Reason for leaving"
                                                                                    class="form-control" type="text"
                                                                                    placeholder="" id="billing-phone" />
                                                                            </td>
                                                                            <!-- <td class="align-middle" style="border:0;">
                                                                                    <div id="addinputfield" onclick="addEmployeeField();" class="input-field-btn">
                                                                                    <i class="fontello icon-plus2 color-white"></i>
                                                                                    </div>
                                                                                </td> -->
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div> <!-- end .table-responsive-->
                                                        @endif
                                                    </div>
                                                    @if (isset($anyconvic))
                                                        <input id="newfieldID" name="any_convic_ID" class="form-control"
                                                            required value="{{ $anyconvic->id }}" type="hidden"
                                                            placeholder="" id="billing-phone" />
                                                        <div>
                                                            <div>
                                                                <h1 class="text-heading_vt">Any Conviction by Court, if yes
                                                                    please give details</h1>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <textarea name="any_conviction" class="form-control" id="example-textarea" rows="3"
                                                                                placeholder="Enter qualification/training">{{ $anyconvic->court_conviction }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div> <!-- end row -->
                                                            </div>
                                                        </div>
                                                    @else<div>
                                                            <div>
                                                                <h1 class="text-heading_vt">Any Conviction by Court, if yes
                                                                    please give details</h1>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <textarea name="any_conviction" class="form-control" id="example-textarea" rows="3"
                                                                                placeholder="Enter qualification/training"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div> <!-- end row -->
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <!-- </form> -->
                                            </div>
                                            <div class="border p-2 mt-4">
                                                <h1 class="text-heading_vt text-overlap_vt" style="width:190px;">
                                                    Employment History</h1>
                                                <!-- <form> -->
                                                <div class="mt-4 mb-3">
                                                    <div class="mb-3">
                                                        <h1 class="text-heading_vt">Were you previously employed by VIION
                                                            TECHNOLOGY</h1>
                                                        <div class="custom-control custom-radio custom-control-inline">

                                                            <input type="radio" value="1" id="customRadio121"
                                                                name="prev_employed" onchange="yesemployed();"
                                                                class="custom-control-input"
                                                                {{ isset($EmpByViion) && count($EmpByViion) > 0 ? 'checked' : '' }}>
                                                            <label class="custom-control-label"
                                                                for="customRadio121">Yes</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="customRadio612" value="0"
                                                                name="prev_employed" onchange="yesemployed();"
                                                                class="custom-control-input"
                                                                {{ isset($EmpByViion) && count($EmpByViion) == 0 ? 'checked' : '' }}>
                                                            <label class="custom-control-label"
                                                                for="customRadio612">No</label>
                                                        </div>
                                                    </div>
                                                    @if (isset($EmpByViion) && count($EmpByViion) > 0)
                                                        <div id="prev_ID" class="table-responsive">
                                                            <table class="table table-bordered mb-0 form-table_vt">
                                                                <thead class="table-head-bg">
                                                                    <tr>
                                                                        <th>Position Held</th>
                                                                        <th>Emp #</th>
                                                                        <th>Location</th>
                                                                        <th>From</th>
                                                                        <th>To</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($EmpByViion as $item)
                                                                        <tr>
                                                                            <td><input name="emp_position"
                                                                                    placeholder="Position"
                                                                                    class="form-control"
                                                                                    value="{{ $item->emp_position }}"
                                                                                    type="text" placeholder=""
                                                                                    id="billing-phone" /></td>
                                                                            <td><input name="prev_emp_no"
                                                                                    placeholder="Enter Emp Id"
                                                                                    value="{{ $item->prev_emp_no }}"
                                                                                    class="form-control" type="number"
                                                                                    placeholder="" id="billing-phone" />
                                                                            </td>
                                                                            <td><input name="emp_location"
                                                                                    placeholder="Location"
                                                                                    value="{{ $item->emp_location }}"class="form-control"
                                                                                    type="text" placeholder=""
                                                                                    id="billing-phone" /></td>
                                                                            <!-- <td class="p-0">
                                                                                    <table style="width:100%;" class="inner-table-body">
                                                                                        <tr> -->
                                                                            <td
                                                                                style="border-right: 1px solid #dee2e6 !important;">
                                                                                <input name="date_from"
                                                                                    value="{{ $item->date_from }}"class="form-control"
                                                                                    type="date" placeholder=""
                                                                                    id="billing-phone" />
                                                                            </td>
                                                                            <td><input name="date_to"
                                                                                    value="{{ $item->date_to }}"class="form-control"
                                                                                    type="date" placeholder=""
                                                                                    id="billing-phone" /> </td>
                                                                            <!-- </tr>
                                                                                    </table>
                                                                                </td> -->
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div> <!-- end .table-responsive-->
                                                </div>

                                            </div>
                                        @else
                                            <div id="prev_ID" style="display:none" class="table-responsive">
                                                <table class="table table-bordered mb-0 form-table_vt">
                                                    <thead class="table-head-bg">
                                                        <tr>
                                                            <th>Position Held</th>
                                                            <th>Emp #</th>
                                                            <th>Location</th>
                                                            <th>From</th>
                                                            <th>To</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><input name="emp_position" placeholder="Position"
                                                                    class="form-control"
                                                                    value="{{ old('emp_position') }}" type="text"
                                                                    placeholder="" id="billing-phone" /></td>
                                                            <td><input name="prev_emp_no" placeholder="Enter Emp Id"
                                                                    value="{{ old('prev_emp_no') }}" class="form-control"
                                                                    type="number" placeholder="" id="billing-phone" />
                                                            </td>
                                                            <td><input name="emp_location" placeholder="Location"
                                                                    class="form-control"
                                                                    value="{{ old('emp_location') }}" type="text"
                                                                    placeholder="" id="billing-phone" /></td>
                                                            <!-- <td class="p-0">
                                                                                    <table style="width:100%;" class="inner-table-body">
                                                                                        <tr> -->
                                                            <td style="border-right: 1px solid #dee2e6 !important;"><input
                                                                    name="date_from"
                                                                    value="{{ old('date_from') }}"class="form-control"
                                                                    type="date" placeholder="" id="billing-phone" />
                                                            </td>
                                                            <td><input name="date_to" class="form-control" type="date"
                                                                    value="{{ old('date_to') }}" id="billing-phone" />
                                                            </td>
                                                            <!-- </tr>
                                                                                    </table>
                                                                                </td> -->
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div> <!-- end .table-responsive-->
                                        </div>

                                    </div>
                                    @endif
                                    <div class="pt-4 pb-2">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a href="{{ url('edit-education/' . $employee_id, ['edit' => 'edit']) }}"
                                                    class="page-btn page-btn-outline hover-btn">Previous</a>
                                                <Button type="submit" name="submit" class="page-btn">Update &
                                                    Continue</Button>
                                            </div>
                                        </div> <!-- end row -->
                                    </div>

                                </div>
                                </form>


                            </div>
                        </div> <!-- end col-->
                    </div> <!-- end row-->

                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
    </div>
    </div>
    <script type="text/javascript">
        document.getElementById('addinputfield').onclick = function addEmployeeField() {

            var input = '';
            input += '<tr>';
            input +=
                '<td><input name="organization[]" class="form-control" value="{{ old('organization') }}" type="text" placeholder="" id="billing-phone" /></td>';
            input +=
                '<td><input  name="prev_position[]" class="form-control" type="text" value="{{ old('prev_position') }}" placeholder="" id="billing-phone" /></td>';
            input +=
                '<td><input  name="prev_salary[]" class="form-control" type="text" value="{{ old('prev_salary') }}"placeholder="" id="billing-phone" /></td>';
            input +=
                '<td style="border-right: 1px solid #dee2e6 !important;"><input  name="exp_from[]" class="form-control" type="date" placeholder="" id="billing-phone" /> </td>';
            input +=
                '<td><input  name="exp_to[]" class="form-control" type="date" placeholder="" id="billing-phone" /> </td>';
            input +=
                '<td><input  name="reason_for_leaving[]"  class="form-control" type="text" placeholder="" id="billing-phone" /></td>';
            input += '  </tr>';

            $('#empTableID tbody').append(input);
            $('#addinputfield').appendTo('#empTableID tbody tr:last td:last');
        }

        function yesemployed() {
            // var d =document.getElementById('customRadio405').value;

            // alert(d);
            if (document.getElementById('customRadio121').checked) {
                document.getElementById('prev_ID').style.display = 'block';

            } else {
                // alert('hello');
                if (document.getElementById('customRadio612').checked) {
                    document.getElementById('prev_ID').style.display = 'none';
                }

            }
        }


        setTimeout(function() {
            $('#alertID').hide('slow')
        }, 3000);
    </script>

@endsection
