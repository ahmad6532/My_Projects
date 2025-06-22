<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NationalAlertDocument extends Model
{
    use HasFactory;
    protected $table = 'national_alert_documents';

    public function national_alert(){
        return $this->belongsTo(NationalAlert::class, 'national_alert_id'); 
    }
    public function document(){
        return $this->belongsTo(Document::class, 'document_id'); 
    }
}
