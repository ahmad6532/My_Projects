<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contact_groups extends Model
{
    use HasFactory;
    public function new_contacts()
    {
        return $this->hasMany(new_contacts::class, 'group_id', 'id');
    }
    public function contact_to_groups()
    {
        return $this->hasMany(contact_to_groups::class, 'group_id', 'id');
    }
}
