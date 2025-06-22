<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceDetail extends Model
{
    use HasFactory;
    protected $table = 'attendance_details';
    protected $fillable = [
        'daily_record_id',
        'check_in_lati',
        'check_out_longi',
        'check_in_address',
        'check_out_address',
        'check_in_image',
        'check_out_image'
    ];
}
