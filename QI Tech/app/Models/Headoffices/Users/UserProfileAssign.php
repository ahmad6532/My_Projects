<?php

namespace App\Models\Headoffices\Users;

use App\Models\head_office_access_rights;
use App\Models\HeadOfficeUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfileAssign extends Model
{
    use HasFactory;

    protected $table = 'head_office_users_profile_assigns';

    public function profile(){
        return $this->belongsTo(head_office_access_rights::class,'user_profile_id');
    }

    public function head_office_user(){
        return $this->belongsTo(HeadOfficeUser::class,'head_office_user_id');
    }
}
