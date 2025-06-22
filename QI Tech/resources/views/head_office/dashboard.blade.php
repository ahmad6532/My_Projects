@extends('layouts.head_office_app')
@section('title', 'Head Office Dashboard')
@section('sidebar')
@include('layouts.company.sidebar')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-info">Head Office Dashboard </h1>
        </div>
        <!-- Content Row -->
        <div class="row">
            <!-- Patient safety alerts Card Example -->
            {{-- <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    patient safety alerts</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">2x</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300 "></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Pending Requests Card Example -->
            {{-- <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Root Cause Analysis</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">5x</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            {{-- <div class="col-xl-3 col-md-6 mb-4">
                <a href="{{route('head_office.my_organisation')}}" class="no-arrow" style="text-decoration: none;">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Newly Joined Locations</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{count($locations)}}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-comments fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <a href="{{route('links.link.removeable_links')}}" class="no-arrow" style="text-decoration: none;">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        To be removed links</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{count($to_be_removed_links)}}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-comments fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div> --}}
        </div>
    </div>

    @php
        use App\Helpers\Helper;
        $HeadOfficeServiceMessage=Helper::ServiceMessage('Head Office','web');
        $HeadOfficeServiceMessageLength=count($HeadOfficeServiceMessage);
    @endphp
    <input type="hidden" id="service_message_length" data-mdb-toggle="modal" value="{{$HeadOfficeServiceMessageLength}}" role="button">

    @if($HeadOfficeServiceMessageLength)
        @foreach($HeadOfficeServiceMessage as $key=> $service_message)
            <div class="modal fade" id="ServiceMessageModalToggle{{$key}}" aria-hidden="true" aria-labelledby="exampleModalToggleLabel1" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content  modal-fullscreen">

                        <div class="modal-header">
                            <h5 class
                                ="modal-title" id="exampleModalToggleLabel1">{{$service_message->title}}</h5>
                        </div>
                        <div class="modal-body">
                            {{$service_message->message}}
                        </div>

                        <div class="modal-footer">
                            @if($HeadOfficeServiceMessageLength==$key+1)
                                <button class="btn btn-info" id="dismiss" data-dismiss="modal">
                                    Dismiss
                                </button>
                            @endif
                            @if($HeadOfficeServiceMessageLength>1 && $HeadOfficeServiceMessageLength!=$key+1)
                                <button class="btn btn-info" onclick="nextModal({{$key + 1}})" data-dismiss="modal">
                                    Next
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@endsection

@section('scripts')
    <script>
        function nextModal(i)
        {
            $('#ServiceMessageModalToggle' + i).modal({backdrop: 'static', keyboard: false},'show');
        }
        $(document).ready(function(){
            var sml=$("#service_message_length").val();
            if(sml>0){
                $('#ServiceMessageModalToggle0').modal({backdrop: 'static', keyboard: false},'show');
                setTimeout(function () {
                    $('#dismiss').attr('disabled',false);
                },5000);
                // $('#ServiceMessageModalToggle0').modal({
                //     backdrop: 'static'
                // });
//                 for(var i=0;i<sml;i++)
//                 {
//                     $('#ServiceMessageModalToggle'+i).modal({backdrop: 'static', keyboard: false}, 'show');
//                     for(var j=0;j<i;j++)
//                     {
//                         $('#ServiceMessageModalToggle'+j).modal('hide');
//                     }
//
//                     for(var k=sml;k>i;k--)
//                     {
//                         $('#ServiceMessageModalToggle'+k).modal('hide');
//                     }
// //not working
//                 }
            }
        });
    </script>
@endsection