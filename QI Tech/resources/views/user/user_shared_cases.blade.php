@extends('layouts.users_app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/alertify.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

<style>
    .page-title {
        /*margin-top: -44px;*/
        /*margin-bottom: 34px;*/
        font-weight: 400;
        font-size: 2rem;
        /* padding-bottom: 10px; */
        margin:20px;
    }

    .new-table tbody tr td.dataTables_empty:last-child,
    .new-table tbody tr td.dataTables_empty:first-child {
        border: none !important;
        font-size: 21px;
        font-weight: bold;
    }
    .dataTables_empty {
    font-size: 18px !important;
    font-weight: normal !important;
    color: black !important;
    text-align: left !important;
}



</style>



@section('sidebar')
@include('layouts.user.sidebar-header')
@endsection

    


@section('content')
    <div class="profile-center-area">
        @php
            use Carbon\Carbon;
        @endphp

                <div class="col-md-12">

            <h3 class="text-left text-dark h3 font-weight-bold mb-4" style="padding-bottom: 0 !important;">Shared Cases</h3>
            @If(!empty($share_cases))
            
            <table id="dataTable" class="row-border new-table" style="width:100%">
                <thead>
                    <tr>
                        <th><input type="checkbox" name="select_all" value="1" id="dataTable-select-all"></th>
                        <th></th>
                        <th>Date</th>
                        {{--                        <th>Shared by</th> --}}
                        <th>Status</th>
                        {{--                        <th>Description</th> --}}
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($share_cases as $key => $share)
                        <tr>
                            <td></td>
                            <td style="vertical-align: middle"><img style="width: 100px;" src="{{ $share->case->case_head_office->getLogoAttribute() }}"
                                    alt="headoffice logo">
                            </td>

                            <td>{{ $share->created_at->format('d/m/Y') }}<br />({{ $share->created_at->diffForHumans() }})
                            </td>
                            {{--                            <td>{{ $share->user->name }}</td> --}}
                            <td>
                                @if ($share->removed_by_user)
                                    <div class="cm_comment_comment">
                                        <b style="color: red">Cancelled</b>
                                    </div>
                                @elseif ($share->is_revoked)
                                    <div class="cm_comment_comment">
                                        <b style="color: red">Access Revoked </b> by <b>{!! $share->user->name !!} </b>
                                        {!! $share->updated_at->format(config('app.dateFormat')) !!} {!! $share->updated_at->format(config('app.timeFormat')) !!} ({!! $share->updated_at->diffForHumans() !!})

                                        @if (count($share->extension))
                                            <br>
                                            @php $val = $share->extension->last(); @endphp
                                            Extension requested until {!! $val->extension_time->format(config('app.dateFormat')) !!} {!! $val->extension_time->format(config('app.timeFormat')) !!}
                                            ({!! $val->extension_time->diffForHumans() !!})
                                        @endif
                                    </div>
                                @else
                                    @if ($share->duration_of_access > Carbon::now())
                                        <div class="cm_comment_comment">
                                            <b style="color: green">Available</b><br />
                                            ({!! $share->duration_of_access->diffForHumans() !!})
                                        </div>
                                    @else
                                        <div class="cm_comment_comment">
                                            <b style="color: red"> Expired</b><br />
                                            <span style="font-size: 15px ;">
                                                ({!! $share->duration_of_access->diffForHumans() !!})
                                            </span>
                                        </div>
                                    @endif
                                @endif
                            </td>
                            {{--                            <td>{{ $share->note ? $share->note : 'No description available' }}</td> --}}
                            <td>
                                @if ($share->removed_by_user)
                                    <span class="badge badge-secondary badge-user">Cancelled</span>
                                @elseif ($share->is_revoked || $share->duration_of_access < Carbon::now())
                                    @if ($share->share_case_extension->where('status', 0)->last())
                                        <div class="d-flex gap-1">
                                            <span data-bs-toggle="tooltip" title="Extension Requested"
                                                class="badge badge-info badge-user">Extension Requested</span>
                                            @if (count($share->extension))
                                                <br>
                                                @php $val = $share->extension->last(); @endphp
                                                <a href="{{ route('user.share_case.request_extension_remove', [$share->id, $val->id,'_token'=>csrf_token()]) }}"
                                                    data-msg="Are you sure you want to remove this?"
                                                    class="badge bg-danger badge-user delete_extension">Cancel Request</a>
                                        </div>
                                    @endif
                                @elseif (
                                    $share->share_case_extension->where('status', 2)->last() &&
                                        $share->share_case_extension->where('status', 2)->last()->status ==
                                            $share->share_case_extension->last()->status)
                                    <a data-bs-toggle="modal" data-bs-target="#request_access_{{ $share->id }}"
                                        href="{{ route('user.share_case', $share->id) }}"
                                        class="badge badge-danger badge-user">
                                        Extension Rejected, Requested Again</a>
                                @else
                                    <a data-bs-toggle="modal" data-bs-target="#request_access_{{ $share->id }}"
                                        href="{{ route('user.share_case', $share->id) }}"
                                        class="badge badge-warning badge-user">Request Extension</a>
                                @endif
                                @include('user.request_access', ['share' => $share])
                            @else
                                <a href="{{ route('user.share_case', $share->id) }}"
                                    class="badge badge-success badge-user">View</a>




                                <div class="modal fade" id="loginModal" tabindex="-1" role="dialog"
                                    aria-labelledby="loginModal" aria-hidden="true">


                                    <div class="modal-dialog position-relative" role="document">
                                        <div class="modal-content p-4">
                                            {{-- <button type="button" class="close"
                                                style="position:absolute; top:4px; right:4px;" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button> --}}
                                            <button type="button" class="close" style="position:absolute; top:10px; right:10px; border: none; background: none; font-size: 1.5rem; color: #000;" data-bs-dismiss="modal" aria-label="Close">
                                                <i class="fas fa-times"></i>
                                            </button>

                                            <form action="{{ route('user.share_case.remove', ['id'=>$share->id,'_token'=>csrf_token()]) }}"
                                                method="get">
                                                <div class="modal-body">
                                                    <h5 class="modal-title"
                                                        style="color:black;font-weight: bold; text-align:center;"
                                                        id="change_password_ModalLabel">Remove shared case</h5>
                                                    @csrf
                                                    <div class="">
                                                        <label style="margin: 0;font-size: 12px;"
                                                            for="email">Comment</label>
                                                        <input type="text" name="comment" placeholder="Comment"
                                                            class="form-control" style="height:50px" required>

                                                    </div>


                                                    <button type="submit"
                                                        class="primary-btn font-weight-bold user-custom-btn w-100 d-flex justify-content-center"
                                                        style="padding: 8px 30px; background:red !important; margin-top:8px">Remove</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>




                                <button data-bs-toggle="modal" data-bs-target="#loginModal"
                                    class="badge bg-danger badge-sm remove-share border-0">Remove</button>
                    @endif
                    </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>

            @else
            <div class="text-left my-4">
                <p>There are no Shared Case</p>
            </div>
            @endif

        </div>
    </div>


    <!-- profile page contents -->







@section('scripts')
    <script src="{{ asset('js/alertify.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            loadActiveTab();

            let table = $('#dataTable').DataTable({
    paging: false,
    info: false,
    language: {
    search: "",
    // emptyTable: "You have no shared cases"
    },
    'columnDefs': [{
        'targets': 0,
        'searchable': false,
        'orderable': false,
        'className': '',
        'render': function(data, type, full, meta) {
            return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
        }
    }]
});



            // Function to check and hide empty columns
            function hideEmptyColumns() {
                table.columns().every(function(index) {
                    let column = this;
                    let data = column.data().toArray();
                    let hasData = data.some(function(cell) {
                        return cell !== null && cell !== "";
                    });
                    if (!hasData) {
                        column.visible(false);
                        $('#dataTable_filter').css('margin', '0').css('margin-top', '-45px');
                    }
                });
            }

            hideEmptyColumns();

            $('#dataTable-select-all').on('click', function() {
                // Get all rows with search applied
                var rows = table.rows({
                    'search': 'applied'
                }).nodes();
                // Check/uncheck checkboxes for all rows in the table
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            })

        });

        function loadActiveTab(tab = null) {
            if (tab == null) {
                tab = window.location.hash;
            }
            console.log($('.nav-tab li > a[data-bs-target="' + tab + '"]'));

            $('.nav-tab li > a[data-bs-target="' + tab + '"]').tab('show');
        }
        $(document).on("click", ".delete_extension", function(e) {
            e.preventDefault();
            let href = $(this).attr('href');

            let msg = $(this).data('msg');
            alertify.defaults.glossary.title = 'Alert!';
            alertify.confirm("Are you sure?", msg,
                function() {
                    window.location.href = href;
                },
                function(i) {
                    console.log(i);
                });
        });
    </script>
@endsection
@endsection



