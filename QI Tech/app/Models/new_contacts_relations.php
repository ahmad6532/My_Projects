<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class new_contacts_relations extends Model
{
    use HasFactory;
    public function source_contact(){
        return $this->belongsTo(new_contacts::class,'source_contact_id');
    }
    public function target_contact(){
        return $this->belongsTo(new_contacts::class,'target_contact_id');
    }
}
