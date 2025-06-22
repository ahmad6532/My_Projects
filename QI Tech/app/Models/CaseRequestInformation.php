<?php

namespace App\Models;

use App\Models\Headoffices\CaseManager\HeadOfficeCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseRequestInformation extends Model
{
    protected $table = 'case_request_informations';
    use HasFactory;
    public function case()
    {
        return $this->belongsTo(HeadOfficeCase::class,'case_id');
    }
    public function questions()
    {
        return $this->hasMany(CaseRequestInformationQuestion::class,'case_request_information_id');
    }
    public function documents()
    {
        return $this->hasMany(CaseRequestInformationDocument::class,'case_request_information_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function requested_by_user()
    {
        return $this->belongsTo(User::class,'requested_by');
    }
}
