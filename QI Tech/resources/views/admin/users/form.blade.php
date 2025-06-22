
<div class="form-group {{ $errors->has('position_id') ? 'has-error' : '' }}">
    <label for="position_id" class="">Position</label>
    <div class="col-md-10">
        <select class="form-control" id="position_id" name="position_id" required="true">
        	    <option value="" style="display: none;" {{ old('position_id', optional($user)->position_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select position</option>
        	@foreach ($positions as $key => $position)
			    <option value="{{ $key }}" {{ old('position_id', optional($user)->position_id) == $key ? 'selected' : '' }}>
			    	{{ $position }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('position_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('is_registered') ? 'has-error' : '' }}">
    <label for="is_registered" class="">Is Registered with Regulatory Body</label>
    <div class="col-md-10">
        <div class="checkbox">
            <label for="is_registered_1" class="mt-4 ml-3">
            	<input id="is_registered_1" class="" name="is_registered" type="checkbox" value="1" {{ old('is_registered', optional($user)->is_registered) == '1' ? 'checked' : '' }}>
                Yes
            </label>
        </div>

        {!! $errors->first('is_registered', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('registration_no') ? 'has-error' : '' }}">
    <label for="registration_no" class="">Registration No</label>
    <div class="col-md-10">
        <input class="form-control" name="registration_no" type="text" id="registration_no" value="{{ old('registration_no', optional($user)->registration_no) }}" minlength="2" maxlength="50" placeholder="Enter registration no here...">
        {!! $errors->first('registration_no', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('location_regulatory_body_id') ? 'has-error' : '' }}">
    <label for="location_regulatory_body_id" class="">Location Regulatory Body</label>
    <div class="col-md-10">
        <select class="form-control" id="location_regulatory_body_id" name="location_regulatory_body_id">
        	    <option value="" style="display: none;" {{ old('location_regulatory_body_id', optional($user)->location_regulatory_body_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select location regulatory body</option>
        	@foreach ($locationRegulatoryBodies as $key => $locationRegulatoryBody)
			    <option value="{{ $key }}" {{ old('location_regulatory_body_id', optional($user)->location_regulatory_body_id) == $key ? 'selected' : '' }}>
			    	{{ $locationRegulatoryBody }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('location_regulatory_body_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('country_of_practice') ? 'has-error' : '' }}">
    <label for="country_of_practice" class="">Country Of Practice</label>
    <div class="col-md-10">
        <select class="form-control" id="country_of_practice" name="country_of_practice">
        	    <option value="" style="display: none;" {{ old('country_of_practice', optional($user)->country_of_practice ?: '') == '' ? 'selected' : '' }} disabled selected>Enter country of practice here...</option>
        	@foreach (['0' => 'England',
'1' => 'Scotland',
'2' => 'Wales',
'3' => 'Channel Islands',
'4' => 'Northern Ireland',
'5' => 'Republic of Ireland'] as $key => $text)
			    <option value="{{ $key }}" {{ old('country_of_practice', optional($user)->country_of_practice) == $key ? 'selected' : '' }}>
			    	{{ $text }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('country_of_practice', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
    <label for="first_name" class="">First Name</label>
    <div class="col-md-10">
        <input class="form-control" name="first_name" type="text" id="first_name" value="{{ old('first_name', optional($user)->first_name) }}" minlength="1" maxlength="50" required="true" placeholder="Enter first name here...">
        {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('surname') ? 'has-error' : '' }}">
    <label for="surname" class="">Surname</label>
    <div class="col-md-10">
        <input class="form-control" name="surname" type="text" id="surname" value="{{ old('surname', optional($user)->surname) }}" minlength="1" maxlength="50" required="true" placeholder="Enter surname here...">
        {!! $errors->first('surname', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('mobile_no') ? 'has-error' : '' }}">
    <label for="mobile_no" class="">Mobile No</label>
    <div class="col-md-10">
        <input class="form-control" name="mobile_no" type="text" id="mobile_no" value="{{ old('mobile_no', optional($user)->mobile_no) }}" minlength="1" maxlength="20" required="true" placeholder="Enter mobile no here...">
        {!! $errors->first('mobile_no', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
    <label for="email" class="">Email</label>
    <div class="col-md-10">
        <input class="form-control" name="email" type="email" id="email" value="{{ old('email', optional($user)->email) }}" required="true" placeholder="Enter email here...">
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
    <label for="password" class="">Password</label>
    <div class="col-md-10">
        <input class="form-control" name="password" type="password" id="password" value="{{ old('password') }}" placeholder="Enter password here...(Leave blank if you want to keep unchanged)">
        {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
    </div>
</div>

