@extends('layouts.admin.master')
@section('content')
    <style>
        .icon-file-path {
            font-size: 18px;
        }

        .widget_data_vt {
            overflow: hidden;
            border-bottom: 1px dashed #D8D8D8;
            padding-top: 15px;
        }

        .float-left {
            float: left !important;
        }

        .action-done h4 {
            font-size: 12px;
            line-height: 17px;
            font-weight: 600;
            margin: 0;
        }

        .person-info {
            display: flex;
            justify-content: space-between;
        }

        .person-info h5 {
            font-size: 12px;
            line-height: 15px;
            font-weight: 400;
        }

        .person-info p {
            font-size: 12px;
            line-height: 15px;
            font-weight: 400;
            margin: 10px 0;
        }
    </style>
    <div class='row'>
        <div class="col-xl-12 mt-4">
            <div class="card-box border-1">
                <div class="table-responsive">
                    <table id="table1"
                        class="table table-bordered table-striped table-nowrap table-centered table-atten-sheet m-0">
                        <tbody class="Listing_vt">
                            <!--begin::Content Wrapper-->
                            <div class="main d-flex flex-column flex-row-fluid">
                                <!--end::Subheader-->
                                <div class="flex-column-fluid" id="kt_content">
                                    <div class="">
                                        <div class="row align-items-center">
                                            <div class="col-lg-6 col-md-6 policy_header_vt">
                                                <h2 class='subtitle_vt'>Recent
                                                    Activities</h2>
                                            </div>
                                            {{-- <div class="col-lg-6 col-md-6">
                            <button data-toggle="modal" data-target="#deleteLogsModal"
                                class="btn btn-danger btn-sm float-right"><i class="fa-regular fa-trash-can"
                                    style="font-size:12px;"></i> Delete Log</button>
                        </div> --}}
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="content-wrapper_vt">
                                            @foreach ($logs as $log)
                                                <div class="widget_data_vt">
                                                    <div class="mr-2 float-left">
                                                        <i
                                                            class="fontello-icon {{ app\models\Log::$log_type[$log->type]['icon'] }}"></i>
                                                    </div>
                                                    <div class="action-done">
                                                        <h4>{!! app\models\Log::$log_type[$log->type]['msg'] . ' ' . $log->msg !!}</h4>
                                                        <div class="person-info">
                                                            <h5>{{ $log->user ? $log->user->email : '' }}</h5>
                                                            <p>{{ $log->created_at->diffForHumans() }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="pagination justify-content-end mt-5">
                                                {{ $logs->links() }}
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="modal" id="deleteLogsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="card-body">
                        <div class="mb-2 row">
                            <div class="col-lg-12 px-0">
                                <h4 class="heading-user text-center">Delete Logs</h4>
                            </div>
                        </div>
                        <form action="#" id="logDeleteForm" class="align-items-center justify-content-center">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <Label for="date_from">From</Label>
                                    <input type="date" class="form-control" name="start_from" id="start_from">
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <Label for="date_to">To</Label>
                                    <input type="date" class="form-control" name="end_to" id="end_to">
                                </div>
                            </div>
                            <div class="form-group row float-right">
                                <div class="mt-5">
                                    <button type="reset" class="btn btn-dark btn-modal_vt" data-dismiss="modal"
                                        aria-label="Close">Cancel</button>
                                    <button id="submit_delete_form" type="submit" class="btn btn-success  btn-modal_vt btn-md ml-2 mr-3">Delete
                                        Logs</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

{{-- @push('page-js')
    <script>
        const displayTime = document.querySelector(".display-time");
        // Time
        function showTime() {
            let time = new Date();
            displayTime.innerText = time.toLocaleTimeString("en-US", {
                hour12: false
            });
            setTimeout(showTime, 1000);
        }

        showTime();

        // Date
        function updateDate() {
            let today = new Date();

            // return number
            let dayName = today.getDay(),
                dayNum = today.getDate(),
                month = today.getMonth(),
                year = today.getFullYear();

            const months = [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December",
            ];
            const dayWeek = [
                "Sunday",
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday",
            ];
            // value -> ID of the html element
            const IDCollection = ["day", "daynum", "month", "year"];
            // return value array with number as a index
            const val = [dayWeek[dayName], dayNum, months[month], year];
            for (let i = 0; i < IDCollection.length; i++) {
                document.getElementById(IDCollection[i]).firstChild.nodeValue = val[i];
            }
        }

        updateDate();
    </script>
    <script>
        var today = new Date().toISOString().split('T')[0];
        document.getElementById('start_from').setAttribute('max', today);
        document.getElementById('end_to').setAttribute('max', today);

        $("#logDeleteForm").submit(function(e) {
            e.preventDefault();
            var startDate = $('#start_from').val();
            var endDate = $('#end_to').val();

            if (startDate == '') {
                toastr.error('You must select a start date !!');
                return;
            }

            if (startDate > endDate) {
                toastr.error('Your selected range is incorrect !!');
                return;
            }

            Swal.fire({
                    title: "Are you sure?",
                    text: 'Do you want to delete the Logs?',
                    imageUrl: "{{ asset('assets/media/alert-icon.png') }}",
                    imageWidth: 70,
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    reverseButtons :true,
                    allowOutsideClick: false,
                    confirmButtonColor: '#D92D20',
                    cancelButtonColor: '#D0D5DD',
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        var data = {
                            '_token': '{{ csrf_token() }}',
                            'start_from': startDate,
                            'end_to': endDate,
                        }

                        $.ajax({
                            url: '{{ route('admin.delete.notifications') }}',
                            type: "POST",
                            data: data,
                            success: function(result) {
                                if (result.status == true) {
                                    Swal.fire({
                                        title: 'Deleted',
                                        text: "Deleted Successfully",
                                        imageUrl: "{{ asset('assets/media/success-icon.png') }}",
                                        imageWidth: 70,
                                        showCancelButton: false,
                                        showConfirmButton: false
                                    })
                                    window.location.reload();
                                } else {
                                    Swal.fire({
                                        title: 'Deleted',
                                        text: "Logs does not deleted",
                                        imageUrl: "{{ asset('assets/media/alert-icon.png') }}",
                                        imageWidth: 70,
                                        showConfirmButton: false,
                                        showConfirmButton: false
                                    })
                                }
                            }
                        });
                    }
                });

        });
    </script>
@endpush --}}
