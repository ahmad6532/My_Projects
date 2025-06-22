<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Designation extends Model
{
    // use HasFactory;
    protected $table = 'designations';
    protected $fillable = [
        'name',
        'department_id'
    ];

    public function getCreatedAtAttribute($value)
    {
        return  Carbon::parse($value)->format('d-m-Y, h:i A');
    }
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y, h:i A');
    }

    public function department()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }
    public function approvals()
    {
        return $this->hasMany(user_approval::class, 'designation_id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeDetail::class, 'emp_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }
}
