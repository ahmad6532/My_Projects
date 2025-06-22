@extends('layouts.admin.master')
@section('content')


    <!-- Start Content-->
    <div class="container-fluid">
        @if(session("error"))
        <div class="alert alert_vt" id="alertID">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <div class="alert alert-danger small " style="max-width:100%;">{{ session('error')}}</div>
        </div>
        @endif
        @if($errors->has("branch_id"))
        <div class="alert alert_vt" id="alertID">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <div class="alert alert-danger small " style="max-width:100%;">{{ $errors->first('branch_id') }}</div>
        </div>
        @endif
        @if($errors->has("branch_name"))
        <div class="alert alert_vt" id="alertID">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <div class="alert alert-danger small " style="max-width:100%;">{{ $errors->first('branch_name') }}</div>
        </div>
        @endif
        <div class="row justify-content-center mt-3">
            <div class="col-lg-10 user-form rounded">
                <div class="white-bg border" style="padding:15px;">
                    <div class="text-center">
                        <h1 class="text-heading_vt pb-2">Update Location</h1>
                    </div>

                    <form id="myform" action="{{route('update.BranchData')}}" method="POST"  enctype="multipart/form-data">
                    @csrf
                    {{-- @method('put') --}}
                    <input type="hidden" value="{{$branch->id}}" name="id">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group position-relative caret-holder">
                                    <label for="title">Company<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <select id="company_id" class="form-control m-b"
                                        name="company_id" style="appearance: none;">
                                        <option value="" disabled selected>Select Company</option>
                                            @foreach ($companies as $company)
                                                <option value="{{$company->id}}" {{($branch->company_id == $company->id) ?'selected':''}}>{{$company->company_name}}</option>
                                            @endforeach
                                    </select>
                                    <i class="awesom-icon icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="text">Location ID <span class="red" style="font-size:22px;">*</span></label>
                                    <input type="text" autocomplete="off" value="{{$branch->branch_id}}" placeholder="Enter additional email" name="branch_id" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="text">Office Location <span class="red" style="font-size:22px;">*</span></label>
                                    <input type="text" autocomplete="off" value="{{$branch->branch_name}}" placeholder="Enter additional email" name="branch_name" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group position-relative caret-holder">
                                    <label for="title">Country <span class="red" style="font-size:22px;">*</span></label>
                                    <select id="country_id" onchange="getCities()" class="form-control m-b" name="country_id" style="appearance: none;">
                                        <option value="" disabled selected>Select Country</option>
                                        @foreach($com_countries as $country)
                                           <option value="{{$country->country_id}}" {{$branch->country_id == $country->country_id ? 'selected' : ''}}>{{$country->country_name}}</option>
                                        @endforeach
                                    </select>
                                    <i class="awesom-icon icon-down-dir purple_vt ca"></i>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group position-relative caret-holder">
                                    <label for="title">City <span class="red cities" style="font-size:22px;">*</span></label>
                                    <select id="city_id" class="form-control m-b" name="city_id" style="appearance: none;">
                                    </select>
                                    <i class="awesom-icon icon-down-dir purple_vt ca"></i>
                                </div>
                            </div>
                        </div>
                        <div class="pt-3 pb-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <Button type="submit" class="page-btn">Update</Button>
                                </div>
                            </div> <!-- end row -->
                        </div>
                </div>
            </div>
        </div>
    </div>
    <script>
         $(document).ready(function() {
            getCities();
        });

        function getCities() {
            var country_id = $('#country_id').val();

            $.ajax({
            url: "{{route('get.cities')}}",
            type: 'GET',
            data: {
                'country_id': country_id,
            },
             success:function(response){
                if(response.success == true){
                    var result=response.data;
                    var city_id = '{{$branch->city_id}}';
                    $('#city_id').html('');
                    var html = '<option selected disabled>Select City</option>';
                    for (var i = 0; i < result.length; i++){
                        var selected = result[i].city_id == city_id ? 'selected':'';
                        html += `<option value="${result[i].city_id}" ${selected}>${result[i].city_name}</option>`;
                    }
                    $('#city_id').html(html);
                }
            }
        });
    }

    setTimeout(function(){
        $('#alertID').hide('slow')
        }, 3000);
</script>
@endsection

