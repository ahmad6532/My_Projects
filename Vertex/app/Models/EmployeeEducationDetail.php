<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeEducationDetail extends Model
{
    protected $table ='emp_edu_details';
    protected $fillable =
    [
        'emp_id',
        'degree',
        'subject',
        'grade',
        'division',
        'degree_from',
        'degree_to',
        'institution',
        'other_qualifications',
        'is_active',
        'is_deleted',

    ];
    public function EmployeeDetails(){
        return $this->belongsTo(EmployeeDetail::class, 'emp_id','emp_id');
    }
}
