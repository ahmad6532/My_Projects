@extends('layouts.head_office_app')
@section('title', 'Case '.$case->id())

@section('sub-header')
@include('head_office.case_manager.notes.sub-header')
@endsection

@section('content')
<div id="content">
@include('layouts.error')
<div class="card card-qi content_widthout_sidebar">
    <div class="card-body">
        <div class="cm_content pt-2">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="text-center text-dark h3 font-weight-bold mb-4">Information Requests</h3>
                            <div class="table-responsive">
                                @if($case->case_request_informations->isEmpty())
                                <div class="text-left my-4">
                                <p>You have no Request Information.</p>
                            </div>
                                @else
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Sent to</th>
                                            <th>Requested by</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($case->case_request_informations as $case_request_information)
                                                <tr>
                                                    <td>{{$case_request_information->created_at->format('d/m/y')}} <br> {{ $case_request_information->created_at->diffForHumans()}}</td>
                                                    <td>{{$case_request_information->user->email}} / {{$case_request_information->user->name}}</td>
                                                    <td>{{$case_request_information->case->case_head_office->company_name}}</td>
                                                    <td>
                                                        @if($case_request_information->status == 0)
                                                        Waiting
                                                        @else
                                                        Received
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($case_request_information->status == 0)
                                                        <a class="btn btn-primary" href="{{route('head_office.case.edit_request',[$case->id,$case_request_information->id])}}" style="width: 132px">Edit Request</a><br/>
                                                        <a class="btn btn-danger" href="{{route('head_office.case.requested_information_delete',[$case->id,$case_request_information->id])}}">Cancel Request</a>
                                                        @else
                                                            <a class="btn btn-info" href="{{route('head_office.case.requested_information',[$case->id,$case_request_information->id])}}">View Response</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@section('styles')

<link rel="stylesheet" href="{{asset('tribute/tribute.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection

@section('scripts')
<script src="{{asset('admin_assets/js/view_case.js')}}"></script>
<script src="{{asset('admin_assets/js/form-template.js')}}"></script>
<script>
    $(document).on("click", ".delete_share_case", function(e) {
        e.preventDefault();
        let href = $(this).attr('href');
        
        let msg = $(this).data('msg');
        alertify.defaults.glossary.title = 'Alert!';
        alertify.confirm("Are you sure?", msg,
        function(){
            window.location.href = href;
        }, function(i){
            console.log(i);
        });
    });
</script>
@endsection

@endsection
