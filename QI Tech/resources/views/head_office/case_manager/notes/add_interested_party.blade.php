{{-- <form method="post"
    @if(isset($interested_party))action="{{route('case_manager.edit_interested_parties',[$case->id,$interested_party->id])}}"
    @else action="{{route('case_manager.add_interested_parties',$case->id)}}" @endif class="cm_task_form">
    @csrf
    <div class="modal fade file_upload_model"
        @if(isset($interested_party))id="edit_interested_party_{{$interested_party->id}}" @else
        id="add_interested_parties" @endif tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        <p class="text-success"><i class="fa fa-tasks fa-2x"></i></p>Add interested Party
                    </h4>
                    <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control" name="first_name"
                            value="@if(isset($interested_party)){{$interested_party->first_name}}@endif"
                            title="First Name">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control" name="last_name"
                            value="@if(isset($interested_party)){{$interested_party->last_name}}@endif"
                            title="last Name">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" name="email"
                            value="@if(isset($interested_party)){{$interested_party->email}}@endif" title="Email">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();"
                            data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form> --}}

@if($case->can_share_case_responsibility)
<form method="post" action="{{ route('case_manager.add_interested_parties', $case->id) }}" class="cm_task_form">
    @csrf
    <div class="modal fade file_upload_model" id="add_interested_parties" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        <p class="text-success"></p>Add Case Investigator
                    </h4>
                    <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label>Users</label>
                    <div class="form-group" style="margin-top: 0px">
                        <select name="head_office_user_ids[]" multiple style="width: 100%" class="form-control select2 mt-4" required>
                            @foreach($case->case_head_office->users as $u)
                                <option value="{{ $u->user->id }}">{{ $u->user->name }} ({{ $u->user->position->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="note">Note</label>
                        <textarea spellcheck="true" name="note" id="note" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
                <div class="case-investigators d-flex align-items-start" style="gap: 10px; overflow-x: auto; white-space: nowrap;">
                    <div class="u-title">
                        <div style="font-weight: bold; font-size: 16px;">Existing Investigators</div>
                        <div>
                            @foreach ($case->case_interested_parties as $case_interested_party)
                                @if(isset($case_interested_party->case_head_office_user))
                                    @if(isset($case_interested_party->case_head_office_user->user->logo))
                                        <img src="{{ $case_interested_party->case_head_office_user->user->logo }}" alt="{{ $case_interested_party->case_head_office_user->user->name }}'s logo" style="width: 30px; height: 30px; border-radius: 50%;">
                                    @else
                                        <span>{{ $case_interested_party->case_head_office_user->user->name }}</span>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endif
