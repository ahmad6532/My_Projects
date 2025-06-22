<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftManagement extends Model
{
    // use HasFactory;
    protected $table="shift_management";
    protected $fillable = ['shift_name','start_time','end_time','late_time','break_start_time','break_end_time','is_recurring','note','working_days'];
}
