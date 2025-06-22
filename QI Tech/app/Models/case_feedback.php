<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class case_feedback extends Model
{
    use HasFactory;

    public function HeadOffice(){
        return $this->belongsTo(HeadOffice::class,'head_office_id');
    }
}
