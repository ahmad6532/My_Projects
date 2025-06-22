@extends('layouts.admin_app')

@section('title', 'Edit Head Office')
@section('content')

    <div class="card">

        <div class="card-body">
  
        <div class="mb-3 clearfix">

            <div class="float-left">
                <h4 class="text-info font-weight-bold">{{ !empty($title) ? $title : 'Head Office' }}</h4>
            </div>
            <div class="btn-group btn-group-sm float-right" role="group">

                <a href="{{ route('head_offices.head_office.index') }}" class="btn btn-info" title="Show All Head Office">
                    <span class="fas fa-th-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('head_offices.head_office.create') }}" class="btn btn-success" title="Create New Head Office">
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

            <form method="POST" action="{{ route('head_offices.head_office.update', $headOffice->id) }}" id="edit_head_office_form" name="edit_head_office_form" accept-charset="UTF-8" class="form-horizontal">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            @include ('admin.head_offices.form', [
                                        'headOffice' => $headOffice,
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