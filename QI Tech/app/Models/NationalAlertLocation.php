<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NationalAlertLocation extends Model
{
    use HasFactory;

    protected $table = 'national_alert_locations';
    public function national_alert(){
        return $this->belongsTo(NationalAlert::class, 'national_alert_id'); 
    }

    public function location(){
        return $this->belongsTo(Location::class, 'location_id'); 
    }
}
