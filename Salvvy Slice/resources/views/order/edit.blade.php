@extends('layout.master')
@section('content')
    <div class="container w-75">
        <h2 class=" text-center m-4">Update Order</h2>
        <form action="{{route('order.update',$orderData->orderId)}}" method="POST" >
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Product Name</label>
                <input type="text"  class="form-control" name="productName" value="{{$orderData->productName}}" placeholder="Enter name">
               
            </div>
            <div class="form-group">
                <label>Quantity</label>
                <input type="text" class="form-control" name="quantity" value="{{$orderData->quantity}}" placeholder="Enter email">
            </div>
            
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
