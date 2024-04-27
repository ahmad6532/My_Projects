@extends('layout.master')
@section('content')
    <div class="container w-75">
        <h2 class=" text-center m-4">Order Detail</h2>

        <div class="form-group">
            <label>Product Name</label>
            <input type="text" readonly value="{{$orderData->productName}}" class="form-control">
        </div>
         <div class="form-group">
            <label>Quantity</label>
            <input type="text" readonly value="{{$orderData->quantity}}" class="form-control">
        </div>
        <div class="form-group">
            <label>Created At</label>
            <input type="text" readonly value="{{$orderData->createdAt}}" class="form-control">
        </div>
    </div>
@endsection
