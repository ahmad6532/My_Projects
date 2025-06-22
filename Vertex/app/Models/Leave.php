<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Leave_Type;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    // use HasFactory;
    protected $table ='emp_leaves';
    protected $fillable =
    [
        'company_id',
        'branch_id',
        'emp_id',
        'remaining',
        'leave_type',
        'leave_status',
        'requested_days',
        'approved_days',
        'from_date',
        'to_date',
        'approved_by',
        'is_approved',
        'remarks',
        'is_deleted'
    ];

    // protected $hidden = [
    //     "company_id",
    //     "branch_id",
    //     "remarks",
    // ];
    public function employee()
    {
        return $this->belongsTo(EmployeeDetail::class, 'emp_id', 'id')->where('status','1');
    }

    public function branch()
    {
        return $this->belongsTo(Location::class, 'branch_id', 'id');
    }

    public function roles()
    {
        return $this->belongsTo(Role::class, 'approved_by', 'id');
    }

    public function leave_type()
    {
        return $this->belongsTo(Leave_Type::class,'id','leave_type');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i A');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i A');
    }

    //start new for me


    // public function employee()
    // {
    //     return $this->belongsTo(EmployeeDetail::class, 'emp_id');
    // }

    public function location()
    {
        return $this->belongsTo(Location::class, 'branch_id');
    }

    public function leaveType()
    {
        return $this->belongsTo(Leave_Type::class, 'leave_type');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function leaveSetting()
    {
        return $this->belongsTo(Leave_setting::class, 'company_id');
    }
}
