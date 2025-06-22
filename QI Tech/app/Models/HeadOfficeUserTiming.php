<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadOfficeUserTiming extends Model
{
    protected $primaryKey = 'id';

    use HasFactory;
    protected $fillable = [
        'id',
        'is_open_monday',
        'monday_start_time',
        'monday_end_time',
        
        'is_open_tuesday',
        'tuesday_start_time',
        'tuesday_end_time',

        'is_open_wednesday',
        'wednesday_start_time',
        'wednesday_end_time',

        'is_open_thursday',
        'thursday_start_time',
        'thursday_end_time',

        'is_open_friday',
        'friday_start_time',
        'friday_end_time',

        'is_open_saturday',
        'saturday_start_time',
        'saturday_end_time',

        'is_open_sunday',
        'sunday_start_time',
        'sunday_end_time',
    ];

    public function convert_time($time){
        if($time == null)
            return "Not Available";
        return Carbon::createFromFormat('H:i:s',$time)->format('H:i');
    }
}
