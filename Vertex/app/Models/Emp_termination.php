<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emp_termination extends Model
{
    // use HasFactory;
    protected $table = "emp_terminations";
    protected $fillable = [
        'company_id',
        'branch_id',
        'emp_id',
        'termination_type',
        'termination_date',
        'notice_date',
        'reason',
        'is_deleted'
    ];
    public function employee_detail(){
        return $this->belongsTo(EmployeeDetail::class,'emp_id','id');
    }

    public function employee_approval(){
        return $this->hasOne(user_approval::class, 'emp_id','emp_id');
    }
    
    public function branch(){
        return $this->belongsTo(Location::class, 'branch_id','id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
