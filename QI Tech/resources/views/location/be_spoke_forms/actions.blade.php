@extends('layouts.location_app')
@section('title', 'Bespoke Form Actions')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('be_spoke_forms.be_spoke_form.index')}}">Bespoke Forms</a></li>
        <li class="breadcrumb-item"><a href="{{route('be_spoke_forms_templates.form_template',$stage->form_id)}}">Form -
                {{substr($stage->form->name,0,30)}} </a></li>
        <li class="breadcrumb-item"><a href="{{route('be_spoke_forms_templates.form_template',$stage->form_id)}}">Stage
                - {{substr($stage->stage_name,0,30)}} </a></li>
        <li class="breadcrumb-item">Group</li>
        <li class="breadcrumb-item "><a
                href="{{route('be_spoke_forms_templates.form_stage_questions',[$question->stage_id,$question->group_id])}}">Questions</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
        <a href="{{route('be_spoke_forms_templates.form_stage_questions.action',$question->id)}}">Actions</a></li>
    </ol>
</nav>
<div class="card">
    @include('layouts.error')
    <div class="card-header float-left">
        <h4 class="text-info font-weight-bold">Question - {{$question->question_name}}</h4>
    </div>
    <div class="card-body">
        <!-- Custom Designs starts from here -->
        <h4>Please add actions based on question result</h4>
        <form name="fields_form" method="post" enctype="multipart/form-data" 
            action="{{route('be_spoke_forms_templates.form_stage_questions.action.save',$question->id)}}">
            @csrf
            <input type="hidden" name="question_id" class="question_id" id="question_id" value="{{$question->id}}">
            <input type="hidden" name="condition_id" class="condition_id" id="condition_id" @if(isset($condition)) value="{{$condition->id}}"  @endif>
           
            <div class="table-responsive">
                <table class="table  table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Condition(s) for: {{$question->question_name}}</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($question->conditions as $key=>$con)
                        <tr>
                            <td>
                                {{$key+1}}. {{$con->showConditionTitle($question->question_type)}} -
                                {{App\Models\Forms\ActionCondition::showActionTitle($con->condition_action_type)}}
                            </td>
                            <td>
                            <a class="btn btn-info" href="{{route('be_spoke_forms_templates.form_stage_questions.action.edit',[$question->id,$con->id])}}"><i class="fas fa-edit"></i> Edit Condition</a>
                                <a class="btn btn-danger delete_action"
                                    href="{{route('be_spoke_forms_templates.form_stage_questions.action.condition.delete',['condition_id'=>$con->id,'_token'=>csrf_token()])}}"><i
                                        class="fas fa-times"></i> Delete Action</a>
                            </td>
                        </tr>
                        @endforeach
                        @if(count($question->conditions) == 0)
                            <tr><td colspan="2">No Conditions found.</td></td>
                        @endif
                    </tbody>
                </table>
                <div class="card-body">
                @if(isset($condition))<h4 class="text text-primary">Editing Condition</h4>@else 
                <h4 class="text">New Condition</h4>
                @endif
                <div>Fields are shown based on question type <span class="badge badge-primary">{{$question->question_type}}</span></div>
                @if( $question->question_type=='text' ||
                $question->question_type=='textarea' ||
                $question->question_type=='dm+d' ||
                $question->question_type=='address')
                <div class="">
                    <div class="form-group ">
                        <input type="hidden" name="if_value" value="word_detected">
                        <label for="condition_value">If Word Detected</label>
                        <input type="text" class="form-control w-50" name="condition_value" @if(isset($condition))
                            value="{{$condition->condition_value}}" @endif
                            placeholder="Enter the word that has to be detected" required>
                    </div>
                </div>
                @endif
                @if($question->question_type == 'number' ||
                $question->question_type=='age' ||
                $question->question_type=='5x5_risk_matrix')
                <div class="">
                    <div class="form-group ">
                        <label for="if_value">If Value</label>
                        <select name="if_value" id="if_value" class="form-control w-50">
                            <option value="greater_then" @if(isset($condition) && $condition->condition_if_value =='greater_then' ) selected @endif>Greater Then</option>
                            <option value="less_then" @if(isset($condition) && $condition->condition_if_value =='less_then' ) selected @endif>Less Then</option>
                            <option value="between" @if(isset($condition) && $condition->condition_if_value =='between' ) selected @endif>Between</option>
                            <option value="equal_to" @if(isset($condition) && $condition->condition_if_value =='equal_to' ) selected @endif>Equal to</option>
                        </select>
                    </div>
                    <div class="form-group ">
                        <label for="condition_value">Value</label>
                        <input type="number" class="form-control w-50" name="condition_value" @if(isset($condition))
                            value="{{$condition->condition_value}}" @endif
                            placeholder="Enter the number value" required>
                        <input @if(isset($condition) && $condition->condition_if_value !='between' )  style="display:none" @endif @if(!isset($condition)) style="display:none"  @endif type="number" class="form-control w-50 m-t-10 condition_value_2"
                            name="condition_value_2" @if(isset($condition)) value="{{$condition->condition_value_2}}" @endif
                            placeholder="Enter the number value">
                    </div>
                </div>
                @endif
                @if($question->question_type == 'date')
                <div class="">
                    <div class="form-group ">
                        <label for="if_value">If Entered Date</label>
                        <select name="if_value" id="if_value" class="form-control w-50">
                            <option value="less_then"  @if(isset($condition) && $condition->condition_if_value =='less_then' ) selected @endif>Days Before Reported Date</option>
                            <option value="greater_then"  @if(isset($condition) && $condition->condition_if_value =='greater_then' ) selected @endif>Days After Reported Date</option>
                        </select>
                    </div>
                    <div class="form-group ">
                        <label for="condition_value">Value in Days</label>
                        <input type="number" class="form-control w-50" name="condition_value" @if(isset($condition))
                            value="{{$condition->condition_value}}" @endif
                            placeholder="Enter the number of days" required>
                    </div>
                </div>

                @endif

                @if($question->question_type =='radio'||
                $question->question_type=='checkbox' ||
                $question->question_type=='select')

                <div class="">
                    <input type="hidden" name="if_value" value="option_selected">
                    <div class="options">
                    @if(isset($condition) && json_decode($condition->condition_value))
                    @foreach(json_decode($condition->condition_value) as $value)
                    <div class="input-group form-group mb-3 w-50">
                        <label for="condition_value">If Value Selected</label>
                        <input type="text" class="form-control w-50" name="condition_value[]" value="{{$value}}">
                        <div class="input-group-prepend">
                            <span class="input-group-text minus">
                                <i class="fas fa-fw fa-minus "></i>
                            </span>
                        </div>
                    </div>
                    @endforeach
                    @endif
                        <div class="clone_row">
                            <div class="form-group input-group mb-3 w-50 ">
                                <label for="condition_value">If Value(s) Selected</label>
                                <input type="text" class="form-control w-50" name="condition_value[]">
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
                </div>
                @endif

                @if($question->question_type =='time')

                @endif
                @if($question->question_type=='user')

                @endif

                @if($question->question_type=='user_type')

                @endif
                <div class="">
                    @if($question->question_type !=='time'
                    && $question->question_type !== 'user'
                    && $question->question_type !== 'user_type')

                    <div class="form-group"><label for="action_type">Then</label>
                        <select
                            data-href="{{route('be_spoke_forms_templates.form_stage_questions.action.type_details')}}"
                            name="action_type" id="action_type" class="form-control w-50 action_type">
                            @foreach(App\Models\Forms\ActionCondition::$actions as $key=>$a)
                            <option value="{{$key}}"  @if(isset($condition) && $condition->condition_action_type == $key) selected @endif>{{$a}}</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <p>No action can be added for this question.</p>
                    @endif
                </div>
                </div>
            </div>

        <div class="card-body">
            @if($question->question_type !=='time' && $question->question_type !== 'user' && $question->question_type
            !== 'user_type')
            <h4>Action Details</h4>
            <div class="action_details">
                <!-- Action Type needed -->
                @if(isset($condition))
                <?php $type = $condition->condition_action_type ?>
                @else
                <?php $type = 'send_email' ?>
                @endif
                @include('location.be_spoke_forms.action_type')
            </div>
            <button type="submit" name="submit" class="nav-link btn btn-info inline"><i class="fas fa-save"></i> Save
                Action</button>
            @endif
        </div>
        </div>
    </form>
    <!-- End custom design -->

</div>

<div class="card-footer text-center">

</div>

<!-- Modal -->
<div class="modal modal-md fade" id="stage_groups_model" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mt-2">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection
@section('styles')
<link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">

@endsection
@section('scripts')
<script src="{{asset('js/alertify.min.js')}}"></script>
<script src="{{asset('/tinymce/tinymce.min.js')}}"></script>
<script src="{{asset('/tinymce/tinymce-jquery.min.js')}}"></script>
<!-- <script src="{{asset('tinymce/themes/silver/theme.min.js')}}"></script> -->
@include('location.be_spoke_forms.script')
@endsection