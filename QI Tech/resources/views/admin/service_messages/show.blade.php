@extends('layouts.admin_app')

@section('content')

<div class="card">

    <div class="card-body">
    <div class="mb-3">
        <span class="float-left">
            <h4 class="text-info font-weight-bold">{{ isset($serviceMessage->title) ? $serviceMessage->title : 'Service Message' }}</h4>
        </span>

        <div class="float-right">

            <form method="POST" action="{!! route('service_messages.service_message.destroy', $serviceMessage->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('service_messages.service_message.index') }}" class="btn btn-success" title="Show All Service Message">
                        <span class="fa fa-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('service_messages.service_message.create') }}" class="btn btn-info" title="Create New Service Message">
                        <span class="fas fa-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('service_messages.service_message.edit', $serviceMessage->id ) }}" class="btn btn-primary" title="Edit Service Message">
                        <span class="fas fa-edit" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Service Message" onclick="return confirm(&quot;Click Ok to delete Service Message.?&quot;)">
                        <span class="fa fa-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
</div>
<div class="table-responsive">
    <table class="table table-striped">
                <tr>
            <th>Title</th>
            <td>{{ $serviceMessage->title }}</td>
            </tr>
            <tr>
            <th>Message</th>
            <td>{{ $serviceMessage->message }}</td>
            </tr>
            <tr>
            <th>Send To</th>
            <td>{{ $serviceMessage->send_to }}</td>
            </tr>
            <tr>
            <th>Countries</th>
            <td>{{ $serviceMessage->countries }}</td>
            </tr>
            <tr>
            <th>Duration</th>
            <td>{{ $serviceMessage->duration }}</td>
            </tr>

    </table>
</div>

    </div>
</div>

@endsection