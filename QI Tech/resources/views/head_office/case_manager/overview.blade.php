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
                        href="{{route('case_manager.case_archives')}}" onclick="event.preventDefault();">
                        Case Updates <span></span>
                </a>
            </li>
                    
            <li> <a class="{{request()->route()->getName() == 'case_manager.case_archives' ? 'active' : ''}}"
                    href="{{route('case_manager.case_archives')}}">Archived Cases <span></span></a></li>
        </ul>

@endsection
@section('content')
<script>
    $(document).ready(function() {
        if ($('.sidebar-btn').length) {
            $('.sidebar-btn').trigger('click');
        }
    });
</script>

<style>
    .open-case-row{
        cursor: pointer;
        transition: 0.3s;
    }
    .open-case-row:not(.open):hover td{
        opacity: 0.5;
        transition: 0.3s;
    }
    .open-case-row:not(.open):hover td:first-child{
        opacity: 1;
        transition: 0.3s;
    }
    .new-card-wrap{
        opacity: 1 !important;
    }
</style>
<div id="content">
<div class="card card-qi content_widthout_sidebar" style="min-height: 70vh;">
    <div class="card-body scrollbar_custom_green" style="padding:10px;overflow-x: auto;position: relative;">
        
        <div class="cm_content pt-2">
            @if(!count($ho_users))
                <p class="font-italic center">No cases are found for the given conditions!</p>
            @endif

            
                @foreach($ho_users as $ho_user)
                @if ($ho_user->head_office_user_cases->count() > 0)

            <table class="table table-striped table-bordered" id="dataTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Total Open Cases</th>
                        <th>Assigned Tasks</th>
                        <th>Awaiting requested information </th>
                        <th>Shared cases</th>
                        <th style="white-space: nowrap">Awaiting root cause analysis</th>
                        <th>Final Approval</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $user = $ho_user->user;
                        $open_cases = $ho_user->head_office_user_cases->filter(function($case){
                            return $case->case->status == 'open';
                        });
                        $open_sorted_cases = $open_cases->sortBy('case.created_at');
                    @endphp
                        <tr class="open-case-row">
                            <td>
                                <div style="cursor:pointer;"
                                    class="user-icon-circle new-card-wrap"
                                    title="{{ $user->name }}">
                                    @if (isset($user->logo))
                                        <img src="{{ $user->logo }}"
                                            alt="png_img"
                                            style="width: 30px; height: 30px; border-radius: 50%;">
                                    @else
                                        <div class="user-img-placeholder"
                                            id="user-img-place"
                                            style="width: 30px; height: 30px;">
                                            {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $user->name))) }}
                                        </div>
                                    @endif
                                    @if (isset($user))
                                        @include(
                                            'head_office.user_card_component',
                                            [
                                                'user' => $user,
                                            ]
                                        )
                                    @endif
                                </div>
                                <strong>{{ $user->name }}</strong>
                            </td>
                            <td style="width:300px;">
                            <a href="#">{{count($open_cases)}}</a><br />
                            Oldest open: {{isset($open_sorted_cases->first()->case->created_at) ? $open_sorted_cases->first()->case->created_at->diffInDays() . ' days ago' : 0}}

                                <div style="width:300px;display: none;" class="shadow nested p-1 bg-white mt-2"  >
                                    <table class="table table-striped">
                                        <thead>
                                            <th style="word-wrap: no-wrap;">PROGRESS</th>
                                            <th style="word-wrap: no-wrap;">CASE OPENED</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($open_cases as $case)
                                                <tr data-route='{{route('case_manager.view',$case->case_id)}}'>
                                                    <td>
                                                        <div class="p-1">
                                                            <p style="font-size: 12px;font-weight:600;margin-bottom:3px;">CASE {{$case->case->id}}</p>
                                                            
                                                            <div data-toggle="tooltip" data-bs-placement="auto" title="{{ $case->case->percentComplete() }}%"
                                                                class="progress mt-1" role="progressbar" aria-label="Case progress" aria-valuenow="75"
                                                                aria-valuemin="0" aria-valuemax="100"
                                                                style="height: 8px;cursor: pointer;">
                                                                <div class="progress-bar 
                                                            @if ($case->case->percentComplete() == 20)
                                                            bg-danger
                                                            @elseif($case->case->percentComplete() == 40)
                                                            bg-info
                                                            @elseif($case->case->percentComplete() == 60)
                                                            bg-primary
                                                            @elseif($case->case->percentComplete() == 80)
                                                            bg-warning
                                                            @elseif($case->case->percentComplete() == 100)
                                                            bg-success @endif
                                                            "
                                                                style="width: {{ $case->case->percentComplete() }}%"></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{isset($case->case->created_at) ? $case->case->created_at->diffInDays() . ' days ago' : 0}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                            <td>
                                <a href="#">{{count($ho_user->stage_task_assigns())}}</a><br />
                            Oldest unactioned: {{isset($ho_user->stage_task_assigns()->sortBy('created_at')->first()->created_at) ? $ho_user->stage_task_assigns()->sortBy('created_at')->first()->created_at->diffForHumans() : 0 }}
                            <div style="width:300px;display: none;" class="shadow nested p-1 bg-white mt-2"  >
                                <table class="table table-striped">
                                    <thead>
                                        <th style="word-wrap: no-wrap;">PROGRESS</th>
                                        <th style="word-wrap: no-wrap;">Task</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($ho_user->stage_task_assigns() as $task_assign)
                                            <tr data-route='{{route('case_manager.view',$task_assign->task->caseStage->case->id)}}'>
                                                <td>
                                                    <div class="p-1">
                                                        <p style="font-size: 12px;font-weight:600;margin-bottom:3px;">CASE {{$task_assign->task->caseStage->case->id}} ({{$task_assign->task->caseStage->name}})</p>
                                                        <div class="progress" role="progressbar" aria-label="Case progress" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="height: 8px">
                                                            <div class="progress-bar 
                                                            @if ($task_assign->task->caseStage->percentComplete() == 20)
                                                            bg-danger
                                                            @elseif($task_assign->task->caseStage->percentComplete() == 40)
                                                            bg-info
                                                            @elseif($task_assign->task->caseStage->percentComplete() == 60)
                                                            bg-primary
                                                            @elseif($task_assign->task->caseStage->percentComplete() == 80)
                                                            bg-warning
                                                            @elseif($task_assign->task->caseStage->percentComplete() == 100)
                                                            bg-success
                                                            @endif
                                                            " style="width: {{ $task_assign->task->caseStage->percentComplete() }}%"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column text-left">
                                                        <p class="m-0 fw-bold text-left" style="font-size: 12px;font-weight:600;margin-bottom:3px;">{{$task_assign->task->title}}</p>
                                                        <p class="text-muted fw-semibold m-0" style="font-size: 12px;">{{isset($task_assign->task->dead_line_date) ? \carbon\carbon::parse($task_assign->task->dead_line_date)->diffForHumans() : ''}}</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            </td>
                            <td>
                                <a href="#">{{$ho_user->user->case_request_informations()->count()}}</a><br />
                            Oldest awaiting: {{$ho_user->user->case_request_informations()->orderBy('created_at', 'asc')->first() ? $ho_user->user->case_request_informations()->orderBy('created_at', 'asc')->first()->created_at->diffForHumans() : 'None'}}
                            <div style="width:300px;display: none;" class="shadow nested p-1 bg-white mt-2"  >
                                <table class="table table-striped">
                                    <thead>
                                        <th style="word-wrap: no-wrap;">PROGRESS</th>
                                        <th style="word-wrap: no-wrap;">REQUESTED INFO</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($ho_user->user->case_request_informations as $case_info)
                                            <tr data-route='{{route('case_manager.view',$case_info->case->id)}}'>
                                                <td>
                                                    <div class="p-1">
                                                        <p style="font-size: 12px;font-weight:600;margin-bottom:3px;">CASE {{$case_info->case->id}}</p>
                                                        <div class="progress" role="progressbar" aria-label="Case progress" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="height: 8px">
                                                            <div class="progress-bar 
                                                            @if ($case_info->case->percentComplete() == 20)
                                                            bg-danger
                                                            @elseif($case_info->case->percentComplete() == 40)
                                                            bg-info
                                                            @elseif($case_info->case->percentComplete() == 60)
                                                            bg-primary
                                                            @elseif($case_info->case->percentComplete() == 80)
                                                            bg-warning
                                                            @elseif($case_info->case->percentComplete() == 100)
                                                            bg-success
                                                            @endif
                                                            " style="width: {{ $case_info->case->percentComplete() }}%"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column text-left">
                                                        <p class="m-0 fw-bold text-left" style="font-size: 12px;font-weight:600;margin-bottom:3px;">{{$case_info->first_name .' '.$case_info->last_name}}</p>
                                                        <p class="text-muted fw-semibold m-0" style="font-size: 12px;">{{isset($case_info->created_at) ? $case_info->created_at->diffForHumans() : ''}}</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            </td>
                            <td>
                                <a href="#">{{count($ho_user->head_office_user_share_cases())}}</a>
                                <div style="width:300px;display:none;" class="shadow nested p-1 bg-white mt-2"  >
                                    <table class="table table-striped">
                                        <thead>
                                            <th style="word-wrap: no-wrap;">PROGRESS</th>
                                            <th style="word-wrap: no-wrap;">SHARED INFO</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($ho_user->head_office_user_share_cases() as $share_cases)
                                                @foreach ($share_cases as $share_case )
                                                    <tr data-route='{{route('case_manager.view',$share_case->case->id)}}'>
                                                        <td>
                                                            <div class="p-1">
                                                                <p style="font-size: 12px;font-weight:600;margin-bottom:3px;">CASE {{$share_case->case->id}}</p>
                                                                <div class="progress" role="progressbar" aria-label="Case progress" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="height: 8px">
                                                                    <div class="progress-bar 
                                                                    @if ($share_case->case->percentComplete() == 20)
                                                                    bg-danger
                                                                    @elseif($share_case->case->percentComplete() == 40)
                                                                    bg-info
                                                                    @elseif($share_case->case->percentComplete() == 60)
                                                                    bg-primary
                                                                    @elseif($share_case->case->percentComplete() == 80)
                                                                    bg-warning
                                                                    @elseif($share_case->case->percentComplete() == 100)
                                                                    bg-success
                                                                    @endif
                                                                    " style="width: {{ $share_case->case->percentComplete() }}%"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-column text-left">
                                                                <p class="m-0 fw-bold text-left" style="font-size: 12px;font-weight:600;margin-bottom:3px;">{{$share_case->email}}</p>
                                                                <p class="text-muted fw-semibold m-0" style="font-size: 12px;">Shared {{isset($share_case->created_at) ? $share_case->created_at->diffForHumans() : ''}}</p>
                                                                <p class="text-muted fw-semibold m-0" style="font-size: 12px;">Expires {{isset($share_case->duration_of_access) ? $share_case->duration_of_access->diffForHumans() : ''}}</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                            <td></td>
                            <td>
                                <a href="#">{{count($ho_user->head_office_user_final_cases())}}</a>
                                <div style="width:300px;display:none;" class="shadow nested p-1 bg-white mt-2"  >
                                    <table class="table table-striped">
                                        <thead>
                                            <th style="word-wrap: no-wrap;">PROGRESS</th>
                                            <th style="word-wrap: no-wrap;">SHARED INFO</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($ho_user->head_office_user_final_cases() as $final_case)
                                            @if ($final_case->case)
                                                <tr data-route='{{route('case_manager.view',$final_case->id)}}'>
                                                    <td>
                                                        <div class="p-1">
                                                            <p style="font-size: 12px;font-weight:600;margin-bottom:3px;">CASE {{$final_case->id}}</p>
                                                            <div class="progress" role="progressbar" aria-label="Case progress" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="height: 8px">
                                                                <div class="progress-bar 
                                                                @if ($final_case->case->percentComplete() == 20)
                                                                bg-danger
                                                                @elseif($final_case->case->percentComplete() == 40)
                                                                bg-info
                                                                @elseif($final_case->case->percentComplete() == 60)
                                                                bg-primary
                                                                @elseif($final_case->case->percentComplete() == 80)
                                                                bg-warning
                                                                @elseif($final_case->case->percentComplete() == 100)
                                                                bg-success
                                                                @endif
                                                                " style="width: {{ $final_case->case->percentComplete() }}%"></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column text-left">
                                                            <p class="m-0 fw-bold text-left" style="font-size: 12px;font-weight:600;margin-bottom:3px;">Case {{$final_case->id}}</p>
                                                            <p class="text-muted fw-semibold m-0" style="font-size: 12px;">{{isset($final_case->updated_at) ? $final_case->updated_at->diffForHumans() : ''}}</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                                    
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                            
                        </tr>
                
                </tbody>
            </table>
            @else
            {{-- <p style="font-size: 20px;font-weight:600; text-align: center; align-items: center;">There are no case handlers with assigned cases</p> --}}
            @endif
            @endforeach
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
    <script>
        $(document).ready(function() {
        $('.open-case-row').click(function() {
            $(this).find('.nested').slideToggle();
            $(this).toggleClass('open');
        });
        $('tr[data-route]').on('click', function(event) {
            event.stopPropagation()
            var routeUrl = $(this).data('route');
            window.open(routeUrl, '_blank');
        });
    });
    </script>
@endsection