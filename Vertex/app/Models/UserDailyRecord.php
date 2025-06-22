<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDailyRecord extends Model
{
    // use HasFactory;
    protected $table = 'user_daily_records';
    protected $fillable = [
        'emp_id',
        'present',
        'check_in',
        'check_out',
        'leave',
        'leave_type',
        'holiday',
        'weekend',
        'pull_time',
        'dated',
        'working_hours',
        'device_serial_no',
        'check_in_type',
        'check_out_type',
        'check_in_ip',
        'check_out_ip',
        'mark_in_status',
        'mark_out_status'

    ];

    public function dailyToEmpDetail(){
        return $this->belongsTo(EmployeeDetail::class,'emp_id');
    }
    public function dailyRecordToLeaveType(){
        return $this->belongsTo(Leave_Type::class,'leave_type');
    }
}
