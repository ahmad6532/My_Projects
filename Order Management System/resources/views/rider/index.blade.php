@extends('layout.master')
@section('content')
<div class="container">
<a href="{{route('rider.create')}}" class="btn btn-primary m-3">Create Rider</a>
</div>
   <div class="container mt-2 ">
        <div class="card">
            <div class="card-header">Manage Riders</div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
    <!-- Modal For Delete Confirmation-->
<div class="modal fade" id="deleteRiderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Rider</h5>
      </div>
      <div class="modal-body">
        Do You Want to Delete?
      </div>
      <div class="modal-footer">
       <form id="deleteRiderForm" method="POST">
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