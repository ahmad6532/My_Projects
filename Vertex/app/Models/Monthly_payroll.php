<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monthly_payroll extends Model
{
    // use HasFactory;
    protected $table = 'monthly_payroll';
    protected $fillable = [
        'company_id',
        'branch_id',
        'emp_id',
        'current_salary',
        'net_salary',
        'conveince_allowance',
        'increment',
        'arrears',
        'late_count',
        'absent_ELA',
        'absent_L_adj',
        'mobile_allowance',
        'late_deduction',
        'remarks',
        'year',
        'month',
        'status'
    ];
}
