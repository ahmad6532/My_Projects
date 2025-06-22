<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class share_case_communications_views extends Model
{
    use HasFactory;

    public function head_office_user(){
        return $this->belongsTo(HeadOfficeUser::class,'head_office_user_id');
    }
}
