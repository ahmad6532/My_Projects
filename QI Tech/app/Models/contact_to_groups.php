<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contact_to_groups extends Model
{
    use HasFactory;
    public function new_contact(){
        return $this->belongsTo(new_contacts::class, 'contact_id');
    }
    public function contact_group(){
        return $this->belongsTo(contact_groups::class, 'group_id');
    }
}
