<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_experience extends Model
{
    protected $table ='emp_experiences';
    protected $fillable =
    [
        'emp_id',
        'organization',
        'prev_position',
        'prev_salary',
        'exp_from',
        'exp_to',
        'reason_for_leaving',
        'court_conviction',
        'is_active',
        'is_deleted',

    ];
    
    public function EmployeeDetails(){
        return $this->belongsTo(EmployeeDetail::class, 'emp_id','emp_id');
    }
}
