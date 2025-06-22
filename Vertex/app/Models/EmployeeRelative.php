<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeRelative extends Model
{
    protected $table ='emp_relatives';
    protected $fillable =
    [
        'emp_id',
        'relative_name',
        'relative_position',
        'relative_dept',
        'relative_location',
        'relative_relation',
        'is_active',
        'is_deleted',
       
    ];
}
