@extends('layouts.admin_app')

@section('content')

    <div class="card">

        <div class="card-body">
  
        <div class="mb-3 clearfix">

            <div class="float-left">
                <h4 class="text-info font-weight-bold">{{ !empty($serviceMessage->title) ? $serviceMessage->title : 'Service Message' }}</h4>
            </div>
            <div class="btn-group btn-group-sm float-right" role="group">

                <a href="{{ route('service_messages.service_message.index') }}" class="btn btn-info" title="Show All Service Message">
                    <span class="fas fa-th-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('service_messages.service_message.create') }}" class="btn btn-success" title="Create New Service Message">
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

            <form method="POST" action="{{ route('service_messages.service_message.update', $serviceMessage->id) }}" id="edit_service_message_form" name="edit_service_message_form" accept-charset="UTF-8" class="form-horizontal">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            @include ('admin.service_messages.form', [
                                        'serviceMessage' => $serviceMessage,
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

@section('scripts')

    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            $(document).ready(function() {
                $('select').select2();
            });
        });
    </script>
@endsection