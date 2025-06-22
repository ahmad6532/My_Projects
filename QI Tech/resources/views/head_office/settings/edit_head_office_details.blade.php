<form action="{{ route('head_office.request_update_details') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="company_name">Company Name</label>
        <input type="text" id="company_name" class="form-control" value="{{ old('company_name', $headOffice->company_name) }}" name="company_name" placeholder="Company Name" required>
    </div>
    <div class="form-group">
        <label for="address">Address</label>
        <input type="text" id="address" name="address" value="{{ old('address', $headOffice->address) }}" class="form-control" placeholder="Address Line 1" required>
    </div>
    <div class="form-group">
        <label for="telephone_no">Telephone</label>
        <input type="text" id="telephone_no" name="telephone_no" value="{{ old('telephone_no', $headOffice->telephone_no) }}" class="form-control" placeholder="Telephone" required>
    </div>
    <div class="form-group">
        <button class="btn btn-info" type="submit" name="submit">Update</button>
    </div>
</form>