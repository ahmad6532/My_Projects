<form action="{{ route('head_office.finance_department_detail.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="finance_email">Finance Email</label>
        <input type="text" id="finance_email" class="form-control" value="{{ old('finance_email', $headOffice->finance_email) }}" name="finance_email" placeholder="Finance Department Email" required>
    </div>
    <div class="form-group">
        <label for="finance_phone">Finance Phone</label>
        <input type="text" id="phone" name="finance_phone" value="{{ old('finance_phone', $headOffice->finance_phone) }}" class="form-control" placeholder="Finance Department Phone" required>
    </div>
    <div class="form-group">
        <button class="btn btn-info" type="submit" name="submit">Update</button>
    </div>
</form>