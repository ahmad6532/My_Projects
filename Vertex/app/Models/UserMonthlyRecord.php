<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMonthlyRecord extends Model
{
    // use HasFactory;
    protected $table = 'user_monthly_record';
    protected $fillable = [
        'company_id',
        'branch_id',
        'emp_id',
        'month_of',
        'presents',
        'absents',
        'late_comings',
        'leaves',
        'holidays',
        'half_leaves',
        'actual_working_hours',
        'working_hours',
        'working_days'
    ];
}
