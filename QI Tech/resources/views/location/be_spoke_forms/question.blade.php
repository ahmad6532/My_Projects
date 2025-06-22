{{-- <div class="col-md-6"> --}}
    <script>
        conditions[{{$question->id}}] = [];
    </script>
    @if(count($question->conditions))
    @foreach($question->conditions as $c)
    @if($c->condition_action_type =='hide_section'|| $c->condition_action_type =='hide_question' || $c->condition_action_type =='show_question')
    <?php $temp = array('question_id' =>$c->question_id, 
                        'condition_if_value' => $c->condition_if_value,
                         'condition_value' => $c->condition_value ,
                         'condition_value_2' => $c->condition_value_2,
                         'condition_action_type' => $c->condition_action_type,
                         'condition_action_value' => $c->condition_action_value,
                         'question_type' => $question->question_type);
        if($question->question_type == 'radio' || $question->question_type == 'checkbox' || $question->question_type == 'select'){

            $temp['condition_value'] = (json_decode($temp['condition_value'],true))?json_decode($temp['condition_value'],true):[];
        }
        ?>
    <script>
        condition =[];
        condition = JSON.parse('{!!json_encode($temp)!!}');
        conditions[{{$c->question_id}}].push(condition);
    </script>
    @endif
    @endforeach
    @endif
    @if($question->question_type =='text')
    <div class="question_{{$question->id}}">
        <label for="question_{{$question->id}}" class="inputGroup">{{$question->question_title}}:
            <input type="text" name="question_{{$question->id}}" id="question_{{$question->id}}" class="form_question"
            @if($question->question_required) required @endif
            @if($question->question_min) minlength="{{$question->question_min}}" @endif
            @if($question->question_max) maxlength="{{$question->question_max}}" @endif
            
            @if(isset($record))
                @foreach ($record->data as $data)
                    @if($data->question_id == $question->id)
                        value="{{$data->question_value}}"
                    @endif
                @endforeach
            @endif
            >
        </label>
    </div>
    @elseif($question->question_type =='number')
    <div class="question_{{$question->id}}">
        <label for="question_{{$question->id}}" class="inputGroup">{{$question->question_title}}
            <input type="number" name="question_{{$question->id}}" id="question_{{$question->id}}" class="form_question"
            @if($question->question_required) required @endif
            @if($question->question_min) min="{{$question->question_min}}" @endif
            @if($question->question_max) max="{{$question->question_max}}" @endif
            
            @if(isset($record))
                @foreach ($record->data as $data)
                    @if($data->question_id == $question->id)
                        value="{{$data->question_value}}"
                    @endif
                @endforeach
            @endif
            >
        </label>
    </div>
    @elseif($question->question_type =='date')
    <div class="question_{{$question->id}}">
        <label for="question_{{$question->id}}" class="inputGroup">{{$question->question_title}}
            <input type="date" name="question_{{$question->id}}" id="question_{{$question->id}}" class=" form_question"
            @if($question->question_required) required @endif
            @if($question->question_min) min="{{$question->question_min}}" @endif
            @if($question->question_max) max="{{$question->question_max}}" @endif
            @if(isset($record))
                @foreach ($record->data as $data)
                    @if($data->question_id == $question->id)
                        value="{{$data->question_value}}"
                    @endif
                @endforeach
            @endif
            >
        </label>
    </div>
    @elseif($question->question_type =='time')
    <div class="question_{{$question->id}}">
        <label for="question_{{$question->id}}" class="inputGroup">{{$question->question_title}}
            <input type="time" name="question_{{$question->id}}" id="question_{{$question->id}}" class="form_question"
            @if($question->question_required) required @endif
            @if($question->question_min) min="{{$question->question_min}}" @endif
            @if($question->question_max) max="{{$question->question_max}}" @endif
            
            @if(isset($record))
                @foreach ($record->data as $data)
                    @if($data->question_id == $question->id)
                    @php
                        $time = \Carbon\Carbon::createFromFormat($data->question_value,'H:i:s')
                    @endphp
                        value="{{$data->question_value}}"
                    @endif
                @endforeach
            @endif
            >
        </label>
    </div>
    @elseif($question->question_type =='radio')
    <div class="question_{{$question->id}}">
        <label for="question_{{$question->id}}" class="inputGroup">{{$question->question_title}}
            <div class=" border-checkbox-radio">
                @if(json_decode($question->question_values))
                @foreach(json_decode($question->question_values) as $value)
                <div class="radio-value">
                    <input type="radio" name="question_{{$question->id}}"  value='{{$value}}' class="checkbox-radio form_question" @if($question->question_required) required @endif
                    
                    @if(isset($record))
                    @foreach ($record->data as $data)
                        @if($data->question_id == $question->id)
                            checked
                        @endif
                    @endforeach
                    @endif
                    >
                    <label>{{$value}}</label>
                </div>
                @endforeach
                @endif
            </div>
        </label>
    </div>
    @elseif($question->question_type =='checkbox')
    <div class="question_{{$question->id}}">
        <label for="question_{{$question->id}}" class="inputGroup">{{$question->question_title}}
            <div class=" border-checkbox-radio">
                @if(json_decode($question->question_values))
                @foreach(json_decode($question->question_values) as $value)
                <div class="checkbox-value">
                    <input type="checkbox" name="question_{{$question->id}}[]"  value='{{$value}}' class="checkbox-radio form_question" @if($question->question_required) required @endif>
                    <label>{{$value}}</label>
                </div>
                @endforeach
                @endif
            </div>
        </label>
    </div>
    @elseif($question->question_type =='select')
    <div class="question_{{$question->id}}">
        <label for="question_{{$question->id}}" class="inputGroup">{{$question->question_title}}
            <select name="question_{{$question->id}}[]" class="form_question"
                @if($question->question_required) required @endif 
                @if($question->question_select_multiple) multiple @endif   >
                @if(json_decode($question->question_values))
                @foreach(json_decode($question->question_values) as $value)
                    <option value="{{$value}}"

                    @if(isset($record))
                    @foreach ($record->data as $data)
                        @if($data->question_id == $question->id)
                            selected
                        @endif
                    @endforeach
                    @endif

                    >{{$value}}</option>
                @endforeach
                @endif
            </select>
        </label>
    </div>
    @elseif($question->question_type =='textarea')
    <div class="question_{{$question->id}}">
        <label for="question_{{$question->id}}" class="inputGroup">{{$question->question_title}}
            <textarea spellcheck="true"  type="text" name="question_{{$question->id}}" id="question_{{$question->id}}" class="form-control form_question"
            @if($question->question_required) required @endif
            @if($question->question_min) minlength="{{$question->question_min}}" @endif
            @if($question->question_max) maxlength="{{$question->question_max}}" @endif>@if(isset($record))
            @foreach ($record->data as $data)
                @if($data->question_id == $question->id)
                    {{$data->question_value}}
                @endif
            @endforeach
            @endif
        </textarea>
    </label>
    </div>
   
    @elseif($question->question_type =='user')
    <div class="question_{{$question->id}}">
        <label for="question_{{$question->id}}" class="inputGroup">{{$question->question_title}}
            <select name="question_{{$question->id}}[]" class="form_question"
                @if($question->question_required) required @endif 
                @if($question->question_select_multiple) multiple @endif   >
                    @foreach($form->usersToDisplay() as $value)
                        <option value="{{$value->id}}"
                            @if($question->question_select_loggedin_user) @if(Auth::guard('web')->user() && Auth::guard('web')->user()->id == $value->id ) selected @endif  @endif

                            @if(isset($record))
                            @foreach ($record->data as $data)
                                @if($data->question_id == $question->id)
                                    selected
                                @endif
                            @endforeach
                        @endif

                        >{{$value->name}} - {{$value->email}}</option>
                    @endforeach
            </select>
        </label>
    </div>

    @elseif($question->question_type =='dm+d')
    <div class="question_{{$question->id}}">
        <label for="question_{{$question->id}}" class="inputGroup">{{$question->question_title}}
            <input type="text"  name="question_{{$question->id}}" id="question_{{$question->id}}" class="form-control form_question drug-field"
            @if($question->question_required) required @endif 
            @if(isset($record))
            @foreach ($record->data as $data)
                @if($data->question_id == $question->id)
                    value="{{$data->question_value}}"
                @endif
            @endforeach
            @endif
            >
        </label>
    </div>

    @elseif($question->question_type =='address')
    <div class="question_{{$question->id}}">
        @if($question->question_extra_value =='free_type')
            <label for="question_{{$question->id}}" class="inputGroup">{{$question->question_title}}
            <input type="text"  name="question_{{$question->id}}" id="question_{{$question->id}}" class="form-control form_question free-type-address"
            @if($question->question_required) required @endif 
            
            @if(isset($record))
            @foreach ($record->data as $data)
                @if($data->question_id == $question->id)
                    value="{{$data->question_value}}"
                @endif
            @endforeach
        @endif

            >
            </label>
        @elseif($question->question_extra_value =='locations')
        <label for="question_{{$question->id}}" class="inputGroup">{{$question->question_title}}
            <select name="question_{{$question->id}}" class=""
             @if($question->question_required) required @endif >
                @foreach($form->locationAddressToDisplay() as $value)
                    <option value="{{$value->full_address}}"
                    @if($question->question_extra_value_1 =='select_logged_in_address') 
                        @if(Auth::guard('location')->user()->id == $value->id ) selected @endif
                    @endif

                    @if(isset($record))
                    @foreach ($record->data as $data)
                        @if($data->question_id == $question->id)
                            selected
                        @endif
                    @endforeach
                @endif
                    >{{$value->name()}}</option>
                @endforeach
        </select>
        </label>
        @endif
    </div>

    @elseif($question->question_type =='user_type')
    <div class="question_{{$question->id}}">
        <label for="question_{{$question->id}}" class="inputGroup">{{$question->question_title}}
            <select name="question_{{$question->id}}" class="form_question"
             @if($question->question_required) required @endif >
                @foreach(App\Models\Position::all() as $value)
                    <option value="{{$value->name}}" 
                    @if($question->question_select_loggedin_user) @if(Auth::guard('web')->user() && Auth::guard('web')->user()->position_id == $value->id ) selected @endif  @endif
                    
                    @if(isset($record))
                    @foreach ($record->data as $data)
                        @if($data->question_id == $question->id)
                            selected
                        @endif
                    @endforeach
                @endif

                    >{{$value->name}}</option>
                @endforeach
            </select>
        </label>
    </div>
    @elseif($question->question_type =='5x5_risk_matrix')
        @include('location.be_spoke_forms.question-risk-matrix')
    @elseif($question->question_type =='age')
        <div class="question_{{$question->id}} " class="inputGroup">
            <label for="question_{{$question->id}}">{{$question->question_title}}
            <input type="date" name="question_{{$question->id}}" id="question_{{$question->id}}" class="form_question"
            @if($question->question_required) required @endif
            
            @if(isset($record))
            @foreach ($record->data as $data)
                @if($data->question_id == $question->id)
                    value="{{$data->question_value}}"
                @endif
            @endforeach
        @endif

            >
            </label>
        </div>
    @endif
{{-- </div> --}}