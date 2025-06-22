@extends('layouts.admin_app')
@section('title', 'Show a User')

@section('content')

<div class="card">

    <div class="card-body">
    <div class="mb-3">
        <span class="float-left">
            <h4 class="text-info font-weight-bold">{{ isset($title) ? $title : 'User' }}</h4>
        </span>

        <div class="float-right">

            <form method="POST" action="{!! route('users.user.destroy', $user->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('users.user.index') }}" class="btn btn-success" title="Show All User">
                        <span class="fa fa-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('users.user.create') }}" class="btn btn-info" title="Create New User">
                        <span class="fas fa-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('users.user.edit', $user->id ) }}" class="btn btn-primary" title="Edit User">
                        <span class="fas fa-edit" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete User" onclick="return confirm(&quot;Click Ok to delete User.?&quot;)">
                        <span class="fa fa-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
</div>
<div class="table-responsive">
    <table class="table table-striped">
                <tr>
            <th>Status</th>
            <td>{{ $user->status[0]}}</td>
            </tr>
        <tr>
            <th>Position</th>
            <td>{{ optional($user->position)->name }}</td>
            </tr>
            <tr>
            <th>Is Registered with Regulatory Body</th>
            <td>{{ ($user->is_registered) ? 'Yes' : 'No' }}</td>
            </tr>
            <tr>
            <th>Registration No</th>
            <td>{{ $user->registration_no }}</td>
            </tr>
            <tr>
            <th>Location Regulatory Body</th>
            <td>{{ optional($user->locationRegulatoryBody)->name }}</td>
            </tr>
            <tr>
            <th>Country Of Practice</th>
            <td>{{ $user->country_of_practice }}</td>
            </tr>
            <tr>
            <th>First Name</th>
            <td>{{ $user->first_name }}</td>
            </tr>
            <tr>
            <th>Surname</th>
            <td>{{ $user->surname }}</td>
            </tr>
            <tr>
            <th>Mobile No</th>
            <td>{{ $user->mobile_no }}</td>
            </tr>
            <tr>
            <th>Email</th>
            <td>{{ $user->email }}</td>
            </tr>
            <tr>
            <th>Password Updated At</th>
            <td>{{ $user->password_updated_at }}</td>
            </tr>

    </table>
</div>

    </div>
</div>

@endsection