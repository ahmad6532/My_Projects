<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'module_id',
        'selected_id',
        'selected_type',
        'approval_level',
        'bypass_approval',
    ];


    // An approval setting belongs to a an employee
    public function approvalSettingToEmp(){
        return $this->belongsTo(EmployeeDetail::class, 'selected_id');
    }

    // An approval can belong to designation
    public function approvalSettingToDesignation(){
        return $this->belongsTo(Designation::class, 'selected_id');
    }
}
