@extends('layout.master')
@section('content')
    <div class="container w-75">
        <h2 class=" text-center m-4">Update Customer</h2>
        <form action="{{route('customer.update',$customerData->id)}}" method="POST" >
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Name</label>
                <input type="text"  class="form-control" name="name" value="{{$customerData->name}}" placeholder="Enter name">
                @error('name')
                    {{ $message }}
                @enderror
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" value="{{$customerData->email}}" placeholder="Enter email">
                @error('email')
                    {{ $message }}
                @enderror
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" class="form-control" name="phone" value="{{$customerData->phone}}" placeholder="Enter phone">
                @error('phone')
                    {{ $message }}
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
