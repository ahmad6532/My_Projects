@extends('layouts.location_app')
@section('title', 'Edit Location Details')
@section('top-nav-title', 'Edit Location Details')
@section('content')

<div class="card">
    <div class="card-body">
        <h3 class="text-center text-info h3 font-weight-bold">Edit Location Details</h3>
        @include('layouts.error')
        <div class="row">
            <div class="col-md-6">
            <h4 class="text-info">General Details</h4>
            <?php 
                $action = '#';
                if($location->head_office()){
                    $action = '#';
                }elseif($location->hasManagers() && $location->userIsManager($user->id)){
                    $action = 'location.manager_update_details';
                }elseif(!$location->hasManagers()){
                    $action = 'location.request_update_details';
                }
                ?>
            @if($location->head_office())
                <p class="text-info">Note: Only Head Office can change this location details.</p>
                <strong>Current Head Office: {{$location->head_office()->company_name}}</strong>
                <hr />
            @elseif($location->hasManagers() && $location->userIsManager($user->id) == false)
                <p class="text-info">Note: Only manager can change this location details.</p> 
            @endif
            <form action="@if($action != '#') {{ route($action) }} @else {{$action}}  @endif" method="POST">
                @csrf
                <div class="form-group">
                    <label for="trading_name">Trading Name</label>
                    <input type="text" @if($location->head_office() || ($location->hasManagers() && $location->userIsManager() == false)) disabled @endif id="trading_name" class="form-control" value="{{ old('trading_name', $location->trading_name) }}" name="trading_name" placeholder="Trading Name" required>
                </div>
                <div class="form-group">
                    <label for="address_line1">Address Line 1</label>
                    <input type="text" id="address_line1" @if($location->head_office() || ($location->hasManagers() && $location->userIsManager() == false)) disabled @endif name="address_line1" value="{{ old('address_line1', $location->address_line1) }}" class="form-control" placeholder="Address Line 1" required>
                </div>
                <div class="form-group">
                    <label for="address_line2">Address Line 2</label>
                    <input type="text" id="address_line2" @if($location->head_office() || ($location->hasManagers() && $location->userIsManager() == false)) disabled @endif name="address_line2" value="{{ old('address_line2', $location->address_line2) }}" class="form-control" placeholder="Address Line 2" >
                </div>
                <div class="form-group">
                    <label for="address_line3">Address Line 3</label>
                    <input type="text" id="address_line3" @if($location->head_office() || ($location->hasManagers() && $location->userIsManager() == false)) disabled @endif name="address_line3" value="{{ old('address_line3', $location->address_line3) }}" class="form-control" placeholder="Address Line 3" >
                </div>
                <div class="form-group">
                    <label for="registration_no">GPhC Premises No</label>
                    <input type="text" id="registration_no" @if($location->head_office() || ($location->hasManagers() && $location->userIsManager() == false)) disabled @endif name="registration_no" value="{{ old('registration_no', $location->registration_no) }}" class="form-control" placeholder="GPhC Premises No" required>
                </div>
                <div class="form-group">
                    <label for="telephone_no">Telephone</label>
                    <input type="text" id="telephone_no" @if($location->head_office() || ($location->hasManagers() && $location->userIsManager() == false)) disabled @endif name="telephone_no" value="{{ old('telephone_no', $location->telephone_no) }}" class="form-control" placeholder="Telephone" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input disabled  type="email" id="email" name="" value="{{ $location->email }}" class="form-control" placeholder="email" required>
                </div>
                <div class="form-group">
                    @if($location->hasManagers() == false && !$location->head_office())
                        <button class="btn btn-info" type="submit" name="submit">Request Update</button>
                    @elseif($location->userIsManager($user->id))
                        <button class="btn btn-info" type="submit" name="submit">Update</button>
                    @else
                    <button class="btn btn-info btn-disabled" disabled type="btn" name="submit">Update</button>
                    @endif
                </div>
            </form>
            </div>
            <div class="col-md-6">
                
            </div>
        </div>          
    </div>
</div>
@endsection