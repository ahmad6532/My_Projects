<div class="parent_div mt-3">
    <style>
        select {
            cursor: pointer;
            background: #F2F6F7;
            border-radius: 5px;
            padding: 5px
        }
        input,select{
            border:1px solid #dee2e6!important;
        }
        .select2-container{
            width: 100% !important;
        }
    </style>
    <p class="fw-semibold text-info" style="font-size: 14px;"> Task Deadline</p>
    <div class="d-flex align-items-center">
        <label class="inputGroup mb-0 d-flex align-items-center" style="width: fit-content;">Set Task Deadline&nbsp;&nbsp;
        </label>
        <input style="width: fit-content;flex:unset;" type="checkbox" name="is_dead_line"
            @if (isset($task)) id="is_dead_line_{{ $task->id }}" @endif value="1"
             class="  is_dead_line">
        <div class="deadline-wrap" style="display: none">
            {{-- <input class="form-control custom-input " style="max-width: 50px;" type="number" oninput="fitSize(event)"
                        min="0" max="1000"  
                        value="0"> --}}

            <input type="number" name="dead_line_duration"
                value="1" min="1"
                style="width: 50px;background:rgba(242, 246, 247, 255);" class="mx-2 rounded dead_line_duration">
            <select class=" w-auto  dead_line_units" name="dead_line_unit" id="dead_line_unit">
                <option value="days" >Days
                </option>
                <option value="weeks" >Weeks
                </option>
                <option value="months" >Months
                </option>
                <option value="years">Years
                </option>
            </select>
            </label>

        </div>
    </div>


    <div class="hide_all"  style="display: none">


        <div class="row dead_line_option mt-2">
            <div class="">
                <label class="inputGroup">From
                    <select name="dead_line_start_from" id="" class=" w-auto dead_line_start_from">
                        <option value="">Select option</option>
                        <option value="incident_date">Incident Date</option>
                        <option value="reported_date">Reported Date</option>
                        <option value="task_started">Task start</option>
                        <option value="task_complete">Task complete</option>
                        <option value="stage_started">Stage start</option>
                        <option value="stage_complete">Stage complete</option>
                    </select>
                </label>
            </div>
            <div class="incident_date_select_field_wrap mt-2 w-100" style="display: none;">
                <select name="incident_date_selected" class=" w-100  select2 incident_date_select_field">
                    @if (!empty($incident_date_items))
                        <option value="0" disabled>Select Field</option>
                        @foreach ($incident_date_items as $incident_date_item)
                            <option value="{{ $incident_date_item['id'] }}"
                                >{{ $incident_date_item['label'] }}
                            </option>
                        @endforeach
                    @else
                        <option value="" disabled>No Field Marked as Incident Date</option>
                    @endif
                </select>
            </div>
            <div class="task_started_select_field_wrap" style="display: none;">
                <select name="task_started_selected" class=" w-auto select2 task_select_field">
                    @if (!empty($case->tasks()))
                        <option value="0">Select a task</option>
                        @foreach ($case->tasks() as $task_opt)
                            <option value="{{ $task_opt->id }}" >
                                {{ $task_opt->title }}</option>
                        @endforeach
                    @else
                        <option value="">No Task yet.</option>
                    @endif
                </select>
            </div>
            <div class="task_completed_select_field_wrap" style="display: none;">
                <select name="task_completed_selected" class=" w-auto select2 task_select_field">
                    @if (!empty($case->tasks()))
                        <option value="0">Select a task</option>
                        @foreach ($case->tasks() as $task_opt)
                            <option value="{{ $task_opt->id }}" >
                                {{ $task_opt->title }}</option>
                        @endforeach
                    @else
                        <option value="0" disabled>No Task yet.</option>
                    @endif
                </select>
            </div>
            <div class="stage_started_select_field_wrap" style="display: none;">
                <select name="stage_started_selected" class=" w-auto select2 task_select_field">
                    @if (isset($case) && !empty($case->stages()->get()))
                        <option value="0" disabled selected>Select a stage</option>
                        @foreach ($case->stages()->get() as $stage_opt)
                            <option value="{{ $stage_opt->id }}" >
                                {{ $stage_opt->name }}</option>
                        @endforeach
                    @else
                        <option value="0">No Stage yet.</option>
                    @endif
                </select>
            </div>
            <div class="stage_completed_select_field_wrap" style="display: none;">
                <select name="stage_completed_selected" class=" w-auto select2 task_select_field">
                    @if (isset($case) && !empty($case->stages()->get()))
                        <option value="0">Select a stage</option>
                        @foreach ($case->stages()->get() as $task_opt)
                            <option value="{{ $task_opt->id }}" >
                                {{ $task_opt->name }}</option>
                        @endforeach
                    @else
                        <option value="" disabled>No Task yet.</option>
                    @endif
                </select>
            </div>

            <div class="">
                    <label class="inputGroup my-1">Then
                        <select name="dead_line_option" class=" w-auto  dead_line_option_select">

                            <option value="do_nothing" selected>Do nothing
                            </option>
                            <option value="move_task_to_another_user_random"
                                >Move task to another user (random)
                            </option>
                            <option value="move_user" >Move task to
                                specific user</option>
                            <option value="move_profile" >Move task
                                to another user with a user profile of...</option>
                            <option value="mail_user" >Email specific
                                user</option>
                            <option value="mail_profile" >Email
                                person with user profile…</option>
                            <option value="mail_custom" >Custom
                                Profile</option>
                        </select>
                    </label>
            </div>



            <div class=" dead_line_users" style="display: none;">
                @php
                    $user = Auth::guard('web')->user()->selected_head_office;
                @endphp

                <label class="inputGroup">
                    Select Users
                    <select class=" w-auto  dead_line_user_option select_2" name="dead_line_user[]" multiple="multiple">
                        @foreach ($user->users as $u)
                            <option value="{{ $u->user->id }}" >
                                {{ $u->user->name }}
                            </option>
                        @endforeach
                    </select>
                </label>
            </div>


            <div class=" dead_line_profiles"  style="display: none;">

                <label class="inputGroup">
                    Select Profiles
                    <select class=" w-auto  select_2" name="dead_line_profile[]" multiple="multiple">
                        @foreach ($user->head_office_user_profiles as $user)
                            <option value="{{ $user->id }}" >
                                {{ $user->profile_name }}
                            </option>
                        @endforeach
                    </select>
                </label>
            </div>
            <div class=" dead_line_email_profile" style="display: none;">

                <label class="inputGroup">
                    Please specify
                    <select class=" w-auto  dead_line_user_option select_2" name="dead_line_user_email_profile">
                        <option value="1">Email all users with this profile</option>
                        <option value="2">Email single user with this profile (random)</option>
                    </select>
                </label>
            </div>
            <div class=" dead_line_email_tag"  style="display: none;">
                <p class="m-0 fw-semibold">Please specify emails</p>
                <select name="custom_dead_line_emails[]" multiple="multiple" class="w-100 select_2_custom"
                    style="width: 100%">
                    <option value="0" disabled>Type a valid Email</option>
                </select>
                <label class="inputGroup">Custom Email
                    <textarea spellcheck="true"  name="dead_line_email_template" class="form-control border mt-2 task-rich" id="" cols="30"
                        rows="10"></textarea>
                </label>
            </div>
        </div>


        {{-- task over user --}}
        <p class="fw-semibold text-info mt-3 mb-1" style="font-size: 14px;">If Overdue</p>
        <div class="d-flex align-items-center">
            <label class="inputGroup d-flex align-items-center" style="width: fit-content;margin-bottom:0px;">If task
                overdue&nbsp;&nbsp;
                <input type="checkbox" name="is_task_over_due" id="is_task_over_due"
                     value="1"
                    class=" w-auto  is_task_over_due">
            </label>
            <div class="over_due_wrap" style="display: none">
                <input style="width: 50px;background:rgba(242, 246, 247, 255);" class="mx-2 rounded" type="number"
                    name="over_due_duration"
                    value="1"
                    min="1" class="">
                <select class="  w-auto  dead_line_units" name="over_due_unit">
                    <option value="days" >Days</option>
                    <option value="weeks">Weeks
                    </option>
                    <option value="months" >Months
                    </option>
                    <option value="years" >Years
                    </option>
                </select>
            </div>
        </div>
        <!-- Over Due Section -->
        <div class="over_due_date"  style="display: none;">
            <div class="">
                <label class="inputGroup my-1">Then
                    <select name="over_due_line_option" class=" w-auto  over_due_option_select">

                        <option value="do_nothing" selected>Do nothing
                        </option>
                        <option value="move_task_to_another_user_random"
                            >Move task to another user (random)
                        </option>
                        <option value="move_user" >Move task to
                            specific user</option>
                        <option value="move_profile" >Move task
                            to another user with a user profile of...</option>
                        <option value="mail_user" >Email specific
                            user</option>
                        <option value="mail_profile" >Email
                            person with user profile…</option>
                        <option value="mail_custom" >Custom
                            Profile</option>
                    </select>
                </label>
        </div>



        <div class=" over_due_users" style="display: none;">
            @php
                $user = Auth::guard('web')->user()->selected_head_office;
            @endphp

            <label class="inputGroup">
                Select Users
                <select class=" w-auto  over_due_user_option select_2" name="over_due_user[]" multiple="multiple">
                    @foreach ($user->users as $u)
                        <option value="{{ $u->user->id }}" @if (isset($task) &&
                                is_null($task->over_due_user_id) &&
                                ($task->over_due_option == 'move_user' &&
                                    $task->over_due_user_id &&
                                    $u->user &&
                                    in_array($u->user->id, json_decode($task->over_due_user_id)))) selected @endif>
                            {{ $u->user->name }}
                        </option>
                    @endforeach
                </select>
            </label>
        </div>


        <div class=" over_due_profiles" style="display: none;">

            <label class="inputGroup">
                Select Profiles
                <select class=" w-auto  select_2" name="over_due_profile[]" multiple="multiple">
                    @foreach ($user->head_office_user_profiles as $user)
                        <option value="{{ $user->id }}" @if (isset($task) &&
                                $task->move_profile == 'move_profile' &&
                                $task->over_due_profile_id &&
                                in_array($user->id, json_decode($task->over_due_profile_id))) selected @endif>
                            {{ $user->profile_name }}
                        </option>
                    @endforeach
                </select>
            </label>
        </div>
        <div class=" over_due_email_profile" style="display: none;"> 
            <label class="inputGroup">
                Please specify
                <select class=" w-auto  over_due_user_option select_2" name="over_due_user_email_profile">
                    <option value="1">Email all users with this profile</option>
                    <option value="2">Email single user with this profile (random)</option>
                </select>
            </label>
        </div>
        <div class=" over_due_email_tag" style="display: none;">
            <p class="m-0 fw-semibold">Please specify emails</p>
            <select name="custom_over_due_emails[]" multiple="multiple" class="w-100 select_2_custom"
                style="width: 100%">
                <option value="0" disabled>Type a valid Email</option>
            </select>
            <label class="inputGroup">Custom Email
                <textarea spellcheck="true"  name="task_over_due_email_template" class="form-control border mt-2 task-rich" id="" cols="30"
                    rows="10"></textarea>
            </label>
        </div>
        </div>

        <div class="d-flex justify-content-between mt-2">
            <div></div>
            <button data-toggle="tooltip" data-bs-placement="left" title="Adds more task deadline"
                type="submit" class="primary-btn dead-line-more">Add more...</button>
        </div>
    </div>

</div>
<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        $(document).ready(function() {
            $('.select_2').select2();

            $('.select_2_custom').select2({
                tags: true,
                createTag: function(params) {
                    var term = $.trim(params.term);

                    if (validateEmail(term)) {
                        return {
                            id: term,
                            text: term,
                            newTag: true // add additional parameters
                        };
                    }

                    return null;
                },
                insertTag: function(data, tag) {
                    // Insert the tag only if it is valid
                    if (tag.newTag) {
                        data.push(tag);
                    }
                }
            });
        });

        function validateEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    });

    $('.dead_line_start_from').on('change', function(e) {
        const $this = $(this);
        const fieldWrapClasses = {
            'incident_date': '.incident_date_select_field_wrap',
            'task_started': '.task_started_select_field_wrap',
            'task_complete': '.task_completed_select_field_wrap',
            'stage_started': '.stage_started_select_field_wrap',
            'stage_complete': '.stage_completed_select_field_wrap'
        };

        $.each(fieldWrapClasses, function(value, className) {
            const $fieldWrap = $this.parent().parent().siblings().closest(className);
            if (e.target.value === value) {
                $fieldWrap.slideDown();
            } else {
                $fieldWrap.slideUp();
            }
        });
    });
</script>
