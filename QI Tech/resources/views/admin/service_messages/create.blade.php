@extends('layouts.admin_app')

@section('content')
<div class="card">

<div class="card-body">
<div class="mb-3 clearfix">
            <span class="float-left">
                <h4 class="text-info font-weight-bold">Create New Service Message</h4>
            </span>
            <div class="btn-group btn-group-sm float-right" role="group">
                <a href="{{ route('service_messages.service_message.index') }}" class="btn btn-info" title="Show All Service Message">
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


            <form method="POST" action="{{ route('service_messages.service_message.store') }}" accept-charset="UTF-8" id="create_service_message_form" name="create_service_message_form" class="form-horizontal">
            {{ csrf_field() }}
            @include ('admin.service_messages.form', [
                                        'serviceMessage' => null,
                                      ])

                <div class="form-group {{ $errors->has('duration') ? 'has-error' : '' }}">
                    <label for="duration" class=" control-label">Duration</label>
                    <div class="col-md-10">
                        <input class="form-control" name="duration" type="number" id="duration" value="{{ old('duration') }}" minlength="1" required="true" placeholder="Enter duration here...">
                        {!! $errors->first('duration', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
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

@section('scripts')

    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            $(document).ready(function() {
                $('select').select2();
            });
        });
    </script>
@endsection
