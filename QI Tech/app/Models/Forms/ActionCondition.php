<?php

namespace App\Models\Forms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Forms\ActionEmail;
use Illuminate\Support\Str;
use Auth;
class ActionCondition extends Model
{
    use HasFactory;
    protected $table = 'be_spoke_form_action_conditions';

    public static $actions = array(
        'send_email' => 'Send Email',
        'add_user_to_case_manager' => 'Add User to Case Manager',
        'add_priority_value' => 'Add Priority Value',
        'hide_section' => 'Hide Section',
        'hide_question' => 'Hide Question',
        'show_question' => 'Show Question',
        'trigger_root_cause_analysis' => 'Trigger Root Cause Analysis',
        'display_information_to_user' => 'Display Information to User',
        'trigger_another_form' => 'Trigger User to Complete Another Form',
        'auto_close_case' => 'Auto Close Case Immediately Regardless of Priority Value',
        'donot_auto_close_case' => 'Do Not Auto Close Case Regardless of Priority Value',
        'create_custom_task_in_case_manager' => 'Create Custom Task for this Case in Case Manager',
        'when_case_is_closed_approval_is_required' => 'When case is closed approval is required',
        'when_case_is_closed_approval_is_not_required' => 'When case is closed approval is not required',
    );

    public function actionEmail(){
       return $this->hasOne(\App\Models\Forms\ActionEmail::class,'condition_id');
    }

    public static function showActionTitle($key){
        return self::$actions[$key];
    }

    public function question(){
        return $this->belongsTo(StageQuestion::class,'question_id');
    }
    public function showConditionTitle($question_type = ''){
        if($question_type == 'text' ||
            $question_type == 'textarea' ||
            $question_type == 'dm+d' ||
            $question_type == 'address'){
            return "If Word '". $this->condition_value."' Detected Then";
        }
        if($question_type == 'number' ||
        $question_type == 'age' ||
        $question_type == '5x5_risk_matrix'){
            switch($this->condition_if_value){
                case 'between':
                    return "If question result is between '".$this->condition_value."' and '".$this->condition_value_2."'";
                case 'greater_then':
                    return "If question result is greater then '".$this->condition_value."'";
                case 'less_then':
                    return "If question result is less then '".$this->condition_value."'";
                case 'equal_to':
                    return "If question result is equal to '".$this->condition_value."'";
                default:
                    return '';
            }
            
        }

        if($question_type == 'date'){
            switch($this->condition_if_value){
                case 'less_then':
                    return "If date result is '".$this->condition_value."' days before report date";
                case 'greater_then':
                    return "If date result is '".$this->condition_value."' days after report date";
                    default:
                    return '';
            }
        }

        if($question_type == 'radio' ||
            $question_type == 'checkbox'||
            $question_type == 'select'){
                $values = json_decode($this->condition_value);
                return "If result has values '".implode(', ',$values)."'";
        }

        return '';
    }

    public function deleteAllAssociatedActions(){
        ActionEmail::where('condition_id',$this->id)->delete();
        // Delete other actions too
    }

    public function processAction($data){
        switch($this->condition_action_type){
            case 'add_user_to_case_manager':

            break;
            case 'add_priority_value':
                $value = $this->condition_action_value;
                $record = $data->record;
                $record->priority = $value;
                $record->save();
            break;
            case 'trigger_root_cause_analysis':

            break;

            case 'trigger_another_form':
                
            break;

            case 'auto_close_case':
                
            break;

            case 'donot_auto_close_case':
                
            break;

            case 'create_custom_task_in_case_manager':
                
            break;
            //Requires Final approval is part of Case. Not incident. so we used this in previous caller controller.//

        }
    }
    public function checkConditionTriggers($data){
        $type = $this->question->question_type;
        if($type == 'text' || $type == 'textarea' || $type == 'dm+d'|| $type == 'address'){
            # Word detected
            $from_detect = $data->question_value;
            $to_detect = $this->condition_value;
            if(Str::contains($from_detect,$to_detect)){
                return true;
            }
            return false;
        }

        if($type == 'number' || $type == 'age' || $type == '5x5_risk_matrix'){
            if($type == 'age'){
                $user_value = \Carbon\Carbon::parse($data->question_value)->age;
            }else{
                $user_value = $data->question_value;
            }
            $value = $this->condition_value;
            $value_1 = $this->condition_value_2;
            switch($this->condition_if_value){
                case 'greater_then':
                    if($user_value > $value){
                        return true;
                    }
                    break;
                case 'less_then':
                    if($user_value < $value){
                        return true;
                    }
                    break;
                case 'between':
                    if($user_value > $value && $user_value < $value_1  ){
                        return true;
                    }
                    break;
                case 'equal_to':
                    if($user_value == $value){
                        return true;
                    }
                    break;
                default: 
                return false;
            }
            return false;
        }
        if($type == 'date'){
            $user_value = strtotime($data->question_value);
            $number_of_days = $this->condition_value;
            $repored_date = strtotime(date('Y-m-d'));
            
            $date_value_is_greater = false;
            if($repored_date < $user_value){
                $date_value_is_greater = true;
            }

            $difference_in_days = 0;
            $difference_in_days = abs(($repored_date - $user_value) /(60*60*24));
            
            switch($this->condition_if_value){
                case 'less_then':
                    if($date_value_is_greater == false && $difference_in_days <= (int)$number_of_days){
                        return true;
                    }
                    break;
                case 'greater_then':
                    if($date_value_is_greater == true && $difference_in_days >= $number_of_days){
                        return true;
                    }
                    break;
                default: 
                    return false;
                }
                return false;
        }


        if($type == 'radio' || $type == 'checkbox' || $type == 'select' ){
            $user_values = explode(',',$data->question_value);
            $to_check_values = (json_decode($this->condition_value))?json_decode($this->condition_value,true):[];
            foreach($to_check_values as $val){
                if(in_array($val,$user_values )){
                    return true;
                }
            }
            return false;
        }
        return false;
    }
    public static function formToSubmit($id){
        $record = self::find($id);
        if($record && $record->condition_action_type == 'trigger_another_form'){
            return array(
                'form_id' => $record->condition_action_value,
                'message' => $record->condition_action_value_1,
            );
        }
        return null;
        
    }
    public function generateEmailData($data){
       $actionEmail =  $this->actionEmail;
       $to = '';
       switch($this->actionEmail->send_email_type){
            case 'free_type_email':
                $to = $actionEmail->free_type_email; 
                break;
            case 'head_office_profile_type':
                $to = '';
                break;
            case 'reported_by':
                $to = Auth::guard('location')->user()->email;
                break;
            case 'user_selected_in_question_x':
                # Find user question
                $record = $data->record;
                $dataVal = RecordData::where('record_id',$record->id)->where('question_id',$actionEmail->email_question_id)->first();
                if(!$dataVal){
                    break;
                }
                $user = \App\Models\User::find($dataVal->question_value);
                if($user){
                    $to = $user->email;
                }
                break;
       }
       if($actionEmail->email_attachment){
        $attachment = storage_path($actionEmail->uploadPath.$actionEmail->email_attachment);
       }else{
        $attachment = '';
       }
       return array('to' => $to, 
                    'message' => $actionEmail->email_message,
                    'attachment' => $attachment);

    }

}
