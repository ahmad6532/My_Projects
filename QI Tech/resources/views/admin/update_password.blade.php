@extends('layouts.admin_app')
@section('title', 'update admin Password')
@section('content')


    <div class="container-fluid">
        <div class="row justify-content-center ">
            <div class="col-md-12 mb-1">
                <div class="card vh-75 ">
                    <div class="card-body">
                        <h3 class="text-center text-info h3 font-weight-bold">Update Password</h3>
                        <form action="#">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection