<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_to_contacts extends Model
{
    use HasFactory;
    public function new_contact(){
         return $this->belongsTo(new_contacts::class,'contact_id','id');
    }

    public function head_office_user(){
        return $this->belongsTo(HeadOfficeUser::class,'head_office_user_id','id');
    }

    public function case_handler(){
        return $this->hasMany(CaseHandlerUser::class,'head_office_user_id','head_office_user_id');
    }
}
