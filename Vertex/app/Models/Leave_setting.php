<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave_setting extends Model
{
    // use HasFactory;
    protected $table ='leave-settings';
    protected $fillable =
    [
        'company_id',
        'annual_days',
        'casual_days',
        'sick_days',
        'maternity_days',
        'is_active',
        'is_deleted',
        'annual_before_days',
        'casual_before_days',
        'is_loss_of_pay_active',
        'is_annual_days_active',
        'is_sick_days_active',
        'is_maternity_days_active',
        'annual_carry_forward',
        'annual_forward_days',
         'casual_carry_forward',
        'casual_forward_days',
    ];

    // protected $hidden = [
    //     'company_id',
    // ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
