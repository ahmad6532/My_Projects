
@extends('layouts.admin.master')
@section('content')
    @php
        $employeeId = 0;
        if (!empty(Session::get('employee_id'))) {
            $employeeId = Session::get('employee_id');
        }
    @endphp
        <!-- Start Content-->
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
                                        <a class="nav-link  mt-2 py-2" id="custom-v-pills-billing-tab"  href="{{url('employee/directory/edit-education/'.base64_encode($employeeId))}}" role="tab" aria-controls="custom-v-pills-billing"
                                            aria-selected="true">Personal Details
                                        </a>
                                        <a class="nav-link mt-2 py-2" id="custom-v-pills-shipping-tab"  href="{{url('employee/directory/edit-education/'.base64_encode($employeeId))}}" role="tab" aria-controls="custom-v-pills-shipping"
                                            aria-selected="false">Education</a>
                                        <a class="nav-link mt-2 py-2" id="custom-v-pills-payment-tab"  href="{{url('employee/directory/edit-experiences/'.base64_encode($employeeId))}}" role="tab" aria-controls="custom-v-pills-payment"
                                            aria-selected="false">Employment</a>
                                        <a class="nav-link active show mt-2 py-2" id="custom-v-pills-payment1-tab"  href="#" role="tab" aria-controls="custom-v-pills-payment2"
                                            aria-selected="false">References</a>
                                        <a class="nav-link  show mt-2 py-2" id="custom-v-pills-payment1-tab"  href="{{url('employee/directory/edit-account/'.base64_encode($employeeId))}}" role="tab" aria-controls="custom-v-pills-payment2"
                                            aria-selected="false">Account</a>
                                        <a class="nav-link mt-2 py-2" id="custom-v-pills-payment2-tab"  href="{{url('employee/directory/edit-approval/'.base64_encode($employeeId))}}" role="tab" aria-controls="custom-v-pills-payment2"
                                            aria-selected="false">Approvals</a>
                                    </div>
                                </div> <!-- end col-->
                                <div class="col-lg-12">
                                    <div class="tab-content main-tabs-content">
                                        <form action="{{url('update-employee-references/'.base64_encode($employeeId))}}" method="POST" >
                                            @csrf
                                            @method('PUT')
                                            <input name="edit" value="preview" type="hidden">
                                            <div class="tab-pane fade active show" id="custom-v-pills-billing" role="tabpanel" aria-labelledby="custom-v-pills-billing-tab">
                                                <div class="tab-pane fade active show" id="custom-v-pills-payment1" role="tabpanel" aria-labelledby="custom-v-pills-payment1-tab">
                                                <div class="border p-2 mt-4">
                                                    <h1 class="text-heading_vt text-overlap_vt" style="width:115px;">Family Data</h1>
                                                        <div class="mt-4 mb-3">
                                                        <h1 class="text-heading_vt">Immediate Family Data ( Parents, Children, Brothers and Sisters )</h1>
                                                            <div class="table-responsive">
                                                                <table id="FamilytableID" class="table table-bordered mb-0 form-table_vt">
                                                                    <thead class="table-head-bg">
                                                                        <tr>
                                                                            <th>Name</th>
                                                                            <th>Phone</th>
                                                                            <th>Relationship</th>
                                                                            <th>Age (years)</th>
                                                                            <th>Occupation</th>
                                                                            <th>Work Place</th>
                                                                            <th>Prefrences</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @forelse ($EmpRefrences as $refrences)
                                                                            <tr>
                                                                                <input name="Ref_id[]" class="form-control" type="hidden" value="{{$refrences->id}}" id="billing-phone" />
                                                                                <td><input placeholder="Enter..." name="memeber_name[]" class="form-control"  type="text" value="{{ $refrences->memeber_name }}" id="memeber_name" /></td>
                                                                                <td><input placeholder="Enter..." name="phone_number[]" class="form-control"  required type="intiger" value="{{ $refrences->phone_number }}" id="phone_number" /></td>
                                                                                <td><input placeholder="Enter..." name="memeber_relation[]" class="form-control"  type="text" value="{{ $refrences->memeber_relation }}" id="memeber_relation" /></td>
                                                                                <td><input placeholder="Enter..." name="memeber_age[]" class="form-control"  type="text" value="{{ $refrences->memeber_age }}" id="memeber_age" /></td>
                                                                                <td><input placeholder="Enter..." name="memeber_occupation[]" class="form-control"  type="text" value="{{ $refrences->memeber_occupation }}" id="memeber_occupation" /> </td>
                                                                                <td><input placeholder="Enter..." name="place_of_work[]" class="form-control"  type="text" value="{{ $refrences->place_of_work }}" id="place_of_work" /></td>
                                                                                <td>
                                                                                    <select name="contact_preference[]" id="contact_preference" style="margin:10px auto;">
                                                                                        <option value="">Select</option>
                                                                                        <option value="1" {{ ($refrences->emergency_preference == "1"?'selected':'') }}>Primary</option>
                                                                                        <option value="2" {{ ($refrences->emergency_preference == "2"?'selected':'') }}>Secondary</option>
                                                                                    </select>
                                                                                </td>
                                                                                <td class="align-middle" style="border:0;">
                                                                                    <div id="addfamilyrow" onclick="addFamilyField();" class="input-field-btn">
                                                                                    <i class="fontello icon-plus2 color-white"></i>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @empty
                                                                            <tr>
                                                                                <td><input placeholder="Enter..." name="memeber_name[]" class="form-control"  type="text" value="{{ old('memeber_name') }}" id="memeber_name" /></td>
                                                                                <td><input placeholder="Enter..." name="phone_number[]" class="form-control"  required type="intiger" value="{{ old('phone_number') }}" id="phone_number" /></td>
                                                                                <td><input placeholder="Enter..." name="memeber_relation[]" class="form-control"  type="text" value="{{ old('memeber_relation') }}" id="memeber_relation" /></td>
                                                                                <td><input placeholder="Enter..." name="memeber_age[]" class="form-control"  type="text" value="{{ old('memeber_age') }}" id="memeber_age" /></td>
                                                                                <td><input placeholder="Enter..." name="memeber_occupation[]" class="form-control"  type="text" value="{{ old('memeber_occupation') }}" id="memeber_occupation" /> </td>
                                                                                <td><input placeholder="Enter..." name="place_of_work[]" class="form-control"  type="text" value="{{ old('place_of_work') }}" id="place_of_work" /></td>
                                                                                <td>
                                                                                    <select name="contact_preference[]" id="contact_preference" style="margin:10px auto;">
                                                                                        <option value="">Select</option>
                                                                                        <option value="1">Primary</option>
                                                                                        <option value="2">Secondary</option>
                                                                                    </select>
                                                                                </td>
                                                                                <td class="align-middle" style="border:0;">
                                                                                    <div id="addfamilyrow" onclick="addFamilyField();" class="input-field-btn">
                                                                                    <i class="fontello icon-plus2 color-white"></i>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforelse
                                                                    </tbody>
                                                                </table>
                                                            </div> <!-- end .table-responsive-->
                                                        </div>
                                                    <!-- </form> -->
                                                </div>
                                                <div class="border p-2 mt-4">
                                                    <h1 class="text-heading_vt text-overlap_vt" style="width:190px;">Employment History</h1>
                                                    <!-- <form> -->

                                                    <!-- <input type="hidden" name="employeeId" value="" class="form-control"> -->
                                                        <div class="mt-4 mb-3">
                                                            <div class="mb-3">
                                                                <h1 class="text-heading_vt">Do you have any relative working at VIION TECHNOLOGY?</h1>
                                                                <div class="custom-control custom-radio custom-control-inline">
                                                                    <input type="radio" id="yes" value="1" name="yesrelative" class="custom-control-input" onchange="yesworking();" {{(isset($EmpRelative) && count($EmpRelative)>0) ? 'checked':''}}>
                                                                    <label class="custom-control-label" for="yes">Yes</label>
                                                                </div>
                                                                <div class="custom-control custom-radio custom-control-inline">
                                                                    <input type="radio" id="no" value="0" name="yesrelative" onchange="yesworking();" class="custom-control-input" >
                                                                    <label class="custom-control-label" for="no">No</label>
                                                                </div>
                                                            </div>
                                                            @if(isset($EmpRelative) && count($EmpRelative)>0)
                                                            <div class="table-responsive">
                                                            <table class="table table-bordered mb-0 form-table_vt">
                                                                    <thead class="table-head-bg">
                                                                    <tr>
                                                                        <th>Name</th>
                                                                        <th>Position</th>
                                                                        <th>Department</th>
                                                                        <th>Location</th>
                                                                        <th>Relationship</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($EmpRelative as $item)
                                                                    <tr>
                                                                        <td><input name="relative_name" class="form-control" placeholder="Enter name"  type="text" value="{{$item->relative_name}}" id="billing-phone" /></td>
                                                                        <td><input name="relative_position" class="form-control" placeholder="Position" type="text" value="{{$item->relative_position}}" id="billing-phone" /></td>
                                                                        <td><input name="relative_dept" class="form-control" placeholder="Enter Department" type="text" value="{{$item->relative_dept}}" id="billing-phone" /></td>
                                                                        <td><input name="relative_location" class="form-control" placeholder="Enter Location" type="text" value="{{$item->relative_location}}" id="billing-phone" /> </td>
                                                                        <td><input name="relative_relation" class="form-control" placeholder="Relationship" type="text" value="{{$item->relative_relation}}" id="billing-phone" /></td>
                                                                    </tr>
                                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div> <!-- end .table-responsive-->
                                                        </div>
                                                    <!-- </form> -->
                                                </div>
                                                @else
                                                <div id="workingID"  style="display:none" class="table-responsive">
                                                            <table class="table table-bordered mb-0 form-table_vt">
                                                                    <thead class="table-head-bg">
                                                                    <tr>
                                                                        <th>Name</th>
                                                                        <th>Position</th>
                                                                        <th>Department</th>
                                                                        <th>Location</th>
                                                                        <th>Relationship</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <tr>
                                                                        <td><input name="relative_name" class="form-control" placeholder="Enter name" type="text" value="{{ old('relative_name') }}" id="billing-phone" /></td>
                                                                        <td><input name="relative_position" class="form-control" placeholder="Position" type="text" value="{{ old('relative_position') }}" id="billing-phone" /></td>
                                                                        <td><input name="relative_dept" class="form-control" placeholder="Enter Department" type="text" value="{{ old('relative_dept') }}" id="billing-phone" /></td>
                                                                        <td><input name="relative_location" class="form-control" placeholder="Enter Location" type="text" value="{{ old('relative_location') }}" id="billing-phone" /> </td>
                                                                        <td><input name="relative_relation" class="form-control" placeholder="Relationship" type="text" value="{{ old('relative_relation') }}" id="billing-phone" /></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div> <!-- end .table-responsive-->
                                                        </div>
                                                    <!-- </form> -->
                                                </div>
                                                @endif
                                                <div class="border p-2 mt-4">
                                                    <h1 class="text-heading_vt text-overlap_vt" style="width:95px;">References</h1>
                                                    <!-- <form> -->
                                                        <div class="mt-4 mb-3">
                                                            <div class="mb-3">
                                                                <p class="text-para_vt">Two reference to whom you are not related and by whom you have not been employed</p>
                                                                <div class="custom-control custom-radio custom-control-inline">
                                                                    <input type="radio" id="customRadioyes" value="1" name="hasrefrence" class="custom-control-input" onchange="yesrelativeworking();" {{(isset($EmpRelatedRef) && count($EmpRelatedRef)>0) ? 'checked' : ''}}>
                                                                    <label class="custom-control-label" for="customRadioyes">Yes</label>
                                                                </div>
                                                                <div class="custom-control custom-radio custom-control-inline">
                                                                    <input type="radio" id="customRadiono" value="0" name="hasrefrence"  onchange="yesrelativeworking();" class="custom-control-input" >
                                                                    <label class="custom-control-label" for="customRadiono">No</label>
                                                                </div>
                                                            </div>
                                                            @if(isset($EmpRelatedRef) && count($EmpRelatedRef)>0)
                                                            <div class="table-responsive">
                                                            <table class="table table-bordered mb-0 form-table_vt">
                                                                    <thead class="table-head-bg">
                                                                    <tr>
                                                                        <th>Name</th>
                                                                        <th>Position</th>
                                                                        <th>Address</th>
                                                                        <th>Phone Number</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($EmpRelatedRef as $item)
                                                                    <input name="refrence_id[]" class="form-control" type="hidden" value="{{$item->id}}" id="billing-phone" />
                                                                    <tr>
                                                                        <td><input name="refrence_name[]" class="form-control" placeholder="Enter name" type="text"   value="{{$item->refrence_name}}" id="billing-phone" /></td>
                                                                        <td><input name="ref_position[]" class="form-control" type="text" placeholder="Position"  value="{{$item->ref_position}}" id="billing-phone" /></td>
                                                                        <td><input name="ref_address[]" class="form-control" type="text" placeholder="Enter address"  value="{{$item->ref_address}}" id="billing-phone" /></td>
                                                                        <td><input name="ref_phone[]" class="form-control" type="number"  placeholder="Enter phone number" value="{{$item->ref_phone}}" id="billing-phone" /> </td>

                                                                    </tr>
                                                                    @endforeach
                                                                    <!-- <tr>
                                                                        <td><input name="refrence_name1" class="form-control" type="text" placeholder="" id="billing-phone" /></td>
                                                                        <td><input name="ref_position1" class="form-control" type="text" placeholder="" id="billing-phone" /></td>
                                                                        <td><input name="ref_address1" class="form-control" type="text" placeholder="" id="billing-phone" /></td>
                                                                        <td><input name="ref_phone1" class="form-control" type="text" placeholder="" id="billing-phone" /> </td>

                                                                    </tr> -->
                                                                    </tbody>
                                                                </table>
                                                            </div> <!-- end .table-responsive-->
                                                            @else
                                                            <div id="refID" style="display:none" class="table-responsive">
                                                            <table class="table table-bordered mb-0 form-table_vt">
                                                                    <thead class="table-head-bg">
                                                                    <tr>
                                                                        <th>Name</th>
                                                                        <th>Position</th>
                                                                        <th>Address</th>
                                                                        <th>Phone Number</th>

                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @php
                                                                        for($i=0; $i<2; $i++)
                                                                            {
                                                                        @endphp
                                                                    <tr>
                                                                        <td><input name="refrence_name[]" class="form-control" placeholder="Enter name" type="text" value="{{ old('refrence_name') }}" id="billing-phone" /></td>
                                                                        <td><input name="ref_position[]" class="form-control" type="text" placeholder="Position" value="{{ old('ref_position') }}" id="billing-phone" /></td>
                                                                        <td><input name="ref_address[]" class="form-control" type="text" placeholder="Enter address" value="{{ old('ref_address') }}" id="billing-phone" /></td>
                                                                        <td><input name="ref_phone[]" class="form-control" type="text" placeholder="Enter phone number" value="{{ old('ref_phone') }}" id="billing-phone" /> </td>

                                                                    </tr>
                                                                    @php } @endphp
                                                                    <!-- <tr>
                                                                        <td><input name="refrence_name1" class="form-control" type="text" placeholder="" id="billing-phone" /></td>
                                                                        <td><input name="ref_position1" class="form-control" type="text" placeholder="" id="billing-phone" /></td>
                                                                        <td><input name="ref_address1" class="form-control" type="text" placeholder="" id="billing-phone" /></td>
                                                                        <td><input name="ref_phone1" class="form-control" type="text" placeholder="" id="billing-phone" /> </td>

                                                                    </tr> -->
                                                                    </tbody>
                                                                </table>
                                                            </div> <!-- end .table-responsive-->
                                                            @endif
                                                        </div>

                                                </div>
                                                <div class="pt-4 pb-2">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                        <a  href="{{url('/employee/directory/edit-experiences/'.base64_encode($employeeId))}}" class="page-btn page-btn-outline hover-btn">Previous</a>
                                                            <Button type="submit" name="submit" class="page-btn mbl-view-btn">Update & Continue</Button>
                                                        </div>
                                                    </div> <!-- end row -->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <a href="{{url('/employee/directory/edit-account/'.base64_encode($employeeId))}}">
                                        <Button  class="page-btn page-btn-outline hover-btn">Skip</Button>
                                        </a>
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
        function yesworking() {
            if (document.getElementById('yes').checked) {
                document.getElementById('workingID').style.display = 'block';

            } else {
                if(document.getElementById('no').checked){
                    document.getElementById('workingID').style.display = 'none';
                }

            }
        }
        function yesrelativeworking() {
            var d =document.getElementById('customRadioyes').value;

            // alert(d);
            if (document.getElementById('customRadioyes').checked) {
                document.getElementById('refID').style.display = 'block';

            } else {
                if(document.getElementById('customRadiono').checked){
                    document.getElementById('refID').style.display = 'none';
                }

            }
        }


    document.getElementById('addfamilyrow').onclick =function addFamilyField() {

    var input = '';
    input+='<tr>';
    input+='<td><input name="memeber_name[]" class="form-control" type="text" placeholder="" id="memeber_name" /></td>';
    input+='<td><input name="phone_number[]" class="form-control" type="number" placeholder="" id="phone_number" /></td>';
    input+='<td><input name="memeber_relation[]" class="form-control" type="text" placeholder="" id="memeber_relation" /></td>';
    input+='<td><input name="memeber_age[]" class="form-control" type="text" placeholder="" id="memeber_age" /></td>';
    input+='<td><input name="memeber_occupation[]" class="form-control" type="text" placeholder="" id="memeber_occupation" /> </td>';
    input+='<td><input name="place_of_work[]" class="form-control" type="text" placeholder="" id="place_of_work" /></td>';
    input+='<td><select name="contact_preference[]" style="margin:10px auto;">'
                +'<option value="">Select</option>'
                +'<option value="1">Primary</option>'
                +'<option value="2">Secondary</option>'
            +'</select>'
        +'</td>';
    input+='<td><input name="" class="" type="" placeholder="" id="" /></td>';

    input+='</tr>';
    $('#FamilytableID ').append(input);
    }

        $(function() {
        $.ajaxSetup({
            headers : {
                'CSRFToken' : getCSRFTokenValue()
            }
        });
    });
    setTimeout(function(){
            $('#alertID').hide('slow')
            }, 3000);
</script>

@endsection

