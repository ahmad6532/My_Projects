<form method="post" action="{{route('head_office.setting.default_five_whys_question_save')}}" class="cm_task_form">
    @csrf

    @if(isset($question))
    <input type="hidden" name="default_five_whys_question" value="{{$question->id}}">
    @endif
    <div class="modal fade" @if(isset($question)) id="default_five_whys_questions_{{$question->id}}" @else
        id="default_five_whys_questions" @endif tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        Default Five Why's Question
                    </h4>
                    <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Question</label>
                        <textarea spellcheck="true"  type="text" name="question" class="form-control"
                            required>@if(isset($question)){{$question->question}}@endif</textarea>
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
</form>