<input type="hidden" name="action_email_id" @if(isset($actionEmail)) value="{{$actionEmail->id}}" @endif>
<div class="row">
    <div class="">
        <div class="form-group">
            <label for="email_type">Send Email To</label>
            <select name="send_email_type" id="email_type" class="form-control email_type">
                <option value="free_type_email"  @if(isset($actionEmail) && $actionEmail->send_email_type == 'free_type_email') selected @endif >Free Type Email</option>
                <option value="head_office_profile_type" @if(isset($actionEmail) && $actionEmail->send_email_type == 'head_office_profile_type') selected @endif >Head Office Profile Type</option>
                <option value="reported_by" @if(isset($actionEmail) && $actionEmail->send_email_type == 'reported_by') selected @endif>Reported By User</option>
                <option value="user_selected_in_question_x"  @if(isset($actionEmail) && $actionEmail->send_email_type == 'user_selected_in_question_x') selected @endif>User Selected In Question X</option>
            </select>
        </div>
        
        <div class="form-group free_type_email" @if(isset($actionEmail) && $actionEmail->send_email_type != 'free_type_email') style="display:none"  @endif>
            <label for="free_type_email">Email Address</label>
            <input type="email" class="form-control " name="free_type_email" @if(isset($actionEmail) && $actionEmail->send_email_type == 'free_type_email') value="{{$actionEmail->free_type_email}}"       @endif>
        </div>
        <div class="form-group user_select_question_x" @if(isset($actionEmail) && $actionEmail->send_email_type != 'user_selected_in_question_x') style="display:none" @elseif(!isset($actionEmail))  style="display:none" @endif>
            @if(count($question->questionsOnlyUserType()) == 0)
                <p class="text text-danger m-t-10">No user questions are found. Please add some questions with question type "User"</p>
            @else 
            <label for="user_select_question_x">Please Select Question X</label>
            <select name="email_question_id" class="form-control email_question_id" >
            @foreach($question->questionsOnlyUserType() as $q)
                <option value="{{$q->id}}" @if(isset($actionEmail) && $actionEmail->email_question_id == $q->id) selected  @endif>{{$q->question_name}}</option>
            @endforeach 
            </select>
            @endif
        </div>
        <div class="form-group">
            <label for="email_message">Message</label>
            <textarea spellcheck="true"  name="email_message" class="form-control tinymce" required> @if(isset($actionEmail)) {{$actionEmail->email_message}} @endif</textarea>
        </div>
        <div class="form-group">
            <p for="email_attachment">Attachment 
                @if(isset($actionEmail) && !empty($actionEmail->email_attachment)): 
                    <a target="_blank" class="preview-email-attachment btn btn-success" href="{{route('head_office.be_spoke_forms_templates.form_stage_questions.email.attachment.view',$actionEmail->id)}}" >View Attachment</a>
                    <a class='delete-email-attachment btn btn-danger' href="{{route('head_office.be_spoke_forms_templates.form_stage_questions.email.attachment.delete',['action_id'=>$actionEmail->id,'_token'=>csrf_token()])}}">Remove Attachment</a>
                @endif
            </p>
            <input type="file" name="email_attachment" class="form-control">
        </div>
    </div>
</div>