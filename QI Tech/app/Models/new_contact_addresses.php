<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class new_contact_addresses extends Model
{
    use HasFactory;
    public function contacts_to_addresses()
    {
        return $this->hasMany(contacts_to_addresses::class,'address_id','id');
    }

    public function tag_to_addresses(){
        return $this->hasMany(tab_to_addresses::class,'address_id','id');
    }
    public function user_to_addresses(){
        return $this->hasMany(user_to_addresses::class,'address_id','id');
    }
    public function address_comments(){
        return $this->hasMany(address_comments::class,'address_id','id');
    }
}
