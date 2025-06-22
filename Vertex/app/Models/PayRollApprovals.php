<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayRollApprovals extends Model
{
    use HasFactory;
    protected $fillable = [
        'pay_period_id',
        'emp_card',
        'emp_name',
        'emp_type',
        'emp_id',
        'department',
        'designation',
        'basic_salary',
        'late',
        'leave',
        'absent',
        'sales_incentive',
        'allowances',
        'loan',
        'deduction',
        'absent_deduction',
        'monthly_incom',
        'monthly_tax',
        'net_salary',
        'status',
        'paid_date',
    ];

    // An pay roll approval belongs to an employee
    public function payRollApprovalToEmp(){
        return $this->belongsTo(EmployeeDetail::class,'emp_id');
    }

}
