<form method="post" action="{{route('head_office.case.share_case_reject',[$case->id,$share->id,$extension->id])}}" class="cm_task_form">
    @csrf
    <div class="modal fade file_upload_model " id="reject_extension_{{$extension->id}}" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        <p class="text-success"><i class="fa fa-tasks fa-2x"></i></p>Reject Extension Request
                    </h4>
                    <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- <div class="form-group">
                        <label>Extend Until</label>
                        <input type="datetime-local" name="duration_of_access" readonly value="{{$extension->extension_time}}" min="{{date('Y-m-d')}}" class="form-control" required>
                    </div> --}}
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea spellcheck="true"  name="head_office_notes" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Reject</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

