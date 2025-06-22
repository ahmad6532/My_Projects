<?php

namespace App\Models\Forms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionEmail extends Model
{
    use HasFactory;
    protected $table= 'be_spoke_form_action_emails';
    public $uploadDir = 'emails';
    public $uploadPath  = 'app/public/';
    public static function processSave($request, $condition){
        if($condition->condition_action_type !== 'send_email'){
            self::where('condition_id',$condition->id)->delete(); 
            return;
        }
        $actionEmail = ActionEmail::find($request->action_email_id);
        if(!$actionEmail){
            $actionEmail = new ActionEmail();
        }
        $actionEmail->question_id = $condition->question_id;
        $actionEmail->condition_id = $condition->id;

        $actionEmail->send_email_type = $request->send_email_type;
        $actionEmail->free_type_email = $request->free_type_email;
        $actionEmail->email_question_id = $request->email_question_id;
        $actionEmail->email_message = $request->email_message;
        $actionEmail->email_attachment = $actionEmail->saveFile($request);
        $actionEmail->save();
    }

    public function saveFile($request){
        if($request->file('email_attachment')){
            $filePath = $request->file('email_attachment')->store($this->uploadDir, 'public');
            return $filePath;
        }
        if(!empty($this->email_attachment)){
            return $this->email_attachment;
        }
       return '';
    }
}
