<?php

namespace App\Models;
use App\Models\EmployeeDetail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_details_id',
        'starting_salary',
        'pay_period',
        'salary_type',
        'first_working_date',
        'net_salary'
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
