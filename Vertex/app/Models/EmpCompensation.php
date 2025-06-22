<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpCompensation extends Model
{
    use HasFactory;
    protected $table = 'employee_compensation';
    protected $fillable = [
        'emp_id',
        'type_id',
        'amount',
        'type_of',
        'is_taxable'
    ];

    public function typeDetail()
    {
        return $this->belongsTo(EmpCompensationDetails::class, 'type_id');
    }

    public function salary()
    {
        return $this->hasOne(EmpSalary::class, 'emp_id');
    }
}
