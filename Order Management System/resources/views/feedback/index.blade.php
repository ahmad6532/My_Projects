@extends('layout.master')
@section('content')
   <div class="container mt-2 ">
        <div class="card">
            <div class="card-header">Manage Feedbacks</div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection
 
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush