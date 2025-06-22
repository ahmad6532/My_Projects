
<div class="form-group {{ $errors->has('company_name') ? 'has-error' : '' }}">
    <label for="company_name" class="">Company Name</label>
    <div class="col-md-10">
        <input class="form-control" name="company_name" type="text" id="company_name" value="{{ old('company_name', optional($headOffice)->company_name) }}" minlength="1" maxlength="100" required="true" placeholder="Enter company name here...">
        {!! $errors->first('company_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
    <label for="address" class="">Address</label>
    <div class="col-md-10">
        <input class="form-control" name="address" type="text" id="address" value="{{ old('address', optional($headOffice)->address) }}" minlength="1" maxlength="150" required="true" placeholder="Enter address here...">
        {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('telephone_no') ? 'has-error' : '' }}">
    <label for="telephone_no" class="">Telephone No</label>
    <div class="col-md-10">
        <input class="form-control" name="telephone_no" type="text" id="telephone_no" value="{{ old('telephone_no', optional($headOffice)->telephone_no) }}" minlength="1" maxlength="20" required="true" placeholder="Enter telephone no here...">
        {!! $errors->first('telephone_no', '<p class="help-block">:message</p>') !!}
    </div>
</div>






