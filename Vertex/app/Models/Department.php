<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Department extends Model
{
    // use HasFactory;
    protected $table = 'departments';
    protected $fillable = [
        'name',
        'head_emp_id',
        'is_deleted'
    ];
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i A');
    }
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i A');
    }

    public function sub_departments()
    {
        return $this->hasMany(SubDepartment::class,'department_id','id');
    }

    // a department belongs to many employees
    public function departToEmp(){
        return $this->belongsTo(EmployeeDetail::class,'head_emp_id');
    }

    protected $hidden = [
        "created_at",
            "updated_at"
    ];
}
