<?php

use Illuminate\Support\Facades\Auth;

function perm($permission,$object){
    switch($permission){
    case 'can_share_case_responsibility':
        $head_office = Auth::guard('web')->user()->selected_head_office;
        $user = $head_office->users()->where('user_id',Auth::user()->id)->first();
        return ($object->can_share_case_responsibility ) || ($user->user_profile_assign->profile->profile_name == 'Super User');
    }
    return true;
}