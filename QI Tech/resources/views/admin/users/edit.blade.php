@extends('layouts.admin_app')
@section('title', 'Edit a User')

@section('content')

    <div class="card">

        <div class="card-body">
  
        <div class="mb-3 clearfix">

            <div class="float-left">
                <h4 class="text-info font-weight-bold">{{ !empty($title) ? $title : 'User' }}</h4>
            </div>
            <div class="btn-group btn-group-sm float-right" role="group">

                <a href="{{ route('users.user.index') }}" class="btn btn-info" title="Show All User">
                    <span class="fas fa-th-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('users.user.create') }}" class="btn btn-success" title="Create New User">
                    <span class="fas fa-plus" aria-hidden="true"></span>
                </a>

            </div>
        </div>


<div class="row">
<div class="col-md-12">

            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('users.user.update', $user->id) }}" id="edit_user_form" name="edit_user_form" accept-charset="UTF-8" class="form-horizontal">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            @include ('admin.users.form', [
                                        'user' => $user,
                                      ])

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input class="btn btn-info" type="submit" value="Update">
                    </div>
                </div>
            </form>

        </div>
        </div>
        </div>
    </div>

@endsection