<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contact_tags extends Model
{
    use HasFactory;
    public function tag_to_contacts(){
        return $this->hasMany(tag_to_contacts::class, 'tag_id', 'id');
    }
    public function tag_to_groups(){
        return $this->hasMany(tag_to_group::class, 'tag_id', 'id');
    }
}
