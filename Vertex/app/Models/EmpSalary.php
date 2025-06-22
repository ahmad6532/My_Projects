<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpSalary extends Model
{
    use HasFactory;
    protected $table = 'employee_salary';

    protected $fillable  = [
        'emp_id',
        'pay_period_id',
        'salary_type_id',
        'working_hours',
        'salary_per_hour',
        'working_days',
        'total_salary',
        'joining_date'
    ];
    public function payPeriod()
    {
        return $this->belongsTo(PayPeriod::class, 'pay_period_id');
    }
}
