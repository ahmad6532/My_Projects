@extends('layouts.head_office_app')
@section('title', 'Cases Overview')
@section('sidebar')
@include('layouts.company.sidebar')
@endsection
@section('sub-header')
        <ul style="padding-left: 270px">
            <li> <a class="{{request()->route()->getName() == 'case_manager.index' ? 'active' : ''}}"
                    href="{{route('case_manager.index')}}">Cases <span></span></a> </li>
            <li> <a class="{{request()->route()->getName() == 'case_manager.overview' ? 'active' : ''}}"
                    href="{{route('case_manager.overview')}}">Overview <span></span></a></li>
            <li> <a data-toggle="tooltip" data-placement="top" title="Coming Soon" class="{{request()->route()->getName() == 'case_manager.case_updates' ? 'active' : ''}} placeholder-link"
                    href="{{route('case_manager.case_archives')}}">Case Updates <span></span></a></li>
            <li> <a class="{{request()->route()->getName() == 'case_manager.case_archives' ? 'active' : ''}}"
                    href="{{route('case_manager.case_archives')}}">Archived Cases <span></span></a></li>
        </ul>

@endsection
@section('content')
<div id="content">
<div class="card card-qi content_widthout_sidebar">
    <div class="card-body">
        
        <div class="cm_content pt-2">
            @if(!count($ho_users))
                <p class="font-italic left">No cases are found for the given conditions!</p>
            @endif

            <table class="table table-striped table-bordered" id="dataTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th style="white-space: nowrap">Case ID</th>
                        <th>Case Summary</th>
                        <th>Updates</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                
                    <!-- Begin loop -->
                    <tr>
                        <td>
                            11/12/2023 12:19 PM
                        </td>
                        <td>
                           1231
                        </td>
                        <td>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vitae elementum enim. Duis ullamcorper justo justo, vitae bibendum justo finibus eget. Donec at velit non ex egestas molestie. Vestibulum dignissim vestibulum tempor. Ut congue vitae nibh vitae lacinia. Integer mattis odio neque, in tincidunt augue blandit...
                        </td>
                       
                        <td>
                            Information request received from [X] [Email]
                        </td>
                        <td>
                            <a href="#" target="_blank">Go to Case Log</a>
                        </td>
                        
                    </tr>
                <!-- End loop -->
                </tbody>
            </table>
            
            {{-- <div>{!! $cases->render('pagination::bootstrap-5') !!}</div> --}}
        </div>


    </div>
</div>
</div>
@endsection
@section('top_bar_search')
<form method="get" class="form print-display-none header_search_bar">
    <div class="input-group form-group mb-3">
        <input type="text" class="form-control search-nearmiss" name="search" @if(request()->query('search')) value="{{request()->query('search')}}" @endif>
        <button type="submit" class="btn btn-info search_button"><i class="fa fa-search"></i></button>
    </div>
</form>

@endsection
@section('case_manager_tabs')
<nav class="nearmiss-navbar topheader-nav" style="white-space: nowrap">
    <ul>
        <li> <a class="{{request()->route()->getName() == 'case_manager.index' ? 'active' : ''}}" href="{{route('case_manager.index')}}"><span>Cases</span></a> </li>
        <li> <a class="{{request()->route()->getName() == 'case_manager.overview' ? 'active' : ''}}" href="{{route('case_manager.overview')}}"><span>Overview</span></a></li>
        <li> <a class="{{request()->route()->getName() == 'case_manager.case_updates' ? 'active' : ''}}" href="{{route('case_manager.case_updates')}}"><span>Case Updates</span></a></li>
    </ul>
</nav>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
@endsection

@section('scripts')
    <script src="{{asset('js/alertify.min.js')}}"></script>
@endsection