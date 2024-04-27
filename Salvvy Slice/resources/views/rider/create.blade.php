@extends('layout.master')
@section('content')
    <div class="container w-75">
        <h2 class=" text-center m-4">Create New Rider</h2>
        <form action="{{route('rider.store')}}" method="POST" >
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
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
