@extends('layouts.location_app')
@section('title', 'Patients Safety Alerts')
{{-- @section('top-nav-title', 'Patients Safety Alerts') --}}
@section('content')
<div id="content">
    <div class="headingWithSearch">

        <div class="heading-center">
            Patients Safety Alerts
        </div>
    </div>
    <form method="get" class="form search-form print-display-none" style="margin-top: -78px;">
        <div class="input-group form-group mb-3 search-wrapper">
            <div class="form-group-search">
                <input type="text" class="form-control search-nearmiss" name="search" @if(request()->query('search'))
                value="{{request()->query('search')}}" @endif>
            </div>
            <div class="form-group-search">
                <select name="status" class="form-control psa_topbar_actions_select" onchange="this.form.submit()">
                    <option value="all" @if(request()->query('status') == 'all') selected @endif>Show All Alerts
                    </option>
                    <option value="unactioned" @if(request()->query('status') == 'unactioned') selected @endif>Show
                        Unactioned</option>
                    <option value="actioned" @if(request()->query('status') == 'actioned') selected @endif>Show Actioned
                    </option>
                </select>
            </div>
            @if(request()->query('format'))
            <input type="hidden" name="format" value="{{request()->query('format')}}">
            @endif
            <button type="submit" class="btn btn-info search_button"><i class="fa fa-search"></i></button>
        </div>
    </form>
    <div class="right print-display-none" style="margin-top: -51px">
        <div class="btn-group btn-group-sm float-right" role="group">
            <a href="{{route('location.view_patient_safety_alerts',['format'=>'timeline'])}}" class="btn btn-info"
                title=" View as Timeline">
                <i class="fa fa-th" aria-hidden="true"></i>
            </a>
            <a href="{{route('location.view_patient_safety_alerts',['format'=>'table'])}}" class="btn btn-info"
                title=" View as List">
                <span class="fas fa-list" aria-hidden="true"></span>
            </a>
        </div>
    </div>


    @if(request()->query('success'))
    <div class="alert to_hide_10 alert-success w-50" style="margin:0 auto">
        {{request()->query('success')}}
        <i class="right to_hide_to_manual fa fa-times" onclick="$('.to_hide_10').hide()"></i>
    </div>
    @endif
    @if(request()->query('error'))
    <div class="alert to_hide_10 alert-danger w-50" style="margin:0 auto">
        {{request()->query('error')}}
        <i class="right to_hide_to_manual fa fa-times" onclick="$('.to_hide_10').hide()"></i>
    </div>
    @endif
    <h3 class="text-info h3 font-weight-bold"></h3>

    @if(request()->query('format') == 'table')

    <table class="table table-bordered table_view_alerts">
        <thead>
            <tr>
                <th>Date & Actions</th>
                <th>Alert & Class</th>
                <th>Type & Originator</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if(count($received_alerts))
            @include('location.view_psa_table_record')
            <tr class="line-reloading print-display-none" style="display:none">
                <td colspan="5" class="center"><i class="spinning_icon fa-spin fa fa-spinner"></i></td>
            <tr>
        </tbody>
        @else
        <tr>
            <td colspan="5">
                <h5 class="text-info text-center">No Record Available</h5>
            </td>
        </tr>
        @endif
    </table>
    @else
    @if(count($received_alerts))
    <div class="timeline timeline_alerts">
        @include('location.view_psa_timeline_record')
        <div class="line line-date line-reloading print-display-none" style="display:none">
            <div class="timeline-label"><i class="spinning_icon fa-spin fa fa-spinner"></i></div>
        </div>
        <div class="line line-date last-line">
            <div class="timeline-label">Start</div>
        </div>
        <div class="account_created center">
            <h4 class="timeline_category_title">Account Created</h4>
            <p>{{date('D jS F Y',strtotime($location->created_at))}}</p>
        </div>
    </div>
    @else
    <h5 class="text-info text-center">No Record Available</h5>
    @endif
    @endif

    <!-- End card body and card -->
</div>
@endsection