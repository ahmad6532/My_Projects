<?php

namespace App\Models;

use App\Models\Headoffices\Organisation\Group;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovedLocationGroupUser extends Model
{
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function is_location()
    {
        return false;
    }
    public function group()
    {
        return $this->belongsTo(Group::class,'head_office_organisation_group_id');
    }
}
