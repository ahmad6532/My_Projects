<input type="hidden" name="action_email_id" @if(isset($actionEmail)) value="{{$actionEmail->id}}" @endif>
<h6 class="text">Please Add Information For User</h6>
<div class="row">
    <textarea spellcheck="true"  name="condition_action_value" class="tinymce">@if(isset($condition)) {{$condition->condition_action_value}}@endif</textarea>
</div>