<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_favourite_contacts extends Model
{
    use HasFactory;
    public function head_office_user()
    {
        return $this->belongsTo(HeadOfficeUser::class, 'head_office_user_id', 'id');
    }

    public function new_contact(){
        return $this->belongsTo(user_to_contacts::class, 'contact_id', 'id');
    }
}
