<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseRequestInformationQuestion extends Model
{
    use HasFactory;
    public function CaseRequestInformation()
    {
        return $this->belongsTo(CaseRequestInformation::class,'case_request_information_id');
    }
}
