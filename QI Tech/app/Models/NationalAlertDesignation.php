<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Position;
class NationalAlertDesignation extends Model
{
    use HasFactory;
    protected $table = 'national_alert_designations';
    public function national_alert(){
        return $this->belongsTo(NationalAlert::class, 'national_alert_id'); 
    }
    public function position(){
        return $this->belongsTo(Position::class, 'position_id'); 
    }
}
