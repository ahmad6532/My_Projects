<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeHistory extends Model
{
    protected $table ='emp_history';
    protected $fillable =
    [
        'user_id',
        'emp_id',
        'emp_position',
        'prev_emp_no',
        'emp_location',
        'date_from',
        'date_to',
        'is_active',
        'is_deleted',
       
    ];
    public function EmployeeDetails(){
        return $this->belongsTo(EmployeeDetail::class, 'emp_id','emp_id');
    }
}
