@extends('layouts.admin.master')
@section('content')
    <style>
        .blog-inner-img {
            height: 37px;
            position: absolute;
            right: -8px;
            width: 70px;
            object-fit: contain;
            top: 35%;
        }
    </style>
    <!-- Start Content-->
    <div class="container-fluid">

        <div class="row">
            @if (session('success'))
                <div class="alert alert_vt" id="alertID">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <div class="alert alert-success small " style="max-width:100%;">{{ session('success') }}</div>
                </div>
            @endif
            <div class="col-xl-1"></div>
            <div class="col-xl-10 mt-4">
                <div class="card-box border-1">
                    <div class="text-center">
                        <h1 class="text-heading_vt pb-4">Theme Settings</h1>
                    </div>

                    <form action="{{ route('update.theme') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="billing-last-name">App Tab Name</label>
                                    <input name="tab_name" class="form-control" @if (!$user->haspermission(['theme-settings-all','theme-settings-write'])) disabled @endif value="{{ $appName->value }}" type="text"
                                        placeholder="Enter App Tab Name" id="billing-last-name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">App Favicon<small style="color: #6F6F6F;"> (Recommended size
                                            favicon 16*16)</small></label>
                                    <input class="form-control" name="favicon" @if (!$user->haspermission(['theme-settings-all','theme-settings-write'])) disabled @endif value="{{ $AppFaviconL->value }}"
                                        type="file" placeholder="Enter your last name" id="billing-last-name">
                                    @if ($AppFaviconL->value != null)
                                        <img class="blog-inner-img"
                                            src="{{ $AppFaviconL->value != null ? asset('assets/images/theme/' . $AppFaviconL->value) : '' }}">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="billing-last-name">Logo</label>
                                    <input class="form-control" @if (!$user->haspermission(['theme-settings-all','theme-settings-write'])) disabled @endif name="applogo" {{ $AppLogo->value }} type="file"
                                        placeholder="Enter SMTP From Email" id="billing-last-name">
                                    @if ($AppLogo->value != null)
                                        <img class="blog-inner-img"
                                            src="{{ $AppLogo->value != null ? asset('assets/images/theme/' . $AppLogo->value) : '' }}">
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if ($user->haspermission(['theme-settings-all','theme-settings-write']))
                        <div class="pt-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="page-btn sm-page-btn p-2">Update</button>
                                </div>
                            </div> <!-- end row -->
                        </div>
                        @endif
                    </form>
                </div>
            </div>
            <div class="col-xl-1">

            </div>
        </div>
    </div>
    </div>
@endsection
