@extends('layouts.admin_app')

@section('title', 'Details of Head Office')
@section('content')

<div class="card">

    <div class="card-body">
    <div class="mb-3">
        <span class="float-left">
            <h4 class="text-info font-weight-bold">{{ isset($title) ? $title : 'Head Office' }}</h4>
        </span>

        <div class="float-right">

            <form method="POST" action="{!! route('head_offices.head_office.destroy', $headOffice->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('head_offices.head_office.index') }}" class="btn btn-success" title="Show All Head Office">
                        <span class="fa fa-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('head_offices.head_office.create') }}" class="btn btn-info" title="Create New Head Office">
                        <span class="fas fa-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('head_offices.head_office.edit', $headOffice->id ) }}" class="btn btn-primary" title="Edit Head Office">
                        <span class="fas fa-edit" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Head Office" onclick="return confirm(&quot;Click Ok to delete Head Office.?&quot;)">
                        <span class="fa fa-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
</div>
<div class="table-responsive">
    <table class="table table-striped">
                <tr>
            <th>Company Name</th>
            <td>{{ $headOffice->company_name }}</td>
            </tr>
            <tr>
            <th>Address</th>
            <td>{{ $headOffice->address }}</td>
            </tr>
            <tr>
            <th>Telephone No</th>
            <td>{{ $headOffice->telephone_no }}</td>
            </tr>
            <tr>
            <th>Email</th>
            <td>{{ $headOffice->email }}</td>
            </tr>
            <tr>
            <th>Last Login User</th>
            <td>{{ optional($headOffice->lastLoginUser)->id }}</td>
            </tr>

    </table>
</div>

    </div>
</div>

@endsection