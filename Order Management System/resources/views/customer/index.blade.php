@extends('layout.master')
@section('content')
<div class="container">
<a href="{{route('customer.create')}}" class="btn btn-primary m-3">Create Customer</a>
</div>
   <div class="container mt-2 ">
        <div class="card">
            <div class="card-header">Manage Customers</div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
    <!-- Modal For Delete Confirmation-->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Customer</h5>
      </div>
      <div class="modal-body">
        Do You Want to Delete?
      </div>
      <div class="modal-footer">
       <form id="deleteRoute" method="POST">
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