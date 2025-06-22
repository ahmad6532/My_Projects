
@extends('layouts.admin.master')
@section('content')
<!-- Start Content-->
@php
$employeeId = 0;
if (!empty(Session::get('employee_id'))) {
    $employeeId = Session::get('employee_id');
}
@endphp
<div class="container-fluid">
    @if(session("success"))
        <div class="alert alert_vt" id="alertID">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <div class="alert alert-success small " style="max-width:100%;">{{ session('success')}}</div> 
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="nav nav-pills navtab-bg nav-pills-tab text-center justify-content-center" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link  mt-2 py-2" id="custom-v-pills-billing-tab"  href="{{url('employee/directory/edit-employee/'.base64_encode($employeeId))}}" role="tab" aria-controls="custom-v-pills-billing"
                                    aria-selected="true">Personal Details
                                </a>
                                <a class="nav-link active show mt-2 py-2" id="custom-v-pills-shipping-tab"  href="#" role="tab" aria-controls="custom-v-pills-shipping"
                                    aria-selected="false">Education</a>
                                <a class="nav-link mt-2 py-2" id="custom-v-pills-payment-tab"  href="{{url('employee/directory/edit-experiences/'.base64_encode($employeeId))}}" role="tab" aria-controls="custom-v-pills-payment"
                                    aria-selected="false">Employment</a>
                                <a class="nav-link mt-2 py-2" id="custom-v-pills-payment1-tab"  href="{{url('employee/directory/edit-refrences/'.base64_encode($employeeId))}}" role="tab" aria-controls="custom-v-pills-payment2"
                                    aria-selected="false">References</a>
                                <a class="nav-link mt-2 py-2" id="custom-v-pills-payment1-tab"  href="{{url('employee/directory/edit-account/'.base64_encode($employeeId))}}" role="tab" aria-controls="custom-v-pills-payment2"
                                    aria-selected="false">Account</a>
                                <a class="nav-link mt-2 py-2" id="custom-v-pills-payment2-tab"  href="{{url('employee/directory/edit-approval/'.base64_encode($employeeId))}}" role="tab" aria-controls="custom-v-pills-payment2"
                                    aria-selected="false">Approvals</a>
                            </div>  
                        </div> <!-- end col-->
                        
                        <div class="col-lg-12">
                            <div class="tab-content main-tabs-content">
                                <div class="tab-pane fade active show" id="custom-v-pills-billing" role="tabpanel" aria-labelledby="custom-v-pills-billing-tab">
                                <div class="tab-pane fade active show" id="custom-v-pills-shipping" role="tabpanel" aria-labelledby="custom-v-pills-shipping-tab">
                                    <div class="border p-2 mt-4">
                                        <h1 class="text-heading_vt text-overlap_vt" style="width:90px;">Education</h1>
                                        <form action="{{url('update-employee-education/'.base64_encode($employeeId))}}" method="POST" >
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="edit" value="preview">
                                            <div class="mt-4 mb-3">
                                                <div class="table-responsive">
                                                    <table id="table-ID" class="table table-bordered mb-0 form-table_vt">
                                                        <thead class="table-head-bg form-head-detail">
                                                        <tr>
                                                            <th>DEGREE/CERTIFICATE</th>
                                                            <th>SUBJECT</th>
                                                            <th>GRADE/DIV</th>
                                                            <th>From</th>
                                                            <th>To</th>
                                                            <th>INSTITUTION</th>
                                                            <th style="border:0;"></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="form-body-detail">
                                                            @forelse($EmpEducation as $index => $item)
                                                                <tr  id="#row_{{($item?$item->id:'')}}">
                                                                    <input id="edu_id" name="edu_id[]" class="form-control" value="{{ ($item?$item->id:'') }}" type="hidden"/>
                                                                    <td><input id="emp_degree" name="emp_degree[]" placeholder="Enter degree" class="form-control" required  value="{{ old('emp_degree',$item->degree) }}" type="text"/></td>
                                                                    <td><input id="major_sub" name="major_sub[]" placeholder="Subject" class="form-control"  required value="{{ old('major_sub',$item->subject) }}" type="text"/></td>
                                                                    <td><input id="grade_division" name="grade_division[]" placeholder="Grade" class="form-control" required  value="{{ old('grade_division',$item->grade) }}" type="text"/></td>
                                                                    <td><input id="degree_from" name="degree_from[]" class="form-control" value="{{ $item->degree_from }}" type="date"/> </td>
                                                                    <td><input id="degree_to" name="degree_to[]" class="form-control" value="{{ $item->degree_to }}" type="date"/> </td>
                                                                    <td><input id="institute" name="institute[]" placeholder="Institution" class="form-control" value="{{ old('institute',$item->institution) }}"  type="text"/></td>
                                                                    {{-- @if ($index == 0 && empty(old('emp_degree.0')) && empty(old('major_sub.0')) && empty(old('grade_division.0')) && empty(old('degree_from.0')) && empty(old('degree_to.0')) && empty(old('institute.0')))
                                                                        <td class="align-middle" style="border: 0;"> --}}
                                                                            {{-- <div id="deleteField" onclick="javascript:deleteField('{{ $item->id }}');" class="input-field-btn  bg-danger" title="Delete">
                                                                                <i class="fontello icon-trash-1 color-white"></i>
                                                                            </div> --}}
                                                                        {{-- </td>
                                                                    @else --}}
                                                                    {{-- @if ($loop->last) --}}
                                                                        <td class="align-middle" style="border: 0;">
                                                                            <div id="addnewfield" onclick="javascript:addField();" class="input-field-btn" title="Add New">
                                                                                <i class="fontello icon-plus2 color-white"></i>
                                                                            </div>
                                                                        </td>
                                                                    {{-- @endif --}}
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <input id="edu_id" name="edu_id[]" class="form-control" value="" type="hidden"/>
                                                                    <td><input id="emp_degree" name="emp_degree[]" placeholder="Enter degree" class="form-control" required  value="{{ old('emp_degree') }}" type="text"/></td>
                                                                    <td><input id="major_sub" name="major_sub[]" placeholder="Subject" class="form-control"  required value="{{ old('major_sub') }}" type="text"/></td>
                                                                    <td><input id="grade_division" name="grade_division[]" placeholder="Grade" class="form-control" required  value="{{ old('grade_division') }}" type="text"/></td>
                                                                    <td><input id="degree_from" name="degree_from[]" class="form-control" value="{{ old('degree_from',date('d-m-Y')) }}" type="date"/> </td>
                                                                    <td><input id="degree_to" name="degree_to[]" class="form-control" value="{{ old('degree_to',date('d-m-Y')) }}" type="date"/> </td>
                                                                    <td><input id="institute" name="institute[]" placeholder="Institution" class="form-control" value="{{ old('institute') }}"  type="text"/></td>
                                                                    {{-- @if ($index == 0 && empty(old('emp_degree.0')) && empty(old('major_sub.0')) && empty(old('grade_division.0')) && empty(old('degree_from.0')) && empty(old('degree_to.0')) && empty(old('institute.0')))
                                                                        <td class="align-middle" style="border: 0;"> --}}
                                                                            {{-- <div id="deleteField" onclick="javascript:deleteField('{{ $item->id }}');" class="input-field-btn  bg-danger" title="Delete">
                                                                                <i class="fontello icon-trash-1 color-white"></i>
                                                                            </div> --}}
                                                                        {{-- </td>
                                                                    @else --}}
                                                                    {{-- @if ($loop->last) --}}
                                                                    <td class="align-middle" style="border: 0;">
                                                                        <div id="addnewfield" onclick="javascript:addField();" class="input-field-btn" title="Add New">
                                                                            <i class="fontello icon-plus2 color-white"></i>
                                                                        </div>
                                                                    </td>
                                                                    {{-- @endif --}}
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div>
                                                <h1 class="text-heading_vt">Any other qualification/training</h1>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <input id="other_qali_ID" name="other_qali_ID" class="form-control" value="{{ ($Empotherqalifications?$Empotherqalifications->id:'') }}" type="hidden"/>
                                                            <textarea name="other_qualifications" class="form-control" value="" id="example-textarea" rows="3" placeholder="Enter other qualification/training">{{($Empotherqalifications?$Empotherqalifications->other_qualifications:'')}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="transport_vt">
                                                <h1 class="text-heading_vt">Language</h1>
                                                        <input name="emp_language_id" class="form-control" value="" type="hidden"/>
                                                        <div class="d-flex">
                                                            <div class="custom-control custom-checkbox pr-4">
                                                                <input type="checkbox" value="English" name="emp_language1" {{(in_array('English',$language_name)?'checked="checked"':'')}} class="custom-control-input" id="checkbox-signin1">
                                                                <label class="custom-control-label" for="checkbox-signin1"> English</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" value="Urdu" name="emp_language2" {{(in_array('Urdu',$language_name)?'checked="checked"':'')}} class="custom-control-input" id="checkbox-form">
                                                                <label class="custom-control-label" for="checkbox-form"> Urdu</label>
                                                            </div>
                                                        </div>
                                                        {{-- <div class="form-group mt-2 mb-0">
                                                            <label for="billing-email-address">Any Other (Specify)</label>
                                                        </div> --}}
                                                        {{-- <div class=" pb-2">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        @php
                                                                            $excludeLanguages = ['English', 'Urdu'];
                                                                            $languagesToCheck = array_diff($language_name, $excludeLanguages);

                                                                            $foundLanguages = [];
                                                                            foreach ($languagesToCheck as $language) {
                                                                                if (in_array($language, $language_name)) {
                                                                                    $foundLanguages[] = $language;
                                                                                }
                                                                            }

                                                                            $languageString = implode(', ', $foundLanguages);

                                                                        @endphp
                                                                        <input name="other_emp_language" class="form-control" value="{{$languageString}}" type="text" placeholder="Enter Specify" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> --}}
                                              
                                            </div>
                                    </div>
                                    <div class="pt-4 pb-2">
                                        <div class="row">
                                            <div class="col-md-12">
                                            <a  href="{{url('/employee/directory/edit-employee/'.$employeeId,['edit' => "edit"])}}" class="page-btn page-btn-outline hover-btn">Previous</a>
                                                <Button type="submit" name="submit" class="page-btn mbl-view-btn">Update & Continue</Button>
                                            </div>
                                        </div>
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
<script type="text/javascript">
        document.getElementById('addnewfield').onclick =function addField() {
        var input = '';
        input+='<tr>';
        input+='<td><input name="emp_degree[]" class="form-control" value="{{ old('emp_degree') }}" type="text" placeholder="" id="billing-phone" /></td>';
        input+='<td><input name="major_sub[]" class="form-control" value="{{ old('major_sub') }}" type="text" placeholder="" id="billing-phone" /></td>';
        input+='<td><input name="grade_division[]" class="form-control" value="{{ old('grade_division') }}" type="text" placeholder="" id="billing-phone" /></td>';
        input+='<td><input name="degree_from[]" class="form-control" value="{{ old('degree_from') }}" type="date" placeholder="" id="billing-phone" /> </td> ';
        input+='<td><input name="degree_to[]" class="form-control" value="{{ old('degree_to') }}" type="date" placeholder="" id="billing-phone" /> </td> ';
        input+='<td><input name="institute[]" class="form-control" value="{{ old('institute') }}"  type="text" placeholder="" id="billing-phone" /></td>';
        input+='<td><input name="" class="form-control" value=""  type="text" placeholder="" id="" /></td>';

        input+='  </tr>';
        $('#table-ID tbody').append(input);
        $('#addnewfield').appendTo('#table-ID tbody tr:last td:last');
    }

    setTimeout(function(){
        $('#alertID').hide('slow')
    }, 3000);

    function deleteField(id){
        var recordId = id;
            // Ask for confirmation before proceeding
            if (confirm('Are you sure you want to delete this record?')) {
                // Perform an AJAX request to delete the record
                $.ajax({
                    url: "{{url('/delete-record')}}", // Change this URL to your actual route
                    method: 'get',
                    data:{id:recordId},
                    success: function(response) {
                        if(response.status == true){
                        $('#row_' + recordId).remove();
                        alert('Field Deleted Successfully');
                        }
                    },
                    error: function() {
                        alert('An error occurred while deleting the record.');
                    }
                });
    }
    }
</script>
@endsection

          