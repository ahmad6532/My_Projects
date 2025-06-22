@extends('layouts.head_office_app')
@section('title', 'Case '.$case->id())

@section('sub-header')
@include('head_office.case_manager.notes.sub-header')
@endsection

@section('content')


@php 
if($case->stages->count())
    $per = 100/$case->stages->count();
else {
    $per = 0;
}
@endphp
@php 
$eligible_users = [];
$all_users = [];

// Use eager loading to optimize related data retrieval
$type_cases = $case->case_head_office->cases()
    ->where('incident_type', $case->incident_type)
    ->with([
        'case_handlers.case_head_office_user.user.position',
        'case_handlers.case_head_office_user.head_office_user_cases.case.link_case_with_form.form'
    ])->get();

foreach ($type_cases as $type_case) {
    if(isset($type_case->link_case_with_form->form)){
        $current_form_name = $type_case->link_case_with_form->form->name;
    
        foreach ($type_case->case_handlers as $case_handler) {
            $user = $case_handler->case_head_office_user->user;
    
            // Initialize user data if not already set
            if (!isset($eligible_users[$user->id])) {
                $eligible_users[$user->id] = [
                    'name' => "{$user->name} {$user->position->name}",
                    'current_case_type' => $current_form_name,
                    'other_case_types' => []
                ];
            }
    
            // Collect unique form names the user is involved in
            $other_case_types = $case_handler->case_head_office_user->head_office_user_cases
                ->pluck('case.link_case_with_form.form.name')
                ->filter(fn($form_name) => $form_name !== $current_form_name)
                ->unique()
                ->toArray();
    
            // Merge unique case types
            $eligible_users[$user->id]['other_case_types'] = array_unique(
                array_merge($eligible_users[$user->id]['other_case_types'], $other_case_types)
            );
        }
    }
}

// Prepare all users for the current head office
$all_users_raw = $case->case_head_office->users
    ->pluck('user')
    ->mapWithKeys(fn($u) => [$u->id => "{$u->name} {$u->position->name}"])
    ->toArray();
// Convert to JSON for output
$eligible_user_raw = $eligible_users;
$eligible_user = json_encode($eligible_users);
$all_users = json_encode($all_users_raw);

@endphp
<style>
    .min-height
    {
        min-height: {{$per}}%;
    }
    .fix-text{
    position: absolute;
    left: 50%;
    width: fit-content;
    top: 50%;
    font-weight: bold;
    white-space: nowrap;
    transform: translate(-50%, -50%) rotate(-90deg);
    color: black;
    }
    .text-success{
        color: rgb(0,205,69)!important;
    }
    .bg-success{
        background: rgb(0,205,69)!important;
    }
    #searchInput::placeholder{
        color: #D5D5D5 !important;
    }
</style>
<!-- <div id="content">
    <div class="row mt-4 content_widthout_sidebar">
    
    @include('layouts.error')
    <div class="col-1 pl-4 cm_vertical_sidebar_column">
        @foreach ($case->stages as $stage)
    <div class="progress progress-bar-vertical min-height text-center" style="position: relative;">
        @if($stage->percentComplete() == 0)
        <div class="fix-text">
            {{$stage->name}}
        </div>
        @endif
        <div class="progress-bar" style="height: {{$stage->percentComplete()}}%; background-color: rgb(0,205,69);">
            <div class="fix-text">
                {{$stage->name}}
            </div>
        </div>
    </div>
@endforeach


        
        
    </div> -->


    <div class="modal fade" id="archiveModal" tabindex="-1" role="dialog" aria-labelledby="archiveModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
           <form method="get" action="{{ route('case_manager.archive_case', ['id' => $case->id]) }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="archiveModalLabel">Archive Cases</h5>
                <button type="button" class="close" style="position:absolute; top:4px; right:4px;"
                data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
              </div>
              <div class="modal-body">
                  <label style="margin: 0;font-size: 12px;" for="password">Password</label>
                  <input type="password" id="password" name="password" placeholder="Password"
                      class="form-control" style="height:50px" required>
                  <label style="margin: 0;font-size: 12px;" for="reason">Reason</label>
                  <input spellcheck="true" type="text" id="reason" name="reason" placeholder="Reason"
                      class="form-control" style="height:50px" required>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn primary-btn" >Archive</button>
              </div>
           </form>
          </div>
        </div>
    </div>

    <div class="modal fade" id="unarchiveModal" tabindex="-1" role="dialog" aria-labelledby="archiveModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form method="get" action="{{ route('case_manager.archive_case', ['id' => $case->id]) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="archiveModalLabel">UnArchive Cases</h5>
                    <button type="button" class="close" style="position:absolute; top:4px; right:4px;" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label style="margin: 0;font-size: 12px;" for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" class="form-control" style="height:50px" required>
                    <label style="margin: 0;font-size: 12px;" for="reason">Reason</label>
                    <input spellcheck="true" type="text" id="reason" name="reason" placeholder="Reason" class="form-control" style="height:50px" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn primary-btn" >Unarchive</button>
                </div>
            </form>
            
          </div>
        </div>
    </div>

    <div class="d-flex justify-content-between gap-4 w-100">
        <div class="comments" style="width: 55%">
            <div class="card card-qi">
                <div class="card-body pt-0">
                    <!-- <nav class="nav-h-bordered">
                        @if ($case->isArchived == true)
                            <span class="badge" style="background-color:blue;">Archived</span>
                        @endif
                        @if($case->status == "closed")
                        {{-- <a href="#" class="active"><span class="item_with_border">Case Closed</span></a> --}}
                        {{-- @elseif($case->status == "waiting")
                        <a href="#" class="active"><span class="item_with_border">Waiting for final clouser
                                approval</span></a> --}}
                        @php
                        $check = false;
                        $interested_party =
                        $case->case_interested_parties()->where('tag','final_clouser_approval')->first();
                        if($interested_party && ($interested_party->case_head_office_user->user->id ==
                        Auth::guard('web')->user()->id))
                        $check = true;
                        @endphp
                        @if($check)
                        <div class="btn-group right">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#accept_case_approval_request"
                                class="btn btn-info" style="color: #fff !important">Accept</a>
                            <a href="javascript:void(0);" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#reject_case_approval_request" style="color: #fff !important">Reject</a>
                        </div>
                        @endif
                        @endif
                    </nav> -->
                    <form method="get" class="form print-display-none w-85 inline-block">
                        <div class="input-group form-group mb-3 mt-0">
                            <input spellcheck="true" id="searchInput" type="text" class="form-control search-nearmiss shadow-none" name="search"
                                @if(request()->query('search')) value="{{request()->query('search')}}" @endif placeholder="Search..">
                            {{-- <button type="submit" class="btn btn-info search_button mb-0"><i class="fa fa-search"></i></button> --}}
                        </div>
                    </form>
    
                    <button class="dropdown-toggle inline-block primary-btn" type="button" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>
                    <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
    
    
    
                        @if($case->status == 'open' && $case->isArchived == false)
                        <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#close_case_comment"
                            @if($case->case_closed) style="display:none" @endif onclick="resetFrom()" title="Close
                            Case">
                            Close Case
                        </a>
                        @endif
                        @if($case->status== 'open' && $case->isArchived == false)
                        {{-- <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#share_case"
                            @if($case->case_closed) style="display:none" @endif onclick="resetFrom()" title="Close
                            Case">Share Case</a> --}}
                        <a href="{{route('head_office.share_case.share_case_view',$case->id)}}" class="dropdown-item"
                            title="Securely share the form with external people" data-toggle="tooltip" data-placement="top" >Share Case <img class="mb-1" src="{{asset('images/lock-01.svg')}}" alt="lock-01" width="18"></a>
                        <a class="dropdown-item" href="{{route('case_manager.request_information',$case->id)}}" title="Request sensitive information securely from people" data-toggle="tooltip" data-placement="left" > Request Information <img class="mb-1" src="{{asset('images/lock-01.svg')}}" alt="lock-01" width="18"></a>
                        @if($case->getCanShareCaseResponsibilityAttribute())
                        @if($case->status == 'open')
                        <a href="#" data-bs-toggle="modal" data-bs-target="#add_interested_parties" class="dropdown-item">Add Case Investigator</a>
                        @endif
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#share_case_responsibility_id"
                            class="dropdown-item">Share Case Responsibilty</a>
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#transfer_case_responsibility"
                            class="dropdown-item">Transfer Case Responsibilty</a>
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#remove_case_handler_any"
                            class="dropdown-item">Remove user from case</a>
                        @endif
                        @endif
                        @if ($case->isArchived == false)
                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#archiveModal" href="">Archive</a>
                        @else
                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#unarchiveModal" >Unarchive</a>
                        @endif
    
                    </div>
                    @include('head_office.case_manager.notes.add_interested_party',['interested_party' =>null ,'case' =>
                    $case])
                    
                    @if($case->can_share_case_responsibility)
                    <form method="post"
      action="{{$case->can_share_case_responsibility ?route('case_manager.share_case_responsibity',[$case->can_share_case_responsibility->id, $case->id]) : '#' }}"
      class="cm_task_form">

    @csrf
    <div class="modal fade file_upload_model" id="share_case_responsibility_id" tabindex="-1"
         role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        <p class="text">Share Case Responsibility</p>
                    </h4>
                    <button type="button" class="btn-close float-right" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        @if(count($all_users_raw) > 0)
                            <table class="table new-table mx-auto dataTable w-100">
                                <thead class="text-center">
                                    <tr>
                                        <th class="text-center">Users</th>
                                        <th class="text-center">Can Manage</th>
                                    </tr>
                                </thead>
                                <tbody class="all_locations ">
                                    @foreach($all_users_raw as $key => $user_raw)
                                        @php
                                            $isEligible = isset($eligible_user_raw[$key]);
                                            $userData = $isEligible ? $eligible_user_raw[$key] : ['name' => $user_raw, 'current_case_type' => 'N/A', 'other_case_types' => []];
                                        @endphp

                                        <tr style="{{$isEligible ? '' : 'display:none'}}" class="{{$isEligible ? '' : 'eligible-hidden'}}">
                                            <td class="fw-semibold email">
                                                <input type="checkbox" name="caseHandalers[]" value="{{ $key }}">
                                                {{ $userData['name'] }}
                                            </td>
                                            <td>
                                                @if ($isEligible)
                                                <div style="text-align: left !important;">
                                                        <p class="m-0 fw-semibold" style="color: #249b91;">
                                                            {{ $userData['current_case_type'] }}
                                                        </p>
                                                        @foreach ($userData['other_case_types'] as $other_case_type)
                                                            <p class="m-0">{{ $other_case_type }}</p>
                                                        @endforeach
                                                </div>        
                                                    @else
                                                <div style="text-align: center !important;">
                                                    <p class="m-0">-</p>

                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        @else
                            <p class="text-center">There are no users who have been assigned this type of case to manage.</p>
                        @endif
                    </div>

                    <div class="text-center mt-3">
                        <button type="button" class="showAllUsersBtn"style="background-color: transparent; color: rgb(37, 150, 190); border: none; padding: 0; text-decoration: none; cursor: pointer; font-size: 14px;">
                            Show All Users
                        </button>
                    </div>
                    

                    <div class="form-group mt-3">
                        <label for="note">Add note to case</label>
                        <textarea spellcheck="true" name="note" class="form-control spellcheck"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="button" class="btn btn-outline-secondary"
                            onclick="this.form.reset();" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


                    <form method="post"
                        action="{{route('case_manager.transfer_case_responsibity',[$case->can_share_case_responsibility->id,$case->id])}}"
                        class="cm_task_form">
                        @csrf
                        <div class="modal fade file_upload_model" id="transfer_case_responsibility" tabindex="-1"
                            role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header text-center">
                                        <h4 class="modal-title text-info w-100">
                                            <p class="text-success"><i class="fa fa-tasks fa-2x"></i></p>Transfer Case Responsibility
                                        </h4>
                                        <button type="button" class="btn-close float-right" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true"></span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div>
                                            @if(count($all_users_raw) > 0)
                                                <table class="table new-table mx-auto dataTable w-100">
                                                    <thead class="text-center">
                                                        <tr>
                                                            <th class="text-center">Users</th>
                                                            <th class="text-center">Can Manage</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="all_locations ">
                                                        @foreach($all_users_raw as $key => $user_raw)
                                                            @php
                                                                $isEligible = isset($eligible_user_raw[$key]);
                                                                $userData = $isEligible ? $eligible_user_raw[$key] : ['name' => $user_raw, 'current_case_type' => 'N/A', 'other_case_types' => []];
                                                            @endphp
                    
                                                            <tr style="{{$isEligible ? '' : 'display:none'}}" class="{{$isEligible ? '' : 'eligible-hidden'}}">
                                                                <td class="fw-semibold email">
                                                                    <input type="checkbox" name="caseHandalers[]" value="{{ $key }}">
                                                                    {{ $userData['name'] }}
                                                                </td>
                                                                <td>
                                                                    <div style="text-align: left !important;">
                                                                        @if ($isEligible)
                                                                            <p class="m-0 fw-semibold" style="color: #249b91;">
                                                                                {{ $userData['current_case_type'] }}
                                                                            </p>
                                                                            @foreach ($userData['other_case_types'] as $other_case_type)
                                                                                <p class="m-0">{{ $other_case_type }}</p>
                                                                            @endforeach
                                                                            @else
                                                                            <div style="text-align: center !important";>
                                                                                <p class="m-0">-</p>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                    
                                                    </tbody>
                                                </table>
                                            @else
                                                <p class="text-center">There are no users who have been assigned this type of case to manage.</p>
                                            @endif
                                        </div>
                    
                                        <div class="text-center mt-3">
                                            <button type="button" class="showAllUsersBtn"style="background-color: transparent; color: rgb(37, 150, 190); border: none; padding: 0; text-decoration: none; cursor: pointer; font-size: 14px;">
                                                Show All Users
                                            </button>
                                        </div>
                                            {{-- <input type="checkbox" class="transfer_eligible_users_only"  name="transfer_eligible_users_only"  id="">
                                        <div class="form-group">
                                            <label>Users</label>
                                            <select type="text" name="head_office_user_id" style="width: 100%"
                                                class="form-control transfer_head_office_user_id" required>
                                                @foreach($case->case_head_office->users as $u)
                                                @if(!in_array($case->can_share_case_responsibility->head_office_user_id,$case->case_handler_ids))
                                                <option value="{{$u->user->id}}">{{$u->user->name}}
                                                    ({{$u->user->position->name}})
                                                </option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div> --}}
                                        <div class="form-group">
                                            <label for="note">Add note to case</label>
                                            <textarea spellcheck="true"  spellcheck="true" name="note"  class="form-control spellcheck"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="btn-group right">
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="this.form.reset();" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-info">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    @endif
                    <div class="cm_comments_wrapper relative scrollbar_custom_green">
                        @if(!count($comments))
                        <p class="font-italic" style="height: 70%;">No comments are found!</p>
                        @else
                        <div id="contextMenu" class="context-menu" style="display: none">
                            <ul class="menu_comment_link">
                                <li class="share"><a href="#" onclick="copy_to_clipboard(this)" id="t_link_item"
                                        data-bs-link=""><i class="fa fa-copy" aria-hidden="true"></i> Copy Tracking Link</a>
                                </li>
                                <li class="share"><a href="#" onclick="copy_to_clipboard(this)" id="o_link_item"
                                        data-bs-link=""><i class="fa fa-copy" aria-hidden="true"></i> Copy Original Link</a>
                                </li>
                            </ul>
                        </div>
                        @endif
                        @foreach($comments as $key=> $comment)
                        @include('head_office.case_manager.notes.view_comments',compact('comment'))
                        @endforeach
                        <div class="cm_new_comment ">
                            <!-- <p>Add New Comment</p> -->
                            @if($case->status == 'open' && $case->isArchived == 0)
                            @include('head_office.case_manager.notes.form_comment',['comment'=>null,'parent'=>null,'remove_backdrop'=>true])
                            @endif
                        </div>
                    </div>
                </div>
            </div>
                        @if($case->status == "closed")
                        <div style="text-align: center; margin: 20px 0;">
                            <p style="display: inline-block; 
                                      color: #000; 
                                      font-size: 24px; 
                                      padding: 15px 20px; 
                                      text-align: center; 
                                      border-radius: 8px; 
                                      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); 
                                      transition: transform 0.2s;">
                                This case is closed
                            </p>
                        </div>
                        
                        @elseif($case->status == "waiting")
                        @endif
        </div>
        <div class="" style="width: 45%;">
            @include('head_office.case_manager.notes.view_notes_column_3',['case' => $case])
        </div>
    </div>
</div>

<form method="post"
    action="{{route('case_manager.remove_case_handler',[$case->id])}}"
    class="cm_task_form">
    @csrf
    <div class="modal fade file_upload_model" id="remove_case_handler" tabindex="-1"
        role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        Remove Case Handler
                    </h4>
                    <button type="button" class="btn-close float-right" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <table  class="table new-table table-responsive table-bordered mx-auto dataTable w-100 new-table" >
                            <thead class="text-center">
                                <tr>
                                    <th class="text-center">Case Handlers</th>
                                    <th class="text-center">Case Type</th>
                                </tr>
                            </thead>
                            <tbody class="all_locations text-center">
                                @foreach($eligible_user_raw as $user_raw)
                                
                                <tr>
                                    <td class="fw-semibold email">{{$user_raw['name']}}</td>
                                        <td class=" ">
                                            <div>
                                                <p class="m-0 fw-semibold" style="color: #249b91;">{{$user_raw['current_case_type']}}</p>
                                                @foreach ($user_raw['other_case_types'] as $other_case_type )
                                                    <p class="m-0">{{$other_case_type}}</p>
                                                @endforeach
                                            </div>
                                        </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="">
                        <label>Case Handler</label>
                        <input  disabled type="text"  style="width: 100%"
                            class="form-control selected_case_handler" required />
                        <input hidden  type="text" name="case_handler_id" style="width: 100%"
                            class="form-control selected_case_handler_id" required />
                    </div>

                    <div class="d-flex align-items-center gap-1 mt-2">
                        <label for="transfer_eligible_users_only">Show all user</label>
                        <input type="checkbox" class="transfer_eligible_users_only"  name="transfer_eligible_users_only"  id="">
                    </div>
                    <div class="mt-2">
                        <label>Other Case Handler</label>
                        <select type="text" name="other_head_office_user_id" style="width: 100%"
                            class="form-control transfer_head_office_user_id" required>
                            @foreach($case->case_head_office->users as $u)
                                @unless($case->case_handlers->contains('head_office_user_id',$u->user->id))
                                    <option value="{{$u->user->id}}">{{$u->user->name}}
                                        ({{$u->user->position->name}})
                                    </option>
                                @endunless
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-2">
                        <label for="note">Note (Optional)</label>
                        <textarea spellcheck="true"  spellcheck="true" name="note"  class="form-control spellcheck"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="button" class="btn btn-outline-secondary"
                            onclick="this.form.reset();" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@php
    $case_user_ids = $case->case_handlers->map(function ($handler) {
    return $handler->case_head_office_user->user->id;
})->toArray();
$case_user_ids_json = json_encode($case_user_ids);

@endphp
<input type="hidden" id="case_handlers_user_ids" data-ids="{{$case_user_ids_json}}">
<form method="post"
    action="{{route('case_manager.remove_any_case_handler',[$case->id])}}"
    class="cm_task_form">
    @csrf
    <input type="hidden" hidden name="users"  id="users_to_remove"/>
    <div class="modal fade file_upload_model" id="remove_case_handler_any" tabindex="-1"
        role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        Remove Any User
                    </h4>
                    <button type="button" class="btn-close float-right" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <table  class="table new-table table-responsive table-bordered mx-auto dataTable w-100 new-table" >
                            <thead class="text-center">
                                <tr>
                                    <th class="text-center">Case Investigators</th>
                                    <th class="text-center">Case Type</th>
                                </tr>
                            </thead>
                            <tbody class="all_locations text-center">
                                @foreach($eligible_user_raw as $key =>$user_raw)
                                
                                <tr>
                                    <td class="fw-semibold email">
                                        <input type="checkbox" class="select-user" value="{{ $key }}">
                                        {{$user_raw['name']}}
                                        @foreach($case->case_handlers as $u)
                                            @if($u->case_head_office_user->user->id == $key)
                                                <span class="badge bg-info" style="font-size: 10px">Case handler</span>
                                            @endif
                                        @endforeach
                                    </td>
                                        <td class=" ">
                                            <div>
                                                <p class="m-0 fw-semibold" style="color: #249b91;">{{$user_raw['current_case_type']}}</p>
                                                @foreach ($user_raw['other_case_types'] as $other_case_type )
                                                    <p class="m-0">{{$other_case_type}}</p>
                                                @endforeach
                                            </div>
                                        </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(count($case->case_handlers) < 2)
                    <div class="mt-2" id="other_case_handler" style="display: none;">
                        <label>Users</label>
                        <select type="text" name="other_head_office_user_id" style="width: 100%"
                            class="form-control transfer_head_office_user_id shadow-none" required>
                            <option value="0" selected>Select Case Handler</option>
                            @foreach($case->case_head_office->users as $u)
                                @unless($case->case_handlers->contains('head_office_user_id',$u->user->id))
                                    <option value="{{$u->user->id}}">{{$u->user->name}}
                                        ({{$u->user->position->name}})
                                    </option>
                                @endunless
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="mt-2">
                        <label for="note">Note (Optional)</label>
                        <textarea spellcheck="true"  spellcheck="true" name="note"  class="form-control spellcheck"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="button" class="btn btn-outline-secondary"
                            onclick="this.form.reset();" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@if(session('error_owner'))
<div class="modal fade " id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Remove Owner</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            Do you want to remove yourself as a case handler?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <a  href="{{route('case_manager.remove_owner',['case_id'=>$case->id,'_token'=>csrf_token()])}}" class="btn btn-primary">Confirm</a>
        </div>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function(){
        $('#staticBackdrop').modal('show');
    })
  </script>
@endif
</div>
<div class="modal fade" id="close_case_comment" tabindex="-1" @if(isset($remove_backdrop)) data-bs-backdrop="false" @endif
    role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title text-info w-100">
                    {{-- <p class="text-success"><i class="fa fa-paperclip fa-flip-horizontal fa-3x"></i></p>
                    --}}
                    Close Case
                </h4>
                <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" @if($case->requires_final_approval && $case->status == 'waiting')
                    action="{{route('case_manager.view.close_case',[$case->id,0])}}"
                    @elseif($case->requires_final_approval && $case->status == 'open')
                    action="{{route('case_manager.view.close_case',[$case->id,1])}}" @else
                    action="{{route('case_manager.view.close_case',[$case->id,0])}}" @endif id="reset_form">
                    <div class="new_link_wrapper">
                        @csrf
                        <!-- <input type="hidden" name="links[]" value=""> -->
                        <span class="inline-block links relative">Please provide reason to close case</span>
                        {{-- <p class="inline-block cm_comment_box case-close-textarea-border" name="close_comment" required
                            placeholder="Type notes here" contenteditable="true">
                            </p> --}}
                        <textarea spellcheck="true"  spellcheck="true" required placeholder="Type notes here" class="inline-block cm_comment_box case-close-textarea-border spellcheck"  rows="3" 
                            name="close_comment">@if(isset($edit_comment)){!!strip_tags($comment->comment,$comment->allowedHtmlTags()) !!}@endif</textarea>
                        @if($case->status == 'open' && $case->requires_final_approval)
                        <span>
                            Once closed, the case will be moved for final closure approval
                        </span>
                        @endif
                        <br />
                        <div class="uploaded_files mt-2 mb-2"></div>
                        <h6 class="text-info">Select documents/images to upload</h6>
                        <div class="cm_upload_box_with_model center">
                            <i class="fa fa-cloud-upload-alt" style="font-size:48px"></i><br>Drop files here
                        </div>
                        <input type="file" name="file" multiple value="" class="form-control commentMultipleFiles">
                        @if (isset($case->getReporter()->id) && $case->getReporter()->email != 'external@qitech.com' && isset($case->location->id) && $case->location->email != 'external@qitech.com')
                        <h4 class="text-info h5 my-2 mt-3">Provide Feedback</h4>
                        @endif
                        @if(isset($case->getReporter()->id) && $case->getReporter()->email != 'external@qitech.com')
                        <div>
                            <input type="checkbox" id="send_feedback_to_reporter_user_input" name="send_feedback_to_reporter_user"  onchange="show_reporter_user(this)">
                            <label for="send_feedback_to_reporter_user_input" class="fw-semibold m-0">To the Reporter</label>
                            <div style="display: none;" class="mb-2" id="send_feedback_to_reporter_user">
                                <select type="text" readonly class="form-select" id="user_feedback_input" >
                                    <option value="{{$case->getReporter()->id ?? 0}}">{{ isset($case->getReporter()->id) && ($case->getReporter()->email == 'external@qitech.com' ? 'External User' : $case->getReporter()->name) }}</option>
                                </select>
                                <div class="">
                                    <label for="feedback my-4">Feedback
                                    </label>
                                    <textarea spellcheck="true"  spellcheck="true" name="feedback_user" id="" class="form-control tinymce"></textarea>
                                </div>
    
                            </div>
                        </div>
                        @endif
                        @if (
    isset($case->location->id) && 
    $case->location->email != 'external@qitech.com' && 
    (!isset($case->link_case_with_form) || !$case->link_case_with_form->hide)
)
                            <div>
                                <input type="checkbox" id="send_feedback_to_reporter_input" name="send_feedback_to_reporter"  onchange="show_reporter(this)">
                                <label for="send_feedback_to_reporter_input" class="fw-semibold m-0">To the Location</label>
                                <div style="display: none;"  id="send_feedback_to_reporter">
                                    
                                    <input type="text" readonly class="form-control" value="{{isset($case) ? $case->location->name() : ''}}">
                                    <div class="form-gro">
                                        <label for="feedback my-4">Feedback</label>
                                        <textarea spellcheck="true"  spellcheck="true" name="feedback" id="" class="form-control tinymce"></textarea>
                                    </div>
        
                                </div>
                            </div>
                        @endif
                    </div>
                    <br>
                    <div class="from-group">

                    </div>
                    @if($case->requires_final_approval && $case->status == 'waiting')
                    <button type="submit" name="submit"
                        class="btn btn-info btn-submit inline-block mb-0">Accept</button>

                    @else
                    <button type="submit" name="submit" class="btn btn-info btn-submit inline-block mb-0"><i
                            class="fa fa-location-arrow"></i> </button>
                    @endif
            </div>
            </form>
        </div>
    </div>
</div>

@if($case->requires_final_approval && $case->status == 'waiting')
<div class="modal fade" id="accept_case_approval_request" tabindex="-1" @if(isset($remove_backdrop))
    data-bs-backdrop="false" @endif role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title text-info w-100">
                    {{-- <p class="text-success"><i class="fa fa-paperclip fa-flip-horizontal fa-3x"></i></p>
                    --}}
                    Accept Request
                </h4>
                <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('case_manager.view.accept_case_close_request',$case->id)}}"
                    id="reset_form">
                    <div class="new_link_wrapper">
                        @csrf
                        <!-- <input type="hidden" name="links[]" value=""> -->
                        <span class="inline-block links relative">Please provide reason for acceptance</span>
                        <p class="inline-block cm_comment_box case-close-textarea-border spellcheck" name="close_comment" required
                            placeholder="Type notes here" contenteditable="true"></p>
                        <textarea spellcheck="true"  spellcheck="true" required style="display:none" rows="3" class="cm_comment_box_hidden"
                            name="close_comment"></textarea>
                        <br />
                        <div class="uploaded_files mt-2 mb-2"></div>
                        <h6 class="text-info">Select documents/images to upload</h6>
                        <div class="cm_upload_box_with_model center">
                            <i class="fa fa-cloud-upload-alt" style="font-size:48px"></i><br>Drop files here
                        </div>
                        <input type="file" name="file" multiple value="" class="form-control commentMultipleFiles">
                    </div>
                    <br>
                    <button type="submit" name="submit" class="btn btn-info btn-submit inline-block mb-0"><i
                            class="fa fa-location-arrow"></i> </button>

            </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="reject_case_approval_request" tabindex="-1" @if(isset($remove_backdrop))
    data-bs-backdrop="false" @endif role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title text-info w-100">
                    {{-- <p class="text-success"><i class="fa fa-paperclip fa-flip-horizontal fa-3x"></i></p>
                    --}}
                    Reject Request
                </h4>
                <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('case_manager.view.reject_case_close_request',$case->id)}}"
                    id="reset_form">
                    <div class="new_link_wrapper" style="justify-content: center;">
                        @csrf
                        <!-- <input type="hidden" name="links[]" value=""> -->
                        <span class="inline-block links relative">Please provide reason for rejection</span>
                        <p class="inline-block cm_comment_box case-close-textarea-border spellcheck" name="close_comment" required
                            placeholder="Type notes here" contenteditable="true"></p>
                        <textarea spellcheck="true"  spellcheck="true" style="display:none" rows="3" class="cm_comment_box_hidden"
                            name="close_comment"></textarea>
                        <br />
                        <div class="uploaded_files mt-2 mb-2"></div>
                        <h6 class="text-info">Select documents/images to upload</h6>
                        <div class="cm_upload_box_with_model center">
                            <i class="fa fa-cloud-upload-alt" style="font-size:48px"></i><br>Drop files here
                        </div>
                        <input type="file" name="file" multiple value="" class="form-control commentMultipleFiles">
                    </div>
                    <br>
                    <button type="submit" name="submit" class="btn btn-info btn-submit inline-block mb-0"><i
                            class="fa fa-location-arrow"></i> </button>

            </div>
            </form>
        </div>
    </div>
</div>


@endif

<form method="POST" action="{{route('case_manager.assign_task_user')}}">
    @csrf
    <div class="modal fade file_upload_model" id="task_assign_user" tabindex="-1"
             role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title text-info w-100">
                            <p class="text">Assign User</p>
                        </h4>
                        <button type="button" class="btn-close float-right" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <input type="hidden" id="selected_task_id" name="selected_task_id">
                            @if(count($case->getAllInvolvedUsers()) > 0)
                                <table class="table new-table mx-auto dataTable w-100">
                                    <thead class="text-center">
                                        <tr>
                                            <th class="text-center">Users</th>
                                            <th class="text-center">Position</th>
                                        </tr>
                                    </thead>
                                    <tbody class="all_locations ">
                                        @foreach ($case->getAllInvolvedUsers() as $case_ho_user )
                                            <tr class="text-center">
                                                <td>
                                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                                        <input type="checkbox" name="assigned[]" value="{{ $case_ho_user->id }}">
                                                        {{$case_ho_user->user->name}}
                                                    </div>
                                                </td>
                                                <td>{{$case_ho_user->position}}</td>
                                            </tr>
                                        @endforeach
                                            <tr class="eligible-hidden" style="display: none;">
                                                <td colspan="2">
                                                    <p class="m-0">Assign to</p>
                                                    <select type="text" name="assigned[]" style="width: 100%" class=" select_2_modal" 
                                                        multiple>
                                                        @foreach($head_office_users as $u)
                                                            @isset($u->user)
                                                                <option @if(isset($task) && $task->hasAssignedUser($u->id)) selected @endif
                                                                    
                                                                    value="{{$u->id}}">{{$u->user->name}}</option>
                                                                
                                                            @endisset
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                    </tbody>
                                </table>
                            @else
                                <p class="text-center">There are no users who have been assigned this type of case to manage.</p>
                            @endif
                        </div>
    
                        <div class="text-center mt-3">
                            <button type="button" class="showAllUsersBtn"style="background-color: transparent; color: rgb(37, 150, 190); border: none; padding: 0; text-decoration: none; cursor: pointer; font-size: 14px;">
                                Show All Users
                            </button>
                        </div>
                        
    
                        {{-- <div class="form-group mt-3">
                            <label for="note">Add note to case</label>
                            <textarea spellcheck="true" name="note" class="form-control spellcheck"></textarea>
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group right">
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="this.form.reset();" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info">Save</button>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</form>







<input type="hidden" value="{{$eligible_user}}" id="eligible_user_only">
<input type="hidden" value="{{$all_users}}" id="all_user_only">

@endsection
@section('styles')

<link rel="stylesheet" href="{{asset('tribute/tribute.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script>

    window.addEventListener('DOMContentLoaded', (event) => {
        document.onclick = hideMenu; 
        $(document).ready(function() {
            $('.select_2_custom').select2(
                @if(1) //$case->link_case_with_form->form->is_allow_non_approved_emails
                {
                tags: true,
                }
            @endif
            );
            $(".select_2_modal").select2();
        });
    });
    
    function show_reporter(element)
    {
        if($(element).is(':checked'))
        {
            $('#send_feedback_to_reporter').show();
        }
        else{
            $('#send_feedback_to_reporter').hide();
        }

    }
    
</script>
<style>
    .context-menu {
        position: absolute;
        z-index: 2;
    }

    .context-menu ul {
        list-style: none;
    }

    .menu_comment_link {
        display: flex;
        flex-direction: column;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 10px 20px rgb(64 64 64 / 5%);
        padding: 10px 0;
    }

    .menu_comment_link>li>a {
        font: inherit;
        border: 0;
        padding: 10px 30px 10px 15px;
        width: 100%;
        display: flex;
        align-items: center;
        position: relative;
        text-decoration: unset;
        color: #000;
        font-weight: 500;
        transition: 0.5s linear;
        -webkit-transition: 0.5s linear;
        -moz-transition: 0.5s linear;
        -ms-transition: 0.5s linear;
        -o-transition: 0.5s linear;
    }

    .menu_comment_link>li>a:hover {
        background: #f1f3f7;
        color: #4b00ff;
    }

    .menu_comment_link>li>a>i {
        padding-right: 10px;
    }

    .menu_comment_link>li.trash>a:hover {
        color: red;
    }
    
    .custom-scroll-vertical::-webkit-scrollbar{
    height: 5px !important;
    transition: 0.2s ease-in-out;
    }
.custom-scroll-vertical::-webkit-scrollbar-thumb{
    border-radius: 15px;
    background: #249b91;
}
.custom-scroll-vertical::-webkit-scrollbar-track {
    box-shadow: inset 0 0 5px #249b9120; 
    border-radius: 10px;
  }

</style>
@endsection

@section('scripts')
<script src="{{asset('admin_assets/js/view_case.js')}}"></script>
<script src="{{asset('tribute/tribute.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('admin_assets/speech-to-text.js')}}"></script>
<script src="{{asset('admin_assets/js/form-template.js')}}"></script>
<script>

$('#searchInput').on('input', function() {
        var searchValue = $(this).val().toLowerCase(); // Get the input and convert it to lowercase

        // Loop through each comment and filter based on the search value
        $('.cm_comments_wrapper .cm_comment').each(function() {
            var authorText = $(this).find('.cm_comment_author_date').text().toLowerCase(); // Get author and date text
            var commentText = $(this).find('.cm_comment_comment').text().toLowerCase(); // Get comment text

            // Combine author/date and comment text for search
            var combinedText = authorText + ' ' + commentText;

            if (combinedText.includes(searchValue)) {
                $(this).show(); // Show comment if it matches the search
            } else {
                $(this).hide(); // Hide comment if it doesn't match the search
            }
        });
    });

    $(document).on( "click", ".delete_party", function(e) {
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
    $('[data-toggle="tooltip"]').tooltip( {container: 'body', placement: 'right'} )

    $('.is_remind_me').on('click',function(){
    console.log('sa')
    $('.notify-wrap').slideToggle();
})

    function setRandomBackgroundColor(elementId) {
            var randomColor = '#' + Math.floor(Math.random() * 16777215).toString(16);
            console.log(randomColor)
            $('.' + elementId).css('background-color', randomColor);
        }

        setRandomBackgroundColor('user-img-placeholder');


    tinymce.init({
                selector: '.tinymce',
                height: 200,
                font_formats:"Littera Text",
                content_style: "body { font-family: 'Littera Text', sans-serif; }",
        menubar: true,
        automatic_uploads: true,
        images_upload_url: '/forms/attachment/upload',
        browser_spellcheck: true
                
            });
        loadActiveTab();

        // populates the select fields
        var eligible_user_only = $("#eligible_user_only").val();
            eligible_user_only = JSON.parse(eligible_user_only);
            $('.eligible_users_only').parent().find('.head_office_user_id').empty();
            $.each( eligible_user_only, function( key, value ) {
                console.log($('.eligible_users_only'));
                $('.eligible_users_only').parent().find('.head_office_user_id').append($("<option></option>").attr("value",key).text(value['name']));
            });
            var eligible_user_only = $("#eligible_user_only").val();
            eligible_user_only = JSON.parse(eligible_user_only);
            $('.transfer_eligible_users_only').parent().find('.transfer_head_office_user_id').empty();
            console.log(eligible_user_only);
            $.each( eligible_user_only, function( key, value ) {
                console.log($('.transfer_eligible_users_only'));
                $('.transfer_eligible_users_only').parent().find('.transfer_head_office_user_id').append($("<option></option>").attr("value",key).text(value['name']));
            });
    });
    function loadActiveTab(tab = null){
        if(tab == null){
            tab = window.location.hash;
        } 
        console.log(tab);
        $('.nav-tabs a[data-bs-target="' + tab + '"]').tab('show');
    }
    

    
    $('.eligible_users_only').on('change',function(){
        if($(".eligible_users_only").is(':checked'))
        {
            var all_user_only = $("#all_user_only").val();
            all_user_only = JSON.parse(all_user_only);
            $('.eligible_users_only').parent().find('.head_office_user_id').empty();
            $.each( all_user_only, function( key, value ) {
                console.log($('.eligible_users_only'));
                $('.eligible_users_only').parent().find('.head_office_user_id').append($("<option></option>").attr("value",key).text(value));
            });
            
        }
        else
        {
            var eligible_user_only = $("#eligible_user_only").val();
            eligible_user_only = JSON.parse(eligible_user_only);
            $('.eligible_users_only').parent().find('.head_office_user_id').empty();
            $.each( eligible_user_only, function( key, value ) {
                console.log($('.eligible_users_only'));
                $('.eligible_users_only').parent().find('.head_office_user_id').append($("<option></option>").attr("value",key).text(value['name']));
            });
            //$('.head_office_user_id').empty().append($("<option></option>").attr("value",key).text(value));
        }
    });
    $('.transfer_eligible_users_only').on('change',function(){
        if($(".transfer_eligible_users_only").is(':checked'))
        {
            var all_user_only = $("#all_user_only").val();
            all_user_only = JSON.parse(all_user_only);
            $('.transfer_eligible_users_only').parent().find('.transfer_head_office_user_id').empty();
            $.each( all_user_only, function( key, value ) {
                $('.transfer_eligible_users_only').parent().find('.transfer_head_office_user_id').append($("<option></option>").attr("value",key).text(value));
            });
        }
        else
        {
            
             var eligible_user_only = $("#eligible_user_only").val();
            eligible_user_only = JSON.parse(eligible_user_only);
            $('.transfer_eligible_users_only').parent().find('.transfer_head_office_user_id').empty();
            console.log(eligible_user_only);
            $.each( eligible_user_only, function( key, value ) {
                console.log($('.transfer_eligible_users_only'));
                $('.transfer_eligible_users_only').parent().find('.transfer_head_office_user_id').append($("<option></option>").attr("value",key).text(value['name']));
            });
            //$('.head_office_user_id').empty().append($("<option></option>").attr("value",key).text(value));
        }
    });


    $(document).ready(function() {
    let cas_user_ids = $('#case_handlers_user_ids').data('ids'); 
    if (cas_user_ids != undefined) {
    }

    $('.select-user').on('change', function() {
        let selectedUsers = [];

        $('.select-user:checked').each(function() {
            selectedUsers.push($(this).val());
        });


        let matchFound = selectedUsers.some(userId => cas_user_ids.includes(parseInt(userId)));

        if (matchFound) {
            $('#other_case_handler').fadeIn();
        } else {
            $('#other_case_handler').fadeOut();
        }

        $('#users_to_remove').val(JSON.stringify(selectedUsers));
    });
});


$('.showAllUsersBtn').on('click', function() {
    const table = $(this).parent().siblings().find('table');
    table.find('.eligible-hidden').fadeToggle();
    if($(this).text() == 'Show Less Users') {
        $(this).text('Show All Users');
    } else {
        $(this).text('Show Less Users');
    }
})

$(document).on('click', '.selected_task_btn', function () {
    const taskId = $(this).data('task_id');
    $('#selected_task_id').val(taskId);
});

    
</script>
@if(Session::has('success'))
    <script>
        alertify.success("{{ Session::get('success') }}");
    </script>
@elseif(Session::has('error'))
<script>
    alertify.error("{{ Session::get('error') }}");
</script>
@endif
@endsection
