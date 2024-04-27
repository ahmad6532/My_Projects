@extends('layout.master')
@section('content')
    <form action="{{route('manager.update',$managerData->id)}}" id="editManager" method="POST" class="w-100 mt-3">
        @csrf
        @method('PUT')
        <div class="w-100 d-flex ">
            <div class="w-50">
                <div class="form-outline mb-3 signup-input-div">
                    <label class="form-label">First Name</label>
                    <input required type="text" name="firstName" class="form-control auth-form " value="{{$managerData->firstName}}" />
                    <i class="fa-regular fa-user"></i>
                </div>
                 <div class="form-outline mb-3 signup-input-div">
                    <label class="form-label">Phone Number</label>
                    <input required type="text" name="phone" class="form-control auth-form single-input"
                        value="{{$managerData->phone}}"  />

                </div>
            </div>
            <div class="w-50">
                <div class="form-outline mb-3 signup-input-div">
                    <label class="form-label">Last Name</label>
                    <input required type="text" name="lastName" class="form-control auth-form " value="{{$managerData->lastName}}"  />
                    <i class="fa-regular fa-user"></i>
                </div>

                <div class="form-outline mb-3 signup-input-div">
                    <label class="form-label">Gender</label>
                    <div class="d-flex justify-content-between  w-100">
                        <span class="form-control auth-form gender ">
                            <input required type="radio" value="Male" name="gender" class=" p-5 " />
                            <label class="form-label">Male</label>
                        </span>
                        <span class="form-control auth-form gender ">
                            <input required type="radio" value="Female" name="gender" class=" p-5 " />
                            <label class="form-label">Female</label>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-100">
            <div class="form-outline mb-3 signup-input-div single-input-div">
                <label class="form-label">Address</label>
                <input required type="text" name="address" class="form-control auth-form single-input"
                    value="{{$managerData->address}}"  />
            </div>
        </div>
        <div class="w-100 d-flex ">
            <div class="w-50">
                <div class="form-outline mb-3 signup-input-div">
                    <label class="form-label">Country</label>
                    <select name="country" required class="form-control auth-form single-input">
                        <option value="{{$managerData->country}}">{{$managerData->country}}</option>
                        <option value="USA">USA</option>
                        <option value="UAE">UAE</option>
                        <option value="Pakistan">Pakistan</option>
                    </select>
                </div>

            </div>
            <div class="w-50">
                
                <div class="form-outline mb-3 signup-input-div">
                    <label class="form-label">Postal Code</label>
                    <input required type="text" name="postalCode" class="form-control auth-form single-input"
                       value="{{$managerData->postalCode}}"  />
                </div>
               
            </div>

        </div>
        <div class="w-100 d-flex ">
            <div class="w-50">
                
            </div>

        </div>
        <div class="d-flex justify-content-end w-100 mb-5">
            <button type="submit" class="btn btn-success auth-submit-btn btn-block w-50" id="updateManagerBtn">Update Manager</button>
        </div>
    </form>
@endsection
