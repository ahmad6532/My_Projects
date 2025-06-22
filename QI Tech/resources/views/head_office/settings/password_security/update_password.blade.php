
<form action="{{ route('head_office.request_update_password') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="old">Old Password</label>
        <input type="password" id="old" name="old_password" placeholder="Old Password" minlength="8" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="new">New Password</label>
        <input type="password" id="new" name="new_password" placeholder="New Password" minlength="8" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="confirm">Confirm Password</label>
        <input type="password" id="confirm" name="confirm_password" placeholder="Confirm Password" minlength="8" class="form-control" required>
    </div>
    <div class="form-group">
        <button class="btn btn-info" type="submit" name="submit">Update</button>
    </div>
</form>