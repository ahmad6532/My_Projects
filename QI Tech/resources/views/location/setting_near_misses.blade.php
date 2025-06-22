@extends('layouts.location_app')
@section('title', 'Near Misses Settings')
@section('top-nav-title', 'Near Misses Settings')
@section('content')
<div class="container-fluid">
    <div class="card vh-75 ">
        <div class="card-body">
            @if(request()->query('success'))
            <div class="alert to_hide_10 alert-success w-50" style="margin:0 auto">
                {{request()->query('success')}} 
                <i class="right to_hide_to_manual fa fa-times" onclick="$('.to_hide_10').hide()"></i>
            </div>
            @endif
            @if(request()->query('error'))
            <div class="alert to_hide_10 alert-danger w-50" style="margin:0 auto">
                {{request()->query('error')}} 
                <i class="right to_hide_to_manual fa fa-times" onclick="$('.to_hide_10').hide()"></i>
            </div>
            @endif
            @include('layouts.error')
            <form method="post" action="{{route('location.settings.nearmisses')}}">
            <h3 class="text-info h3 font-weight-bold">Near Misses Settings</h3>
            <div class="row">
                <div class="col-md-5 col-lg-5">
                    @csrf
                    <h4 class="text-info">General Settings</h4>
                    <div class="form-group">
                        <label>Prescriptions dispensed at a Hub?</label>
                        <select name="near_miss_prescirption_dispensed_at_hub" class="form-control">
                            <option value="No" @if($location->near_miss_prescirption_dispensed_at_hub == 0) selected @endif>No</option>
                            <option value="Yes" @if($location->near_miss_prescirption_dispensed_at_hub == 1) selected @endif>Yes</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name='near_miss_ask_for_who' class="near_miss_ask_for_who" value="1" @if($location->near_miss_ask_for_who == 1) checked @endif>
                        <span for="ask_for">Ask for who made and detected the error?</span>
                    </div>
                    
                    <div class="near_miss_ask_for_details"  @if($location->near_miss_ask_for_who != 1) style="display:none" @endif>
                        <div class="form-group" >
                            <input type="radio" name="near_miss_ask_for_user_detail" value="name" class="" @if($location->near_miss_ask_for_user_detail == 'name') checked @endif>
                            <span>Ask for name (position will automatically be selected)</span>
                        </div>
                        <div class="form-group">
                            <input type="radio" name="near_miss_ask_for_user_detail" value="position" class="" @if($location->near_miss_ask_for_user_detail == 'position') checked @endif>
                            <span>Ask for position only</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="checkbox" name='near_miss_robot_in_use' class="near_miss_robot_in_use" value="1" @if($location->near_miss_robot_in_use == 1) checked @endif>
                        <span>Robot dispenser in use?</span>
                    </div>
                    <div class="form-group near_miss_robot_name" @if($location->near_miss_robot_in_use != 1) style="display:none" @endif>
                        <label>Robot Name</label>
                        <input type="text" name="near_miss_robot_name" value="{{$location->near_miss_robot_name}}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Notify me if reporting less than value per week</label>
                        <input type="number" name="near_miss_reporting_less_than_week" value="{{$location->near_miss_reporting_less_than_week}}" class="form-control">
                        <p class="">This setting only work when you set location opening hours.</p>
                        <p>Click <a href="#" data-toggle="modal" data-target="#opening_hours_modal" title="Delete">here</a> to set opening hours.</p>
                    </div>

                    <button class="btn btn-info" type="submit" name="submit">Submit</button>
                    <hr>
                </div>
                <div class="col-md-7 col-lg-7">
                    
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@include('location.opening_hours')
@endsection