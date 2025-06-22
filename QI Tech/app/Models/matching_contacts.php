<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class matching_contacts extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function get_contact_1(){

        return $this->belongsTo(new_contacts::class, 'contact_1');
    }
    public function get_contact_2(){
        return $this->belongsTo(new_contacts::class, 'contact_2');
    }

    public function get_other_matched_contact($currentContactId)
{
    if ($this->contact_1 == $currentContactId) {
        return $this->get_contact_2;
    }

    if ($this->contact_2 == $currentContactId) {
        return $this->get_contact_1;
    }

    return null;
}

}
