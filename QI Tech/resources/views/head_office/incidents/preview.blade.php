@extends('layouts.head_office_app')
@section('title', 'Import location incidents')
@section('sidebar')
@include('layouts.company.sidebar')
@endsection
@section('content')
<div id="content">


@include('layouts.error')

<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <div class="">
                <h4 class="text-info font-weight-bold">{{$location->name()}} Records
                <span style="float: right">
                    <button style="display: none" id="submit_link_record" onclick="document.getElementById('head_office.location.single_record_link').submit();" class="btn btn-sm btn-info">Import selected incidents</button>
                </span>
                </h4>
            </div>
        </div>

        @if(count($records) == 0)
        <h5 class="text-info text-center">No Record Available</h5>
        @else
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
                <thead>
                    <tr>
                        <th></th>
                        <th>Date</th>
                        <th>Status</th>

                        <th>Actions</th>
                    </tr>
                </thead>
                <form action="{{route('head_office.location.single_record_link')}}" id="head_office.location.single_record_link" method="post">
                    @csrf
                    <tbody class="verified_devices_body">
                        @include('head_office.incidents.records',['records' => $records])
                        <tr class="line-reloading" style="display:none">
                            <td colspan="4" >
                                <div class="line line-date  print-display-none" >
                                    <div class="timeline-label"><i class="spinning_icon fa-spin fa fa-spinner"></i></div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </form>
            </table>

        </div>

        @endif
    </div>
    
</div>
@include('head_office.incidents.import_cases')
</div>

@if(Session::has('success'))
    <script>
        alertify.success("{{ Session::get('success') }}");
    </script>
@elseif(Session::has('error'))
<script>
    alertify.success("{{ Session::get('error') }}");
</script>
@endif
@endsection