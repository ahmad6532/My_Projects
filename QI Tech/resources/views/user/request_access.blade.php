<form method="post" action="{{route('user.share_case.request_extension',$share->id)}}" class="cm_task_form">
    @csrf
    <div class="modal fade file_upload_model " id="request_access_{{$share->id}}" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        Request Extension
                    </h4>
                    <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="profile-center-area">
                        <div class="user-page-contents hide-placeholder-parent">
                            <label class="inputGroup">Extend Until:
                                <input type="date" required name="duration_of_access" value="{{date('Y-m-d')}}" min="{{date('Y-m-d')}}"  placeholder="select date" />
                            </label>
                            <label class="inputGroup">Reason for extension
                                <textarea spellcheck="true"  type="date" name="note" placeholder="Enter Note"></textarea>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Send request</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

