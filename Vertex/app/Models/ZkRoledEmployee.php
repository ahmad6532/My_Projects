<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZkRoledEmployee extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'emp_id',
        'role_no',
        'synced',
        'action'
    ];

    // a zkrole belongs to multi employees
    public function zkRoleToEmp(){
        return $this->belongsTo(EmployeeDetail::class,'emp_id');
    }
    public function zkRoleToDevice(){
        return $this->belongsTo(DeviceManagement::class,'device_id');
    }
}
