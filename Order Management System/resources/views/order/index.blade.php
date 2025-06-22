@extends('layout.master')
@section('content')
    <div class="container">
        <a href="{{ route('order.create') }}" class="btn btn-primary m-3">Create Order</a>
    </div>
    <div class="container mt-2 ">
        <div class="card">
            <div class="card-header">Manage Orders</div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
    <!-- Modal For Delete Confirmation-->
    <div class="modal fade" id="deleteOrderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Order</h5>
                </div>
                <div class="modal-body">
                    Do You Want to Delete?
                </div>
                <div class="modal-footer">
                    <form id="deleteOrderForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-success" id="closeModal">Close</button>
                        <button type="submit" class="btn btn-danger" id="delBtn">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
