@extends('layouts.head_office_app')
@section('title', 'Head office Settings')

@section('sub-header')

    <div class="container mx-auto">
        <a href="{{route('head_office.location_page_view',$ho_location->id)}}" class="link text-info">Details</a>
        <a href="{{route('head_office.location_page_view_timeline',$ho_location->id)}}" class="link text-info ms-4">Timeline</a>
    </div>
@endsection
@section('content')
@include('head_office.location_sidebar')


    <div id="content" style="margin: 0;padding:0;">
        @php
            $location = $ho_location->location;
        @endphp
        <style>
            .grid-wrap{
                display: grid;
                grid-template-columns: 30% 1fr;
            }
            .cm_case_status{
                position: absolute;
                top: 10px;
                right: 20px;
            }
        </style>
        @include('layouts.error')

        <div class="container-lg mx-auto">
            <div class="timeline timeline_nearmiss border-add nearmiss-timeline" style="margin-left:13rem;">
                <div class="border-top-form"></div>
                @include('head_office.record_data_company_location_view')


                <div class="line line-date  print-display-none" style="display:none">
                    <div class="timeline-label"><i class="spinning_icon fa-spin fa fa-spinner"></i></div>
                </div>
                <div class="line line-date last-line">
                    <div class="timeline-label">Start</div>
                </div>
                <div class="account_created center" style="margin: unset;">
                    <h4 class="timeline_category_title">Account Created</h4>
                    <p>{{ date('D jS F Y', strtotime($location->created_at)) }}</p>
                </div>
            </div>
        </div>
    </div>


@section('scripts')
    <script>
        $(document).ready(function() {
            loadActiveTab();

        });
        window.addEventListener('DOMContentLoaded', (event) => {
            $(document).ready(function() {
                $('.select_2_custom').select2({
                    tags: true,
                });
            });
        });

        function loadActiveTab(tab = null) {
            if (tab == null) {
                tab = window.location.hash;
            }
            console.log(tab);
            $('.nav-tabs button[data-bs-target="' + tab + '"]').tab('show');
        }

    </script>




    <script src="{{ asset('js/alertify.min.js') }}"></script>

@endsection
@endsection
