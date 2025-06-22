@extends('layouts.head_office_app')
@section('title', 'Case '.$case->id())
@section('sub-header')
@include('head_office.case_manager.notes.sub-header')
@endsection
@php
use Carbon\Carbon;
@endphp
@section('content')
<div id="content">

    <div class="row">
        <div class="col-md-6">

            <div class="case-intelligence-container">
                <div class="nav nav-tabs" style="display:block;" id="myTab" role="tablist">
                    @foreach ($share_cases as $key => $share)
                    <div class="pointer" id="tab_{{$key}}_tab" data-bs-toggle="tab"
                        data-bs-target="#tab_{{$key}}" role="tab" aria-controls="tab_{{$key}}"
                        aria-selected="true">
                        <div class="card border-left-secondary shadow w-100">
                            <div class="card-body">
                                <div class="row align-items-center ">
                                    <div class="col-sm-4">
                                        <div class="font-weight-bold text-black" title="Case Number">
                                            {{$share->email}}</div>
                                    </div>
                                    <div class="col-sm-4">
                                        <span class="cm_incident_type">
                                            @if ($share->removed_by_user)
                                                <div class="cm_comment_comment">
                                                    <b style="color: red">Cancelled</b>
                                                </div>
                                            @elseif ($share->is_revoked)
                                                <div class="cm_comment_comment">
                                                    <b style="color: red">Access Revoked </b> by {!! $share->user->name !!}
                                                    {!! $share->updated_at->format(config('app.dateFormat')) !!} ({!! $share->updated_at->diffForHumans() !!})
                                                </div>
                                            @else
                                                @if ($share->duration_of_access > Carbon::now())
                                                    <div class="cm_comment_comment">
                                                        <b style="color: green">Available</b> until {!! $share->duration_of_access->format(config('app.dateFormat')) !!} ({!! $share->duration_of_access->diffForHumans() !!})
                                                    </div>
                                                @else
                                                    <div class="cm_comment_comment">
                                                        <b style="color: red">Expired</b> on {!! $share->duration_of_access->format(config('app.dateFormat')) !!} ({!! $share->duration_of_access->diffForHumans() !!})
                                                    </div>
                                                @endif
                                            @endif
                                        </span><br>
                                    </div>
                                    
                                    <div class="col-sm-4">
                                        <i class="float-right text-gray-900"
                                            style="font-size: 15px">{{$share->created_at->format(config('app.dateFormat'))}}
                                            {{$share->created_at->format(config('app.timeFormat'))}}</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('head_office.case_manager.notes.edit_duration',['share' => $share])
                        <div class="cm_comment_reply">
                        </div>
                    </div>

                    @endforeach
                    @if(count($share_cases) ==0)
                    No data found
                    @endif


                </div>
            </div>
        </div>
        <div class="col-sm-6">
            @if(count($share_cases))
            <div class="card">
                <div class="card-body">
                    <div class="case-intelligence-container">
                        <div class="tab-content" id="myTabContent">

                            @foreach ($share_cases as $k => $share)
                            <div class="tab-pane fade @if ($k == 0) active @endif show" id="tab_{{$k}}" role="tabpanel" aria-labelledby="tab_{{$k}}-tab">
                                <nav class="nav nav-tabs nav-h-bordered" id="myTab" role="tablist">
                                    <a href="#" class="active" data-bs-toggle="tab" data-bs-target="#details"><span class="item_with_border">Details</span> <span class="badge badge-danger">1</span></a>
                                    <a href="#" data-bs-toggle="tab" data-bs-target="#communication_{{$share->id}}"><span class="item_with_border">Communication</span></a>
                                </nav>
                                <div class="tab-content" id="myTabContent">
                                    <div id="details" class="details scrollbar_custom_green relative tab-pane show active">
                                        <div data-comment="{{$share->id}}" class="cm_comment card @if( $key % 2 == 0 ) cm_comment_grey @endif" style="margin: 0px !important">
                                            <div class="cm_comment_author_date">
                                                <b>{{$share->user->name}}</b> shared a case with {{$share->email}}
                                                {{$share->created_at->diffForHumans()}}
                                                <span class="float-right">{{$share->created_at->format(config('app.dateFormat'))}} {{$share->created_at->format(config('app.timeFormat'))}}</span>
                                            </div>
                                            @if($share->removed_by_user)
                                                <div class="cm_comment_comment">
                                                    <b style="color: Red">Cancelled</b>
                                                </div>
                                            @elseif($share->is_revoked)
                                                <div class="cm_comment_comment">
                                                    <b style="color: red">Access Revoked</b> by {!! $share->user->name !!}
                                                    {!! $share->updated_at->format(config('app.dateFormat')) !!}
                                                    {!! $share->updated_at->format(config('app.timeFormat')) !!} ({!! $share->updated_at->diffForHumans() !!})
                                                </div>
                                            @else
                                                @if($share->duration_of_access > Carbon::now())
                                                    <div class="cm_comment_comment">
                                                        <b style="color: green">Available</b> until {!! $share->duration_of_access->format(config('app.dateFormat')) !!} {!! $share->duration_of_access->format(config('app.timeFormat')) !!} ({!! $share->duration_of_access->diffForHumans() !!})
                                                    </div>
                                                @else
                                                    <div class="cm_comment_comment">
                                                        <b style="color: red">Expired</b> on {!! $share->duration_of_access->format(config('app.dateFormat')) !!} {!! $share->duration_of_access->format(config('app.timeFormat')) !!} ({!! $share->duration_of_access->diffForHumans() !!})
                                                    </div>
                                                @endif
                                            @endif
                                            <div class="cm_comment_comment">
                                                {!! $share->note !!}
                                            </div>
                                            <div class="cm_comment_people">
                                                @if(!$share->is_revoked && !$share->removed_by_user)
                                                    <a data-bs-toggle="tooltip" title="Revoke Access" href="{{route('head_office.share_case.revoke_access',[$case->id,$share->id])}}" data-msg="Are you sure you want to revoke this access?" class="badge badge-danger badge-user revoke_access_share_case regular-badge">Revoke Access</a>
                                                @elseif($share->is_revoked)
                                                    <span title="Access Revoked" class="badge badge-danger regular-badge">Access Revoked</span>
                                                @endif
                            
                                                @if(!count($share->extension) && !$share->removed_by_user)
                                                    <a href="#" class="badge badge-warning badge-user regular-badge" data-bs-toggle="modal" data-bs-target="#edit_duration_{{$share->id}}">Extend Access</a>
                                                @endif
                            
                                                @if(!$share->removed_by_user)
                                                    <a href="{{route('head_office.case.share_case_delete',[$case->id,$share->id])}}" data-msg="Are you sure you want to delete this?" class="badge badge-danger badge-user delete_share_case regular-badge">Delete</a>
                                                    <a href="{{route('head_office.share_case.share_case_view',[$case->id,$share->id])}}" class="badge badge-success badge-user regular-badge">Edit</a>
                                                @endif
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div id="communication_{{$share->id}}" class="details scrollbar_custom_green relative tab-pane show">
                                        <div class="relative scrollbar_custom_green">
                                            @if(!count($share->communications))
                                                <p class="font-italic">No comments are found!</p>
                                            @else
                                                <div id="contextMenu" class="context-menu" style="display: none">
                                                    <ul class="menu_comment_link">
                                                        <li class="share"><a href="#" onclick="copy_to_clipboard(this)" id="t_link_item" data-link=""><i class="fa fa-copy" aria-hidden="true"></i> Copy Tracking Link</a></li>
                                                        <li class="share"><a href="#" onclick="copy_to_clipboard(this)" id="o_link_item" data-link=""><i class="fa fa-copy" aria-hidden="true"></i> Copy Original Link</a></li>
                                                    </ul>
                                                </div>
                                            @endif

                                            @if(count($share->extension))
                                                <hr>
                                                <div data-comment="{{$share->id}}" class="cm_comment card @if( $key % 2 == 0 ) cm_comment_grey @endif">
                                                    <div class="cm_comment_author_date">
                                                        @php $extension = $share->extension->last(); @endphp
                                                        <b>{{$extension->requested_by_user->name}}</b> requested extension {{$extension->created_at->diffForHumans()}}
                                                        <span class="float-right">{{$extension->created_at->format(config('app.dateFormat'))}} {{$extension->created_at->format(config('app.timeFormat'))}}</span>
                                                    </div>
                                                    <div class="cm_comment_comment">
                                                        Extension Request until <b>{{$extension->extension_time->format(config('app.dateFormat'))}} {{$extension->extension_time->format(config('app.timeFormat'))}}</b> (additional {{$extension->extension_time->diffForHumans()}})
                                                    </div>
                                                    <div class="cm_comment_comment">
                                                        {!! $extension->note !!}
                                                    </div>
                                                    <div class="cm_comment_people">
                                                        <a href="#" class="badge badge-info" data-bs-toggle="modal" data-bs-target="#accept_extension_{{$extension->id}}">Approve</a>
                                                        <a href="#" class="badge bg-danger" data-bs-toggle="modal" data-bs-target="#reject_extension_{{$extension->id}}">Deny</a>
                                                        @include('head_office.case_manager.notes.accept_extension', ['share' => $share, 'extension' => $extension])
                                                        @include('head_office.case_manager.notes.reject_extension', ['share' => $share, 'extension' => $extension])
                                                    </div>
                                                </div>
                                            @endif
                                            @foreach($share->communications as $key=> $comment)
                                                @include('head_office.case_manager.notes.shared_case.view_comments', compact('comment'))
                                            @endforeach
                                            <div class="cm_new_comment">
                                                @include('head_office.case_manager.notes.shared_case.form_comment', ['case' => $case, 'shared_case' => $share])
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                @include('head_office.case_manager.notes.edit_duration', ['share' => $share])
                            </div>
                            


                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>



@section('styles')

<link rel="stylesheet" href="{{asset('tribute/tribute.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection

@section('scripts')
<script src="{{asset('admin_assets/js/view_case.js')}}"></script>
<script src="{{asset('admin_assets/js/form-template.js')}}"></script>
<script>
    // Show on hover
    $('.seen-btn').on('mouseenter', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const main = $(this).closest('.users-seen-main');
        main.find('.users-seen-inner').fadeIn();
    });
    
    $('.users-seen-main').on('mouseleave', function(e) {
        const inner = $(this).find('.users-seen-inner');
        
        // Check if cursor has left both .seen-btn and .users-seen-inner
        if (!$(e.relatedTarget).closest('.users-seen-inner, .seen-btn').length) {
            inner.fadeOut();
        }
    });
    
    $('.users-seen-inner').on('mouseleave', function(e) {
        // Hide the inner content only if cursor is outside the parent and button
        if (!$(e.relatedTarget).closest('.users-seen-main').length) {
            $(this).fadeOut();
        }
    });
    $('.seen-btn').on('click',function(e){
        e.preventDefault();
        e.stopPropagation();
        const main = $(this).closest('.users-seen-main');
        main.find('.users-seen-inner').fadeToggle();
    })
    $('.user-seen-backdrop').on('click',function(e){
        e.preventDefault();
        e.stopPropagation();
        $('.users-seen-inner').fadeOut();
    })
    $(document).on( "click", ".delete_share_case", function(e) {
        e.preventDefault();
        let href= $(this).attr('href');
        
        let msg = $(this).data('msg');
        alertify.defaults.glossary.title = 'Alert!';
        alertify.confirm("Are you sure?", msg,
        function(){
            window.location.href= href;
        },function(i){
            console.log(i);
        });
    });
    $(document).on( "click", ".revoke_access_share_case", function(e) {
        e.preventDefault();
        let href= $(this).attr('href');
        
        let msg = $(this).data('msg');
        alertify.defaults.glossary.title = 'Alert!';
        alertify.confirm("Are you sure?", msg,
        function(){
            window.location.href= href;
        },function(i){
            console.log(i);
        });
    });
    $(document).ready(function (){
            loadActiveTab();
        });

        function loadActiveTab(tab = null)
        {
            if(tab == null){
                tab = window.location.hash;
            } 
            console.log(tab);
            $('.nav-tabs a[data-bs-target="' + tab + '"]').tab('show');
        }
</script>
@endsection

@endsection