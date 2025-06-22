<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NationalAlertHeadOffice extends Model
{
    use HasFactory;
    protected $table = 'national_alert_head_offices';
    public function national_alert(){
        return $this->belongsTo(NationalAlert::class, 'national_alert_id'); 
    }

    public function head_office(){
        return $this->belongsTo(HeadOffice::class, 'head_office_id'); 
    }
}
