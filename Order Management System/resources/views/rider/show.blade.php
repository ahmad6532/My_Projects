@extends('layout.master')
@section('content')
    <div class="container w-75">
        <h2 class=" text-center m-4">Rider Detail</h2>

        <div class="form-group">
            <label>Name</label>
            <input type="text" readonly value="{{$riderData->name}}" class="form-control">
        </div>
         <div class="form-group">
            <label>Email</label>
            <input type="text" readonly value="{{$riderData->email}}" class="form-control">
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" readonly value="{{$riderData->phone}}" class="form-control">
        </div>
    </div>
@endsection
