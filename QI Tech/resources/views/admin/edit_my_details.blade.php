@extends('layouts.admin_app')
@section('title', 'Edit Admin Details')
@section('content')


    <div class="container-fluid">
        <div class="row justify-content-center ">
            <div class="col-md-12 mb-1">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center text-info h3 font-weight-bold">Edit Admin Details</h3>
                        <form onsubmit="return false" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="First_name">First Name</label>
                                <input type="text" id="First_name" class="form-control" name="first_name" value="{{ old('first_name', $admin->first_name) }}" placeholder="First Name" required>
                            </div>

                            <div class="form-group">
                                <label for="Sur_name">Sur Name</label>
                                <input type="text" id="Sur_name" class="form-control" value="{{ old('surname', $admin->surname) }}" name="sur_name" placeholder="SurName" required>
                            </div>

                            <div class="form-group">
                                <label for="mobile">Mobile</label>
                                <input type="text" id="mobile" name="mobile_no" value="{{ old('mobile_no', $admin->mobile_no) }}" class="form-control"  No" required>
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