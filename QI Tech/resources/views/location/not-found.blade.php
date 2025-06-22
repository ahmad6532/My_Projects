@extends('layouts.location_app')
@section('title', 'near Miss')
@section('content')


    <div class="container-fluid">
        <div class="row justify-content-center ">
            <div class="col-md-12 mb-1">
                <div class="card vh-75 ">
                    <div class="card-body">
                        <h5 class="text-info text-center">{{$message}}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection