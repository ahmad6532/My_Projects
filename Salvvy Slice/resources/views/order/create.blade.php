@extends('layout.master')
@section('content')
    <div class="container w-75">
        <h2 class=" text-center m-4">Create Order</h2>
        <form action="{{ route('order.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" class="form-control" name="productName" placeholder="Enter name">
                @error('productName')
                    {{ $message }}
                @enderror
            </div>
            <div class="form-group">
                <label>Quantity</label>
                <input type="number" class="form-control" name="quantity" placeholder="Enter Product Quantity">
            </div>
            <div class="form-group">
                <label>Customer</label>
                <select class="form-control" name="customer">
                    <option value="">Select Customer</option>
                   @foreach ($customers as $customer)
                       <option value="{{$customer->id}}">{{$customer->name}}</option>
                   @endforeach
                </select>
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
