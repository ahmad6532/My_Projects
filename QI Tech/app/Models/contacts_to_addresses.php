<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contacts_to_addresses extends Model
{
    use HasFactory;
    public function new_contact(){
        return $this->belongsTo(new_contacts::class, 'contact_id');
    }
    public function new_contact_address(){
        return $this->belongsTo(new_contact_addresses::class, 'address_id');
    }
}
