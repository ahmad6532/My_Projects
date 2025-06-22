@extends('layouts.location_app')
@section('title', 'Near Misses')
@section('content')
<div id="content">
    <div class="headingWithSearch">
        
        <div class="heading-center">
            All Reported
        </div>
    </div>
    
    <form method="get" class="form search-form print-display-none" style="margin-top: -78px;">
        <div class="input-group form-group mb-3 search-wrapper">
            <div class="form-group-search">
                <input type="text" class="form-control search-nearmiss" name="search" @if(request()->query('search'))
                value="{{request()->query('search')}}" @endif>
            </div>
            <div class="form-group-search">
                <input type="text" name="start_date" class="datepicker form-control" @if(request()->query('start_date'))
                value="{{request()->query('start_date')}}" @else value="{{date('d/m/Y', strtotime('-1 week'))}}" @endif>
            </div>
            <div class="form-group-search">
                <input type="text" name="end_date" class="datepicker form-control" @if(request()->query('end_date'))
                value="{{request()->query('end_date')}}" @else value="{{date('d/m/Y')}}" @endif>
            </div>
            @if(request()->query('format'))
            <input type="hidden" name="format" value="{{request()->query('format')}}">
            @endif
            <button type="submit" class="btn btn-info search_button"><i class="fa fa-search"></i></button>
        </div>

    </form>
    
    <div class="btn-group btn-group-sm float-right" role="group" style="margin-top: -51px">
        @if(request()->query('format') == 'table')
        <a href="{{route('location.view_near_miss',['hide'=>'deleted'])}}" class="btn btn-info"
            title=" Hide Deleted">
            <i class="fas fa-eye-slash"></i>
        </a>
        @else
        <a href="#" class="btn btn-info btn-toggle-delete" title="Show Deleted" data-show-title="Show Deleted"
            data-hide-title="Hide Deleted">
            <i class="fas fa-eye-slash"></i>
        </a>
        @endif
        <a href="{{route('location.near_miss.qr_code')}}" class="btn btn-info" title="QR Code" target="_blank">
            <i class="fa fa-qrcode"></i>
        </a>
        <a href="{{route('location.view_near_miss',['format'=>'timeline'])}}" class="btn btn-info"
            title=" View as Timeline">
            <i class="fa fa-th" aria-hidden="true"></i>
        </a>
        <a href="{{route('location.view_near_miss',['format'=>'table'])}}" class="btn btn-info"
            title=" View as List">
            <span class="fas fa-list" aria-hidden="true"></span>
        </a>
    </div>
    <div class="">
            
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
        @if(request()->query('format') == 'table')

        <table class="table table-bordered datatable table_view_nearmiss">
            <thead>
                <tr>
                    <th>When?</th>
                    <th>Error</th>
                    <th>Drugs Involved</th>
                    <th>Point of Detection</th>
                    <th>Why?</th>
                    <th>People</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(count($near_misses))
                @include('location.view_near_miss_table_record')
                <tr class="line-reloading print-display-none" style="display:none">
                    <td colspan="7" class="center"><i class="spinning_icon fa-spin fa fa-spinner"></i></td>
                <tr>
            </tbody>
            @else
            <tr>
                <td colspan="7">
                    <h5 class="text-info text-center">No Record Available</h5>
                </td>
            </tr>
            @endif
        </table>
        @else
        @if(count($near_misses))
        <div class="timeline timeline_nearmiss">
            @include('location.view_near_miss_timeline_record')
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
    </div>
</div>
@endsection

@section('scripts')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script> --}}
<script>

</script>
@endsection