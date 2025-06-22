<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user_approval extends Model
{
    protected $table ='emp_approvals';
    protected $fillable =
    [
        'user_id',
        'emp_id',
        'designation_id',
        'emp_no',
        'report_to',
        'joining_date',
        'phone_issued',
        'mail_issued',
        'starting_sal',
        'job_status_id',
        'is_active',
        'is_deleted',
    ];
    protected $hidden =
    [
        'is_active',
        'is_deleted',
        'created_at',
        'updated_at',
        'emp_no'
    ];
    public function emp_details(){
        return $this->belongsTo(EmployeeDetail::class, 'emp_id');
    }

    public function designation(){
        return $this->belongsTo(Designation::class, 'designation_id','id');
    }

//  An approval belongs to a department
public function approvalToDept(){
    return $this->belongsTo(Department::class, 'department_id');
}

//  An approval belongs to a job status
public function approvalToJobStatus(){
    return $this->belongsTo(Job_type::class, 'job_status_id');
}
}
