@extends('layouts.users_app')
@section('title', 'user requests')
@section('sidebar')
@include('layouts.user.sidebar-header')
@endsection
@section('content')

<style>
    .page-title {
        font-weight: 400;
        font-size: 2rem;
        /* padding-bottom: 10px; */
        /* margin:20px; */
    }
    
</style>

<div class="profile-center-area">
    <h3 class="page-title-1">
        Information Requests
    </h3>

    <table id="dataTable" class="row-border new-table" style="width:100%; display: none;">
        <thead>
        <tr>
            <!-- <th><input type="checkbox" name="select_all" value="1" id="dataTable-select-all"></th> -->
            <th></th>
            <th>Date</th>
            <th>From</th>
            <th>Reason</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($case_request_informations as $case_request_information)
                <tr>
                    <!-- <td></td> -->
                    <td style="text-align: center; vertical-align: middle; padding: 0;">
                            <div style="display: flex; justify-content: left; align-items: center; height: 100%;">
                                <img style="max-width: 150px; max-height: 150px; object-fit: cover;" src="{{$case_request_information->case->case_head_office->getLogoAttribute()}}" alt="headoffice logo">
                            </div>
                        </td>

                        
                    <td>{{ $case_request_information->created_at->format('d/m/Y') }}<br>{{$case_request_information->created_at->diffForHumans()}}</td>
                    <td>{{$case_request_information->first_name}} {{$case_request_information->last_name}}</td>
                    <td>{{isset($case_request_information->note) ? $case_request_information->note : 'No description available' }}</td>
                    <td>
                        @if($case_request_information->status == 0)
                        <a class="primary-btn" style="width: 137px;" href="{{route('user.statement.single_statement',$case_request_information->id)}}">Submit Request</a>
                        @else
                        <a class="primary-btn" style="width: 137px; background-color: rgb(0, 255, 213); color: white; pointer-events: none; cursor: not-allowed; opacity: 0.6; display: flex; justify-content: center; align-items: center; text-align: center; height: 40px;">Submited</a>



                        {{-- <a class="primary-btn" style="width: 137px;" href="{{route('user.statement.single_statement',$case_request_information->id)}}">Submitted</a> --}}

                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- <div id="emptyMessage" style="text-align: left; margin-top: 20px;">
        <p class="h5 draft-err-msg" style="display: none; padding-left: 20px;">You have not received any Information Requests.</p> 
    </div> -->

    <div id="emptyMessage" style="font-size: 18px; font-weight: normal; color: black; text-align: left;  padding-left:30px; ">
        <p class="emp-msg">You have not received any Information Requests.</p> 
    </div>
    
</div>

@endsection

@section('scripts')

<script>
    $(document).ready(function() {
        let data = @json($case_request_informations);

        if (data.length === 0) {
            $('#dataTable').hide();
            $('#emptyMessage p').show();
        } else {
            $('#dataTable').show();
            $('#emptyMessage p').hide();
            $('#dataTable').DataTable({
                paging: false,
                info: false,
                searching: false,
                language: {
                    emptyTable: "",
                    zeroRecords: "",
                }
            });
        }

        $('#dataTable-select-all').on('click', function() {
            var rows = $('#dataTable').DataTable().rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });
    });
</script>

@endsection
