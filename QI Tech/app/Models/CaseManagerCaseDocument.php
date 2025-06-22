<?php

namespace App\Models;

use App\Models\Headoffices\CaseManager\Comment;
use App\Models\Headoffices\CaseManager\HeadOfficeCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseManagerCaseDocument extends Model
{
    use HasFactory;

    public function documents(){
        return $this->hasMany(CaseManagerCaseDocumentDocument::class,'c_m_c_d_id');
    }
    public function case()
    {
        return $this->belongsTo(HeadOfficeCase::class,'case_id');
    }

    public function uploadedByUser(){
        return $this->belongsTo(User::class,'uploaded_by');
    }
    public function updatedByUser(){
        return $this->belongsTo(User::class,'updated_by');
    }

}
