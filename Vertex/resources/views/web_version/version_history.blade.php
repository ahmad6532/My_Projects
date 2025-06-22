@extends('layouts.admin.master')
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        @if (session('error'))
        <div class="alert alert_vt" id="alertID">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <div class="alert alert-danger small " style="max-width:100%;">{{ session('error') }}</div>
        </div>
        @endif
        @if (session('success'))
        <div class="alert alert_vt" id="alertID">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <div class="alert alert-success small " style="max-width:100%;">{{ session('success') }}</div>
        </div>
        @endif
        <div class="row">
            <div class="col-xl-12 mt-4">
                <div class="card-box border-1">

                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <h4 class="header-title m-0 pt-2">Version History</h4>
                        </div>
                        {{-- <div class="col-lg-6">
                            <div class="">
                                @if ($user->haspermission(['web-version-history-all','web-version-history-write']))
                                <a href="#" data-toggle="modal" data-target="#versionmodal1"
                                    class="page-btn float-right">Add History</a>
                                @endif
                            </div>
                        </div> --}}
                    </div>
                    <div class="table-responsive">
                        <table id="table1"
                            class="table table-bordered table-striped table-nowrap table-hover table-centered m-0">
                            <thead class="table-head border-top border-bottom">
                                <tr>
                                    <th>Sr</th>
                                    <th>Version</th>
                                    <th>Update Reason</th>
                                    <th>Type</th>
                                    <th>Updated On</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($versions as $key => $item)
                                    <tr>
                                        <td>{{  $key + 1}}</td>
                                        <td>{{ $item->version }}</td>
                                        <td>{{ $item->reason }}</td>
                                        <td>{{ $item->type }}</td>
                                        <td>{{ date('d-m-Y, h:i A', strtotime($item->updated_at)) }}</td>
                                        <td>
                                            <a href="#" style="color: #3F80FF;"
                                                onclick="openModal('{{ $item->id }}', '{{ $item->version }}', '{{ $item->updated_at }}', '{{ $item->reason }}')">View</a>

                                        </td>
                                    </tr>
                                @empty
                                    <tr class="text-center">
                                        <td colspan="7">No Record Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="versionmodal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="exampleModalLabel">Add Version History</span>
                    <button type="button" class="btn-close close" data-dismiss="modal" aria-label="Close">
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('save.version') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nameInput" class="form-label">Version<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <input type="number" name="version" class="form-control" placeholder="Enter Version">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nameInput" class="form-label">Update Reason<span class="red"
                                            style="font-size:14px;">*</span></label>
                                    <textarea class="form-control" name="reason" rows="7" placeholder="Enter Update Reason"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" value="mobile" id="customRadio451" name="type"
                                        onchange="yeslicensed();" class="custom-control-input" checked="">
                                    <label class="custom-control-label" for="customRadio451">Mobile</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" value="web" id="customRadio143" name="type"
                                        onchange="yeslicensed();" class="custom-control-input" checked="">
                                    <label class="custom-control-label" for="customRadio143">Web</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="button" class="page-btn page-btn-outline hover-btn sm-page-btn" value="Cancel"
                                    onclick="closeModal()">

                                <button class="page-btn sm-page-btn">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="versionmodal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header pb-0">
                    <button type="button" class="btn-close close" data-dismiss="modal" aria-label="Close">
                </div>
                <div class="modal-body py-0">
                    <div class="version-head d-flex">
                        <div>
                            <h4 style="border-right: 1px solid #CCCCCC; padding-right: 10px;">Version : <span
                                    id="version_id"><b> 1.3.2</b></span>
                            </h4>
                        </div>
                        <div>
                            <h4 class="px-2">Updated On : <span id="updated_id"> </span></h4>
                        </div>
                    </div>
                    <div class="version-detail">
                        <p id="reason_id"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    $(document).ready(function() {
                var customButton = `
                <div class="col-lg-2 col-md-2 px-1 mb-2">
                    <div class="d-flex justify-content-center">
                        <div>
                            @if ($user->haspermission(['web-version-history-all','web-version-history-write']))
                                <a href="#" data-toggle="modal" data-target="#versionmodal1"
                                    class="page-btn float-right">Add History</a>
                                @endif
                        </div>
                    </div>
                </div>
                `;
                $('#table1').DataTable({
                    dom: '<"d-flex justify-content-between"lBf>rtip',
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search..."
                    },
                    buttons: [
                        {
                            extend: 'csvHtml5',
                            text: '<img src="' + "{{ asset('assets/images/csv.png') }}" + '" />',
                            exportOptions: {
                                columns: ':not(:last-child):not(:last-child-1)'
                            }
                        },
                        {
                            extend: 'print',
                            text: '<img src="' + "{{ asset('assets/images/print.png') }}" + '" />',
                            exportOptions: {
                                columns: ':not(:last-child):not(:last-child-1)'
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<img src="' + "{{ asset('assets/images/pdf.png') }}" + '" />',
                            exportOptions: {
                                columns: ':not(:last-child):not(:last-child-1)'
                            }
                        },
                    ]
                });
                $('#table1_filter').before(customButton);
            });

        function closeModal() {
            $('#versionmodal1').modal('hide');
        }

        function openModal(id, version, updated_at, reason) {
            document.getElementById('version_id').innerHTML = version;
            document.getElementById('updated_id').innerHTML = updated_at;
            document.getElementById('reason_id').innerHTML = reason;
            $('#versionmodal2').modal('show');
        }


        function yeslicensed() {
            if (document.getElementById('customRadio451').checked) {
                document.getElementById('licenseNo').style.display = 'block';
            } else {
                if (document.getElementById('customRadio143').checked)
                    document.getElementById('licenseNo').style.display = 'none';
            }
        }
        setTimeout(function() {
                $('#alertID').hide('slow')
            }, 3000);
    </script>
@endsection
