@extends('layouts.admin_app')
@section('title', 'Show Location Details')
@section('content')

<div class="card">

    <div class="card-body">
    <div class="mb-3">
        <span class="float-left">
            <h4 class="text-info font-weight-bold">{{ isset($title) ? $title : 'Location' }}</h4>
        </span>

        <div class="float-right">

            <form method="POST" action="{!! route('locations.location.destroy', $location->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('locations.location.index') }}" class="btn btn-success" title="Show All Location">
                        <span class="fa fa-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('locations.location.create') }}" class="btn btn-info" title="Create New Location">
                        <span class="fas fa-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('locations.location.edit', $location->id ) }}" class="btn btn-primary" title="Edit Location">
                        <span class="fas fa-edit" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Location" onclick="return confirm(&quot;Click Ok to delete Location.?&quot;)">
                        <span class="fa fa-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
</div>
<div class="table-responsive">
    <table class="table table-striped">
                <tr>
            <th>Location Status</th>
            <td>{!! $location->status[0] !!}</td>
            </tr>
        <tr>
            <th>Location Type</th>
            <td>{{ optional($location->locationType)->id }}</td>
            </tr>
            <tr>
            <th>Location Pharmacy Type</th>
            <td>{{ optional($location->locationPharmacyType)->id }}</td>
            </tr>
            <tr>
            <th>Location Regulatory Body</th>
            <td>{{ optional($location->locationRegulatoryBody)->name }}</td>
            </tr>
            <tr>
            <th>Registered Company Name</th>
            <td>{{ $location->registered_company_name }}</td>
            </tr>
            <tr>
            <th>Trading Name</th>
            <td>{{ $location->trading_name }}</td>
            </tr>
            <tr>
            <th>Registration No</th>
            <td>{{ $location->registration_no }}</td>
            </tr>
            <tr>
            <th>Address Line1</th>
            <td>{{ $location->address_line1 }}</td>
            </tr>
            <tr>
            <th>Address Line2</th>
            <td>{{ $location->address_line2 }}</td>
            </tr>
            <tr>
            <th>Address Line3</th>
            <td>{{ $location->address_line3 }}</td>
            </tr>
            <tr>
            <th>Town</th>
            <td>{{ $location->town }}</td>
            </tr>
            <tr>
            <th>County</th>
            <td>{{ $location->county }}</td>
            </tr>
            <tr>
            <th>Country</th>
            <td>{{ $location->country }}</td>
            </tr>
            <tr>
            <th>Postcode</th>
            <td>{{ $location->postcode }}</td>
            </tr>
            <tr>
            <th>Telephone No</th>
            <td>{{ $location->telephone_no }}</td>
            </tr>
            <tr>
            <th>Email</th>
            <td>{{ $location->email }}</td>
            </tr>

    </table>
</div>

    </div>
</div>

@endsection