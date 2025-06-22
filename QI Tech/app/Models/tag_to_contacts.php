<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tag_to_contacts extends Model
{
    use HasFactory;
    
    public function new_contact() {
        return $this->belongsTo(new_contacts::class,'contact_id');
    }
    public function contact_tag() {
        return $this->belongsTo(contact_tags::class,'tag_id');
    }
   
}
