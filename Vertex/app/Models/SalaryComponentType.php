<?php

namespace App\Models;
use App\Models\SalaryComponent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryComponentType extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type'];

    // One-to-Many relationship with SalaryComponent
    public function salaryComponents()
    {
        return $this->hasMany(SalaryComponent::class, 'component_type_id');
    }
}
