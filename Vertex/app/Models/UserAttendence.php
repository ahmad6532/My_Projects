<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmployeeDetail;

class UserAttendence extends Model
{
    protected $table ='user_attendence';
    protected $fillable =
    [
        'emp_id',
        'name',
        'check_in',
        'device_serial_no',
        'pull_time',
        'check_out',
        'check_in_status',
        'check_in_lati',
        'check_in_longi',
        'check_in_address',
        'check_out_status',
        'check_out_lati',
        'check_out_longi',
        'check_out_address',
        'check_in_ip_address',
        'check_out_ip_address',
    ];

    public function employee_details(){
        return $this->belongsTo(EmployeeDetail::class);
    }
}
