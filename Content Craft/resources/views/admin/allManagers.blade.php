@extends('layout.master')
@section('content')
    <div class="container">
        <div class="row mt-2 mb-4">
            <div class="d-flex justify-content-end w-100">
                <a href="{{route('manager.create')}}" class="btn btn-success" >Create Manager</a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="card">
            <div class="card-header">Manage Managers</div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush