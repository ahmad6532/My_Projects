<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NationalAlertCountry extends Model
{
    use HasFactory;

    protected $table = 'national_alert_countries';

    public function national_alert(){
        return $this->belongsTo(NationalAlert::class, 'national_alert_id'); 
    }
}
