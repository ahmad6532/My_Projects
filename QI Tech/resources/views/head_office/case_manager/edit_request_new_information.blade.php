<form>
    <div class="modal fade file_upload_model " @if(isset($defualt_request_information)) id="edit_request_{{$defualt_request_information->id}}" @else id="request_information" @endif tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        <p class="text-success"><i class="fa fa-tasks fa-2x"></i></p>Edit Default Text
                    </h4>
                    <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Text</label>
                        <input type="text" name="value" value="@if($defualt_request_information){{$defualt_request_information->value}}@endif" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();"
                            data-dismiss="modal">Cancel</button>
                        <a href="javascript:void(0);" onclick="save_information_text('@if(isset($defualt_request_information)){{route('head_office.case.default_request_information_text',$defualt_request_information->id)}}@else{{route('head_office.case.default_request_information_text')}}@endif',this)" class="btn btn-info">Save</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

