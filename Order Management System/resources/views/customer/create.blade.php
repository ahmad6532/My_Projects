@extends('layout.master')
@section('content')
    <div class="container w-75">
        <h2 class=" text-center m-4">Create New Customer</h2>
        <form action="{{ route('customer.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" name="name" placeholder="Enter name">
                @error('name')
                    {{ $message }}
                @enderror
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" placeholder="Enter email">
                @error('email')
                    {{ $message }}
                @enderror
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" class="form-control" name="phone" placeholder="Enter phone">
                @error('phone')
                    {{ $message }}
                @enderror
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <label>Rider</label>
                <select class="form-control" name="rider">
                    <option value="">Select Rider</option>
                   @foreach ($riders as $rider)
                       <option value="{{$rider->id}}">{{$rider->name}}</option>
                   @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
