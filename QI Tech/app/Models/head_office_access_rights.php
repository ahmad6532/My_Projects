<?php

namespace App\Models;

use App\Models\Headoffices\Users\UserProfileAssign;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class head_office_access_rights extends Model
{
    use HasFactory;

    public function user_profile_assign(){
        $user = Auth::guard('web')->user();
        if(!isset($user)){
            $headOffice = HeadOffice::find($this->head_office_id);
        }else{
            $headOffice = $user->selected_head_office;
        }
        return $this->hasMany(UserProfileAssign::class,'user_profile_id')
        ->whereHas('head_office_user',function ($query) use ($headOffice) {
            $query->where('head_office_id', $headOffice->id);
        });
    }

}
