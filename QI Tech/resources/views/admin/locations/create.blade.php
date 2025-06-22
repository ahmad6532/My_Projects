@extends('layouts.admin_app')

@section('title', 'Create a Location')

@section('content')
<div class="card">

<div class="card-body">
<div class="mb-3 clearfix">
            <span class="float-left">
                <h4 class="text-info font-weight-bold">Create New Location</h4>
            </span>
            <div class="btn-group btn-group-sm float-right" role="group">
                <a href="{{ route('locations.location.index') }}" class="btn btn-info" title="Show All Location">
                    <span class="fas fa-th-list" aria-hidden="true"></span>
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


            <form method="POST" action="{{ route('locations.location.store') }}" accept-charset="UTF-8" id="create_location_form" name="create_location_form" class="form-horizontal">
            {{ csrf_field() }}
            @include ('admin.locations.form', [
                                        'location' => null,
                                      ])

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input class="btn btn-info" type="submit" value="Add">
                    </div>
                </div>

            </form>
</div>
</div>


</div>
</div>

@endsection


