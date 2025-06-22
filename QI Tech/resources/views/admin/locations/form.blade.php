<div ng-app="myApp" ng-init="location_type_id='{{old('location_type_id', optional($location)->location_type_id)}}'">

<div class="form-group {{ $errors->has('location_type_id') ? 'has-error' : '' }}">
    <label for="location_type_id" class="">Location Type</label>
    <div class="col-md-10">
        <select class="form-control" id="location_type_id" name="location_type_id" required="true" ng-model="location_type_id">
        	    <option value="">Please select</option>
        	@foreach ($locationTypes as $key => $locationType)
			    <option value="{{ $key }}">
			    	{{ $locationType }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('location_type_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('location_pharmacy_type_id') ? 'has-error' : '' }}" ng-if="location_type_id == 1">
    <label for="location_pharmacy_type_id" class="">Location Pharmacy Type</label>
    <div class="col-md-10">
        <select class="form-control" id="location_pharmacy_type_id" name="location_pharmacy_type_id">
        	    <option value="" style="display: none;" {{ old('location_pharmacy_type_id', optional($location)->location_pharmacy_type_id ?: '') == '' ? 'selected' : '' }} disabled selected>Enter location pharmacy type here...</option>
        	@foreach ($locationPharmacyTypes as $key => $locationPharmacyType)
			    <option value="{{ $key }}" {{ old('location_pharmacy_type_id', optional($location)->location_pharmacy_type_id) == $key ? 'selected' : '' }}>
			    	{{ $locationPharmacyType }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('location_pharmacy_type_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('location_regulatory_body_id') ? 'has-error' : '' }}">
    <label for="location_regulatory_body_id" class="">Location Regulatory Body</label>
    <div class="col-md-10">
        <select class="form-control" id="location_regulatory_body_id" name="location_regulatory_body_id">
        	    <option value="" style="display: none;" {{ old('location_regulatory_body_id', optional($location)->location_regulatory_body_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select location regulatory body</option>
        	@foreach ($locationRegulatoryBodies as $key => $locationRegulatoryBody)
			    <option value="{{ $key }}" {{ old('location_regulatory_body_id', optional($location)->location_regulatory_body_id) == $key ? 'selected' : '' }}>
			    	{{ $locationRegulatoryBody }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('location_regulatory_body_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('registered_company_name') ? 'has-error' : '' }}">
    <label for="registered_company_name" class="">Registered Company Name</label>
    <div class="col-md-10">
        <input class="form-control" name="registered_company_name" type="text" id="registered_company_name" value="{{ old('registered_company_name', optional($location)->registered_company_name) }}" minlength="1" maxlength="80" required="true" placeholder="Enter registered company name here...">
        {!! $errors->first('registered_company_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('trading_name') ? 'has-error' : '' }}">
    <label for="trading_name" class="">Trading Name</label>
    <div class="col-md-10">
        <input class="form-control" name="trading_name" type="text" id="trading_name" value="{{ old('trading_name', optional($location)->trading_name) }}" minlength="1" maxlength="80" required="true" placeholder="Enter trading name here...">
        {!! $errors->first('trading_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('registration_no') ? 'has-error' : '' }}">
    <label for="registration_no" class="">Registration No</label>
    <div class="col-md-10">
        <input class="form-control" name="registration_no" type="text" id="registration_no" value="{{ old('registration_no', optional($location)->registration_no) }}" minlength="1" maxlength="40" required="true" placeholder="Enter registration no here...">
        {!! $errors->first('registration_no', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('address_line1') ? 'has-error' : '' }}">
    <label for="address_line1" class="">Address Line1</label>
    <div class="col-md-10">
        <input class="form-control" name="address_line1" type="text" id="address_line1" value="{{ old('address_line1', optional($location)->address_line1) }}" minlength="1" maxlength="100" required="true" placeholder="Enter address line1 here...">
        {!! $errors->first('address_line1', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('address_line2') ? 'has-error' : '' }}">
    <label for="address_line2" class="">Address Line2</label>
    <div class="col-md-10">
        <input class="form-control" name="address_line2" type="text" id="address_line2" value="{{ old('address_line2', optional($location)->address_line2) }}" minlength="1" maxlength="50" placeholder="Enter address line2 here...">
        {!! $errors->first('address_line2', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('address_line3') ? 'has-error' : '' }}">
    <label for="address_line3" class="">Address Line3</label>
    <div class="col-md-10">
        <input class="form-control" name="address_line3" type="text" id="address_line3" value="{{ old('address_line3', optional($location)->address_line3) }}" minlength="1" maxlength="50" placeholder="Enter address line3 here...">
        {!! $errors->first('address_line3', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('town') ? 'has-error' : '' }}">
    <label for="town" class="">Town</label>
    <div class="col-md-10">
        <input class="form-control" name="town" type="text" id="town" value="{{ old('town', optional($location)->town) }}" minlength="1" maxlength="50" required="true" placeholder="Enter town here...">
        {!! $errors->first('town', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('county') ? 'has-error' : '' }}">
    <label for="county" class="">County</label>
    <div class="col-md-10">
        <input class="form-control" name="county" type="text" id="county" value="{{ old('county', optional($location)->county) }}" minlength="1" maxlength="50" required="true" placeholder="Enter county here...">
        {!! $errors->first('county', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('country') ? 'has-error' : '' }}">
    <label for="country" class="">Country</label>
    <div class="col-md-10">
        <select class="form-control" name="country"  id="country"required="true" >
            <option value="">Please select</option>
        @foreach ($countries as $c)
            <option {{ $c == optional($location)->country ? 'selected' : '' }} >{{ $c }}</option>
        @endforeach
        </select>
        {!! $errors->first('country', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('postcode') ? 'has-error' : '' }}">
    <label for="postcode" class="">Postcode</label>
    <div class="col-md-10">
        <input class="form-control" name="postcode" type="text" id="postcode" value="{{ old('postcode', optional($location)->postcode) }}" minlength="1" maxlength="30" required="true" placeholder="Enter postcode here...">
        {!! $errors->first('postcode', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('telephone_no') ? 'has-error' : '' }}">
    <label for="telephone_no" class="">Telephone No</label>
    <div class="col-md-10">
        <input class="form-control" name="telephone_no" type="text" id="telephone_no" value="{{ old('telephone_no', optional($location)->telephone_no) }}" minlength="1" maxlength="20" required="true" placeholder="Enter telephone no here...">
        {!! $errors->first('telephone_no', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
    <label for="email" class="">Email</label>
    <div class="col-md-10">
        <input class="form-control" name="email" type="email" id="email" value="{{ old('email', optional($location)->email) }}" required="true" placeholder="Enter email here...">
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
    <label for="password" class="">Password</label>
    <div class="col-md-10">
        <input class="form-control" name="password" type="password" id="password" value="{{ old('password') }}" minlength="1" maxlength="80" 
        {{$location ? '' : 'required="true"'}}  placeholder="Enter password here {{$location ? '(Leave blank to keep unchanged)' : ''}}...">
        {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
    </div>
</div>

@section('scripts')
<script src="/js/angular.min.js"></script>
<script>
    var app = angular.module('myApp',[]);
</script>
@endsection