<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayPeriod extends Model
{
    use HasFactory;

    protected $table = 'pay_period';
    protected $fillable = [
        'payroll_type',
        'company_id',
        'branch_id',
        'department_id',
        'start_date',
        'end_date',
        'total_emp',
        'net_salary',
        'closed',
    ];
    public function salaries()
    {
        return $this->hasMany(EmpSalary::class, 'pay_period_id');
    }

    // A pay roll belongs to a company
    public function payRollToCompany(){
        return $this->belongsTo(Company::class,'company_id');
    }
     // A pay roll belongs to a branch
     public function payRollToBranch(){
        return $this->belongsTo(Location::class,'branch_id');
    }
}
