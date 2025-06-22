<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user_family_refrence extends Model
{
   protected $table ='emp_family_refrences';
    protected $fillable =
    [
        'emp_id',
        'memeber_name',
        'phone_number',
        'memeber_relation',
        'memeber_age',
        'memeber_occupation',
        'place_of_work',
        'emergency_contact',
        'is_active',
        'is_deleted',
    ];
}
