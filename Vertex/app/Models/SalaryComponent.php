<?php

namespace App\Models;
use App\Models\EmployeeDetail;
use App\Models\SalaryComponentType;
use App\Models\Emp_salary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryComponent extends Model
{
    protected $fillable = ['employee_details_id','component_type_id','percentage','amount', 'tax_applicable'];
    use HasFactory;
    public function employee()
    {
        return $this->belongsTo(EmployeeDetail::class);
    }
    public function componentType()
    {
        return $this->belongsTo(SalaryComponentType::class, 'component_type_id');
    }
    public function salary()
    {
        return $this->belongsTo(Emp_salary::class, 'employee_details_id', 'employee_details_id');
    }
}
