
<div class="modal fade file_upload_model" id="edit_request_information_{{$case_request_information->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title text-info w-100">
                    Edit Request
                </h4>
                <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="post"
                action="{{route('head_office.statement.single_statement_edit',[$case->id,$case_request_information->id,1])}}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Type a general overview for this statement provider</label>
                        <textarea spellcheck="true"  name="note" id="note" class="form-control">{{$case_request_information->note}}</textarea>
                    </div>
                    <div class="form-inline">
                        <label>Make report available to above person?</label> &nbsp;
                        <input type="checkbox" name="is_available_to_person" @if($case_request_information->is_available_to_person) checked @endif id="is_available_to_person" class="form-control">
                    </div>
                    <div class="request_div">
                        @foreach ($case_request_information->questions as $question)
                            <div class="form-group">
                                <label>{{$question->question}}</label>
                                <input type="text" name="note" class="form-control" required>
                            </div>
                        @endforeach
                    </div>
                    
                    
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <a id="edit_custom_button" class="edit_custom_button btn btn-info">
                            <i class="fa fa-plus"></i>
                            Add Another Section
                        </a>
                        <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();"
                            data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

