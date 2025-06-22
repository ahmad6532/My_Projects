<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Related_refrence extends Model
{
   protected $table ='related_refrences';
    protected $fillable =
    [
        'emp_id',
        'refrence_name',
        'ref_position',
        'ref_address',
        'ref_phone',
        'is_active',
        'is_deleted',

    ];
}
