<?php

namespace App\Models\Forms;

use App\Models\DefaultCardField;
use App\Models\DefaultField;
use App\Models\GdprFormField;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StageQuestion extends Model
{
    use HasFactory;

    protected $table = 'be_spoke_form_questions';

    public static $fields = array(
        'text' => array( 'required_fields' => array('min','max')),
        'number' => array('required_fields' => array('min','max')),
        'date' => array('required_fields' => array('min','max')),
        'time' => array('required_fields' => array('min','max')),
        'radio' => array('required_fields' => array('options')),
        'checkbox' => array('required_fields' => array('options')),
        'select' => array('required_fields' => array('options','multi_select')),
        'textarea' => array('required_fields' => array('min','max')),
        'user' => array('required_fields' => array('multi_select','select_loggedin_user','select_loggedin_user_changed')),
        'dm+d' => array('required_fields' => array()),
        'address' => array('required_fields' => array('address_specific')),
        'user_type' => array('required_fields' => array('select_loggedin_user')),
        '5x5_risk_matrix' => array('required_fields' => array()),
        'age' => array('required_fields' => array()),
    );

    public function stage(){
        return $this->belongsTo(FormStage::class,'stage_id');
    }
    public function form(){
        return $this->belongsTo(Form::class,'form_id');
    }
    public function group(){
        return $this->belongsTo(QuestionGroup::class,'group_id');
    }

    public function conditions(){
        return $this->hasMany(ActionCondition::class,'question_id');
    }
    public function data(){
        return $this->hasOne(RecordData::class,'question_id');
    }

    public function questionsOnlyUserType(){
        $questions  = Self::where('question_type','user')->where('form_id',$this->form_id)->get();
        return $questions;
    }
    public function selectAllGroupsofAForm(){
        $stages = FormStage::where('form_id',$this->form_id)->get();
        $groups = array();
        foreach($stages as $stage){
            foreach($stage->groups as $group){
                $groups[] =  $group; 
            }
           
        }
        return $groups;
    }
    public function selectAllQuestionsofAForm(){
        $questions = StageQuestion::where('form_id',$this->form_id)->get();
        return $questions;
    }
    public function displaySubmission($record_id){
        $value = \App\Models\Forms\RecordData::where('record_id',$record_id)->where('question_id',$this->id)->first();
        if(!$value){
            return 'Null';
        }
        $value = $value->question_value;
        switch($this->question_type){
            case 'date':
            $value = \Carbon\Carbon::parse($value)->format('d/m/Y ');
            break;

            case 'time':
                $value = \Carbon\Carbon::parse($value)->format('H:i a ');
            break;

            case 'user':
                $user = User::find($value);
                $value = $user->name .' <'.$user->email.'>';
            break;
            
            case '5x5_risk_matrix':
                $value = explode('-',$value);
                $value = trim($value[1]);
            break;
            case 'age':
                $value = \Carbon\Carbon::parse($value)->age;
                $value = $value. " Years";
            break;
        }
       
        return $value;
    }
    public function formCard()
    {
        return $this->belongsTo(FormCard::class,'form_card_id');
    }

    public function formCardDefaultField()
    {
        return $this->belongsTo(DefaultField::class,'default_field_id');
    }
    public function question()
    {
        return $this->belongsTo(RecordData::class,'question_id');
    }
    public function gdpr_form_field()
    {
        return $this->hasOne(GdprFormField::class,'be_spoke_form_question_id');
    }
}


