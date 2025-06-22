<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emp_salary extends Model
{
    // use HasFactory;
    protected $table = "emp_salary";
    protected $fillable = [
        'company_id',
        'branch_id',
        'employee_details_id',
        'basic_salary',
        'net_salary',
        'pay_period',
        'salary_type',
        'first_working_date',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeDetail::class);
    }
    public function components()
    {
        return $this->hasMany(SalaryComponent::class, 'employee_details_id', 'employee_details_id');
    }
}
