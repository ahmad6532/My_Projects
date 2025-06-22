<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceDeviceStatus extends Model
{
    use HasFactory;
    protected $table = 'attendance_device_status';
    protected $fillable = ['id, device_status'];
}
