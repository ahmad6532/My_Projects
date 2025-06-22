<?php

namespace App\Models\Headoffices\CaseManager;

use App\Models\HeadOfficeUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;

    protected $table = 'case_manager_case_tasks';

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function case(){
        return $this->belongsTo(HeadOfficeCase::class,'case_id');
    }

    public function documents(){
        return $this->hasMany(TaskDocument::class,'task_id');
    }
    public function assigned(){
        return $this->hasMany(TaskAssign::class,'task_id');
    }
    public function currentUserIsAuthor(){
        $user = Auth::guard('web')->user();
        if($this->user_id == $user->id){
            return true;
        }
        return false;
    }
    public function hasAssignedUser($head_office_user_id){
        return TaskAssign::where('task_id',$this->id)->where('head_office_user_id',$head_office_user_id)->first();
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
}
