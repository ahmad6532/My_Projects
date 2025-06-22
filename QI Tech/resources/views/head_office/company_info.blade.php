<?php
$headOffice = Auth::guard('web')->user()->selected_head_office;
$customTheme = false;
if ($headOffice->link_token == Session::get('token')) {
    $customTheme = true;
    $themeData = $headOffice;
}
?>

@extends('layouts.head_office_app')
@section('title', 'Head office Settings')

<div class="loader-container" ng-show="UI.loading" style="display:none">
    <div class="loader"></div>
</div>
@section('styles')
    <style>
        .profile-pic {
            color: transparent;
            transition: all .3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            transition: all .3s ease;
            background: white;

            img {
                position: absolute;
                object-fit: cover;
                width: 120px;
                height: 120px;
                box-shadow: 0 0 10px 0 rgba(255, 255, 255, .35);
                border-radius: 100px;
                z-index: 0;
            }

            .-label {
                cursor: pointer;
                height: 120px;
                width: 120px;
            }

            &:hover {
                .-label {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    background-color: rgba(0, 0, 0, .8);
                    z-index: 10000;
                    color: rgb(250, 250, 250);
                    ;
                    transition: background-color .2s ease-in-out;
                    border-radius: 100px;
                    margin-bottom: 0;
                }
            }

            span {
                display: inline-flex;
                padding: .2em;
                height: 2em;
            }
        }

        .brand {
            color: #2BAFA5;
        }

        .loc-wrap-card {
            background: linear-gradient(0deg, #ffffff, #ffffff);
            transition: 0.5s ease-in-out !important;
            cursor: pointer;
            width: 100%;
        }

        .loc-wrap-card:hover {
            background: linear-gradient(0deg, #25958b, #31c7ba);
            transition: 0.3s ease-in-out !important;
        }

        .loc-wrap-card:hover strong,
        .loc-wrap-card:hover small,
        .loc-wrap-card:hover i {
            transition: 0.2s ease-in-out;
            color: #fff !important;
        }

        input[type='color']{
            height: 100%;
            border-radius: 6px !important;
        }
    </style>
@endsection
@section('content')

    <input type="hidden" value="{{ route('head_office.update_profile') }}" id="update_profile">
    

    <input type="hidden" id="_token" value="{{ csrf_token() }}">
    <div id="content">
        


        {{-- <img src="{{ $user->getProfilePictureUrl() }}" alt="profile picture" class="img-fluid rounded-circle img-profile profile-picture-top" width="100"> --}}

        <div class="form-group form-name-input-edit justify-content-center resizing-input d-flex flex-column align-items-center" style="padding: 0; margin: 0;">
            <div class="position-relative profile-pic mt-3 d-flex flex-column align-items-center" style="padding: 0; margin: 0;">
                <label for="" class="d-flex align-items-center justify-content-center flex-column" 
                    style="width: 200px; height: 100px; padding: 0; margin: 0;">
                    @if (isset($head_office->logo))
                        <img src="{{ $head_office->logo }}" style="border: none; max-width: 100%; max-height: 100%; object-fit: contain;" />
                    @else
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 16.2422C2.79401 15.435 2 14.0602 2 12.5C2 10.1564 3.79151 8.23129 6.07974 8.01937C6.54781 5.17213 9.02024 3 12 3C14.9798 3 17.4522 5.17213 17.9203 8.01937C20.2085 8.23129 22 10.1564 22 12.5C22 14.0602 21.206 15.435 20 16.2422M8 16L12 12M12 12L16 16M12 12V21"
                                stroke="#6c757d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>

                    @endif
                </label>
                
                <div style="display: flex; flex-direction: row; align-items: center; justify-content: space-between; margin-top: 8px; width: 100%;">
                    <input class="form-control" type="text" id="form_name" name="form_name" placeholder="Add Form Name" required
                        onfocusout="updateCompanyName(this)" value="{{ $head_office->name() }}" 
                        style="width: auto; flex-grow: 1; margin-right: 8px; padding: 4px 8px;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.5 21.4998L8.04927 19.3655C8.40421 19.229 8.58168 19.1607 8.74772 19.0716C8.8952 18.9924 9.0358 18.901 9.16804 18.7984C9.31692 18.6829 9.45137 18.5484 9.72028 18.2795L21 6.99982C22.1046 5.89525 22.1046 4.10438 21 2.99981C19.8955 1.89525 18.1046 1.89524 17 2.99981L5.72028 14.2795C5.45138 14.5484 5.31692 14.6829 5.20139 14.8318C5.09877 14.964 5.0074 15.1046 4.92823 15.2521C4.83911 15.4181 4.77085 15.5956 4.63433 15.9506L2.5 21.4998ZM2.5 21.4998L4.55812 16.1488C4.7054 15.7659 4.77903 15.5744 4.90534 15.4867C5.01572 15.4101 5.1523 15.3811 5.2843 15.4063C5.43533 15.4351 5.58038 15.5802 5.87048 15.8703L8.12957 18.1294C8.41967 18.4195 8.56472 18.5645 8.59356 18.7155C8.61877 18.8475 8.58979 18.9841 8.51314 19.0945C8.42545 19.2208 8.23399 19.2944 7.85107 19.4417L2.5 21.4998Z"
                            stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
        
        
        <div class="profile-center-area">

            <nav class='page-menu bordered'>
                <ul class="nav nav-tab main_header">

                    <li>
                        <a data-bs-toggle="tab" data-bs-target="#info" class="info active" href="javascript:void(0)"
                            id="infoClick" onclick="changeTabUrl('infoClick')" {{--
                        href="{{route('head_office.company_info')}}" --}}>
                            Details
                            <span></span>
                        </a>
                    </li>
                    <li><a id="depInfoClick" onclick="changeTabUrl('depInfoClick')" data-bs-toggle="tab"
                            data-bs-target="#department_info" class="department_info" href="javascript:void(0)">Departments
                            <span></span></a></li>
                    <li>
                        <a data-bs-toggle="tab" data-bs-target="#my_locations" class="my_locations" id="my_locationClick"
                            onclick="changeTabUrl('my_locationClick')" href="javascript:void(0)" {{-- href="{{route('head_office.my_organisation')}}" --}}>
                            Locations
                            <span></span>
                        </a>
                    </li>
                    <li id="os">
                        <a data-bs-toggle="tab" data-bs-target="#organization_structure" href="javascript:void(0)"
                            id="orgStructureClick" onclick="changeTabUrl('orgStructureClick')" {{--
                        href="{{route('head_office.my_organisation')}}" --}}>
                            Structure
                            <span></span>
                        </a>
                    </li>
                    <li><a id="veriDeviceClick" onclick="changeTabUrl('veriDeviceClick')" data-bs-toggle="tab"
                            data-bs-target="#verified_devices" class="verified_devices" href="javascript:void(0)">Verified
                            Devices <span></span></a></li>
                    <li><a id="themeClick" onclick="changeTabUrl('themeClick')" data-bs-toggle="tab"
                            data-bs-target="#themes" class="themes" href="javascript:void(0)">Themes <span></span></a></li>
                    {{-- <li id="os">
                        <a data-bs-toggle="tab" data-bs-target="#organization_setting" href="javascript:void(0)" id="orgSettingClick" onclick="changeTabUrl('orgSettingClick')"
                            >
                            Settings
                            <span></span>
                        </a>
                    </li> --}}
                </ul>
            </nav>
            <hr class="hrBeneathMenu">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane organization_setting" id="organization_setting">
                    <a href="{{ route('organisation_settings.organisation_setting.create') }}"
                        class="btn btn-info right float-right float-top"><i class="fa fa-plus"></i> Add New Setting</a>
                    @include('head_office.organisation_settings.index', [
                        'head_office_organisation_settings' => $head_office->organisationSettings,
                    ])
                </div>
                <div id="info" class="info relative tab-pane active show">
                    <div class="company-center-area">

                        @include('layouts.company.companyHeader', ['head_office' => $head_office])
                        <!-- company page contents -->
                        <div class="tab-content" id="myTabContent">
                            <div id="company_info" class="company_info relative tab-pane active show">
                                <div class="profile-page-contents hide-placeholder-parent mt-3">
                                    {{-- <div class="inputSection pt-2">Address</div> --}}
                                    <div class="inputSection">General</div>
                                    <label class="inputGroup d-flex">Company Name:
                                        <input type="text" onfocusout="updateCompanyName(this)"
                                            value="{{ $head_office->name() }}">
                                    </label>
                                    <label class="inputGroup">Address:
                                        <input type="text" placeholder="Add address" id="company_address" onfocusout="updateCompanyAddress(this)" value="{{ $head_office->address }}"/>
                                    </label>
                                    <label class="inputGroup">Phone:
                                        <input id="telephone" type="text" placeholder="Add a phone number" onfocusout="updateCompanyPhone(this)" />
                                    </label>
                                    {{-- <label >Contacts Merge Percentage:
                                        <input id="percent_merge" class="form-control form-control-sm shadow-none border" min="0" max="100" value="{{ $head_office->percentage_merge }}" type="number" placeholder="100%" onfocusout="updateMergePercentage(this)" />
                                    </label> --}}

                                    

                                    <div class="inputSection">System Access</div>
                                    @isset($url)
                                        <div class="d-flex align-items-center">
                                            <label class=" pr-5" style="padding-right:0.5rem;">Link:
                                            </label>
                                            <input data-company="{{ $head_office->link_token }}"
                                                class="form-control border custom-input" id="myInput"
                                                style="background: #E9ECEF;" type="text"
                                                value="{{ isset($head_office->link_token) ? $url : 'Customize theme for unique token' }}"
                                                style="font-size: 14px;" readonly>
                                            <div class="tooltip">
                                                <button class="clip-btn" onclick="myFunction()" onmouseout="outFunc()">
                                                    <span class="tooltiptext" id="myTooltip">Copy to clipboard</span>
                                                    <img src="{{ asset('images/copy-01.svg') }}" width="20"
                                                        alt="svg">
                                                </button>
                                            </div>
                                        </div>
                                    @endisset
                                    <div class="form-check mt-1">
                                        <input class="form-check-input" onchange="updateRestrictedCheck(this)"
                                            type="checkbox" {{ $head_office->restricted ? 'checked' : '' }}
                                            value="" id="restrictedCheck">
                                            <label class="form-check-label fw-semibold" for="restrictedCheck" style="font-size: 14px;color:#000;">
                                                Restrict company/site account access to this link 
                                                <span style="cursor: pointer;" title="Logging into the company or site account is disabled from the QI-Tech website ">ℹ️</span>
                                            </label>
                                    </div>
                                    <div class="inputSection">Super Admins</div>
                                    <div class="d-flex align-items-center">
                                        @foreach ($head_office->super_users() as $super_user)
                                        <div class="super-user new-card-wrap">
                                            <span>
                                                <img src="{{ $super_user->logo }}" style= "width: 60px; height: 60px; border-radius: 50%;" alt="png_img">
                                            </span>
                                            @include('head_office.user_card_component',['user'=>$super_user])

                                        </div>
                                    @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="tab-pane verified_devices" id="verified_devices">
                    <table class="table mx-auto new-table" id="session-dataTable" style="width:70%; text-align: center;">
                        <thead>
                            <tr>
                                <th style="text-align: center;">
                                    <input type="checkbox" name="select_all" value="1" id="dataTable-select-all">
                                </th>
                                <th style="text-align: center;">Verified by</th>
                                <th style="text-align: center;">Device</th>
                                <th style="text-align: center;">Location</th>
                                <th style="text-align: center;">IP</th>
                                <th style="text-align: center;"></th>
                            </tr>
                        </thead>
                        <tbody class="verified_devices_body">
                            @include('head_office.verified_devices')
                            <tr class="line-reloading" style="display:none">
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;">
                                    <div class="line line-date print-display-none">
                                        <div class="timeline-label">
                                            <i class="spinning_icon fa-spin fa fa-spinner"></i>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="d-none" hidden>
                        <form id="delete-form" hidden
                            action="{{ route('head_office.end_head_office_user_session_all') }}" method="POST">
                            @csrf
                            <input type="text" name="sessionIds[]" value="" id="sessionid-input">
                        </form>

                    </div>
                    <div id="draggable2" class="bottom-nav position-fixed " style="z-index: 9999;"
                        aria-describedby="drag">
                        <div class="left-side">
                            <div class="info-wrapper">
                                <div class="selected-show">
                                    <h5 id="count">0</h5>
                                </div>
                                <div class="info-heading" style="max-width: 180px;overflow:hidden;">
                                    <p>Items Selected</p>
                                    <div class="dots-wrapper">
                                        <span class="dot"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="btn-wrapper">
                                <button id='delete-btn' class="bar-btn" title="Remove selected records">
                                    <img src="{{ asset('images/trash-01.svg') }}" alt="icon">
                                    <p>Remove</p>
                                </button>

                            </div>
                        </div>
                        <button class="drag-btn">
                            <img src="{{ asset('images/dots-horizontal.svg') }}" alt="svg">
                            <img style="margin-top:-15px;" src="{{ asset('images/dots-horizontal.svg') }}"
                                alt="svg">
                        </button>
                    </div>
                </div>
                <div class="tab-pane themes" id="themes">


                    <style>
                        .fade:not(.show) {
                            display: none
                        }

                        .case-nav .nav-item:nth-child(2) {
                            border-bottom: none;
                        }
                    </style>
                    <div id="content" class="d-flex custom-scroll justify-content-between p-0 m-0"
                        style="max-height: 82vh !important;min-height:82vh !important;">
                        <div style="height:100%;">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center">
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('head_office.be_spoke_form.index') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('head_office.be_spoke_form.index') }}">Company</a></li>
                                </ol>
                            </nav>
                            <div style="min-width: 250px;border-right: 2px solid #dddcdf;background:white;z-index:1;position:relative;height:100%;padding-right:2rem !important;"
                                class="px-3 me-2">
                                <ul class="case-nav nav nav-tabs d-flex flex-column " id="myTab" role="tablist">
                                    <li class="nav-item " role="presentation">
                                        <button
                                            class="nav-link active d-flex align-items-center justify-content-between w-100"
                                            id="home-tab" data-bs-toggle="tab" data-bs-target="#company-theme-tab"
                                            type="button" role="tab" aria-controls="home-tab-pane"
                                            aria-selected="true">Company Theme<i class="fa-solid fa-chevron-right"
                                                style="font-size: 13px;"></i></button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link d-flex align-items-center justify-content-between w-100"
                                            id="profile-tab" data-bs-toggle="tab" data-bs-target="#signin-page-tab"
                                            type="button" role="tab" aria-controls="profile-tab-pane"
                                            aria-selected="false">Sign In Page<i class="fa-solid fa-chevron-right"
                                                style="font-size: 13px;"></i></button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link d-flex align-items-center justify-content-between w-100"
                                            id="profile-tab" data-bs-toggle="tab" data-bs-target="#location-themes-tab"
                                            type="button" role="tab" aria-controls="profile-tab-pane"
                                            aria-selected="false">Location Themes<i class="fa-solid fa-chevron-right"
                                                style="font-size: 13px;"></i></button>
                                    </li>
                                </ul>
                            </div>
                        </div>









                        <div style="justify-self:flex-start; width:100%; overflow-Y:auto;"
                            class="tab-content custom-scroll" id="nav-tabContent">

                            <form method="post" action="{{ route('head_office.company_info.apply_theme') }}"
                                enctype="multipart/form-data">
                                <div id="company-theme-tab" role="tabpanel" aria-labelledby="company-theme-tab"
                                    tabindex="0" class="mt-2 tab-pane fade show active"
                                    style="padding:0 100px 0 200px">
                                    @csrf
                                    <div class="mt-3 border rounded-3 p-3 position-relative"
                                        style="transform:translateY(8rem);width:650px; margin:auto;align-items:center;flex-direction:column;display:flex; justify-content:center; background:#f8f8f8;">
                                        <p class="rounded-3 p-2"
                                            style="background: {{ isset($head_office->primary_color) ? $head_office->primary_color : '#49b3d3' }}; align-self: flex-end;width:80%;color:{{ isset($head_office->portal_text_color) ? $head_office->portal_text_color : '#fff' }};"
                                            id="portalName">{{ $head_office->portal_text }}</p>

                                        <p class="p-0 m-2 fw-bold" style="color: {{ $head_office->icon_color }};"
                                            id="tabText">Tab</p>
                                        <div class="d-flex w-100 justify-content-center"
                                            style="height:2px;background:gray;">
                                            <div style="width: 5%; height:2px; border-radius:2px; background: {{ $head_office->icon_color }};"
                                                id="tabLine"></div>
                                        </div>
                                        <p class="p-0 m-4 fw-bold"
                                            style="color: {{ isset($head_office->portal_section_heading_color) ? $head_office->portal_section_heading_color : '#49b3d3' }};align-self: flex-start;"
                                            id="headingText">Heading</p>
                                        <div class="d-flex align-items-center mx-4 gap-1 w-100"
                                            style="align-self: flex-start;">
                                            <div style="width: 50px; height:50px; border-radius:50%; background:#e8edf1">
                                            </div>
                                            <div class="w-100 d-flex flex-column gap-1">
                                                <div
                                                    style="width: 20%; height:10px; border-radius:5px; background:#e8edf1;">
                                                </div>
                                                <div
                                                    style="width: 40%; height:10px; border-radius:5px; background:#e8edf1">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="w-100 d-flex flex-column m-4 gap-1 w-100"
                                            style="align-self: flex-start;">
                                            <div style="width: 70%; height:10px; border-radius:5px; background:#e8edf1;">
                                            </div>
                                            <div style="width: 60%; height:10px; border-radius:5px; background:#e8edf1">
                                            </div>
                                        </div>
                                        <button
                                            style="background: {{ isset($head_office->portal_primary_btn_color) ? $head_office->portal_primary_btn_color : '#49b3d3' }}; color: {{ isset($head_office->portal_primary_btn_text_color) ? $head_office->portal_primary_btn_text_color : '#fff' }}"
                                            class="primary-btn2" type="button"
                                            id="primaryButtonInputBtn">Button</button>
                                        <div class="position-absolut profile-pic"
                                            style="top:-6rem;left:-10rem;flex-direction:column">
                                            <p class="text-secondary m-0 p-0 fw-bold d-flex align-items-center gap-1 mb-1"
                                                style="font-size: 14px;align-self:flex-start;">
                                                Company Logo
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12 16V12M12 8H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z"
                                                        stroke="#6c757d" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                
                                            </p>
                                            <label for="file"
                                                class="rounded-3 d-flex align-items-center justify-content-center flex-column"
                                                style="border:1px dashed gray;width:250px;height:150px;"
                                                id="headOfficeLogoInput">
                                                @if (isset($head_office->logo))
                                                    <img src="{{ $head_office->logo }}"
                                                        class="img-profile rounded-circle" id="output" />
                                                @else
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M4 16.2422C2.79401 15.435 2 14.0602 2 12.5C2 10.1564 3.79151 8.23129 6.07974 8.01937C6.54781 5.17213 9.02024 3 12 3C14.9798 3 17.4522 5.17213 17.9203 8.01937C20.2085 8.23129 22 10.1564 22 12.5C22 14.0602 21.206 15.435 20 16.2422M8 16L12 12M12 12L16 16M12 12V21"
                                                            stroke="#6c757d" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                    <p class="text-secondary p-0 m-2">Drag&Drop files here</p>
                                                    <p class="text-secondary p-0 mb-2" style="font-size:12px;">or</p>
                                                    <p class="outline-btn">Browse</p>
                                                @endif
                                            </label>
                                            <input id="file" type="file" class="d-none"
                                                onchange="loadFile(event)" name="company_profile" accept=".png , .jpg , .jpeg" />
                                        </div>






                                        <div class="d-flex align-items-start justify-content-between flex-column position-absolute"
                                            style="top: -4.2rem; right:9.5rem;z-index:10">
                                            <label for="portalTextColorInput"
                                                class="form-label text-secondary m-1 p-0 fw-bold"
                                                style="font-size:14px;">Portal Background</label>
                                            <div class="d-flex align-items-center justify-content-center bg-white"
                                                style="border:1px solid gray; border-radius:8px;height:44px;">
                                                <input style="width:12rem;max-width:6rem;border:0;height:100%;" type="color"
                                                    class="form-control shadow-none form-control-color"
                                                    id="portalBackgroundColorInput" name="primary_color"
                                                    value="{{ $head_office->primary_color }}"
                                                    title="Choose Primary color">
                                                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="24pt"
                                                    height="24pt" viewBox="0 0 37.000000 39.000000"
                                                    preserveAspectRatio="xMidYMid meet">

                                                    <g transform="translate(0.000000,39.000000) scale(0.100000,-0.100000)"
                                                        id="portalBackgroundColorInputSvg"
                                                        fill="{{ $head_office->primary_color }}" stroke="none">
                                                        <path d="M149 273 c-102 -123 -90 -172 41 -170 69 2 75 4 89 29 12 21 13 31 3
                                                        50 -25 47 -55 93 -66 100 -20 13 -52 9 -67 -9z m70 -21 c8 -15 4 -23 -19 -40
                                                        -36 -27 -37 -27 -50 -2 -15 28 4 60 35 60 13 0 29 -8 34 -18z m35 -58 c23 -9
                                                        21 -61 -3 -74 -26 -14 -50 -3 -57 26 -10 40 20 63 60 48z m-100 -20 c32 -31 9
                                                        -69 -31 -53 -15 5 -23 17 -23 32 0 40 26 50 54 21z" />
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-start justify-content-between flex-column position-absolute"
                                            style="top: -4.2rem; right:-1.5rem;z-index:10">
                                            <label for="portalTextColorInput"
                                                class="form-label text-secondary m-1 p-0 fw-bold"
                                                style="font-size:14px;">Portal Text Color</label>
                                            <div class="d-flex align-items-center justify-content-center bg-white"
                                                style="border:1px solid gray; border-radius:8px;height:44px;">
                                                <input style="width:12rem;max-width:6rem;border:0;" type="color"
                                                    class="form-control shadow-none form-control-color"
                                                    id="portalTextColorInput" name="portal_text_color"
                                                    value="{{ $head_office->portal_text_color }}"
                                                    title="Choose Primary color">
                                                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="24pt"
                                                    height="24pt" viewBox="0 0 37.000000 39.000000"
                                                    preserveAspectRatio="xMidYMid meet">

                                                    <g transform="translate(0.000000,39.000000) scale(0.100000,-0.100000)"
                                                        id="portalTextColorInputSvg"
                                                        fill="{{ $head_office->portal_text_color }}" stroke="none">
                                                        <path d="M149 273 c-102 -123 -90 -172 41 -170 69 2 75 4 89 29 12 21 13 31 3
                                                        50 -25 47 -55 93 -66 100 -20 13 -52 9 -67 -9z m70 -21 c8 -15 4 -23 -19 -40
                                                        -36 -27 -37 -27 -50 -2 -15 28 4 60 35 60 13 0 29 -8 34 -18z m35 -58 c23 -9
                                                        21 -61 -3 -74 -26 -14 -50 -3 -57 26 -10 40 20 63 60 48z m-100 -20 c32 -31 9
                                                        -69 -31 -53 -15 5 -23 17 -23 32 0 40 26 50 54 21z" />
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>

                                        <div class="position-absolute" style="top: -4.2rem; right:20.5rem;z-index:10">
                                            <label for="portalTitleInput"
                                                class="form-label text-secondary m-1 p-0 fw-bold"
                                                style="font-size:14px;">Portal Name</label>
                                            <input type="text" class="form-control shadow-none" id="portalTitleInput"
                                                name="portal_text"
                                                value="{{ empty($head_office->portal_text) ? 'Please add title' : $head_office->portal_text }}"
                                                placeholder="Please add title" style="height: 44px;">
                                        </div>


                                        <div class="d-flex align-items-start justify-content-between flex-column position-absolute"
                                            style="top: 7rem; right:12rem;z-index:10">
                                            <label for="tabColorInput" class="form-label text-secondary m-1 p-0 fw-bold "
                                                style="font-size:14px;">Tabs</label>
                                            <div class="d-flex align-items-center justify-content-center bg-white"
                                                style="border:1px solid gray; border-radius:8px;height:44px;">
                                                <input name="icon_color" style="width:12rem;max-width:6rem;border:0;"
                                                    type="color" class="form-control shadow-none form-control-color"
                                                    id="tabColorInput" name="icon_color"
                                                    value="{{ $head_office->icon_color }}" title="Choose Tab color">
                                                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="24pt"
                                                    height="24pt" viewBox="0 0 37.000000 39.000000"
                                                    preserveAspectRatio="xMidYMid meet">

                                                    <g transform="translate(0.000000,39.000000) scale(0.100000,-0.100000)"
                                                        id="tabColorInputSvg" fill="{{ $head_office->icon_color }}"
                                                        stroke="none">
                                                        <path d="M149 273 c-102 -123 -90 -172 41 -170 69 2 75 4 89 29 12 21 13 31 3
                                                        50 -25 47 -55 93 -66 100 -20 13 -52 9 -67 -9z m70 -21 c8 -15 4 -23 -19 -40
                                                        -36 -27 -37 -27 -50 -2 -15 28 4 60 35 60 13 0 29 -8 34 -18z m35 -58 c23 -9
                                                        21 -61 -3 -74 -26 -14 -50 -3 -57 26 -10 40 20 63 60 48z m-100 -20 c32 -31 9
                                                        -69 -31 -53 -15 5 -23 17 -23 32 0 40 26 50 54 21z" />
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>




                                        <div class="d-flex align-items-start justify-content-between flex-column position-absolute"
                                            style="top: 8rem; left:-7rem;z-index:10">
                                            <label for="sectionHeadingInput"
                                                class="form-label text-secondary m-1 p-0 fw-bold "
                                                style="font-size:14px;">Section Heading</label>
                                            <div class="d-flex align-items-center justify-content-center bg-white"
                                                style="border:1px solid gray; border-radius:8px;height:44px;">
                                                <input style="width:12rem;max-width:6rem;border:0;" type="color"
                                                    class="form-control shadow-none form-control-color"
                                                    id="sectionHeadingInput" name="portal_section_heading_color"
                                                    value="{{ $head_office->portal_section_heading_color }}"
                                                    title="Choose Primary color">
                                                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="24pt"
                                                    height="24pt" viewBox="0 0 37.000000 39.000000"
                                                    preserveAspectRatio="xMidYMid meet">

                                                    <g transform="translate(0.000000,39.000000) scale(0.100000,-0.100000)"
                                                        id="sectionHeadingInputSvg"
                                                        fill="{{ $head_office->portal_section_heading_color }}"
                                                        stroke="none">
                                                        <path d="M149 273 c-102 -123 -90 -172 41 -170 69 2 75 4 89 29 12 21 13 31 3
                                                        50 -25 47 -55 93 -66 100 -20 13 -52 9 -67 -9z m70 -21 c8 -15 4 -23 -19 -40
                                                        -36 -27 -37 -27 -50 -2 -15 28 4 60 35 60 13 0 29 -8 34 -18z m35 -58 c23 -9
                                                        21 -61 -3 -74 -26 -14 -50 -3 -57 26 -10 40 20 63 60 48z m-100 -20 c32 -31 9
                                                        -69 -31 -53 -15 5 -23 17 -23 32 0 40 26 50 54 21z" />
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-start justify-content-between flex-column position-absolute"
                                            style="bottom: -2rem; right:-2rem;z-index:10">
                                            <label for="buttonTextInput"
                                                class="form-label text-secondary m-1 p-0 fw-bold "
                                                style="font-size:14px;">Button Text</label>
                                            <div class="d-flex align-items-center justify-content-center bg-white"
                                                style="border:1px solid gray; border-radius:8px;height:44px;">
                                                <input style="width:12rem;max-width:6rem;border:0;" type="color"
                                                    class="form-control shadow-none form-control-color"
                                                    id="buttonTextInput" name="portal_primary_btn_text_color"
                                                    value="{{ $head_office->portal_primary_btn_text_color }}"
                                                    title="Choose Primary color">
                                                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="24pt"
                                                    height="24pt" viewBox="0 0 37.000000 39.000000"
                                                    preserveAspectRatio="xMidYMid meet">

                                                    <g transform="translate(0.000000,39.000000) scale(0.100000,-0.100000)"
                                                        id="buttonTextInputSvg"
                                                        fill="{{ $head_office->portal_primary_btn_text_color }}"
                                                        stroke="none">
                                                        <path d="M149 273 c-102 -123 -90 -172 41 -170 69 2 75 4 89 29 12 21 13 31 3
                                                        50 -25 47 -55 93 -66 100 -20 13 -52 9 -67 -9z m70 -21 c8 -15 4 -23 -19 -40
                                                        -36 -27 -37 -27 -50 -2 -15 28 4 60 35 60 13 0 29 -8 34 -18z m35 -58 c23 -9
                                                        21 -61 -3 -74 -26 -14 -50 -3 -57 26 -10 40 20 63 60 48z m-100 -20 c32 -31 9
                                                        -69 -31 -53 -15 5 -23 17 -23 32 0 40 26 50 54 21z" />
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>


                                        <div class="d-flex align-items-start justify-content-between flex-column position-absolute"
                                            style="bottom: -2rem; right:7rem;z-index:10">
                                            <label for="primaryButtonInput"
                                                class="form-label text-secondary m-1 p-0 fw-bold "
                                                style="font-size:14px;">Primary Button</label>
                                            <div class="d-flex align-items-center justify-content-center bg-white"
                                                style="border:1px solid gray; border-radius:8px;height:44px;">
                                                <input style="width:12rem;max-width:6rem;border:0;" type="color"
                                                    class="form-control shadow-none form-control-color"
                                                    id="primaryButtonInput" name="portal_primary_btn_color"
                                                    value="{{ $head_office->portal_primary_btn_color }}"
                                                    title="Choose Primary color">
                                                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="24pt"
                                                    height="24pt" viewBox="0 0 37.000000 39.000000"
                                                    preserveAspectRatio="xMidYMid meet">

                                                    <g transform="translate(0.000000,39.000000) scale(0.100000,-0.100000)"
                                                        id="primaryButtonInputSvg"
                                                        fill="{{ $head_office->portal_primary_btn_color }}"
                                                        stroke="none">
                                                        <path d="M149 273 c-102 -123 -90 -172 41 -170 69 2 75 4 89 29 12 21 13 31 3
                                                        50 -25 47 -55 93 -66 100 -20 13 -52 9 -67 -9z m70 -21 c8 -15 4 -23 -19 -40
                                                        -36 -27 -37 -27 -50 -2 -15 28 4 60 35 60 13 0 29 -8 34 -18z m35 -58 c23 -9
                                                        21 -61 -3 -74 -26 -14 -50 -3 -57 26 -10 40 20 63 60 48z m-100 -20 c32 -31 9
                                                        -69 -31 -53 -15 5 -23 17 -23 32 0 40 26 50 54 21z" />
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>


                                    </div>

                                    <button type="submit" class="primary-btn"
                                        style="transform:translateY(12rem); margin:auto;align-items:center;flex-direction:column;display:flex; justify-content:center;">Apply
                                        Changes!</button>
                                </div>
                                <div class="mt-2 tab-pane fade" id="signin-page-tab" role="tabpanel"
                                    aria-labelledby="nav-profile-tab" tabindex="0">
                                    @csrf
                                    <div class="mt-3 border rounded-3 position-relative gap-4"
                                        style="transform:translateY(8rem);width:700px; margin:auto;align-items:center ;display:flex; background:#f8f8f8; padding:4rem;">
                                        <div class="mx-4 "
                                            style="border:1px dashed gray; height:270px; border-radius:10px; overflow:hidden; width:250px;">
                                            @isset($head_office->background_image)
                                                <div class="profile-pic" style="position: relative;width:100%;height:100%;">
                                                    <img id="bgImgId"
                                                        src="{{ asset('storage/images/' . $head_office->background_image) }}"
                                                        alt="image" class="img-profile"
                                                        style="object-fit: cover;width: 250px;height: 270px;border-radius:0;">
                                                </div>
                                            @endisset
                                            @if(!isset($head_office->background_image))
                                                <img src="" id="bgImgId" alt=""  style="object-fit: cover;width: 250px;height: 270px;border-radius:0;">
                                            @endif
                                        </div>
                                        <div style="width:50%">
                                            <p class="text-secondary fw-bold" id="signInMessageText">
                                                {{ empty($head_office->title_text) ? 'Please add title' : $head_office->title_text }}
                                            </p>
                                            <div class="w-100 bg-white p-4 rounded-3">
                                                <div
                                                    style="width: 70%; height:10px; border-radius:2px; background:#e8edf1;">
                                                </div>
                                            </div>
                                            <div class="w-100 bg-white p-4 rounded-3 my-4">
                                                <div
                                                    style="width: 70%; height:10px; border-radius:2px; background:#e8edf1;">
                                                </div>
                                            </div>
                                            <button class="primary-btn2" id="signButtonColorInputBtn"
                                                style="background:{{ $head_office->sign_button_color }};color:{{ $head_office->sign_btn_text_color }}"
                                                type="button">Sign
                                                in</button>
                                        </div>
                                        <div class="position-absolut profile-pic"
                                            style="top:-3rem;left:-3rem;flex-direction:column">
                                            <p class="text-secondary m-0 p-0 fw-bold d-flex align-items-center gap-1 mb-1"
                                                style="font-size: 14px;align-self:flex-start;">Hero Image <svg
                                                    width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12 16V12M12 8H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z"
                                                        stroke="#6c757d" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </p>
                                            <label for="backgroundInput"
                                                class=" rounded-3 d-flex align-items-center justify-content-center flex-column"
                                                style="border:1px dashed gray;width:250px;height:150px;">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M4 16.2422C2.79401 15.435 2 14.0602 2 12.5C2 10.1564 3.79151 8.23129 6.07974 8.01937C6.54781 5.17213 9.02024 3 12 3C14.9798 3 17.4522 5.17213 17.9203 8.01937C20.2085 8.23129 22 10.1564 22 12.5C22 14.0602 21.206 15.435 20 16.2422M8 16L12 12M12 12L16 16M12 12V21"
                                                        stroke="#6c757d" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <p class="text-secondary p-0 m-2">Drag&Drop files here</p>
                                                <p class="text-secondary p-0 mb-2" style="font-size:12px;">or</p>
                                                <p class="outline-btn">Browse</p>
                                            </label>

                                            <input onchange="previewImage('backgroundInput','bgImgId')"
                                                class="form-control d-none" type="file" id="backgroundInput"
                                                name="background_image" accept=".png , .jpg , .jpeg">
                                        </div>






                                        <div class="position-absolute"
                                            style="top: -2.5rem; right:-1.5rem;z-index:10;width:300px;">
                                            <label for="signInMessage" class="form-label text-secondary m-1 p-0 fw-bold"
                                                style="font-size:14px;">Sign in Message</label>
                                            <input type="text" class="form-control shadow-none" id="signInMessage"
                                                name="title_text"
                                                value="{{ empty($head_office->title_text) ? 'Please add title' : $head_office->title_text }}"
                                                placeholder="Please add title" style="height: 44px;">
                                        </div>








                                        <div class="d-flex align-items-start justify-content-between flex-column position-absolute"
                                            style="bottom: -2rem; right:2rem;z-index:10">
                                            <label for="signButtonColorInput"
                                                class="form-label text-secondary m-1 p-0 fw-bold "
                                                style="font-size:14px;">Sign in Button</label>
                                            <div class="d-flex align-items-center justify-content-center bg-white"
                                                style="border:1px solid gray; border-radius:8px;height:44px;">
                                                <input style="width:12rem;max-width:6rem;border:0;" type="color"
                                                    class="form-control shadow-none form-control-color"
                                                    id="signButtonColorInput" name="sign_button_color"
                                                    value="{{ $head_office->sign_button_color }}"
                                                    title="Choose Sign in Button color">
                                                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="24pt"
                                                    height="24pt" viewBox="0 0 37.000000 39.000000"
                                                    preserveAspectRatio="xMidYMid meet">

                                                    <g transform="translate(0.000000,39.000000) scale(0.100000,-0.100000)"
                                                        id="signButtonColorInputSvg"
                                                        fill="{{ $head_office->sign_button_color }}" stroke="none">
                                                        <path d="M149 273 c-102 -123 -90 -172 41 -170 69 2 75 4 89 29 12 21 13 31 3
                                                        50 -25 47 -55 93 -66 100 -20 13 -52 9 -67 -9z m70 -21 c8 -15 4 -23 -19 -40
                                                        -36 -27 -37 -27 -50 -2 -15 28 4 60 35 60 13 0 29 -8 34 -18z m35 -58 c23 -9
                                                        21 -61 -3 -74 -26 -14 -50 -3 -57 26 -10 40 20 63 60 48z m-100 -20 c32 -31 9
                                                        -69 -31 -53 -15 5 -23 17 -23 32 0 40 26 50 54 21z" />
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>


                                        <div class="d-flex align-items-start justify-content-between flex-column position-absolute"
                                            style="bottom: -2rem; right:11rem;z-index:10">
                                            <label for="signInButtonTextInput"
                                                class="form-label text-secondary m-1 p-0 fw-bold "
                                                style="font-size:14px;">Sign in Button Text</label>
                                            <div class="d-flex align-items-center justify-content-center bg-white"
                                                style="border:1px solid gray; border-radius:8px;height:44px;">
                                                <input style="width:12rem;max-width:6rem;border:0;" type="color"
                                                    class="form-control shadow-none form-control-color"
                                                    id="signInButtonTextInput" name="sign_btn_text_color"
                                                    value="{{ $head_office->sign_btn_text_color }}"
                                                    title="Choose Primary color">
                                                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="24pt"
                                                    height="24pt" viewBox="0 0 37.000000 39.000000"
                                                    preserveAspectRatio="xMidYMid meet">

                                                    <g transform="translate(0.000000,39.000000) scale(0.100000,-0.100000)"
                                                        id="signInButtonTextInputSvg"
                                                        fill="{{ $head_office->sign_btn_text_color }}" stroke="none">
                                                        <path d="M149 273 c-102 -123 -90 -172 41 -170 69 2 75 4 89 29 12 21 13 31 3
                                                        50 -25 47 -55 93 -66 100 -20 13 -52 9 -67 -9z m70 -21 c8 -15 4 -23 -19 -40
                                                        -36 -27 -37 -27 -50 -2 -15 28 4 60 35 60 13 0 29 -8 34 -18z m35 -58 c23 -9
                                                        21 -61 -3 -74 -26 -14 -50 -3 -57 26 -10 40 20 63 60 48z m-100 -20 c32 -31 9
                                                        -69 -31 -53 -15 5 -23 17 -23 32 0 40 26 50 54 21z" />
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="primary-btn"
                                        style="transform:translateY(12rem); margin:auto;align-items:center;flex-direction:column;display:flex; justify-content:center;">Apply
                                        Changes!</button>

                                </div>

                            </form>


                            {{-- Sign in page design --}}
                            {{-- @dd($head_office->getBrandingAttribute()) --}}



                            <div class="tab-pane fade" id="location-themes-tab" role="tabpanel"
                                aria-labelledby="nav-contact-tab" tabindex="0"
                                style="transform: translateY(5rem); width:900px; justify-self:center; margin-left:8rem;">
                                <div class="inputSection d-flex justify-content-between align-items-center"
                                    style="font-size: 14px;">Location Themes <button data-bs-toggle="modal"
                                        data-bs-target="#location-theme" type="button" class="primary-btn">Add
                                        New</button></div>
                                @if (isset($org_settings) && count($org_settings) != 0)
                                    <table class="table table-bordered new-table" id="setting-table">
                                        <thead>
                                            <tr>
                                                <th>Theme Name</th>
                                                <th>Assigned To</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($org_settings as $setting)
                                                <tr>
                                                    <td>{{ $setting->name }}</td>
                                                    <td>{{ count($setting->organisationSettingAssignments) }}</td>
                                                    <td>
                                                        <div
                                                            class="mx-auto d-flex justify-content-evenly align-items-center">
                                                            <button data-id="{{ $setting->id }}"
                                                                data-name="{{ $setting->name }}"
                                                                data-color="{{ $setting->bg_color_code }}"
                                                                data-logo="{{ $setting->setting_logo() }}"
                                                                data-bg_logo="{{ $setting->setting_bg_logo() }}"
                                                                data-location_section_heading_color="{{ $setting->location_section_heading_color }}"
                                                                data-location_form_setting_color="{{ $setting->location_form_setting_color }}"
                                                                data-location_button_color="{{ $setting->location_button_color }}"
                                                                data-location_button_text_color="{{ $setting->location_button_text_color }}"
                                                                data-bs-toggle="modal" data-bs-target="#location-theme"
                                                                type="button"
                                                                class="org_edit_btn btn border-0 p-0 m-0 edit-theme"><i
                                                                    class="fa fa-edit"></i></button>
                                                            <a @if (count($setting->organisationSettingAssignments) != 0) data-msg='This location is applied to {{ count($setting->organisationSettingAssignments) }} locations. Are you sure you want to delete it?' @else data-msg='Are you sure you want to delete it?' @endif
                                                                href="{{ route('organisation_settings.organisation_setting.delete', ['id' => $setting->id,'_token'=>csrf_token()]) }}"
                                                                class="btn border-0 p-0 m-0 delete-theme"><i
                                                                    class="fa fa-trash"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                    </table>
                                @else
                                    <p class="m-0 fw-bold">You have no themes saved</p>
                                @endif
                            </div>


                        </div>











                    </div>






                </div>
                <div class="tab-pane department_info" id="department_info">
                    <div class="profile-page-contents hide-placeholder-parent">
                        <div class="inputSection">Finance dept</div>
                        <label class="inputGroup">Email:
                            <input type="telephone" placeholder="Add a pronoun" onfocusout="updateFinanceEmail(this)"
                                value="{{ $head_office->finance_email }}" />
                        </label>
                        <label class="inputGroup">Phone:
                            <input type="text" placeholder="Add a position" onfocusout="updateFinancePhone(this)"
                                value="{{ $head_office->finance_phone }}" />
                        </label>
                        <div class="inputSection">Technical support dept</div>
                        <label class="inputGroup">Email:
                            <input type="email" placeholder="Add a pronoun" onfocusout="updateTechnicalEmail(this)"
                                value="{{ $head_office->technical_email }}" />
                        </label>
                        <label class="inputGroup">Phone:
                            <input type="telephone" placeholder="Add a position" onfocusout="updateTechnicalPhone(this)"
                                value="{{ $head_office->technical_phone }}" />
                        </label>
                        <div class="inputSection">Display help desk</div>
                        <div>
                            <select name="" id="help-desk-select" class="form-select custom-input" onchange="toggleDivVisibility()">
                                <option value="1">QI-Tech help desk details</option>
                                <option value="2" 
                                    {{ $head_office->is_viewable_to_user == 'on' || $head_office->is_viewable_to_user == 1 ? 'selected' : '' }}>
                                    Own help desk details</option>
                            </select>
                        </div> 
                        <div class='d-none'>
                            <input @checked($head_office->is_viewable_to_user) style="width:auto" onchange="updateIsViewableToUser(this)"
                                type="checkbox" id="chkEmp" />
                            <label for="chkEmp">Display own message if user requires support</label>
                        </div>
                        <button class="primary-btn btn-sm mt-1" style="margin-left: 80%;" id="need-btn">Preview</button>
                        <div id="msg" hidden>
                            @if ($head_office->is_viewable_to_user == 0)
                                <span id="help-desk-text">QI tech help desk details</span>
                            @else($head_office->is_viewable_to_user == 'on')
                                <p id="share-msg" style="text-align: left;font-weight: 500;">
                                    {{ $head_office->help_description }}</p>
                                <span id="phone-details-email"
                                    style="display:{{ $head_office->is_email_viewable == 1 ? 'flex' : 'none' }};align-items:center;gap:0.5rem;">
                                    <p class="mb-0" style="color: rgb(30, 30, 30)"><i class="fa-solid fa-envelope"></i>
                                    </p>
                                    {{ $head_office->technical_email }}
                                </span>
                                <span id="phone-details-phone"
                                    style="display:{{ $head_office->is_phone_viewable == 1 ? 'flex' : 'none' }};align-items:center;gap:0.5rem;">
                                    <p class="mb-0" style="color: rgb(30, 30, 30)"><i class="fa-solid fa-phone"></i>
                                    </p> {{ $head_office->technical_phone }}
                                </span>
                                <p style="font-weight: 600;text-align:left;display:{{ $head_office->is_viewable_hours == 1 ? 'flex' : 'none' }};"
                                    class="mt-4 mb-0">Hours Available</p>
                                <table class="table"
                                    style="display:{{ $head_office->is_viewable_hours == 1 ? 'block' : 'none' }};">
                                    <thead>
                                        <tr class="text-left" style="text-align: left;">
                                            {{-- <th style="display: none;"></th> --}}
                                            <th>Day</th>
                                            <th>From</th>
                                            <th>To</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $days = [
                                                'monday',
                                                'tuesday',
                                                'wednesday',
                                                'thursday',
                                                'friday',
                                                'saturday',
                                                'sunday',
                                            ];
                                        @endphp

                                        @csrf

                                        @foreach ($days as $day)
                                            @php
                                                $var = 'is_open_' . $day;
                                                $start = $day . '_start_time';
                                                $end = $day . '_end_time';
                                            @endphp
                                            <tr class=" text-left" style="text-align: left;">

                                                <td style="font-weight: 500;">{{ ucfirst($day) }}</td>
                                                <td>{{ strtolower($head_office_timing->convert_time($head_office_timing->$start)) }}
                                                </td>
                                                <td>{{ strtolower($head_office_timing->convert_time($head_office_timing->$end)) }}
                                                </td>
                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            @endif
                        </div>
                        <div id="sub-ops" style="display: {{ $head_office->is_viewable_to_user ? '' : 'none' }}">
                            <div>
                                <input @checked($head_office->is_phone_viewable) style="width:auto"
                                    onchange="updateIsPhoneViewable(this)" type="checkbox" id="chkPhone" />
                                <label for="chkPhone" style="color: #999;"> Show Phone number</label>
                            </div>
                            <div>
                                <input @checked($head_office->is_email_viewable) style="width:auto"
                                    onchange="updateIsEmailViewable(this)" type="checkbox" id="chkEmail" />
                                <label for="chkEmail" style="color: #999;"> Show Email address</label>
                            </div>
                            <div>
                                <input @checked($head_office->is_viewable_hours) style="width:auto"
                                    onchange="updateIsHoursViewable(this)" type="checkbox" id="chkHours" />
                                <label for="chkHours" style="color: #999;"> Show Hours Available</label>
                            </div>
                            <div>
                                <input @checked($head_office->is_help_viewable) style="width:auto" 
                                onchange="toggleHelpTextarea(this)" type="checkbox" id="chkHelp" />
                                <label for="chkHelp" style="color: #999;"> Display Message</label>
                                <textarea spellcheck="true" onfocusout="updateHelpDescription(this)" class="form-control" id="help_input" rows="2" style="resize:none; margin-top:4px;"{{ $head_office->is_help_viewable ? '' : 'readonly' }}>{{ isset($head_office->help_description) ? $head_office->help_description : '' }}
                                </textarea>
                            </div>    
                            <div class="inputSection">Hours Available
                                <div class="content_right">
                                    <span id="editButton" onclick="enableEditing()">
                                        <svg width="15" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M11 3.99998H6.8C5.11984 3.99998 4.27976 3.99998 3.63803 4.32696C3.07354 4.61458 2.6146 5.07353 2.32698 5.63801C2 6.27975 2 7.11983 2 8.79998V17.2C2 18.8801 2 19.7202 2.32698 20.362C2.6146 20.9264 3.07354 21.3854 3.63803 21.673C4.27976 22 5.11984 22 6.8 22H15.2C16.8802 22 17.7202 22 18.362 21.673C18.9265 21.3854 19.3854 20.9264 19.673 20.362C20 19.7202 20 18.8801 20 17.2V13M7.99997 16H9.67452C10.1637 16 10.4083 16 10.6385 15.9447C10.8425 15.8957 11.0376 15.8149 11.2166 15.7053C11.4184 15.5816 11.5914 15.4086 11.9373 15.0627L21.5 5.49998C22.3284 4.67156 22.3284 3.32841 21.5 2.49998C20.6716 1.67156 19.3284 1.67155 18.5 2.49998L8.93723 12.0627C8.59133 12.4086 8.41838 12.5816 8.29469 12.7834C8.18504 12.9624 8.10423 13.1574 8.05523 13.3615C7.99997 13.5917 7.99997 13.8363 7.99997 14.3255V16Z"
                                                stroke="#888" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                    <span id="cancelButton" onclick="cancelEditing()" style="display: none;">
                                        <svg width="15" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M18 6L6 18M6 6L18 18" stroke="#888" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span> &nbsp;
                                    <span id="updateButton"
                                        onclick="event.preventDefault(); document.getElementById('update-head-office-timing').submit();"
                                        style="display: none;">
                                        <svg width="15" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M20 6L9 17L4 12" stroke="#888" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <form id="update-head-office-timing" method="post"
                                action="{{ route('head_office.update_company_timing') }}">
                                <table class="table table-striped" id="scheduleTable">
                                    <thead>
                                        <tr>
                                            <th style="display: none;"></th>
                                            <th>Day</th>
                                            <th>From</th>
                                            <th>To</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $days = [
                                                'monday',
                                                'tuesday',
                                                'wednesday',
                                                'thursday',
                                                'friday',
                                                'saturday',
                                                'sunday',
                                            ];
                                        @endphp

                                        @csrf

                                        @foreach ($days as $day)
                                            @php
                                                $var = 'is_open_' . $day;
                                                $start = $day . '_start_time';
                                                $end = $day . '_end_time';
                                            @endphp
                                            <tr class="day_{{ $head_office_timing->$var }}">
                                                <td class="checkbox" style="display: none;">
                                                    <input type="checkbox" value="1"
                                                        {{ $head_office_timing->$var ? 'checked' : '' }}
                                                        name="{{ $var }}" />
                                                </td>
                                                <td>{{ ucfirst($day) }}</td>
                                                <td>{{ strtolower($head_office_timing->convert_time($head_office_timing->$start)) }}
                                                </td>
                                                <td>{{ strtolower($head_office_timing->convert_time($head_office_timing->$end)) }}
                                                </td>
                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </form>
                        </div>


                </div>
                </div>
                <div class="tab-pane my_locations" id="my_locations">
                    <div class="dropdown">
                        <button class="dropdown-toggle primary-btn right float-right float-top" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-plus"></i> Add Location
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('head_office.location.create') }}?loc={{ str_replace(' ', '_', $head_office->company_name) }}"
                                    target="_blank" class="dropdown-item">Add New</a></li>
                            <li><button class="dropdown-item" data-bs-toggle="modal"
                                    data-bs-target="#multi_locations">Add Multiple</button></li>
                        </ul>
                    </div>
                    @include('head_office.my_organisation.locations', [
                        'locations' => $locations,
                        'assignedToGroups' => $assignedToGroups,
                        'notAssigned' => $notAssigned,
                    ])
                </div>
                <div class="tab-pane organization_structure" id="organization_structure" >
                    <a href="#" data-bs-toggle="modal" data-action="add_top_level_element" data-level="0"
                        data-bs-target="#level_action_modal" id="organization_structure_button"
                        class="primary-btn organisation_level_actions right float-righ float-top" style="z-index: 99;
    position: relative;"><i
                            class="fa fa-plus"></i> Add
                        Tier/Group</a>
                    @include('head_office.my_organisation.organisation_structure', [
                        'allGroups' => $allGroups,
                    ])
                </div>

            </div>

            <!-- profile page contents -->


        </div>

    </div>
    <div class="modal fade" id="multi_locations" tabindex="-1" aria-labelledby="multipleLocations" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Add Multiple Locations</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex gap-3">
                        <a href="{{ route('head_office.template_download') }}" class="card d-grid loc-wrap-card"
                            style="height: 300px;place-items:center;">
                            <div class="d-flex align-items-center flex-column gap-2">
                                <i class="fa-solid fa-file-arrow-down fs-1 brand"></i>
                                <p class="m-0">Click here to download <strong class="brand">excel sheet</strong>
                                    template.</p>
                                <small class="text-secondary text-center">.xlsx</small>
                            </div>
                        </a>
                        <div class="card d-grid loc-wrap-card" style="height: 300px;place-items:center;"
                            id="template-wrap">
                            <div class="d-flex align-items-center flex-column gap-2 mt-2">
                                <i class="fa-solid fa-file-csv brand fs-1"></i>
                                <p class="m-0">Click here to upload <strong class="brand">csv</strong> file.</p>
                                <small class="text-secondary text-center">.csv</small>
                                <form style="opacity: 0;" id="template_form"
                                    action="{{ route('head_office.template_submit') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="csv_file" id="csv_file" accept=".csv">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="location-theme" tabindex="-1" aria-labelledby="multipleLocations" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Location Theme</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="addThemeCloseBtn"></button>
                </div>
                <div class="modal-body">
                    <form id="location-theme-form" class="d-flex gap-3 flex-column w-100"
                        action="{{ route('organisation_settings.organisation_setting.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-1">
                            <label for="loc_theme_name" class="form-label">Theme Name:</label>
                            <input type="text" class="form-control" id="loc_theme_name" name="name"
                                placeholder="Please add title" required>
                        </div>
                        <div class="d-flex align-items-center w-60 justify-content-between">
                            <label for="loc_theme_color" class="form-label">Primary
                                Color:</label>
                            <div class="d-flex align-items-center justify-content-center bg-white"
                                style="border:1px solid gray; border-radius:8px;height:44px;">
                                <input style="width:12rem;max-width:6rem;border:0;" type="color"
                                    class="form-control shadow-none form-control-color" id="loc_theme_color" value="#000000"
                                    name="bg_color_code" title="Choose Primary color">
                                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="24pt" height="24pt"
                                    viewBox="0 0 37.000000 39.000000" preserveAspectRatio="xMidYMid meet">

                                    <g transform="translate(0.000000,39.000000) scale(0.100000,-0.100000)"
                                        id="loc_theme_colorSvg" stroke="none">
                                        <path d="M149 273 c-102 -123 -90 -172 41 -170 69 2 75 4 89 29 12 21 13 31 3
                                                        50 -25 47 -55 93 -66 100 -20 13 -52 9 -67 -9z m70 -21 c8 -15 4 -23 -19 -40
                                                        -36 -27 -37 -27 -50 -2 -15 28 4 60 35 60 13 0 29 -8 34 -18z m35 -58 c23 -9
                                                        21 -61 -3 -74 -26 -14 -50 -3 -57 26 -10 40 20 63 60 48z m-100 -20 c32 -31 9
                                                        -69 -31 -53 -15 5 -23 17 -23 32 0 40 26 50 54 21z" />
                                    </g>
                                </svg>
                            </div>
                        </div>



                        <div class="d-flex align-items-center w-60 justify-content-between">
                            <label for="location_section_heading_color" class="form-label">Section Heading
                                Color:</label>
                            <div class="d-flex align-items-center justify-content-center bg-white"
                                style="border:1px solid gray; border-radius:8px;height:44px;">
                                <input style="width:12rem;max-width:6rem;border:0;" type="color"
                                    class="form-control shadow-none form-control-color" id="location_section_heading_color" value="#5ac1b6"
                                    name="location_section_heading_color" title="Choose Section Heading color">
                                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="24pt" height="24pt"
                                    viewBox="0 0 37.000000 39.000000" preserveAspectRatio="xMidYMid meet">

                                    <g transform="translate(0.000000,39.000000) scale(0.100000,-0.100000)"
                                        id="location_section_heading_colorSvg" stroke="none">
                                        <path d="M149 273 c-102 -123 -90 -172 41 -170 69 2 75 4 89 29 12 21 13 31 3
                                                        50 -25 47 -55 93 -66 100 -20 13 -52 9 -67 -9z m70 -21 c8 -15 4 -23 -19 -40
                                                        -36 -27 -37 -27 -50 -2 -15 28 4 60 35 60 13 0 29 -8 34 -18z m35 -58 c23 -9
                                                        21 -61 -3 -74 -26 -14 -50 -3 -57 26 -10 40 20 63 60 48z m-100 -20 c32 -31 9
                                                        -69 -31 -53 -15 5 -23 17 -23 32 0 40 26 50 54 21z" />
                                    </g>
                                </svg>
                            </div>
                        </div>


                        



                        <div class="d-flex align-items-center w-60 justify-content-between">
                            <label for="location_button_color" class="form-label">Button
                                Color:</label>
                            <div class="d-flex align-items-center justify-content-center bg-white"
                                style="border:1px solid gray; border-radius:8px;height:44px;">
                                <input style="width:12rem;max-width:6rem;border:0;" type="color"
                                    class="form-control shadow-none form-control-color" id="location_button_color" value="#5ac1b6"
                                    name="location_button_color" title="Choose Button color">
                                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="24pt" height="24pt"
                                    viewBox="0 0 37.000000 39.000000" preserveAspectRatio="xMidYMid meet">

                                    <g transform="translate(0.000000,39.000000) scale(0.100000,-0.100000)"
                                        id="location_button_colorSvg" stroke="none">
                                        <path d="M149 273 c-102 -123 -90 -172 41 -170 69 2 75 4 89 29 12 21 13 31 3
                                                        50 -25 47 -55 93 -66 100 -20 13 -52 9 -67 -9z m70 -21 c8 -15 4 -23 -19 -40
                                                        -36 -27 -37 -27 -50 -2 -15 28 4 60 35 60 13 0 29 -8 34 -18z m35 -58 c23 -9
                                                        21 -61 -3 -74 -26 -14 -50 -3 -57 26 -10 40 20 63 60 48z m-100 -20 c32 -31 9
                                                        -69 -31 -53 -15 5 -23 17 -23 32 0 40 26 50 54 21z" />
                                    </g>
                                </svg>
                            </div>
                        </div>





                        <div class="d-flex align-items-center w-60 justify-content-between">
                            <label for="location_button_text_color" class="form-label">Button Text
                                Color:</label>
                            <div class="d-flex align-items-center justify-content-center bg-white"
                                style="border:1px solid gray; border-radius:8px;height:44px;">
                                <input style="width:12rem;max-width:6rem;border:0;" type="color"
                                    class="form-control shadow-none form-control-color" id="location_button_text_color" value="#000000"
                                    name="location_button_text_color" title="Choose Button Text color">
                                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="24pt" height="24pt"
                                    viewBox="0 0 37.000000 39.000000" preserveAspectRatio="xMidYMid meet">

                                    <g transform="translate(0.000000,39.000000) scale(0.100000,-0.100000)"
                                        id="location_button_text_colorSvg" stroke="none">
                                        <path d="M149 273 c-102 -123 -90 -172 41 -170 69 2 75 4 89 29 12 21 13 31 3
                                                        50 -25 47 -55 93 -66 100 -20 13 -52 9 -67 -9z m70 -21 c8 -15 4 -23 -19 -40
                                                        -36 -27 -37 -27 -50 -2 -15 28 4 60 35 60 13 0 29 -8 34 -18z m35 -58 c23 -9
                                                        21 -61 -3 -74 -26 -14 -50 -3 -57 26 -10 40 20 63 60 48z m-100 -20 c32 -31 9
                                                        -69 -31 -53 -15 5 -23 17 -23 32 0 40 26 50 54 21z" />
                                    </g>
                                </svg>
                            </div>
                        </div>






                        
                </div>







                <div class="mb-1">
                    <label for="loc_logo_input" class="form-label">Location logo:</label>
                    <div class="d-flex align-items-center" style="gap: 1rem">
                        <input style="align-self: flex-start;"
                            onchange="previewImage('loc_logo_input','loc_location_logo_placeholder')" class="form-control"
                            type="file" id="loc_logo_input" name="logo_file" accept=".png , .jpg , .jpeg">
                        <div>
                            <img id="loc_location_logo_placeholder" src="" alt="image"
                                style="border-radius: 100%;width:80px;height:80px;object-fit:cover;object-position:center;padding: 2px;box-shadow: 2px 2px 5px rgba(0,0,0,0.1);display:none;">
                        </div>
                    </div>
                </div>


                <div class="mb-1">
                    <label for="loc_login_img_input" class="form-label">Login page background upload:</label>
                    <div class="d-flex align-items-center" style="gap: 1rem">
                        <input style="align-self: flex-start;"
                            onchange="previewImage('loc_login_img_input','loc_login_placeholder')" class="form-control"
                            type="file" id="loc_login_img_input" name="bg_file" accept=".png , .jpg , .jpeg">
                        <div>
                            <img id="loc_login_placeholder" src="" alt="image"
                                style="border-radius: 100%;width:80px;height:80px;object-fit:cover;object-position:center;padding: 2px;box-shadow: 2px 2px 5px rgba(0,0,0,0.1);display:none;">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-page-content mt-3" style="align-self: flex-end">Save
                    Changes!</button>
                </form>
            </div>
        </div>
    </div>
    </div>

    <div id="cropModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crop Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="cropperContainer" style="width: 400px; height: 400px; margin: auto;">
                        <img id="cropperImage" style="max-width: 100%; height: 100%; display: block;" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="cropImageButton">Crop & Save</button>
                    <button type="button" class="btn btn-primary" id="cropImageButton2">Crop & Save</button>
                </div>
            </div>
        </div>
    </div>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
        



    <input type="hidden" id="route" value="{{ route('head_office.update_head_office_contact_details') }}">
    <input type="hidden" id="token" value="{{ csrf_token() }}">



    {{-- @section('scripts') --}}
    <script>
        $('#locaction-status').on('change', function() {
            console.log('asdfa')
            var selectedValue = $(this).val();

            // Show all rows initially
            $('tr.loc').show();

            // Hide rows based on the selected option
            if (selectedValue == '2') { // Archived
                $('tr.loc[data-live="true"], tr.loc[data-deleted="true"]').hide();
            } else if (selectedValue == '3') { // Live
                $('tr.loc[data-archived="true"], tr.loc[data-deleted="true"]').hide();
            } else if (selectedValue == '4') { // Deleted
                $('tr.loc[data-live="true"], tr.loc[data-archived="true"]').hide();
            }
        });

        $(document).ready(function() {

            $('#titleInput').on('change', function() {
                var newTitle = $(this).val();
                $('#login_preview-sub').text(newTitle);
            })
            $('#portalTitleInput').on('change', function() {
                var Title = $(this).val();
                $('#nd-text').text(Title);
            })

            $('#loginHighlightColorInput').on('change', function() {
                var newColor = $(this).val();
                $('[data-focused-border-color]').each(function() {
                    $(this).attr('data-focused-border-color', newColor);
                });
            });

            $('#iconColorInput').on('change', function() {
                neColor = $(this).val();
                document.documentElement.style.setProperty('--icon-nav-color2', neColor);
            });
            $('#highlightColorInput').on('change', function() {
                neColor = $(this).val();
                document.documentElement.style.setProperty('--highlight-nav-color2', neColor);
            });



            $('#signButtonColorInput').on('change', function() {
                var newColorbtn = $(this).val();
                $('[data-btn-color]').each(function() {
                    $(this).attr('data-btn-color', newColorbtn);
                });
                $('.login-preview-btn').each(function() {
                    this.style.setProperty('background', newColorbtn, 'important');
                });

                // Change text color with !important
                $('#company-head').each(function() {
                    this.style.setProperty('color', newColorbtn, 'important');
                });

            });


            // Apply the highlight color on focus
            $(document).on('focus', '[data-focused-border-color]', function() {
                var highlightColor = $(this).attr('data-focused-border-color');
                $(this).css('border-color', ''); // Clear any existing border color
                this.style.setProperty('border-color', highlightColor, 'important');
            });

            // Optionally, you can reset the border color when the element loses focus
            $(document).on('blur', '[data-focused-border-color]', function() {
                $(this).css('border-color', '');
            });
        });

        $(document).on("click", ".delete-theme", function(e) {
            e.preventDefault();
            let href = $(this).attr('href');
            let msg = $(this).data('msg');
            alertify.defaults.glossary.title = 'Alert!';
            alertify.confirm("Are you sure?", msg,
                function() {
                    window.location.href = href;
                },
                function(i) {});
        });
        $('.organisation_setting_select').on('click', function(event) {
            event.stopPropagation();
        })
        $('.organisation_setting_select').on('change', function(event) {

            const url = this.value;
            if (url) {
                window.location.href = url;
            }
        });

        $('.org_edit_btn').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var color = $(this).data('color');
            var logo = $(this).data('logo');
            var bg_logo = $(this).data('bg_logo');
            let = location_form_setting_color = $(this).data('location_form_setting_color');
            let = location_section_heading_color = $(this).data('location_section_heading_color');
            let = location_button_color = $(this).data('location_button_color');
            let = location_button_text_color = $(this).data('location_button_text_color');

            $('#theme_id').val(id);
            $('#loc_theme_name').val(name);
            $('#loc_theme_color').val(color);
            $("#loc_theme_colorSvg").css("fill", color);
            $("#location_section_heading_color").val(location_section_heading_color);
            $("#location_section_heading_colorSvg").css("fill", location_section_heading_color);
            $("#location_button_color").val(location_button_color);
            $("#location_button_colorSvg").css("fill", location_button_color);
            $("#location_button_text_color").val(location_button_text_color);
            $("#location_button_text_colorSvg").css("fill", location_button_text_color);
            $("#location_form_setting_color").val(location_form_setting_color);
            $("#location_form_setting_colorSvg").css("fill", location_form_setting_color);

            if (logo && logo !== 'null') {
                $('#loc_location_logo_placeholder').attr('src', logo).show();
            } else {
                $('#loc_location_logo_placeholder').hide();
            }

            if (bg_logo && bg_logo !== 'null') {
                $('#loc_login_placeholder').attr('src', bg_logo).show();
            } else {
                $('#loc_login_placeholder').hide();
            }

            // Set form action
            if (id) {
                $('#location-theme-form').attr('action',
                    '{{ route('organisation_settings.organisation_setting.update') }}/' + id);
            } else {
                $('#location-theme-form').attr('action',
                    '{{ route('organisation_settings.organisation_setting.store') }}');
            }
        });


        $(document).ready(function() {
            $('#location-theme').on('hidden.bs.modal', function() {
                $('#location-theme-form').attr('action',
                    '{{ route('organisation_settings.organisation_setting.store') }}');
                $('#location-theme-form').trigger("reset");
                $('#theme_id').val('');
                $('#loc_location_logo_placeholder').hide();
                $('#loc_login_placeholder').hide();
            });
            var dragBtn2 = document.querySelector('.drag-btn');
            var draggable2 = document.getElementById('draggable2');

            var posX2 = 0,
                posY2 = 0,
                mouseX2 = 0,
                mouseY2 = 0;

            if (dragBtn2 && draggable2) {
                dragBtn2.addEventListener('mousedown', mouseDown, false);
                window.addEventListener('mouseup', mouseUp, false);
            }

            function mouseDown(e) {
                e.preventDefault();
                posX2 = e.clientX - draggable2.offsetLeft;
                posY2 = e.clientY - draggable2.offsetTop;
                window.addEventListener('mousemove', moveElement, false);
            }

            function mouseUp() {
                window.removeEventListener('mousemove', moveElement, false);
            }

            function moveElement(e) {
                mouseX = e.clientX - posX2;
                mouseY = e.clientY - posY2;

                const maxX = 1000;
                const maxY = window.innerHeight - draggable2.offsetHeight;
                console.log(maxX);

                mouseX = Math.min(Math.max(mouseX, 0), maxX);
                mouseY = Math.min(Math.max(mouseY, 0), maxY);
                draggable2.style.left = mouseX + 'px';
                draggable2.style.top = mouseY + 'px';
            }
        });

        let table = new DataTable('#session-dataTable', {
            paging: false,
            info: false,
            language: {
                search: ""
            },
            'columnDefs': [{
                "select": 'multi',
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': '',
                'render': function(data, type, full, meta) {
                    return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(
                        data).html() + '">';
                }
            }],
        });

        $('#dataTable-select-all').on('click', function() {
            var rows = table.rows({
                'search': 'applied'
            }).nodes();
            // Check/uncheck checkboxes for all rows in the table
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });

        let sessionIds = []; // Array to store data-session-id values
        table.on('change', 'input', function() {

            let rowData = table.column(0).nodes().filter(function(value, index) {
                let inputElement = $(value).find('input');
                return inputElement.prop('checked');
            });

            sessionIds = [];
            $.each(rowData, function(index, obj) {
                let sessionId = $(obj).data('session-id');
                if (sessionId) {
                    sessionIds.push(sessionId);
                }
            });


            if (rowData.length > 0) {
                $('#draggable2').addClass('anim').removeClass('reverse-anim');
                console.log(rowData.length)
            } else {
                $('#draggable2').addClass('reverse-anim').removeClass('anim');
            }


            const dotsWrapper = $('.dots-wrapper');
            dotsWrapper.empty();

            for (let i = 0; i < rowData.length; i++) {
                dotsWrapper.append('<span class="dot" style="width:8px;height:8px;"></span>')
            }

            $('#count').text(rowData.length);
        });

        $('#delete-btn').on('click', function() {
            $('#sessionid-input').val(sessionIds);
            $('#delete-form').submit();
        })

        $('#help-desk-select').on('change', function() {
            location.reload();
            if ($(this).val() == 2) {
                // $('#chkEmp').prop('checked',true)
                $('#chkEmp').click()
            } else {
                $('#chkEmp').click()
                // $('#chkEmp').prop('checked',false)
            }
        })
        let msg = $('#msg').html();


        $('#chkEmp').on('click', function() {
            if ($(this).prop('checked')) {
                msg = $('#msg').html();
            } else {
                msg = 'QI tech help desk details';
            }
        });

        $('#chkHelp').on('click', function() {

            if ($(this).prop('checked')) {
                $('#share-msg').fadeIn()
            } else {
                $('#share-msg').fadeOut()
            }
        })
        $('#chkEmail').on('click', function() {

            if ($(this).prop('checked')) {
                $('#phone-details-email').fadeIn().css('display', 'flex')
            } else {
                $('#phone-details-email').fadeOut()
            }
        })
        $('#chkPhone').on('click', function() {
            console.log($(this))
            if ($(this).prop('checked')) {
                $('#phone-details-phone').fadeIn().css('display', 'flex')
            } else {
                $('#phone-details-phone').fadeOut()
            }
        })
        $('#help_input').on('change', function() {
            $('#share-msg').text($(this).val())
        })

        $('#need-btn').on('click', function() {
            if ($('#chkEmp').prop('checked')) {
                msg = $('#msg').html();
            } else {
                msg = '0333 335 6476';
            }
            alertify.alert('Need Help!', msg);
        })
    </script>
    <script>
        $(document).ready(function() {
            telnumber = $("#telephone").intlTelInput({
                fixDropdownWidth: true,
                showSelectedDialCode: true,
                strictMode: true,
                utilsScript: "{{ asset('admin_assets/js/utils.js') }}",
                preventInvalidNumbers: true,
                initialCountry: 'auto'
            }).on('countrychange', function(e, countryData) {
                code = $("#telephone").intlTelInput("getSelectedCountryData").dialCode;
            });

        })







        function updateFinanceEmail(element) {
           var email = $(element).val();
           var _token = $('#token').val();
           var route = $('#route').val();

           var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

           if (!emailRegex.test(email)) {
               alertify.error("Invalid email address. Please enter a valid email.");
               return; 
             }
       
           var data = {
               column: 'finance_email',
               value: email,
               _token: _token
           };
       
           $.post(route, data)
               .then(function(response) {
                   if (response.result) {
                       alertify.success("Email updated successfully!");
                       $(element).val(response.value);
                   } else {
                       alertify.error("Failed to update email. Please try again.");
                   }
               })
               .catch(function(error) {
                   console.log(error);
                   alertify.error("An error occurred while updating the email.");
               });
}


        function updateFinancePhone(element) {
            var email = $(element).val();
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'finance_phone',
                value: email,
                _token: _token
            };
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
        }

        function updateTechnicalEmail(element) {
        var email = $(element).val();
        var _token = $('#token').val();
        var route = $('#route').val();
            
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
        if (!emailPattern.test(email)) {
            alertify.error("Invalid email format. Please provide a valid email.");
            return;
        }
    
        var data = {
            column: 'technical_email',
            value: email,
            _token: _token
        };
    
        $.post(route, data)
            .then(function(response) {
                if (response.result) {
                    $(element).val(response.value);
                    alertify.success("Email updated successfully.");
                } else {
                    alertify.error("Failed to update the email.");
                }
            })
            .catch(function(error) {
                console.log(error);
                alertify.error("An error occurred while updating the email.");
            });
    }


        function updateTechnicalPhone(element) {
            var email = $(element).val();
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'technical_phone',
                value: email,
                _token: _token
            };
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
        }

        function updateIsViewableToUser(element) {
            console.log(element);
            var email = $(element).prop('checked') == true ? 1 : 0;
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'is_viewable_to_user',
                value: email,
                _token: _token
            };
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                        $('#sub-ops').slideToggle('fast')
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })


        }

        function updateIsHelpViewable(element) {
            var isChecked = $(element).prop('checked');
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'is_help_viewable',
                value: isChecked ? 1 : 0,
                _token: _token
            };
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                        if (response.value == 1) {
                            $('#help_input').removeAttr('readonly');
                        } else {
                            $('#help_input').attr('readonly', true);
                        }
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })


        }

        function updateIsPhoneViewable(element) {
            var isChecked = $(element).prop('checked');
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'is_phone_viewable',
                value: isChecked ? 1 : 0,
                _token: _token
            };
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                        if (response.value == 1) {
                            alertify.success('settings Updated!');
                        } else {
                            alertify.success('settings Updated!');
                        }
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })


        }

        function updateIsEmailViewable(element) {
            var isChecked = $(element).prop('checked');
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'is_email_viewable',
                value: isChecked ? 1 : 0,
                _token: _token
            };
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                        if (response.value == 1) {
                            alertify.success('settings Updated!');
                        } else {
                            alertify.success('settings Updated!');
                        }
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })


        }

        function updateIsHoursViewable(element) {
            var isChecked = $(element).prop('checked');
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'is_viewable_hours',
                value: isChecked ? 1 : 0,
                _token: _token
            };
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                        if (response.value == 1) {
                            alertify.success('settings Updated!');
                        } else {
                            alertify.success('settings Updated!');
                        }
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })


        }

        function updateHelpDescription(element) {
            var helpMsg = $(element).val();
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'help_description',
                value: helpMsg,
                _token: _token
            };
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
        }

        function updateCompanyName(element) {
            var name = $(element).val();
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'company_name',
                value: name,
                _token: _token
            };
            if (name.trim() === '' || name.length < 1) {
                alertify.error('Invalid Company Name!')
            } else {
                $.post(route, data)
                    .then(function(response) {
                        if (response.result) {
                            $(element).val(response.value);
                            alertify.notify('Company Name updated!', 'success', 5)
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    })
            }
        }

        function updateCompanyAddress(element) {
            var name = $(element).val();
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'address',
                value: name,
                _token: _token
            };
            if (name.trim() === '' || name.length <= 4) {
                alertify.error('Invalid Company Address!')
            } else {
                $.post(route, data)
                    .then(function(response) {
                        if (response.result) {
                            $(element).val(response.value);
                            alertify.notify('Company Address updated!', 'success', 5)
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    })
            }
        }

        function updateCompanyPhone(element) {
            var input = $("#telephone");
            var iti = input.intlTelInput("getInstance");
            var code = $("#telephone").intlTelInput("getSelectedCountryData").dialCode;
            var number = $("#telephone").intlTelInput("getNumber")
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'telephone_no',
                value: number,
                _token: _token
            };
            if ($("#telephone").intlTelInput("isValidNumber") === false) {
                alertify.notify('Invalid Phone', 'error');
                return;
            }
            console.log('datadfaf')
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        alertify.notify('Company Phone Updated!', 'success');
                    }
                })
                .catch(function(error) {
                    alertify.notify('Error Occured', 'error')
                    console.log(error);
                })
        }
        function updateMergePercentage(element) {
            var input = $("#percent_merge").val();
            var _token = $('#token').val();
            var route = "{{ route('head_office.company_info.change_percentage_merge') }}";
            var data = {
                column: 'telephone_no',
                value: input,
                _token: _token
            };
            if (input.trim() === '' || input > 100 || input < 0 || isNaN(input) ) {
                alertify.notify('Invalid Value', 'error');
                return;
            }
            $.post(route, data)
                .then(function(response) {
                    if (response.success == true) {
                        alertify.notify('Value Updated!', 'success');
                    }
                })
                .catch(function(error) {
                    alertify.notify(error.responseJSON.errors[0], 'error')
                    console.log(error);
                })
        }

        var cropper;

var loadFile = function (event) {
    // Display the selected image in the cropper modal
    var image = document.getElementById("cropperImage");
    image.src = URL.createObjectURL(event.target.files[0]);

    $("#cropModal").modal("show");
    $('#cropImageButton2').hide(); // jQuery to open the modal
            $('#cropImageButton').show();

    // Initialize Cropper.js when the image is loaded
    image.onload = function () {
        if (cropper) {
            cropper.destroy(); // Destroy the old cropper instance if it exists
        }
        cropper = new Cropper(image, {
             // Square crop (adjust as needed)
            viewMode: 1,
            scalable: true,
            movable: true,
            zoomable: true,
            minContainerWidth:400,
            minContainerHeight: 400
        });
    };
};

// Handle the cropping and setting the file back into the input
$("#cropImageButton").click(function () {
    if (!cropper) return;

    // Get the cropped image as a Blob
    cropper.getCroppedCanvas({
        width: 300, // Adjust dimensions as needed
        height: 300,
    }).toBlob(function (blob) {
        // Create a new File object
        const file = new File([blob], "cropped-image.jpg", { type: "image/jpeg" });

        // Create a DataTransfer to update the file input
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);

        // Set the file back into the input element
        const fileInput = document.getElementById("file");
        fileInput.files = dataTransfer.files;

        // Update the preview image
        const image = document.getElementById("output");
        image.src = URL.createObjectURL(file);

        // Hide the Bootstrap modal using jQuery
        $("#cropModal").modal("hide");
    }, "image/jpeg");
});

var input_dummy = undefined;
var imgId_dummy = undefined;
// Function to preview the image when a file is selected
function previewImage(inputId, ImgId) {
    var input = document.getElementById(inputId);
    var preview = document.getElementById(ImgId);
    var image = document.getElementById("cropperImage");

    // Store the input and preview IDs for later use
    input_dummy = inputId;
    imgId_dummy = ImgId;

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';

            // Open the cropper modal using jQuery when an image is selected
            $('#cropModal').modal('show'); // jQuery to open the modal
            $('#cropImageButton').hide(); // Show crop button
            $('#cropImageButton2').show(); // Hide the secondary crop button

            // Ensure image element is loaded before initializing the cropper
            image.src = e.target.result; // Set the preview image source to the reader result

            image.onload = function () {
                // Destroy the existing cropper instance if present
                if (cropper) {
                    cropper.destroy(); // Destroy previous cropper instance if any
                }

                // Initialize the new Cropper instance with the loaded image
                cropper = new Cropper(image, { // Square crop (adjust as needed)
                    viewMode: 1,
                    scalable: true,
                    movable: true,
                    zoomable: true,
                    minContainerWidth: 400,
                    minContainerHeight: 400,
                    responsive: true,
                });
            };

            // In case the image is already loaded, manually trigger the 'onload' event
            if (image.complete) {
                image.onload();
            }
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}

// Triggered when the user clicks the crop button
$("#cropImageButton2").click(function () {
    if (!cropper) return; // Ensure the cropper is initialized

    // Get the cropped image as a Blob
    cropper.getCroppedCanvas({
        width: 300, // Adjust dimensions as needed
        height: 300,
    }).toBlob(function (blob) {
        // Create a new File object from the Blob
        const file = new File([blob], "cropped-image.jpg", { type: "image/jpeg" });

        // Create a DataTransfer object to simulate the file input
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);

        // Set the file into the input element (simulating the file selection)
        const fileInput = document.getElementById(input_dummy); // Use the stored inputId
        console.log(fileInput)
        fileInput.files = dataTransfer.files;

        // Call the previewImage function to update the image preview with the cropped image
        previewImage(input_dummy, imgId_dummy); // Pass the specific inputId and ImgId for preview

        // Close the Bootstrap modal using jQuery
        $('#cropModal').modal('hide'); // jQuery to hide the modal
    }, "image/jpeg");
});



        $('#myInput').on('click', function(event) {
            event.stopPropagation();
            $(this).val($(this).data().company);
            $(this).removeAttr('readonly').css('background', 'transparent');
        });

        let isCopying = false;
        $('#myInput').on('blur', function() {
            const protocol = window.location.protocol + '//';
            if (isCopying) {
                isCopying = false;
                return;
            }
            $('#myInput').attr('readonly', true).css('background', '#E9ECEF');
            var company = $(this).val();
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'link_token',
                value: company,
                _token: _token
            };

            let temp_val = protocol + $('#myInput').data().company + '.qi-tech.co.uk';
            if (company == temp_val) {
                $('#myInput').val(protocol + $('#myInput').data().company + '.qi-tech.co.uk');
                return;
            }
            if (!/^[a-zA-Z0-9]+$/.test(company)) {
                alertify.notify('Link cannot contain symbols', 'error', 5);
                $(this).val(protocol + $('#myInput').data('company') + '.qi-tech.co.uk');
                return;
            }
            if ($('#myInput').data().company !== company && company.trim() !== '' && company.length > 1 &&
                company !== temp_val) {
                $.post(route, data)
                    .then(function(response) {
                        console.log('asdf')
                        console.log(response.value);
                        if (response.result) {
                            $('#myInput').data('company', response.value);
                            $('#myInput').val(protocol + $('#myInput').data().company + '.qi-tech.co.uk');
                            alertify.notify('Link Updated!', 'success', 5)
                        }
                    })
                    .catch(function(error) {
                        $('#myInput').val(protocol + $('#myInput').data().company + '.qi-tech.co.uk');
                        if(error.responseJSON?.message){
                            alertify.notify(error.responseJSON.message, 'error', 5)
                        }else{
                            alertify.notify('Error occuerd', 'error', 5)

                        }
                    })
            } else {
                $('#myInput').val(protocol + $('#myInput').data().company + '.qi-tech.co.uk');
            }
        })

        function myFunction() {
            isCopying = true;
            var copyText = document.getElementById("myInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);

            var tooltip = document.getElementById("myTooltip");
            tooltip.innerHTML = "Copied: " + copyText.value.slice(0, 36) + '...';
            // isCopying = false;
        }

        function outFunc() {
            var tooltip = document.getElementById("myTooltip");
            tooltip.innerHTML = "Copy to clipboard";
        }


        document.documentElement.style.setProperty('--highlight-company-subnav-color', @json($headOffice ? $headOffice->icon_color : '#34BFAF'));
        document.documentElement.style.setProperty('--highlight-company-btn-color', @json($headOffice ? $headOffice->highlight_color : '#34BFAF'));
        document.documentElement.style.setProperty('--icon-nav-color', @json($headOffice ? $headOffice->icon_color : '#444'));
        document.documentElement.style.setProperty('--primary-nav-color', @json($headOffice ? $headOffice->primary_color : '#fff'));

        $('#portalTextColorInput').on('input', function() {
            neColor = $(this).val();
            $('#portalTextColorInputSvg').css('fill', neColor);
            $('#portalName').css('color', neColor);
            document.documentElement.style.setProperty('--primary-nav-color2', neColor);
            $
        });
        $('#portalBackgroundColorInput').on('input', function() {
            neColor = $(this).val();
            $('#portalBackgroundColorInputSvg').css('fill', neColor);
            $('#portalName').css('background', neColor);
            document.documentElement.style.setProperty('--primary-nav-color2', neColor);
            $
        });
        $('#iconColorInput').on('input', function() {
            neColor = $(this).val();
            $('#iconColorInputSvg').css('fill', neColor);
        });
        $('#highlightColorInput').on('input', function() {
            neColor = $(this).val();
            $('#highlightColorInputSvg').css('fill', neColor);
        });
        $('#loginHighlightColorInput').on('input', function() {
            neColor = $(this).val();
            $('#loginHighlightColorInputSvg').css('fill', neColor);
        });
        $('#signButtonColorInput').on('input', function() {
            neColor = $(this).val();
            $('#signButtonColorInputSvg').css('fill', neColor);
            $('#signButtonColorInputBtn').css('background', neColor);
        });
        $('#tabColorInput').on('input', function() {
            neColor = $(this).val();
            $('#tabColorInputSvg').css('fill', neColor);
            $('#tabText').css('color', neColor);
            $('#tabLine').css('background', neColor);
        });
        $('#primaryButtonInput').on('input', function() {
            neColor = $(this).val();
            $('#primaryButtonInputSvg').css('fill', neColor);
            $('#primaryButtonInputBtn').css('background-color', neColor);
        });
        $('#buttonTextInput').on('input', function() {
            neColor = $(this).val();
            $('#buttonTextInputSvg').css('fill', neColor);
            $('#primaryButtonInputBtn').css('color', neColor);
        });
        $('#sectionHeadingInput').on('input', function() {
            neColor = $(this).val();
            $('#sectionHeadingInputSvg').css('fill', neColor);
            $('#headingText').css('color', neColor);
        });
        $('#portalTitleInput').on('keyup', function() {
            value = $(this).val();
            $('#portalName').text(value);
        });
        $('#signInMessage').on('keyup', function() {
            value = $(this).val();
            $('#signInMessageText').text(value);
        });

        $('#signInButtonTextInput').on('input', function() {
            neColor = $(this).val();
            $('#signInButtonTextInputSvg').css('fill', neColor);
            $('#signButtonColorInputBtn').css('color', neColor);
        });

        $('#file').on('change', function(event) {
            var input = event.target;
            if (input.files && input.files[0]) {
                var newImage = URL.createObjectURL(input.files[0]);
                $('#headOfficeLogo').attr('src', newImage);
            }

        });
        $("#loc_theme_color").on("input", function() {
            var color = $(this).val();
            $("#loc_theme_colorSvg").css("fill", color);
        });
        
        $("#location_button_color").on("input", function() {
            var color = $(this).val();
            $("#location_button_colorSvg").css("fill", color);
        });
        $("#location_section_heading_color").on("input", function() {
            var color = $(this).val();
            $("#location_section_heading_colorSvg").css("fill", color);
        });
        $("#location_button_text_color").on("input", function() {
            var color = $(this).val();
            $("#location_button_text_colorSvg").css("fill", color);
        });
        $("#location_form_setting_color").on("input", function() {
            var color = $(this).val();
            $("#location_form_setting_colorSvg").css("fill", color);
        });

        $('#addThemeCloseBtn').on('click', function() {
            $('#loc_theme_color').val('#000000');
            $('#loc_theme_colorSvg').css('fill', '#000000');
            $('#location_section_heading_color').val('#5ac1b6');
            $('#location_section_heading_colorSvg').css('fill', '#5ac1b6');
            $('#location_button_color').val('#5ac1b6');
            $('#location_button_colorSvg').css('fill', '#5ac1b6');
            $('#location_button_text_color').val('#000000');
            $('#location_button_text_colorSvg').css('fill', '#000000');
            $('#location_form_setting_color').val('#000000');
            $('#location_form_setting_colorSvg').css('fill', '#000000');
            $('#theme_id').val(0);
            $('#loc_theme_name').val('');
        });

    </script>
    {{-- @endsection --}}
    <script>
        function enableEditing() {
            var checkboxColumn = document.getElementsByClassName("checkbox");
            var fromFields = document.querySelectorAll("#scheduleTable td:nth-child(3)");
            var toFields = document.querySelectorAll("#scheduleTable td:nth-child(4)");
            var editButton = document.getElementById("editButton");
            var cancelButton = document.getElementById("cancelButton");
            var updateButton = document.getElementById("updateButton");

            for (var i = 0; i < checkboxColumn.length; i++) {
                checkboxColumn[i].style.display = "table-cell";
            }

            $('.day_0').show();

            fromFields.forEach(function(field) {
                var value = field.innerHTML;
                field.innerHTML =
                    `<input type="time" class="form-control" name="start_time[]" multiple="multiple" value="${value}" />`;
            });

            toFields.forEach(function(field) {
                var value = field.innerHTML;
                field.innerHTML =
                    `<input type="time" name="end_time[]" class="form-control" multiple="multiple" value="${value}" />`;
            });

            editButton.style.display = "none";
            cancelButton.style.display = "inline-block";
            updateButton.style.display = "inline-block";

            $('#scheduleTable tr').find('th:first').show(); //.display = "inline-block";
        }

        function cancelEditing() {
            var checkboxColumn = document.getElementsByClassName("checkbox");
            var fromFields = document.querySelectorAll("#scheduleTable td:nth-child(3) input");
            var toFields = document.querySelectorAll("#scheduleTable td:nth-child(4) input");
            var editButton = document.getElementById("editButton");
            var cancelButton = document.getElementById("cancelButton");

            var updateButton = document.getElementById("updateButton");

            $('#scheduleTable tr').find('th:first').hide()
            for (var i = 0; i < checkboxColumn.length; i++) {
                checkboxColumn[i].style.display = "none";
            }

            $('.day_0').hide();

            fromFields.forEach(function(field) {
                var value = field.value;
                field.parentNode.innerHTML = value;
            });

            toFields.forEach(function(field) {
                var value = field.value;
                field.parentNode.innerHTML = value;
            });

            editButton.style.display = "inline-block";
            cancelButton.style.display = "none";
            updateButton.style.display = "none";
        }

        function updateRestrictedCheck(element) {
            var email = $(element).prop('checked') == true ? 1 : 0;
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'restricted',
                value: email,
                _token: _token
            };
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                        alertify.success('settings updated!')
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
        }

        $(document).ready(function() {
            loadActiveTab();
            // changeTabUrl('orgStructureClick');
            if (window.location.search.split('=')[1] != undefined) {
                // console.log(window.location.search.split('=')[1].split('&')[0]);
                changeTabUrl(window.location.search.split('=')[1])
            }
        });

        function changeTabUrl(tabId, subTabId = null) {
            const currentURL = new URL(window.location.href);
            currentURL.searchParams.set('tab', tabId);
            // if(subTabId !== null){
            //     currentURL.searchParams.set('subTab',subTabId);
            // }
            window.history.pushState({
                tabId: tabId
            }, null, currentURL.href);

            $('#' + tabId).tab('show');

            // if(subTabId !== null){
            //     $('#'+subTabId).tab('show');
            // }
        }

        function loadActiveTab(tab = null) {
            if (tab == null) {
                tab = window.location.hash;
            }
            $('.main_header > li > a[data-bs-target="' + tab + '"]').tab('show');
        }
    </script>


@if(Session::has('success'))
    <script>
        alertify.success("{{ Session::get('success') }}");
    </script>
@elseif(Session::has('importErrors'))
<script>
    @php
        $errors = Session::get('importErrors');
    @endphp

    @if(!empty($errors))
        @foreach($errors as $error)
            alertify.alert("Import Error", 
                `<strong>Error:</strong> Error occurred while importing data. Following Emails were not imported.<br/>
                <strong>Email:</strong> {{ $error['email'] }}
                `
            ).set({
                transition: 'zoom', 
                closable: false, 
                pinnable: false, 
                label: 'OK'
            }).set('onshow', function() { 
                this.elements.body.style.textAlign = 'left'; 
                this.elements.dialog.style.maxWidth = '600px'; 
            });
        @endforeach
    @endif
</script>

@endif

@if(Session::has('error'))
    <script>
        alertify.error("{{ Session::get('error') }}");
    </script>
@endif
@endsection
