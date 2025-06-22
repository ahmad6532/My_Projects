<?php

namespace App\Models;

use App\Models\Forms\Form;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultTask extends Model
{
    use HasFactory;

    public function documents(){
        return $this->hasMany(DefaultTaskDocument::class,'default_task_id');
    }

    public function be_spoke_form()
    {
        return $this->belongsTo(Form::class,'be_spoke_form_id');
    }
    public function getProfilesAttribute()
    {
        $type = $this->type;
        $ids = json_decode($this->type_ids);
        $profiles = "";
        if($ids)
        {
            foreach($ids as $id)
            {
                if($type)
                {
                    $profile = $this->be_spoke_form->form_owner->head_office_user_profiles()->find($id);
                    if($profile)
                    {
                        // dd($this->be_spoke_form->form_owner->head_office_user_profiles->last()->id== $id);
                        // if($this->be_spoke_form->form_owner->head_office_user_profiles->last()->id == $id)
                        //     $profiles .= $profile->profile_name;
                        // else
                            $profiles .= $profile->profile_name.', ';
                    }
                }
                else
                {
                    $profile = $this->be_spoke_form->form_owner->users()->find($id);
                    if($profile)
                    {
                        // if($this->be_spoke_form->form_owner->users->last()->user->id == $id)
                        //     $profiles .= $profile->user->name;
                        // else
                            $profiles .= $profile->user->name.', ';
                    }
                }
            }
        }
        return $profiles;
    }

    public function getOverDueAttribute()
    {
        $profiles = "";
        if($this->task_over_due_option == 'move_user' || $this->task_over_due_option == 'mail_user')
        {
            $ids = json_decode($this->task_over_due_user_id);
            if($ids)
            {
                foreach($ids as $id)
                {
                    $profile = $this->be_spoke_form->form_owner->head_office_user_profiles()->find($id);
                    if($profile)
                    {
                            $profiles .= $profile->profile_name.', ';
                    }
                    
                }
            }
        }
        if($this->task_over_due_option == 'move_profile' || $this->task_over_due_option == 'mail_profile')
        {
            $ids = json_decode($this->task_over_due_profile_id);
            if($ids)
            {
                foreach ($ids as $id) {
                    $profile = $this->be_spoke_form->form_owner->users()->find($id);
                    if ($profile) {
                        $profiles .= $profile->user->name . ', ';
                    }
                }
            }
        }
        
        return $profiles;
    }

    public function getDeadLineAttribute()
    {
        $profiles = "";
        if($this->dead_line_option == 'move_user' || $this->dead_line_option == 'mail_user')
        {
            $ids = json_decode($this->dead_line_user_id);
            if($ids)
            {
                foreach($ids as $id)
                {
                    $profile = $this->be_spoke_form->form_owner->head_office_user_profiles()->find($id);
                    if($profile)
                    {
                            $profiles .= $profile->profile_name.', ';
                    }
                    
                }
            }
        }
        if($this->dead_line_option == 'move_profile' || $this->dead_line_option == 'mail_profile')
        {
            $ids = json_decode($this->dead_line_profile_id);
            if($ids)
            {
                foreach ($ids as $id) {
                    $profile = $this->be_spoke_form->form_owner->users()->find($id);
                    if ($profile) {
                        $profiles .= $profile->user->name . ', ';
                    }
                }
            }
        }
        
        return $profiles;
    }

}
