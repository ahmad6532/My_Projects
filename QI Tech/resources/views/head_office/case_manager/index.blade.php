@extends('layouts.head_office_app')
@section('title', 'Case Manager')


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
@php 
$eligible_users = [];
$all_users = [];

// Use eager loading to optimize related data retrieval
$type_cases = $headOffice->cases()
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
                    'other_case_types' => [],
                    'case_id' => $type_case->id
                ];
            }
    
            // Collect unique form names the user is involved in
            $other_case_types = $case_handler->case_head_office_user->head_office_user_cases
    ->map(function ($user_case) {
        $new_case = $user_case->case;
        if (isset($new_case->link_case_with_form->form)) {
            return [
                'case_id' => $new_case->id,
                'form_name' => $new_case->link_case_with_form->form->name,
            ];
        }
        return null;
    })
    ->filter()
    ->reduce(function ($carry, $item) {
        // Check if the form_name already exists in the carry (accumulated result)
        $form_name = $item['form_name'];
        $case_id = $item['case_id'];
        
        if (isset($carry[$form_name])) {
            // If form_name exists, push the case_id into the array of case_ids
            $carry[$form_name]['case_ids'][] = $case_id;
        } else {
            // Otherwise, create a new entry with a single case_id
            $carry[$form_name] = [
                'form_name' => $form_name,
                'case_ids' => [$case_id],
            ];
        }
        
        return $carry;
    }, []);


    
            // Merge unique case types
            $existing_case_types = collect($eligible_users[$user->id]['other_case_types']);
            $new_case_types = collect($other_case_types);
            $merged_case_types = $existing_case_types->merge($new_case_types)
                ->unique(fn($item) => $item['form_name'])
                ->values()
                ->toArray();

            $eligible_users[$user->id]['other_case_types'] = $merged_case_types;
        }
    }
}

// Prepare all users for the current head office
$all_users_raw = $headOffice->users
    ->pluck('user')
    ->mapWithKeys(fn($u) => [$u->id => "{$u->name} {$u->position->name}"])
    ->toArray();
// Convert to JSON for output
$eligible_user_raw = $eligible_users;
$eligible_user = json_encode($eligible_users);
$all_users = json_encode($all_users_raw);

@endphp
<div id="content" class="content-custom-scroll">
    <style>
        .case-tags{
            font-size: 12px !important;
            padding: 5px !important;
        }
        .case-tags svg{
            width: 18px;
        }
    </style>

        <div class=" headingWithSearch ">
            <div class="input-group rounded " style="width: 320px;">
                <span class="input-group-text border-0 bg-transparent search-addon" id="search-addon">
                    <i class="fas fa-search" style="color: #969697;z-index: 2"></i>
                </span>
                <input spellcheck="true" type="search" class="form-control rounded shadow-none search-input" placeholder="Search" aria-label="Search" aria-describedby="search-addon" id="caseSearch" />
            </div>
        </div>

    <div class="cm_content pt-2">
        <div id="noResultsMessage" style="display: none; text-align: left;">
            <p class="font-italic">There are no cases</p>
        </div>
        
        @if(!count($cases))
        @if (request()->route()->getName() == 'case_manager.case_archives')
        <p class="font-italic">There are no archived cases</p>
        @else
        <p class="font-italic">There are no cases</p>
        @endif
        
        @endif

        <div id="cases-wrapper">
            @include('head_office.case_manager.cases',['cases' => $cases])
        </div>
        <div class="line-reloading" style="display:none">
            <div class="skeleton-gjdnnl5vvii"></div>
        </div>


        {{-- <div>{!! $cases->render('pagination::bootstrap-5') !!}</div> --}}
    </div>
</div>


<div id="draggable" class="bottom-nav position-fixed display_cases_nav" style="z-index: 10;" aria-describedby="drag" >
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

        <div style="max-width: 570px;overflow-x:scroll;height:100%;" class="custom-drag-scroll">
        <div class="btn-wrapper" style="width: {{request()->route()->getName() == 'case_manager.case_archives' ? 'fit-content': '1150px'}};">
            @if (request()->route()->getName() == 'case_manager.index')
                <button data-toggle="tooltip" id='lfpse-case-btn' class="bar-btn" data-route="{{route('case_manager.submit_nhs_lfpse_bulk')}}" data-token="{{csrf_token()}}" title="Submits the selected cases to NHS LFPSE" style="width: 90px;">
                    <img src="{{ asset('images/shield-plus.svg') }}" alt="icon">
                    <p>LFPSE</p>
                </button>
                <button data-bs-target="#open_case_model" data-bs-toggle="modal" id='open-case-btn' class="bar-btn" title="open selected cases" style="width: 90px;">
                    <img src="{{ asset('images/file-02.svg') }}" alt="icon">
                    <p>Open case</p>
                </button>
                <button data-bs-target="#close_case_comment" data-bs-toggle="modal" id='close-case-btn' class="bar-btn" title="close selected cases" style="width: 90px;">
                    <img src="{{ asset('images/file-x-02.svg') }}" alt="icon">
                    <p>Close Case</p>
                </button>
            @endif
            <button id='export-case-btn' class="bar-btn" title="Export selected cases" style="width: 112px;">
                <img src="{{ asset('images/file-download-02.svg') }}" alt="icon">
                <p>Export Case</p>
            </button>
            @if (request()->route()->getName() == 'case_manager.index')
            <button data-bs-target="#case_approval_request" data-bs-toggle="modal" id='approve-case-btn' class="bar-btn" title="Approve closure for selected cases" style="width: 130px;">
                <img src="{{ asset('images/file-x-03.svg') }}" alt="icon">
                <p>Approve closure</p>
            </button>
            <button class="bar-btn" data-bs-toggle="modal" data-bs-target="#archiveModal" >
                <img src="{{ asset('images/folder-lock.svg') }}" alt="icon">
                <p>Archive</p>
            </button>




           




            @endif
            <button style="width: 100px;" id='unarchive-btn' class="bar-btn" title="Unarchive selected cases" data-route="{{route('case_manager.unarchive_bulk')}}" data-token="{{csrf_token()}}">
                <img src="{{ asset('images/folder.svg') }}" alt="icon">
                <p>Unarchive</p>
            </button>
            @if (request()->route()->getName() == 'case_manager.index')
            <button id='transfer-case-btn' data-bs-toggle="modal" data-bs-target="#transfer_case_responsibility" class="bar-btn" title="Transfer selected cases" style="width: 112px;">
                <img src="{{ asset('images/upload-cloud-02.svg') }}" alt="icon">
                <p>Transfer case</p>
            </button>
            <button id='share-case-btn' data-bs-toggle="modal" data-bs-target="#share_case_responsibility" class="bar-btn" title="Share selected cases" style="width: 200px;">
                <img src="{{ asset('images/users-plus.svg') }}" alt="icon">
                <p>Share Case Responsibility</p>
            </button>
                
            @endif

        </div>
    </div>
    
    </div>
    <button class="drag-btn">
        <img src="{{ asset('images/dots-horizontal.svg') }}" alt="svg">
        <img style="margin-top:-15px;" src="{{ asset('images/dots-horizontal.svg') }}" alt="svg">
    </button>
</div>
<input type="hidden" value="{{csrf_token()}}" id="token_id"/>


{{-- Php for Modals --}}


{{-- All the draggable Modals are here --}}
<form method="post" action="{{route('case_manger.export_cases_bulk')}}" id="export_bulk">
    @csrf
    <input type="text" hidden name="case_ids[]" class="case_ids_input">
</form>
<style>
    .select2-container--open {
        z-index: 9999999 !important;
    }
</style>

<div class="modal fade" id="archiveModal" tabindex="-1" role="dialog" aria-labelledby="archiveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="archiveModalLabel">Archive Cases</h5>
            <button type="button" class="btn-close" style="position:absolute; top:4px; right:4px;"
            data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"></span>
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
            <button class="btn primary-btn" type="button" id="archive-btn" data-token="{{csrf_token()}}" data-route="{{route('case_manager.archive_bulk')}}">Archive</button>
          </div>
      </div>
    </div>
</div>

<div class="modal fade" id="close_case_comment" tabindex="-1" 
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
                <form method="post" 
                    action="{{route('case_manager.view.close_case_bulk')}}"  id="reset_form" enctype="multipart/form-data">
                    <div class="new_link_wrapper">
                        @csrf
                        <!-- <input type="hidden" name="links[]" value=""> -->
                        <span class="inline-block links relative">Please provide reason to close case</span>
                        <textarea spellcheck="true"  required rows="3" class="cm_comment_box case-close-textarea-border spellcheck"
                            name="close_comment" placeholder="Type notes here"></textarea>
                        <br />
                        <div class="uploaded_files mt-2 mb-2"></div>
                        <h6 class="text-info">Select documents/images to upload</h6>
                        <div class="cm_upload_box_with_model center">
                            <i class="fa fa-cloud-upload-alt" style="font-size:48px"></i><br>Drop files here
                        </div>
                        <input type="file" name="file" multiple value="" class="form-control commentMultipleFiles">
                        <h4 class="text-info h5 my-2 mt-3 title-div">Provide Feedback</h4>
                        <div class="reporter-div">
                            <input type="checkbox" id="send_feedback_to_reporter_user_input" name="send_feedback_to_reporter_user"  onchange="show_reporter_user(this)">
                            <label for="send_feedback_to_reporter_user_input" class="fw-semibold m-0">To the Reporter</label>
                            <div style="display: none;" class="mb-2" id="send_feedback_to_reporter_user">
                                <input type="text" readonly class="form-control d-none" id="user_feedback_ids" value="" name="user_feedback_ids[]">
                                <select type="text" id="user_ids" style="width: 100%" class="form-control select2" required multiple>
                                </select>

                                <div class="form-grou">
                                    <label for="feedback my-4">Feedback
                                    </label>
                                    <textarea spellcheck="true"  spellcheck="true" name="feedback_user" id="" class="form-control tinymce spellcheck"></textarea>
                                </div>
    
                            </div>
                        </div>
                        <div class="location-div">
                            <input type="checkbox" id="send_feedback_to_reporter_input" name="send_feedback_to_reporter"  onchange="show_reporter(this)">
                            <label for="send_feedback_to_reporter_input" class="fw-semibold m-0">To the Location</label>
                            <div style="display: none;"  id="send_feedback_to_reporter">
                                
                                <input type="text" readonly class="form-control d-none" id="location_feedback_ids" name="location_feedback_ids[]" >
                                <select type="text" id="location_ids" style="width: 100%" class="form-control select2" required multiple>
                                </select>
                                <div class="form-group">
                                    <label for="feedback my-4">Feedback
                                    </label>
                                    <textarea spellcheck="true"  spellcheck="true" name="feedback" id="" class="form-control tinymce spellcheck"></textarea>
                                </div>
    
                            </div>
                        </div>

                        <input type="text" hidden name="case_ids[]" class="case_ids_input">
                    </div>
                    <br>
                    
                    
                    <button type="submit"  class="btn btn-info btn-submit inline-block mb-0"><i
                            class="fa fa-location-arrow"></i> </button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="open_case_model" tabindex="-1" 
    role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title text-info w-100">
                    {{-- <p class="text-success"><i class="fa fa-paperclip fa-flip-horizontal fa-3x"></i></p>
                    --}}
                    Open Case
                </h4>
                <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" 
                    action="{{route('case_manager.open_case_bulk')}}"  id="reset_form" enctype="multipart/form-data">
                    <div class="new_link_wrapper">
                        @csrf
                        <!-- <input type="hidden" name="links[]" value=""> -->
                        <span class="h5 inline-block links relative my-2">Please provide reason to open case</span>
                        <textarea spellcheck="true"  spellcheck="true" required rows="3" class="cm_comment_box case-close-textarea-border spellcheck"
                            name="close_comment" placeholder="Type reaseon here"></textarea>
                        <br />
                        <input type="text" hidden name="case_ids[]" class="case_ids_input">
                    </div>
                    <br>
                    
                    
                    <button type="submit"  class="btn btn-info btn-submit inline-block mb-0"><i
                            class="fa fa-location-arrow"></i> </button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="loader-container" id="loader" style="top: 0;z-index:9999999;display:none;">
    <div class="loader"></div>
  </div>

<div class="modal fade" id="case_approval_request" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title text-info w-100">
                    {{-- <p class="text-success"><i class="fa fa-paperclip fa-flip-horizontal fa-3x"></i></p>
                    --}}
                    Closure Approvels
                </h4>
                <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('case_manager.case_approval_bulk')}}"
                    id="reset_form">
                    <div class="new_link_wrapper">
                        @csrf
                        <!-- <input type="hidden" name="links[]" value=""> -->
                        <select name="reason" id="closure_select" class="form-select my-2">
                            <option value="accept" selected>Accept closure approvels</option>
                            <option value="reject" >Reject closure approvels</option>
                        </select>
                        <input type="text" hidden name="case_ids[]" class="case_ids_input">
                        <span class="h5 inline-block links relative" id="case_placeholder">Please provide reason for acceptance.</span>
                        <p class="inline-block cm_comment_box case-close-textarea-border" name="close_comment" required
                            placeholder="Type notes here" contenteditable="true"></p>
                        <textarea spellcheck="true"  spellcheck="true" style="display:none" rows="3" class="cm_comment_box_hidden spellcheck"
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
                    <button type="submit"  class="btn btn-info btn-submit inline-block mb-0"><i
                            class="fa fa-location-arrow"></i> </button>

            </div>
            </form>
        </div>
    </div>
</div>

<form method="post"
    action="{{route('case_manager.transfer_case_responsibity_bulk')}}"
    id="transfer_case_form"
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
                    {{-- <div>
                        <table  class="table table-responsive table-bordered mx-auto dataTable w-100" >
                            <thead class="text-center">
                                <tr>
                                    <th class="text-center">Users</th>
                                    <th class="text-center">Case Type</th>
                                </tr>
                            </thead>
                            <tbody class="all_locations text-center">
                                @foreach($headOffice->users as $user_raw)
                                
                                <tr>
                                    <td class="fw-semibold email">{{$user_raw}}</td>
                                    <td class="fw-semibold email">{{$headOffice->cases()->first()->incident_type}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> --}}
                    {{-- <label for="transfer_eligible_users_only">Show all user</label> --}}
                        {{-- <input type="checkbox" class="transfer_eligible_users_only"  name="transfer_eligible_users_only"  id=""> --}}
                    <div class="form-group">
                        <label>Users</label>
                        <select type="text" name="head_office_user_id" style="width: 100%"
                            class="form-select transfer_head_office_user_id p-2" required>
                            @foreach($headOffice->users as $u)
                            <option value="{{$u->user->id}}">{{$u->user->name}}
                                ({{$u->user->position->name}})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="note">Note</label>
                        <textarea spellcheck="true"  spellcheck="true" name="note" id="note" class="form-control spellcheck"></textarea>
                    </div>
                    <input type="text" hidden name="case_ids[]" class="case_ids_input">
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
    action="{{route('case_manager.share_case_responsibity_bulk')}}"
    id="transfer_case_form"
    class="cm_task_form">
    @csrf
    <div class="modal fade file_upload_model" id="share_case_responsibility" tabindex="-1"
        role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        Share Case Responsibility
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
                                                <input type="checkbox" name="caseHandalers[]" class="caseHandalers-input" value="{{ $key }}">
                                                {{ $userData['name'] }}
                                            </td>
                                            <td>
                                                @if ($isEligible)
                                                <div style="text-align: left !important;" >
                                                        {{-- <p class="m-0 fw-semibold" style="color: #249b91;">
                                                            {{ $userData['current_case_type'] }}
                                                        </p> --}}
                                                        @foreach ($userData['other_case_types'] as $other_case_type)
                                                            <p class="m-0" data-selected_case_id="{{json_encode($other_case_type['case_ids'])}}" >{{ $other_case_type['form_name'] }}</p>
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
                    <input type="text" hidden name="case_ids[]" class="case_ids_input">
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
@endsection
@section('top_bar_search')
<form method="get" class="form print-display-none header_search_bar">
    <div class="input-group form-group mb-3">
        <input spellcheck="true" type="text" class="form-control search-nearmiss" name="search" @if(request()->query('search'))
        value="{{request()->query('search')}}" @endif>
        <button type="submit" class="btn btn-info search_button"><i class="fa fa-search"></i></button>
    </div>
</form>

@endsection
@section('case_manager_tabs')

@endsection


@section('styles')
<link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
@endsection

@section('scripts')

<script src="{{asset('admin_assets/js/view_case.js')}}"></script>
<script src="{{asset('js/alertify.min.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var placeholderLinks = document.querySelectorAll('.placeholder-link');
        
        placeholderLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
            //     alert('Coming Soon');
            });
        });
    });
</script>
<script>
    $('.showAllUsersBtn').on('click', function() {
    const table = $(this).parent().siblings().find('table');
    table.find('.eligible-hidden').fadeToggle();
    if($(this).text() == 'Show Less Users') {
        $(this).text('Show All Users');
    } else {
        $(this).text('Show Less Users');
    }
})


    function filterCases() {
        var query = $('#caseSearch').val().toLowerCase();
        $('.case-item').each(function() {
            var text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(query) !== -1);
        });
    }

    $('#caseSearch').on('keyup', filterCases);
    
    $(document).ready(function(){
        tinymce.init({
                selector: '.tinymce',
                font_formats:"Littera Text",
                content_style: "body { font-family: 'Littera Text', sans-serif; }",
                height: 200,
                skin: false,
                browser_spellcheck: true,
                external_plugins: {"nanospell": "{{asset('admin_assets/js/nanospell.tinymce/plugin.js')}}"},
	            nanospell_server: "php"
            });

                // Side bar Close =============
                // var sidebar = $('#sidebar');
                // const imgTag = $('#side-img');
                // const imgPath = imgTag.attr('src').replace('chevron-left-double','chevron-right-double');
                // imgTag.attr('src',imgPath)
                // sidebar.animate({ width: 0,opacity:0 }, 300);

    
    })
    function approved(val)
    {
        document.getElementById('is_approved').value = val;
    }

    $('#share-case-btn').on('click',function(){
        $('#transfer_case_form').attr('action','{{route('case_manager.share_case_responsibity_bulk')}}')
    })
    $('#transfer-case-btn').on('click',function(){
        $('#transfer_case_form').attr('action','{{route('case_manager.share_case_responsibity_bulk')}}')
    })

    $('#export-case-btn').on('click',function(){
        $('#export_bulk').submit();
    })



</script>
@if(Session::has('success'))
    <script>
        alertify.success("{{ Session::get('success') }}");
    </script>
@elseif(Session::has('error'))
<script>
    alertify.success("{{ Session::get('error') }}");
</script>
@endif
@endsection