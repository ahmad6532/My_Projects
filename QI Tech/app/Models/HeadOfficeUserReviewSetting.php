<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadOfficeUserReviewSetting extends Model
{
    use HasFactory;
    function head_office_user(){
        return $this->belongsTo(HeadOfficeUser::class,'head_office_user_id');
    }
}
