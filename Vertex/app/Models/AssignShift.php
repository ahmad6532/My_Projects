<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignShift extends Model
{
    use HasFactory;

    protected $table = 'assign_shifts';

    protected $fillable = [
        'id',
        'emp_id',
        'company_id',
        'branch_id',
        'department_id',
        'shifit_id',
        'shift_type',
        'date',
        'extra_hours',
        'created_at',
        'updated_at'
    ];
}
