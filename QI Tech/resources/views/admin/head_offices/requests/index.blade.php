@extends('layouts.admin_app')

@section('title', 'List of All Head offices')

@section('content')

    @include('layouts.error')
    <div class="card">
        <div class="card-header bg-white">
            <div class="mb-3">

                <div class="float-left">
                    <h4 class="text-info font-weight-bold">Head Office Requests</h4>
                </div>

                <div class="btn-group btn-group-sm float-right" role="group">
                    <a href="{{ route('head_offices.head_office.index') }}" class="btn btn-info" title="Create New Head Office">
                        <span class="fas fa-list" aria-hidden="true"></span>
                    </a>
                </div>

            </div>
        </div>
        <div class="card-body">

            <div class="card mb-3">
            @if(count($headOfficePendingRequests)==0)
                <h4 class="text-info text-center">No Pending Requests Available.</h4>
            @else
                <h4 class="text-info text-center">Head Office Pending Requests</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="dataTable">
                        <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Status</th>
                            <th>Organization</th>
                            <th>Position</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($headOfficePendingRequests as $headOfficeRequest)
                            <tr>
                                <td>
                                    {{ $headOfficeRequest->name }}
                                    <br><b>Telephone:</b>{{ $headOfficeRequest->telephone_no }}
                                    <br><b>Email:</b>{{ $headOfficeRequest->email }}
                                </td>
                                <td class="align-middle text-center">
                                    <span class="badge badge-pill badge-{{ $headOfficeRequest->status[1] }}">{{ $headOfficeRequest->status[0] }}</span>
                                </td>
                                <td>
                                    {{$headOfficeRequest->organization}}
                                </td>
                                <td>
                                    {{$headOfficeRequest->position}}
                                </td>

                                <td class="align-middle text-center">

                                    <div class="dropdown mb-4">
                                        <button class="btn btn-info dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                        </button>
                                        <div class="dropdown-menu animated--fade-in"
                                             aria-labelledby="dropdownMenuButton">

                                            <a class="dropdown-item" href="{{route('head_office.request.request_approved',$headOfficeRequest->id)}}" title="Approve Request" onclick="return confirm(&quot;Click Ok to Approve this request.&quot;)">
                                                    Approve Account
                                                </a>
                                            <a class="dropdown-item" href="{{route('head_office.request.request_rejected',['headOfficeRequest'=>$headOfficeRequest->id,'_token'=>csrf_token()])}}" title="Reject Request" onclick="return confirm(&quot;Click Ok to Reject this request.&quot;)">
                                                    Reject Request
                                                </a>

                                        </div>
                                    </div>

                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            @endif
            </div>
            <div class="card mb-3">
            @if(count($headOfficeApprovedRequests)==0)
                <h4 class="text-info text-center">No Approved Requests Available.</h4>
            @else
                <h4 class="text-info text-center">Head Office Approved Requests</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="dataTable">
                        <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Status</th>
                            <th>Organization</th>
                            <th>Position</th>
{{--                            <th>Action</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($headOfficeApprovedRequests as $headOfficeRequest)
                            <tr>
                                <td>
                                    {{ $headOfficeRequest->name }}
                                    <br><b>Telephone:</b>{{ $headOfficeRequest->telephone_no }}
                                    <br><b>Email:</b>{{ $headOfficeRequest->email }}
                                </td>
                                <td class="align-middle text-center">
                                    <span class="badge badge-pill badge-{{ $headOfficeRequest->status[1] }}">{{ $headOfficeRequest->status[0] }}</span>
                                </td>
                                <td>
                                    {{$headOfficeRequest->organization}}
                                </td>
                                <td>
                                    {{$headOfficeRequest->position}}
                                </td>

{{--                                <td class="align-middle text-center">--}}

{{--                                    <div class="dropdown mb-4">--}}
{{--                                        <button class="btn btn-info dropdown-toggle" type="button"--}}
{{--                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"--}}
{{--                                                aria-expanded="false">--}}
{{--                                        </button>--}}
{{--                                        <div class="dropdown-menu animated--fade-in"--}}
{{--                                             aria-labelledby="dropdownMenuButton">--}}

{{--                                            <a class="dropdown-item" href="{{route('head_office.request.request_approved',$headOfficeRequest->id)}}" title="Approve Request" onclick="return confirm(&quot;Click Ok to Approve this request.&quot;)">--}}
{{--                                                    Approve Account--}}
{{--                                                </a>--}}
{{--                                            <a class="dropdown-item" href="{{route('head_office.request.request_rejected',$headOfficeRequest->id)}}" title="Reject Request" onclick="return confirm(&quot;Click Ok to Reject this request.&quot;)">--}}
{{--                                                    Reject Request--}}
{{--                                                </a>--}}

{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                </td>--}}

                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            @endif
            </div>
            <div class="card mb-3">
            @if(count($headOfficeRejectedRequests)==0)
                <h4 class="text-info text-center">No Rejected Requests Available.</h4>
            @else
                <h4 class="text-info text-center">Head Office Rejected Requests</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="dataTable">
                        <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Status</th>
                            <th>Organization</th>
                            <th>Position</th>
{{--                            <th>Action</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($headOfficeRejectedRequests as $headOfficeRequest)
                            <tr>
                                <td>
                                    {{ $headOfficeRequest->name }}
                                    <br><b>Telephone:</b>{{ $headOfficeRequest->telephone_no }}
                                    <br><b>Email:</b>{{ $headOfficeRequest->email }}
                                </td>
                                <td class="align-middle text-center">
                                    <span class="badge badge-pill badge-{{ $headOfficeRequest->status[1] }}">{{ $headOfficeRequest->status[0] }}</span>
                                </td>
                                <td>
                                    {{$headOfficeRequest->organization}}
                                </td>
                                <td>
                                    {{$headOfficeRequest->position}}
                                </td>

{{--                                <td class="align-middle text-center">--}}

{{--                                    <div class="dropdown mb-4">--}}
{{--                                        <button class="btn btn-info dropdown-toggle" type="button"--}}
{{--                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"--}}
{{--                                                aria-expanded="false">--}}
{{--                                        </button>--}}
{{--                                        <div class="dropdown-menu animated--fade-in"--}}
{{--                                             aria-labelledby="dropdownMenuButton">--}}

{{--                                            <a class="dropdown-item" href="{{route('head_office.request.request_approved',$headOfficeRequest->id)}}" title="Approve Request" onclick="return confirm(&quot;Click Ok to Approve this request.&quot;)">--}}
{{--                                                    Approve Account--}}
{{--                                                </a>--}}
{{--                                            <a class="dropdown-item" href="{{route('head_office.request.request_rejected',$headOfficeRequest->id)}}" title="Reject Request" onclick="return confirm(&quot;Click Ok to Reject this request.&quot;)">--}}
{{--                                                    Reject Request--}}
{{--                                                </a>--}}

{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                </td>--}}

                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            @endif
            </div>

        </div>

    </div>
@endsection
