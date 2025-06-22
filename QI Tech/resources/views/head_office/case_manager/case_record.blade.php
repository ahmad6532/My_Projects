@extends('layouts.head_office_app')
@section('title', 'Case Record')
@section('content')
<div class="card card-qi content_widthout_sidebar">
    <div class="card-body">
        <div class="container">
            <h4 class="text-info font-weight-bold">Create New Case</h4>
            <form method="post" action="{{route('case_manager.case_record_save')}}">
                @csrf
               
                <div class="form-group">
                    <label>Case Description</label>
                    <textarea spellcheck="true"  name="description" class="form-control" required></textarea>
                </div>
                
                <p>--Under Review --</p>
                <div class="form-group">
                    <label>Select Incident</label>
                    <select name="incident_id" class="form-control select2">
                        <option></option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Select Location</label>
                    <select name="location_id" class="form-control select2">
                        <option></option>
                        @foreach($locations as $loc)
                        <option value="{{$loc->location_id}}">{{$loc->location->name()}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Select Manager</label>
                    <select name="user_id" class="form-control select2">
                        <option></option>
                        @foreach($users as $user)
                        <option value="{{$user->user_id}}">{{$user->user->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group"><input type="submit" class="btn btn-info" value="Save"></div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script  src="{{asset('/js/alertify.min.js')}}"></script>
@endsection