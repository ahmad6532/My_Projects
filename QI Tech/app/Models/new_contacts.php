<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class new_contacts extends Model
{
    use HasFactory;
    use SoftDeletes;
    public function contact_groups(){
        return $this->belongsTo(contact_groups::class,'group_id','id');
    }
    public function head_offices(){
        return $this->belongsTo(HeadOffice::class,'head_office_id','id');
    }
    public function new_contact_addresses(){
        return $this->hasMany(new_contact_addresses::class,'contact_id','id');
    }
    public function tag_to_contacts(){
        return $this->hasMany(tag_to_contacts::class,'contact_id','id');
    }
    public function user_to_contacts(){
        return $this->hasMany(user_to_contacts::class,'contact_id','id');
    }
    public function new_contacts_relations(){
        return $this->hasMany(new_contacts_relations::class,'source_contact_id','id');
    }
    public function new_contact_comments(){
        return $this->hasMany(new_contact_comments::class,'contact_id','id');
    }
    public function contacts_to_addresses(){
        return $this->hasMany(contacts_to_addresses::class,'contact_id','id');
    }

    public function contact_to_groups(){
        return $this->hasMany(contact_to_groups::class,'contact_id','id');
    }

    public function matchingContactsAsContact1() {
        return $this->hasMany(matching_contacts::class, 'contact_1', 'id');
    }
    
    public function matchingContactsAsContact2() {
        return $this->hasMany(matching_contacts::class, 'contact_2', 'id');
    }

    public function get_all_matching_contacts()
{
    $matchesAsContact1 = $this->matchingContactsAsContact1;
    $matchesAsContact2 = $this->matchingContactsAsContact2;

    $uniqueMatches = $matchesAsContact1->merge($matchesAsContact2)->unique('id');

    return $uniqueMatches;
}

    
}
