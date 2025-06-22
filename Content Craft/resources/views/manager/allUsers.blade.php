@extends('layout.master')
@section('content')
    <div class="container">
        <div class="row mt-2">
            <div class="d-flex justify-content-end w-100">
                <a href="{{route('user.create')}}" class="btn btn-success" id="userCreateBtn">Create User</a>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">Manage Users</div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
