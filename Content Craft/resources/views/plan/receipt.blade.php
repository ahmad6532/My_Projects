@extends('layout.master')
@section('content')
    <div class="container">
        <div class="text-center">
            <h2>Receipt</h2>
        </div>
        <div class="container col-6 d-flex flex-column justify-content-center align-items-center ">
            <div class="row col-12 ">
                <div class="col-12">
                    @foreach ($receiptData as $receipt)
                    <div class="receipt">
                        <div class="d-flex justify-content-between ">
                            <p><strong>Name:</strong></p>
                        <p> {{$userData}}</p>
                    </div>
                     <div class="d-flex justify-content-between ">
                            <p><strong>Date:</strong></p>
                        <p> {{date('Y-M-d H:i:s', $receipt->created)}}</p>
                    </div>
                    <div class="d-flex justify-content-between ">
                            <p><strong>Amount:</strong></p>
                        <p> {{$receipt->amount/100}}</p>
                    </div>
                    <div class="d-flex justify-content-between ">
                            <p><strong>Method:</strong></p>
                        <p> {{$receipt->calculated_statement_descriptor}}</p>
                    </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
