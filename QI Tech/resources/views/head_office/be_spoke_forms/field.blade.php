<script>
    let form_fields = JSON.parse('{!!json_encode(App\Models\Forms\StageQuestion::$fields)!!}');
</script>
@if(isset($question) && count($question->conditions)!== 0)<p class="alert"><i class="fa fa-alert"></i> To change question type please delete all actions associated with it. </p> @endif
<div class="row custom-field ">
    <div class="col-md-12 mt-2">
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="field_type">Type</label>
            <select name="field_type" class="form-control new_field_type" @if(isset($question) && count($question->conditions)!== 0) readonly  @endif>
                @foreach(App\Models\Forms\StageQuestion::$fields as $key => $data)
                <option value="{{$key}}" @if(isset($question) && $question->question_type == $key) selected  @endif >{{ucwords(str_replace('_',' ',$key))}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3 question_name">
        <div class="form-group ">
            <label for="question_name">Name</label>
            <input type="text" class="form-control" name="question_name" @if(isset($question)) value="{{$question->question_name}}" @endif placeholder="Enter the name of input field" required>
        </div>
    </div>
    <div class="col-md-3 question_label">
        <div class="form-group">
            <label for="field_label">Label</label>
            <input type="text" class="form-control" name="question_label" @if(isset($question)) value="{{$question->question_title}}" @endif placeholder="Enter the label of input field" required>
        </div>
    </div>
    <div class="col-md-3 question_required ">
        <div class="form-group text-center">
            <label for="field_is_required">Mandatory</label>
            <input type="checkbox" @if(isset($question) && $question->question_required) checked  @endif   name="question_required" value="1" class="form-control">
        </div>
    </div>
    
    <div class="col-md-3 form-group min">
            <label for="field_minimum">Min</label>
            <input type="number" id="field_minimum" name="field_minimum" @if(isset($question)) value="{{$question->question_min}}" @endif  min="0" value="" class="form-control">
    </div>
    <div class="col-md-3 form-group max">
            <label for="field_maximum">Max</label>
            <input type="number" id="field_maximum" name="field_maximum" @if(isset($question)) value="{{$question->question_max}}" @endif class="form-control ">
    </div>
    <div class="col-md-3 form-group options">
        
        @if(isset($question) && json_decode($question->question_values))
            @foreach(json_decode($question->question_values) as $value)
            <div class="input-group form-group mb-3">
                <label for="field_options">Options</label>
                <input type="text" id="field_options" name="field_options[]" value="{{$value}}" class="form-control">
                <div class="input-group-prepend">
                    <span class="input-group-text minus">
                        <i class="fas fa-fw fa-minus "></i>
                    </span>
                </div>
            </div>
            @endforeach
            @endif
            <div class="clone_row">
            <div class="input-group form-group mb-3">
                <label for="field_options">Option</label>
                <input type="text" id="field_options" name="field_options[]" class="form-control">
                <div class="input-group-prepend">
                    <span class="input-group-text minus" style="display:none">
                        <i class="fas fa-fw fa-minus "></i>
                    </span>
                    <span class="input-group-text plus">
                        <i class="fas fa-fw fa-plus"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 form-group multi_select">
        <label for="field_select_multiple">Can Select Multiple</label>
        <input type="checkbox" value="1" @if(isset($question) && $question->question_select_multiple) checked  @endif name="field_select_multiple" class="form-control">
    </div>
    <div class="col-md-3 form-group select_loggedin_user">
        <label for="field_select_loggedin_user">Select LoggedIn User</label>
        <input type="checkbox" value="1" @if(isset($question) && $question->question_select_loggedin_user) checked  @endif name="field_select_loggedin_user" class="form-control">
    </div>
    
    <div class="col-md-3 form-group select_loggedin_user_changed" @if(isset($question) && !$question->question_select_loggedin_user) style="display:none" @elseif(!isset($question)) style="display:none" @endif >
        <label for="select_loggedin_user_changed">Allow This To Be Changed</label>
        <input type="checkbox" class="form-control" @if(isset($question) && $question->question_extra_value == 'select_loggedin_user_changed') checked  @endif name="field_allow_change" value="select_loggedin_user_changed"> 
    </div>

    <div class="col-md-3 address_specific" @if(isset($question) && $question->question_type == 'address') @else style="display:none" @endif>
        <div class="form-group">
            <label for="adress_type">Address Type</label>
            <select name="question_extra_value" class="form-control address_type">
                <option value="free_type" @if(isset($question) && $question->question_extra_value =='free_type') selected  @endif >Free Type Goole Api Address</option>
                <option value="locations" @if(isset($question) && $question->question_extra_value == 'locations') selected  @endif>Location Accounts</option>
            </select><br>
            <div class="address_select_loggedIn" @if(isset($question) && $question->question_extra_value != 'locations') style="display:none" @endif >
                <input type="checkbox"  @if(isset($question) && $question->question_extra_value_1 == 'select_logged_in_address') checked  @endif name="question_extra_value_1" value="select_logged_in_address"> <span>Autoselect Location User Signed Into</span>
            </div>
           
        </div>
    </div>
    <div class="col-md-3 is_case_description ">
        <div class="" style="margin-top: 22px;">
            <label for="is_case_description">Use for case description ?</label>
            <input type="checkbox" @if(isset($question) && $question->id == $question->form->case_description_field) checked  @endif   name="case_description_field" value="1" class="">
        </div>
    </div>
    

    @if(isset($question))
<input type="hidden" value="{{$question->default_field_id}}" name="selected_field_id" id="selected_field_id">
@endif
    @if(count($stage->form->formCards) > 0)
    
    <div class="col-md-3 form-group">
        <label for="adress_type">Select Card</label>
        <select name="form_card_id" class="form-control form_card_id">
            <option value="0">Select Option</option>
            @foreach ($stage->form->formCards as $card)
            <option value="{{$card->id}}"  @if(isset($question)) @if($card->id == $question->form_card_id) selected  @endif @endif >{{$card->name}}</option>
            @endforeach
        </select>

</div>
<div class="col-md-3 form-group card_fields">
       
    <label for="adress_type">Select Field</label>
    <select name="default_field_id" class="form-control default_field_id" id="default_field_id">
        @foreach ($default_fields as $default_field)
            <option value="{{$default_field->id}}" @if(isset($question) && $question->default_field_id == $default_field->id) selected @endif>{{$default_field->field_name}}</option>
        @endforeach
    </select>

</div>
<div class="col-md-3 form-group card_fields">
       
    <label for="gdpr_tag">Select GDPR Tag</label>
    <select name="gdpr_tag" class="form-control" id="gdpr_tag">
        <option value="0">Select Tag</option>
        @foreach ($gdpr_tags as $gdpr_tag)
        
            <option value="{{$gdpr_tag->id}}" @if(isset($question) && $question->gdpr_form_field && $question->gdpr_form_field->gdpr_tag->id == $gdpr_tag->id) selected @endif>{{$gdpr_tag->tag_name}}</option>
        @endforeach
    </select>

</div>

<input type="hidden" value="{{route('head_office.be_spoke_form.form_card_fields')}}" name="form_card_fields" id="form_card_fields">

@endif
</div>

