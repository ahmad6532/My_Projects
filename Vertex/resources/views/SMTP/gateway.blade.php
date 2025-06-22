@extends('layouts.admin.master')
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row">
            @if (session('success'))
                <div class="alert alert_vt" id="alertID">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <div class="alert alert-success small " style="max-width:100%;">{{ session('success') }}</div>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert_vt" id="alertID">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <div class="alert alert-danger small " style="max-width:100%;">{{ session('error') }}</div>
                </div>
            @endif
            <div class="col-xl-1">

            </div>
            <div class="col-xl-10 mt-4">
                <div class="card-box border-1">
                    <div class="text-center">
                        <h1 class="text-heading_vt pb-4">SMTP Gateway</h1>
                    </div>

                    <form action="{{ url('smtp-update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-2">
                            <div class="col-lg-6 col-md-6 mb-2">
                                <label for="basic-url" class="form-label">From Email<span
                                        class="text-danger fs-5">*</span></label>
                                <div class="input-group">
                                    <input type="email" name="email" value="{{ $smtp_from_email->value }}"
                                        class="form-control" id="email" placeholder="From Email" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-2">
                                <label for="basic-url" class="form-label">From Name<span
                                        class="text-danger fs-5">*</span></label>
                                <div class="input-group">
                                    <input type="name" name="from_name" value="{{ $smtp_from_name->value }}"
                                        class="form-control" id="from_name" placeholder="From Name" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-2">
                                <label for="basic-url" class="form-label">Encryption Type<span
                                        class="text-danger fs-5">*</span></label>
                                <select class="form-control" aria-label="Default select example" name="encryption">
                                    <option value="tls" {{ $smtp_encryption->value == 'tls' ? 'selected' : '' }}>TLS
                                    </option>
                                    <option value="ssl" {{ $smtp_encryption->value == 'ssl' ? 'selected' : '' }}>SSL
                                    </option>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-2">
                                <label for="basic-url" class="form-label">User Name<span
                                        class="text-danger fs-5">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="username" value="{{ $smtp_user_name->value }}"
                                        class="form-control" id="username" placeholder="User Name" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-2">
                                <label for="basic-url" class="form-label">SMTP Host<span
                                        class="text-danger fs-5">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="smtphost" value="{{ $smtp_host->value }}"
                                        class="form-control" id="smtphost" placeholder="SMTP Host" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-2">
                                <label for="basic-url" class="form-label">Password<span
                                        class="text-danger fs-5">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" value=""
                                        class="form-control" id="password" placeholder="Password">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-2">
                                <label for="basic-url" class="form-label">Port<span
                                        class="text-danger fs-5">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="port" value="{{ $smtp_port->value }}"
                                        class="form-control" id="port" placeholder="Port" required>
                                </div>
                            </div>
                        </div>
                        <div class="pt-4 pb-2">
                            <div class="row">
                                <div class="col-md-12">
                                    @if ($user->haspermission(['smtp-gateway-all', 'smtp-gateway-write']))
                                        <button class="page-btn sm-page-btn}}">Update</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-xl-1">

            </div>
        </div>
    </div>
    </div>
    <script type="text/javascript">
        setTimeout(function() {
            $('#alertID').hide('slow')
        }, 2000);
    </script>
@endsection
