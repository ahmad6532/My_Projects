@extends('layouts.admin_app')


@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif

    <div class="card">

        <div class="card-body">
        <div class="mb-3">

            <div class="float-left">
                <h4 class="text-info font-weight-bold">Service Messages</h4>
            </div>

            <div class="btn-group btn-group-sm float-right" role="group">
                <a href="{{ route('service_messages.service_message.create') }}" class="btn btn-info" title="Create New Service Message">
                    <span class="fas fa-plus" aria-hidden="true"></span>
                </a>
            </div>

        </div>

        @if(count($serviceMessages) == 0)
                <h4 class="text-info text-center">No Service Messages Available.</h4>
         @else
            <div class="table-responsive">

                <table class="table table-striped table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Send To</th>
                            <th>Countries</th>
                            <th>Duration</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($serviceMessages as $serviceMessage)
                        <tr>
                            <td>{{ $serviceMessage->title }}</td>
                            <td>
                                @foreach($serviceMessage->receiver_list as $receiver_list)
                                {{$receiver_list}}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach($serviceMessage->country_list as $country_list)
                                    {{$country_list}}<br>
                                @endforeach</td>
                            <td>
                                {{ $serviceMessage->duration }}
                            <br>
                            <b>Expires at:</b> {{$serviceMessage->expires_at}}
                            </td>

                            <td class="align-middle text-center">


                                <div class="dropdown mb-4">
                                    <button class="btn btn-info dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                    </button>
                                    <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
                                        <form method="POST" action="{!! route('service_messages.service_message.destroy', $serviceMessage->id) !!}" accept-charset="UTF-8">
                                            <input name="_method" value="DELETE" type="hidden">
                                            {{ csrf_field() }}

                                                <a href="{{ route('service_messages.service_message.show', $serviceMessage->id ) }}" class="dropdown-item text-info" title="Show Service Message">
                                                    View
                                                </a>
                                                <a href="{{ route('service_messages.service_message.edit', $serviceMessage->id ) }}" class="dropdown-item text-primary" title="Edit Service Message">
                                                    Edit
                                                </a>
                                                <a href="{{ route('service_messages.service_message.extend_duration_view', $serviceMessage->id ) }}" class="dropdown-item text-secondary" title="Extend Service Message duration">
                                                    Extend Duration
                                                </a>
                                                <button type="submit" class="dropdown-item text-danger" title="Delete Service Message" onclick="return confirm(&quot;Click Ok to delete Service Message.&quot;)">
                                                    Remove
                                                </button>

                                        </form>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

{{--        <div class="card-footer"> --}}
{{--            {!! $serviceMessages->render() !!} --}}
{{--        </div> --}}
        
        @endif
    
    </div>
@endsection