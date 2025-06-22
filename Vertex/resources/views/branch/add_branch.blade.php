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
                        <h1 class="text-heading_vt pb-2">Add Location</h1>
                    </div>
                    <form id="myform" action="{{route('store.branch')}}" method="POST"  enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group position-relative caret-holder">
                                    <label for="title">Company<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <select id="company_id" class="form-control m-b"
                                        name="company_id" value="{{ old('company_id') }}" style="appearance: none;">
                                        <option value="{{ old('company_id') }}" disabled selected>Select Company</option>
                                            @foreach ($companies as $company)
                                                <option value="{{$company->id}}" value="{{ old('company_id') }}">{{$company->company_name}}</option>
                                            @endforeach
                                    </select>
                                    <i class="awesom-icon icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="title">Location ID <span class="red" style="font-size:22px;">*</span></label>
                                    <input type="text" placeholder="Enter Branch ID" name="branch_id" value="{{ old('branch_id') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="title">Office Location <span class="red" style="font-size:22px;">*</span></label>
                                    <input type="text" placeholder="Enter Branch Name" name="branch_name" value="{{ old('branch_name') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group position-relative caret-holder">
                                    <label for="title">Country <span class="red" style="font-size:22px;">*</span></label>
                                    <select id="countrySelect" onchange="getcities()" class="form-control m-b" name="country_id" value="{{ old('country_id') }}" style="appearance: none;">
                                        <option value="" disabled selected>Select Country</option>
                                        @foreach($com_countries as $country)
                                            <option value="{{$country->country_id}}">{{$country->country_name}}</option>
                                        @endforeach
                                    </select>
                                    <i class="awesom-icon icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group position-relative caret-holder">
                                    <label for="title">City <span class="red cities" style="font-size:22px;">*</span></label>
                                    <select id="citySelect" class="form-control m-b" name="city_id" value="{{ old('city_id') }}" style="appearance: none;">
                                        <option selected disabled>Select City</option>
                                    </select>
                                    <i class="awesom-icon icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-md-12 my-2">
                                <Button type="submit" class="page-btn">Save</Button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function getcities() {
            var country_id = $('#countrySelect').val();
            $.ajax({
                method: 'get',
                dataType: 'json',
                url: '{{ route('getcities') }}',
                data: { country_id: country_id },
                success: function(response) {
                    var data = response.data;
                    $('#citySelect').html('');
                    var html = '<option selected disabled>Select City</option>';
                    for (var i = 0; i < data.length; ++i) {
                        html += `<option value="${data[i].city_id}">${data[i].city_name}</option>`;
                    }
                    $('#citySelect').html(html);
                }
            });
        }
    </script>
    
<script>
setTimeout(function(){
    $('#alertID').hide('slow')
    }, 3000);
</script>
@endsection

