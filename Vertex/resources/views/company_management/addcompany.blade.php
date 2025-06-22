@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid">
        @if (session('error'))
            <div class="alert alert_vt" id="alertID">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div class="alert alert-danger small " style="max-width:100%;">{{ session('error') }}</div>
            </div>
        @endif
        @if ($errors->has('email'))
            <div class="alert alert_vt" id="alertID">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div class="alert alert-danger small " style="max-width:100%;">{{ $errors->first('email') }}</div>
            </div>
        @endif
        @if ($errors->has('company_name'))
            <div class="alert alert_vt" id="alertID">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div class="alert alert-danger small " style="max-width:100%;">{{ $errors->first('company_name') }}</div>
            </div>
        @endif
        @if ($errors->has('company_logo'))
            <div class="alert alert_vt" id="alertID">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div class="alert alert-danger small " style="max-width:100%;">{{ $errors->first('company_logo') }}</div>
            </div>
        @endif
        <div class="row justify-content-center mt-3">
            <div class="col-lg-10 user-form rounded">
                <div class="white-bg border" style="padding:15px;">
                    <div class="text-center">
                        <h1 class="text-heading_vt pb-2">Add Company</h1>
                    </div>
                    <form id="myform" action="{{ route('store.company.manage') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group ">
                                    <label for="title">Company Name<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" required class="form-control" placeholder="Enter Company Name">
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group ">
                                    <label for="title">Company Logo<span class="red"
                                            style="font-size:22px;">*</span></label>
                                    <input type="file" id="company_logo" name="company_logo" value="{{ old('company_logo') }}" required class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group ">
                                    <label for="title">Contact Person<span class="red"
                                        style="font-size:22px;">*</span></label>
                                    <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person') }}" class="form-control" placeholder="Enter Person Name">
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group ">
                                    <label for="title">Email<span class="red"
                                        style="font-size:22px;">*</span></label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required placeholder="Enter Your Email">
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group">
                                    <label>Phone<span class="red"
                                        style="font-size:22px;">*</span></label>
                                    <input type="number" id="phone_number" name="phone" value="{{ old('phone') }}" required class="form-control" placeholder="Enter Your Phone Number">
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group">
                                    <label for="title">Telephone</label>
                                <input type="number" id="tel_number" name="tel_number" value="{{ old('tel_number') }}" class="form-control" placeholder="Enter Your Telephone Number">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group position-relative caret-holder">
                                    <label for="title">Country<span class="red"
                                        style="font-size:22px;">*</span></label>
                                    <select id="countrySelect" onchange="getcities()" required class="form-control m-b" value="{{ old('country_id') }}" name="country_id" style="appearance: none;">
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
                                    <label for="title">City<span class="red"
                                        style="font-size:22px;">*</span></label>
                                    <select id="citySelect" class="form-control m-b" required name="city_id" style="appearance: none;">
                                        <option selected disabled>Select City</option>
                                    </select>
                                    <i class="awesom-icon icon-down-dir icon-color"></i>
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group">
                                    <label for="title">Address<span class="red"
                                        style="font-size:22px;">*</span></label>
                                <input type="text" id="address" name="address" required class="form-control" value="{{ old('address') }}" placeholder="Enter Your Address">
                                </div>
                            </div>
                            <div class="col-lg-6 time-icon-holder">
                                <div class="form-group">
                                    <label for="website">Website URL</label>
                                    <input type="url" id="website" name="website" class="form-control" value="{{ old('website') }}" placeholder="Enter Your Website URL">
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
        setTimeout(function() {
            $('#alertID').hide('slow')
        }, 3000);
    </script>
@endsection
