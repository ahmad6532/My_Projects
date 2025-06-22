<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadOfficeUserHoliday extends Model
{
    protected $primaryKey = 'id';

    use HasFactory;
    protected $fillable = [
        'id',
        'head_office_user_id',
        'away_from',
        'return_on',
        'total_days',
        'type',
        'linked_api_holiday_id', // 2023-12-25Chrismas Holiday
    ];

    protected $dates = ['away_from', 'return_on'];
}
