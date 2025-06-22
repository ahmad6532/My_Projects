@extends('layouts.admin.master')
@section('content')
<style>
    .blog-inner-img {
        height: 37px;
        position: absolute;
        right: -8px;
        width: 70px;
        object-fit: contain;
        top: 35%;
    }
</style>
    <div class="container-fluid">
        @if (session('error'))
            <div class="alert alert_vt" id="alertID">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div class="alert alert-danger small " style="max-width:100%;">{{ session('error') }}</div>
            </div>
        @endif
        <div class="row justify-content-center mt-3">
            <div class="col-lg-10 user-form rounded">
                <div class="white-bg border" style="padding:15px;">
                    <div class="text-center">
                        <h1 class="text-heading_vt pb-2">Edit Company</h1>
                    </div>
                    <form id="myform" action="{{ route('update.company.manage',['id' => $companies->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <div class="row">
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group ">
                                    <label for="title">Company Name<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <input type="text" id="company_name" name="company_name" required value="{{old('company_name',$companies->company_name)}}" class="form-control" placeholder="Enter Company Name">
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group ">
                                    <label for="title">Company Logo<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <input type="file" id="company_logo" name="company_logo" value="{{$companies->logo }}" class="form-control">
                                    @if ($companies->logo != null)
                                        <img class="blog-inner-img"
                                            src="{{ $companies->logo != null ? asset($companies->logo): '' }}">
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group ">
                                    <label for="title">Contact Person<span class="red"
                                        style="font-size:22px;">*</span></label>
                                    <input type="text" id="contact_person" name="contact_person" required value="{{old('contact_person',$companies->contact_person)}}" class="form-control" placeholder="Enter Person Name">
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group ">
                                    <label for="title">Email<span class="red"
                                        style="font-size:22px;">*</span></label>
                                    <input type="email" id="email" name="email" required value="{{old('email',$companies->email)}}" class="form-control" placeholder="Enter Your Email">
                                </div>
                            </div>
                            
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group">
                                    <label>Phone<span class="red"
                                        style="font-size:22px;">*</span></label>
                                    <input type="number" id="phone_number" required name="phone" value="{{old('phone',$companies->phone)}}" class="form-control" placeholder="Enter Your Phone Number">
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group">
                                    <label for="title">Telephone</label>
                                <input type="number" id="tel_number" name="tel" value="{{old('tel',$companies->tel)}}" class="form-control" placeholder="Enter Your Telephone Number">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group position-relative caret-holder">
                                    <label for="title">Country<span class="red"
                                        style="font-size:22px;">*</span></label>
                                    <select id="country_id" onchange="getcities()" required class="form-control m-b" name="country_id" style="appearance: none;">
                                        <option value="" disabled>Select Country</option>
                                        @foreach($com_countries as $country)
                                            <option value="{{ $country->country_id }}" {{ old('country_id',$companies->country_id) == $country->country_id ? 'selected' : '' }}>
                                                {{ $country->country_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i class="awesom-icon icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group position-relative caret-holder">
                                    <label for="title">City<span class="red"
                                        style="font-size:22px;">*</span></label>
                                    <select id="city_id" class="form-control m-b" required name="city_id" style="appearance: none;">
                                    </select>
                                    <i class="awesom-icon icon-down-dir purple_vt ca"></i>
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group">
                                    <label for="title">Address<span class="red"
                                        style="font-size:22px;">*</span></label>
                                <input type="text" id="address" value="{{old('address',$companies->address)}}" required name="address" class="form-control" placeholder="Enter Your Address">
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group">
                                    <label for="website">Website URL</label>
                                    <input type="url" id="website" value="{{old('website',$companies->website)}}" name="website" class="form-control" placeholder="Enter Your Website URL">
                                </div>
                            </div>
                            <div class="col-md-12 my-2">
                                <Button type="submit" class="page-btn">Update</Button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
          $(document).ready(function() {
            getcities();
        });

        function getcities() {
            var country_id = $('#country_id').val();
            $.ajax({
            url: "{{route('get.cities')}}",
            type: 'GET',
            data: {
                'country_id': country_id,
            },
            success:function(response){
                if(response.success == true){
                    var result = response.data;
                    var city_id = '{{$companies->city_id}}';
                    $('#city_id').html('');
                    var html = '<option selected disabled>Select City</option>';
                    for (var i = 0; i < result.length; i++){
                        var selected = (city_id !== null && result[i].city_id == city_id) ? 'selected' : '';
                        html += `<option value="${result[i].city_id}" ${selected}>${result[i].city_name}</option>`;
                    }
                    $('#city_id').html(html);
                }
            }
        });
    }
        setTimeout(function() {
            $('#alertID').hide('slow')
        }, 3000);
    </script>
@endsection