<?php

namespace App\Models;

use App\Http\Controllers\HeadOffice\CaseManagerController;
use App\Models\Headoffices\CaseManager\HeadOfficeCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CaseContact extends Model
{
    use HasFactory;

    public function contact()
    {
        return $this->belongsTo(Contact::class,'contact_id');
    }

    public function case()
    {
        return $this->belongsTo(HeadOfficeCase::class,'case_id');
    }
}
