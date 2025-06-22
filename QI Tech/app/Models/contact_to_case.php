<?php

namespace App\Models;

use App\Models\Headoffices\CaseManager\HeadOfficeCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contact_to_case extends Model
{
    use HasFactory;

    public function contact()
    {
        return $this->belongsTo(new_contacts::class,'contact_id');
    }

    public function case()
    {
        return $this->belongsTo(HeadOfficeCase::class,'case_id');
    }
}
