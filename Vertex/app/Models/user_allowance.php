<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user_allowance extends Model
{
   protected $table ='emp_allowances';
    protected $fillable =
    [
        'emp_id',
        'company_allowance_id',
        'role_id',
        'is_active',
        'is_deleted',
    ];
}
