<?php

namespace App\Models;

use App\Models\Headoffices\CaseManager\HeadOfficeCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CaseStageTask extends Model
{
    use HasFactory;

    protected static function boot(){
        parent::boot();


        static::updated(function ($task) {
            $stage = $task->caseStage->stage_completed();
        });
    }
    public function caseStage()
    {
        return $this->belongsTo(CaseStage::class,'case_stage_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function documents(){
        return $this->hasMany(CaseStageTaskDocument::class,'case_stage_task_id');
    }
    public function assigned(){
        return $this->hasMany(CaseStageTaskAssign::class,'task_id');
    }
    public function currentUserIsAuthor(){
        $user = Auth::guard('web')->user();
        if($this->user_id == $user->id){
            return true;
        }
        return false;
    }
    public function hasAssignedUser($head_office_user_id){
        return CaseStageTaskAssign::where('task_id',$this->id)->where('head_office_user_id',$head_office_user_id)->first();
    }
    public function case()
    {
        return $this->belongsTo(CaseStage::class,'case_stage_id');
    }
    public function getOverDueAttribute()
    {
        $profiles = [];
        $user = Auth::guard('web')->user()->selected_head_office;

        if($this->task_over_due_option == 'move_profile' || $this->task_over_due_option == 'mail_profile')
        {
            $ids = json_decode($this->task_over_due_profile_id);
            if($ids)
            {
                foreach($ids as $id)
                {

                    $profile = $user->head_office_user_profiles()->find($id);
                    if($profile)
                    {
                            $profiles[] = $profile->profile_name;
                    }
                    
                }
            }
        }
        if($this->task_over_due_option == 'move_user' || $this->task_over_due_option == 'mail_user')
        {
            $ids = json_decode($this->task_over_due_user_id);
            if($ids)
            {
                foreach ($ids as $id) {
                    $profile = $user->users()->find($id);
                    if ($profile) {
                        $profiles[] = $profile->user->name ;
                    }
                }
            }
        }
        
        return $profiles;
    }

    public function getDeadLineAttribute()
    {
        $profiles = [];
        $user = Auth::guard('web')->user()->selected_head_office;
        if($this->dead_line_option == 'move_profile' || $this->dead_line_option == 'mail_profile')
        {
            $ids = json_decode($this->dead_line_profile_id);
            if($ids)
            {
                foreach($ids as $id)
                {
                    $profile = $user->head_office_user_profiles()->find($id);
                    if($profile)
                    {
                            $profiles[] = $profile->profile_name;
                    }
                    
                }
            }
        }
        if($this->dead_line_option == 'move_user' || $this->dead_line_option == 'mail_user')
        {
            $ids = json_decode($this->dead_line_user_id);
            if($ids)
            {
                foreach ($ids as $id) {
                    $profile = $user->users()->where('user_id',$id)->first();
                    if ($profile) {
                        $profiles[] = $profile->user->name;
                    }
                }
            }
        }
        
        return $profiles;
    }

    public function deadline_records(){
        return $this->hasMany(deadlineCaseTask::class,'case_task_id');
    }
}
