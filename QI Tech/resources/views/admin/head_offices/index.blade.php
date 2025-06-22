@extends('layouts.admin_app')

@section('title', 'List of All Head offices')

@section('content')

    @include('layouts.error')
    <div class="card">

        <div class="card-body">
        <div class="mb-3">

            <div class="float-left">
                <h4 class="text-info font-weight-bold">Head Offices</h4>
            </div>

            <div class="btn-group btn-group-sm float-right" role="group">
                <a href="{{ route('head_offices.head_office.create') }}" class="btn btn-info" title="Create New Head Office">
                    <span class="fas fa-plus" aria-hidden="true"></span>
                </a>
                <a href="{{ route('head_office.request.index') }}" class="btn btn-info" title="Head Office Requests">
                    <span class="fas fa-list" aria-hidden="true"></span>
                </a>
            </div>

        </div>

        @if(count($headOffices) == 0)
                <h4 class="text-info text-center">No Head Offices Available.</h4>
         @else
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Current Super Admins</th>
                            <th>Last Used</th>
                            <th>Notes</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($headOffices as $headOffice)
                        <tr>
                            <td>
                                {{ $headOffice->company_name }}
                                <br>{{ $headOffice->address }}
                                <br>{{ $headOffice->telephone_no }}
                                <br>{{ $headOffice->email }}
                            </td>
                            <td class="align-middle text-center">
                                <span class="badge badge-pill badge-{{ $headOffice->status[1] }}">{{ $headOffice->status[0] }}</span>

                            </td>
                            <td>
                                @foreach($headOffice->users as $head_office_user)
                                {{$head_office_user->user->name}} <br>
                                [ {{$head_office_user->user->email}} ]
                                    @if($headOffice->has_multiple_super_admins)
                               <div class="float-right">
                                    <a href="{{route('head_offices.head_office.assign_super_admin_remove',['headOffice'=>$head_office_user->id,'_token'=>csrf_token()])}}" class="btn btn-danger btn-sm" title="Remove this Super Admin" onclick="return confirm(&quot;Click Ok to Remove this Super Admin.&quot;)"><i class="fas fa-trash"></i></a>
                               </div>
                                    @endif
                                    <hr>
                                @endforeach

                            </td>
                            <td>Last Login:{{ optional($headOffice->lastLoginUser)->id }}<br>Last Login:{{ $headOffice->last_login_at }}</td>
                            <td></td>
                            <td class="align-middle text-center">
                                <div class="dropdown mb-4">
                                    <button class="btn btn-info dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                    </button>
                                    <div class="dropdown-menu animated--fade-in"
                                         aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="{{ route('head_offices.head_office.show', $headOffice->id ) }}" title="Show Head Office">
                                                View
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.headoffice.login', $headOffice->id ) }}" title="Show Head Office">
                                                Login
                                            </a>
                                        <a class="dropdown-item" href="{{route('head_offices.head_office.toggle_archived',$headOffice->id)}}" title="{{$headOffice->archived_status}} Head Office" onclick="return confirm(&quot;Click Ok to {{$headOffice->archived_status}} Head Office.&quot;)">
                                            {{$headOffice->archived_status}}
                                        </a>

                                        <a href="{{ route('head_offices.head_office.edit', $headOffice->id ) }}" class="dropdown-item" title="Edit Head Office">
                                                Edit Details
                                            </a>
                                        <a class="dropdown-item text-danger " href="{{route('head_offices.head_office.toggle_suspend',$headOffice->id)}}" title="{{$headOffice->suspend_status}} Account" onclick="return confirm(&quot;Click Ok to {{$headOffice->suspend_status}} Head Office.&quot;)">
                                            {{$headOffice->suspend_status}} Head Office Account
                                        </a>



                                        <a href="#" class="dropdown-item" title="Access Remotely">
                                                Access Remotely
                                            </a>

                                            <a href="#" class="dropdown-item" title="Copy Form from another head office">
                                                Copy Form from another head office
                                            </a>
                                            <a href="{{route('head_offices.head_office.assign_super_admin_view',$headOffice->id)}}" class="dropdown-item" title="Add New Super Admin">
                                                Add New Super Admin
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

    </div>
@endsection