<div class=" relative">
    <style>
        .new-info-wrapper {

            position: absolute;
            top: 30px;
            right: 60%;
            z-index: 1;
            background-color: white;
            display: flex;
            align-items: center;
            width: 350px;
            padding: 1rem;
            box-shadow: 0px 0px 10px -1px #bbb;
            border-radius: 1.5rem;
            gap: 1rem;
            height: 152px;
            display: none;
            right: 101%;
            top: 0;
            width: 370px;
            flex-direction: column;
            height: auto;
        }

        .new-info-wrapper img {
            width: 105px;
            aspect-ratio: 1 / 1;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-info-details {
            text-align: left;
        }

        .new-user-wrapper {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .expirtise-wrap {
            width: 100%;
        }
    </style>
    @foreach ($case->case_handlers as $case_handler)
        <div class="card border-left-secondary shadow w-100 new-card-wrap">
            @include('head_office.user_card_component', [
                'user' => $case_handler->case_head_office_user->user,
            ])

            <div class="card-body">
                <div class="row align-items-center ">
                    <div class="col-sm-3">
                        <div class="font-weight-bold text-black" title="Case Number">
                            {{ $case_handler->case_head_office_user->user->name }}</div>
                    </div>
                    <div class="col-sm-6">
                        <span class="cm_incident_type">{{ $case_handler->case_head_office_user->position }}</span>
                    </div>
                    <div class="col-sm-3">
                        <div class="cm_comment_people">
                            <span data-toggle="tooltip" title="" class="badge badge-primary badge-user"
                                data-original-title="Case Handler">Case Handler</span>
                        </div>
                    </div>
                    @if ($case->isArchived == false)
                        <div style="position: absolute;top:5;right:0; display:grid;place-items:flex-end;">
                            <a href="javascript:void(0);" class="inline-block" type="button"
                                id="dropdownMenuButton{{ $case_handler->id }} " data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false"><img
                                    src="{{ asset('v2/images/icons/list.svg') }}" alt=""></a>
                            <div class="dropdown-menu animated--fade-in"
                                aria-labelledby="dropdownMenuButton{{ $case_handler->id }}">
                                <a href="#" class="dropdown-item remove_case_handler" data-bs-toggle="modal"
                                    data-bs-target="#remove_case_handler"
                                    data-name='{{ $case_handler->case_head_office_user->user->name }} ({{ $case_handler->case_head_office_user->user->position->name }})'
                                    data-id='{{ $case_handler->id }}'>Remove as Case Handler</a>
                                <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                    data-bs-target="#share_case_responsibility_id">Assign as joint Case Handler</a>
                                <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                    data-bs-target="#transfer_case_responsibility">Transfer Case Handler responsibility
                                </a>
                                <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                    data-bs-target="#remove_case_handler_any">Remove from case </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- <div class="card-body">
        <div class="row align-items-center">
        <div class="col-sm-3">
            <div class="font-weight-bold text-black" title="Case Number">
                {{$case_handler->case_head_office_user->user->name}}
            </div>
        </div>
        <div class="col-sm-6">
            <span class="cm_incident_type">{{$case_handler->case_head_office_user->position}}</span>
        </div>
        <div class="col-sm-3 d-flex justify-content-end align-items-center">
            <div class="cm_comment_people me-2">
                <span data-toggle="tooltip" title="" class="badge badge-primary badge-user" data-original-title="Case Handler">
                    Case Handler
                </span>
            </div>
            <div>
                <a href="javascript:void(0);" class="inline-block" type="button" id="dropdownMenuButton{{$case_handler->id}}"
                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ asset('v2/images/icons/list.svg') }}" alt="" style="width: 18px; height: 18px;">
                </a>
                <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton{{$case_handler->id}}">
                    <a href="#" class="dropdown-item remove_case_handler" data-bs-toggle="modal" data-bs-target="#remove_case_handler" 
                       data-name='{{$case_handler->case_head_office_user->user->name}} ({{$case_handler->case_head_office_user->user->position->name}})' 
                       data-id='{{$case_handler->id}}'>
                       Remove as Case Handler
                    </a>
                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#share_case_responsibility_id">
                        Assign as Joint Case Handler
                    </a>
                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#transfer_case_responsibility">
                        Transfer Case Handler Responsibility
                    </a>
                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#remove_case_handler_any">
                        Remove from case
                    </a>
                </div>
            </div>
        </div>
    </div>
</div> --}}
    @endforeach
    @php
        $uniqueHeadOfficeUsers = [];
        $logged_u = Auth::guard('web')->user()->selected_head_office_user;

        foreach ($case->stages as $stage) {
            foreach ($stage->tasks as $task) {
                foreach ($task->assigned as $assignedUser) {
                    if (
                        isset($assignedUser->head_office_user) &&
                        !in_array($assignedUser->head_office_user, $uniqueHeadOfficeUsers)
                    ) {
                        $uniqueHeadOfficeUsers[] = $assignedUser->head_office_user;
                    }
                }
            }
        }
        $case_interested_party = $case->case_interested_parties()->where('tag', 'final_clouser_approval')->first();
    @endphp

    {{-- =============== other Users ============== --}}
    <hr class="w-100">
    @foreach ($uniqueHeadOfficeUsers as $uniqueUser)
        <div class="card border-left-secondary shadow w-100 new-card-wrap">
            @include('head_office.user_card_component', ['user' => $uniqueUser->user])

            <div class="card-body">
                <div class="row align-items-center ">
                    <div class="col-sm-3">
                        <div class="font-weight-bold text-black" title="Case Number">
                            {{ $uniqueUser->user->name }}</div>
                    </div>
                    <div class="col-sm-6">
                        <span class="cm_incident_type">{{ $uniqueUser->position }}</span>
                    </div>
                    <div class="col-sm-3">
                        <div class="cm_comment_people">
                            <span data-toggle="tooltip" title="" class="badge badge-warning bg-warning text-black"
                                data-original-title="Stage Handler">Task Handler</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @if ($case_interested_party)
        <div class="card border-left-secondary shadow w-100">
            <div class="card-body">
                <div class="row align-items-center ">
                    <div class="col-sm-4">
                        <div class="font-weight-bold text-black" title="Case Number">
                            {{ $case_interested_party->case_head_office_user->user->name }}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <span
                            class="cm_incident_type">{{ $case_interested_party->case_head_office_user->position }}</span>
                    </div>
                    <div class="col-sm-4">
                        <div class="cm_comment_people">
                            <span data-toggle="tooltip" title="" class="badge badge-primary badge-user"
                                data-original-title="Case Handler">Final Approver</span>
                        </div>

                    </div>
                    {{-- <div class="col-sm-2">
                    <a href="javascript:void(0);" class="inline-block" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-angle-right fa-2x text-gray-300"></i></a>
                    <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
                        {{-- <a href="{{route('case_manager.edit_interested_parties',[$case->id,$case_interested_party->id])}}" class="dropdown-item" data-toggle="modal"
                            data-target="#edit_interested_party_{{$case_interested_party->id}}" data-toggle="" title="Delete Party">Edit</a> 
                        <a href="{{route('case_manager.delete_interested_parties',[$case->id,$case_interested_party->id])}}" data-msg="Are you sure you want to remove this party?" class="dropdown-item delete_party" title="Delete Party">Delete</a>
                    </div>
                </div> --}}
                </div>
            </div>
        </div>
    @endif
    @foreach ($case->case_interested_parties as $case_interested_party)
        @if (!$case_interested_party->tag)
            <div class="card border-left-secondary shadow w-100">
                <div class="card-body">
                    <div class="row align-items-center ">
                        <div class="col-sm-4">
                            <div class="font-weight-bold text-black" title="Case Number">
                                {{ $case_interested_party->case_head_office_user->user->name }}
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <span
                                class="cm_incident_type">{{ $case_interested_party->case_head_office_user->position }}</span>
                        </div>
                        @if ($case->status == 'open')
                            <div class="col-sm-3">
                                {{-- <div class="cm_comment_people">
                        <span data-toggle="tooltip" title="" class="badge badge-warning bg-warning text-black" data-original-title="Stage Handler">Investigator</span>
                    </div> --}}
                            </div>
                            <div style="position: absolute;top:0;right:0; display:grid;place-items:flex-end;">
                                <a href="javascript:void(0);" class="inline-block" type="button"
                                    id="dropdownMenuButton{{ $case_interested_party->id }}" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false"><img
                                        src="{{ asset('v2/images/icons/list.svg') }}" alt=""></a>
                                <div class="dropdown-menu animated--fade-in"
                                    aria-labelledby="dropdownMenuButton{{ $case_interested_party->id }}">
                                    <a href="{{ route('case_manager.delete_interested_parties', [$case->id, $case_interested_party->id]) }}"
                                        data-msg="Are you sure you want to remove this party?"
                                        class="dropdown-item delete_party" title="Delete Party">Remove from case</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @include('head_office.case_manager.notes.add_interested_party', [
                'interested_party' => $case_interested_party,
            ])
        @endif
    @endforeach

    <p class="mb-0 mt-4 fw-bold">Case viewable by</p>
    <div class="d-flex alingn-items-center users-img-wrapper">
        @foreach ($case->getUsersWithAccess() as $ho_user)
            <div style="font-size: 14px;" class=" d-flex user-icon-circle new-card-wrap">
                @if (isset($ho_user->user->logo) == null) 
                    <img class="img-profile rounded-circle" width="32" height="32" src="{{ $ho_user->user->logo }}" >
                @else 
                <div class="user-img-placeholder"
                    id="user-img-place"
                    style="width: 30px; height: 30px;">
                    {{ implode('',array_map(function ($word) {return strtoupper($word[0]);}, explode(' ', $ho_user->user->name))) }}
                </div>
                @endif

                    @include('head_office.user_card_component', [
                        'user' => $ho_user->user
                    ])
                @if ((isset($permissions) && $permissions->super_access == true) || ( $case->getCanShareCaseResponsibilityAttribute() != false) )
                    @if ($logged_u->id !== $ho_user->id)
                        <a href="{{route('case_manager.remove_case_access',['id'=>$case->id,'user_id'=>$ho_user->id,'_token'=>csrf_token()])}}" class="new-info-wrapper" style="width:150px;left:-6rem;top:2rem;z-index:99999;padding-block:0.5rem !important;">
                            Remove access to case
                        </a>
                        
                    @endif
                @endif

            </div>
        @endforeach
    </div>

</div>
