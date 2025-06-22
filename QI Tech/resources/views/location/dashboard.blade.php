@extends('layouts.location_app')
@section('title', 'Location Dashboard')
@section('content')
    <div id="content">
        <div class="container-fluid mt-5">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-info">Dashboard</h1>
            </div>

            <!-- Content Row -->
            <div class="row">

                <!-- Patient safety alerts Card Example -->
                {{-- <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Near Misses</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{App\Models\NearMiss::where('location_id',$location->id)->where('status','=','active')->count()}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

                <!-- Patient safety alerts Card Example -->
                {{-- <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    patient safety alerts require actioning</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{App\Models\LocationReceivedAlert::where('location_id',$location->id)->where('status','=',App\Models\LocationReceivedAlert::$unactionedStatus)->count()}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
            </div>
        </div> --}}

                {{-- Notification Table --}}
                @if (count($reminders) != 0)
    <div class="row">
        <div class="col-6 border shadow rounded mx-2">
            <h4 class="mt-3 mb-2 text-info">Form Reminders</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Form</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reminders as $reminder)
                        @php
                            $form = $location->group_forms()->where('id', $reminder->form_id)->first();
                            $reminderTime = \Carbon\Carbon::parse($reminder['time']);
                            $now = now();
                            $timeDifference = $now->diffInMinutes($reminderTime, false); 

                            // Determine status
                            if ($timeDifference < 0) {
                                $status = 'Overdue (' . $reminderTime->diffForHumans($now) . ')';
                            } elseif ($timeDifference <= 60) {
                                $status = 'Within ' . $reminderTime->diffForHumans($now) ;
                            } elseif ($reminderTime->isToday()) {
                                $status = 'Today (' . $reminderTime->diffForHumans($now) . ')';
                            } else {
                                $status = 'Upcoming (' . $reminderTime->diffForHumans($now) . ')';
                            }

                        @endphp

                        @if ($form && isset($form->name))
                            <tr>
                                <td>{{ $form->name }}</td>
                                <td class="{{ $timeDifference < 0 ? 'text-danger' : 'text-success' }}">{{ $status }}</td>
                                <td>{{ \Carbon\Carbon::parse($reminder['created_at'])->format('d F, Y h:i a') }}</td>
                                <td>{{ $reminderTime->format('H:i') }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

            </div>


            @php
                use App\Helpers\Helper;
                $LocationServiceMessage = Helper::ServiceMessage('Location', 'location');
                $LocationServiceMessageLength = count($LocationServiceMessage);
            @endphp
            <input type="hidden" id="service_message_length" data-mdb-toggle="modal"
                value="{{ $LocationServiceMessageLength }}" role="button">

            @if ($LocationServiceMessageLength)
                @foreach ($LocationServiceMessage as $key => $service_message)
                    <div class="modal fade" id="ServiceMessageModalToggle{{ $key }}" aria-hidden="true"
                        aria-labelledby="exampleModalToggleLabel1" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content  modal-fullscreen">
                                <div class="modal-header">
                                    <h5 class
                                ="modal-title" id="exampleModalToggleLabel1">
                                        {{ $service_message->title }}</h5>
                                </div>
                                <div class="modal-body">
                                    {{ $service_message->message }}
                                </div>
                                <div class="modal-footer">
                                    @if ($LocationServiceMessageLength == $key + 1)
                                        <button class="btn btn-info" id="dismiss" data-dismiss="modal">
                                            Dismiss
                                        </button>
                                    @endif
                                    @if ($LocationServiceMessageLength > 1 && $LocationServiceMessageLength != $key + 1)
                                        <button class="btn btn-info" onclick="nextModal({{ $key + 1 }})"
                                            data-dismiss="modal">
                                            Next
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
            {{-- <div class="container-fluid mt-5">
        @if ($location->opening_hours->set() == false)
            @include('location.opening_hours')
        @else
        <!-- Location hours are already set. -->
        @endif
    </div>  --}}
        </div>
    @endsection

    @section('scripts')
        <script>
            $(document).ready(function() {
                let foo = Math.random() * 100;
                if (foo > 60 && $('#opening_hours_modal').length) {
                    const openingModalTimeout = setTimeout(function() {
                        $('#opening_hours_modal').modal('show');
                    }, 5000);

                }
            });

            function nextModal(i) {
                $('#ServiceMessageModalToggle' + i).modal({
                    backdrop: 'static',
                    keyboard: false
                }, 'show');
            }
            $(document).ready(function() {
                var sml = $("#service_message_length").val();
                if (sml > 0) {
                    $('#ServiceMessageModalToggle0').modal({
                        backdrop: 'static',
                        keyboard: false
                    }, 'show');
                    setTimeout(function() {
                        $('#dismiss').attr('disabled', false);
                    }, 5000);
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
