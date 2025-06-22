<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    // use HasFactory;
    protected $table ='company_settings';
    protected $fillable =
    [

        'company_id',
        'branch_id',
        'start_time',
        'end_time',
        'days',
        'lunch_start_time',
        'lunch_end_time',
        'late_time',
        'late_limit',
        'flexible_time',
        'is_deleted',
        'setting_name',
    ];

    protected $hidden = [
        'late_time',
        'flexible_time',
        'half_day',
        'created_at',
        'updated_at',
        'is_deleted',
        "company_id",
        "branch_id"
    ];
}
