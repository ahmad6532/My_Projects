<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpCompensationDetails extends Model
{
    use HasFactory;
    protected $table = 'employee_compensation_details';

    public function compensations()
    {
        return $this->hasMany(EmpCompensation::class, 'type_id');
    }
}
