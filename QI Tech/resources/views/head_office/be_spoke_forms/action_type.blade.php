@if($type == 'send_email')
    @include('head_office.be_spoke_forms.actions.email')
@elseif($type == 'add_priority_value')
    <div class="form-group">
        <label for="condition_action_value">Enter Priority Value </label>
        <input type="number" min="0" max="100" class="form-control w-50" name="condition_action_value" @if(isset($condition))
            value="{{$condition->condition_action_value}}" @endif placeholder="Enter the number value" required>
    </div>
@elseif($type == 'hide_section')  
    <div class="form-group">
        <label for="condition_action_value">Select Section to Hide </label>
        <select name="condition_action_value" class="form-control w-50">
            @foreach($question->selectAllGroupsofAForm() as $g)
                <option value="{{$g->id}}" @if(isset($condition) && $condition->condition_action_value == $g->id) selected  @endif>{{$g->group_name}}</option>
            @endforeach 
        </select>
    </div>
@elseif($type == 'hide_question')  
    <div class="form-group">
        <label for="condition_action_value">Select Question to Hide </label>
        <select name="condition_action_value" class="form-control w-50">
            @foreach($question->selectAllQuestionsofAForm() as $q)
                <option value="{{$q->id}}" @if(isset($condition) && $condition->condition_action_value == $q->id) selected  @endif>{{$q->question_name}}</option>
            @endforeach 
        </select>
    </div>
@elseif($type == 'show_question')  
    <div class="form-group">
        <label for="condition_action_value">Select Question to Show </label>
        <select name="condition_action_value" class="form-control w-50">
            @foreach($question->selectAllQuestionsofAForm() as $q)
                <option value="{{$q->id}}" @if(isset($condition) && $condition->condition_action_value == $q->id) selected  @endif>{{$q->question_name}}</option>
            @endforeach 
        </select>
    </div>
@elseif($type == 'trigger_root_cause_analysis') 
    <?php if(isset($condition)) $analysisModels = (array)json_decode($condition->condition_action_value,true); ?>
    <div class="form-group">
        <input type="checkbox" name="condition_action_value_5_whys" @if(isset($analysisModels['five_whys']) && $analysisModels['five_whys']) checked @endif value="5_whys" class="root_cause_checkbox"> <span  class="m-r-20">5 Why’s</span>
        <input type="checkbox" name="condition_action_value_5_whys_required"  @if(isset($analysisModels['five_whys_required']) && $analysisModels['five_whys_required']) checked @endif value="required" class="5_why_required"> <span>Optional/Mandatory</span>
    </div>
    <div class="form-group">
        <input type="checkbox" name="condition_action_value_fish_bone"  @if(isset($analysisModels['fish_bone']) && $analysisModels['fish_bone']) checked @endif value="fish_bone_model" class="root_cause_checkbox"> <span  class="m-r-20">Fish Bone Model</span>
        <input type="checkbox" name="condition_action_value_fish_bone_required"  @if(isset($analysisModels['fish_bone_required']) && $analysisModels['fish_bone_required']) checked @endif value="required" class="fish_bone_required"> <span>Optional/Mandatory</span>
    </div>
    <div class="form-group both_whys_required" @if(!isset($condition)) style="display:none" @endif>
        <label for="condition_action_value_1">User can complete just one mandatory type</label>
        <select name="condition_action_value_1" class="form-control w-50 ">
            <option value="yes" @if(isset($condition) && $condition->condition_action_value_1 == 'yes') selected   @endif>Yes</option>
            <option value="no"  @if(isset($condition) && $condition->condition_action_value_1 == 'no') selected   @endif>No</option>
        </select>
    </div>
@elseif($type == 'display_information_to_user') 
    @include('head_office.be_spoke_forms.actions.user-information')
@elseif($type == 'trigger_another_form')
    <div class="form-group">
        <label for="condition_action_value">Select Form</label>
        <select name="condition_action_value" class="form-control w-50">
            @foreach(App\Models\Forms\Form::where('id','!=',$question->form_id)->get() as $f)
                <option value="{{$f->id}}" @if(isset($condition) && $condition->condition_action_value == $f->id) selected  @endif>{{$f->name}}</option>
            @endforeach 
        </select>
    </div> 
    <div class="form-group">
        <label for="condition_action_value_1">Enter Message To Show</label>
        <input type="text" class="form-control w-50" name="condition_action_value_1" @if(isset($condition))
            value="{{$condition->condition_action_value_1}}" @endif placeholder="Enter the value" required>
    </div>
@elseif($type == 'auto_close_case')
<div class="form-group">
        <input type="checkbox" name="condition_action_value" 
        @if(isset($condition) && $condition->condition_action_value == 'add_case_note') checked @endif 
        value="add_case_note" class="case_close_checkbox"> <span  class="m-r-20">Automatically add case note when case auto-closed?</span>
</div>
<div class="form-group case_close_checkbox_msg" @if(!isset($condition)) style="display:none" @elseif($condition->condition_action_value != 'add_case_note')  style="display:none" @endif>
        <label for="condition_action_value_1">Enter Case Note</label>
        <input type="text" class="form-control w-50" name="condition_action_value_1" @if(isset($condition))
            value="{{$condition->condition_action_value_1}}" @endif placeholder="Enter the value">
    </div>
@elseif($type == 'create_custom_task_in_case_manager')
<?php if(isset($condition)) $customTask = (array)json_decode($condition->condition_action_value,true); ?>
<div class="form-group">
    <label for="condition_action_value[task_name]">Task Name</label>
    <input type="text" class="form-control w-50" name="condition_action_value[task_name]" @if(isset($customTask) && $condition->condition_action_type =='create_custom_task_in_case_manager' )
        value="{{$customTask['task_name']}}" @endif placeholder="Enter the value" required>
</div>
<div class="form-group">
    <label for="condition_action_value[task_description]">Task Description</label>
    <input type="text" class="form-control w-50" name="condition_action_value[task_description]" @if(isset($customTask) && $condition->condition_action_type =='create_custom_task_in_case_manager')
            value="{{$customTask['task_description']}}" @endif placeholder="Enter the value">
</div>
<div class="form-group">
    <label>Auto Assign To:</label>
    <select name="condition_action_value_1" class="form-control w-50">
        <option value="select_ho_user" @if(isset($condition) && $condition->condition_action_value_1 == 'select_ho_user') selected @endif >Select Ho User</option>
        <option value="select_profile_type" @if(isset($condition) && $condition->condition_action_value_1 == 'select_profile_type') selected @endif >Select Profile Type</option>
    </select>
</div>
<div class="form-group">
    <p>Upload Documents</p>
    <input type="file" name="files[]" multiple class="form-control w-50">
</div>

@if(isset($customTask) && isset($customTask['documents']))
<p>Files</p>
    <ol>
    @foreach($customTask['documents'] as $doc)
    <li><a taget="_blank" href="{{url($doc)}}">Preview</a></li>
    @endforeach
    </ol>
@endif
@elseif($type == 'add_user_to_case_manager' ||
        $type == 'donot_auto_close_case' )
    @include('head_office.be_spoke_forms.actions.no-action')
@endif